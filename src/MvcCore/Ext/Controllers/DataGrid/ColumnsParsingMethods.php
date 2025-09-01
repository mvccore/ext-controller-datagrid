<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Controllers\DataGrid;

use \MvcCore\Ext\Controllers\DataGrids\Configs\Column as ConfigColumn,
	\MvcCore\Ext\Controllers\DataGrids\Models\IGridRow as IGridRow;

/**
 * @mixin \MvcCore\Ext\Controllers\DataGrid
 * @phpstan-type ColumnMeta object{"dbColumnName":string,"allowNulls":bool,"types":array<string>,"parserArgs":array<mixed>,"formatArgs":array<mixed>,"primaryKey":bool,"uniqueKey":array<string>}
 */
trait ColumnsParsingMethods {

	/**
	 * @inheritDoc
	 * @return array<string,ConfigColumn>
	 */
	public function ParseConfigColumns () {
		$rowFullClassName = $this->GetRowClass();
		$rowClassPropsFlags = $this->rowClassPropsFlags !== 0
			? $this->rowClassPropsFlags
			: $rowFullClassName::GetDefaultPropsFlags();
		/** @var array<string,ColumnMeta> $rowModelMetaData */
		$rowModelMetaData = [];
		$this->initModelClasses(); // necessary to call for sure for completed property bellow
		if ($this->rowClassIsExtendedModel)
			$rowModelMetaData = $this->getExtendedModelMetaData($rowClassPropsFlags);
		$props = $this->parseConfigColumnsGetProps(
			$rowFullClassName, $rowClassPropsFlags
		);
		return $this->parseConfigColumnsExecute($props, $rowModelMetaData);
	}
	
	/**
	 * Parse config columns from reflection properties and database layer metadata.
	 * Check duplicity columns and sort config columns by index values.
	 * @param  array<\ReflectionProperty> $props 
	 * @param  array<string,ColumnMeta>   $modelMetaData
	 * @throws \Exception 
	 * @return array<string,ConfigColumn>
	 */
	protected function parseConfigColumnsExecute (array $props, array $modelMetaData) {
		$columnConfigs = [];
		$naturalSort = [];
		$indexSort = [];
		$primaryKeyCols = [];
		/** @var array<string,array<string>> $uniqueKeyCols */
		$uniqueKeyCols = [];

		$app = \MvcCore\Application::GetInstance();
		$attrsAnotations = $app->GetAttributesAnotations();
		/** @var string|\MvcCore\Tool $toolClass */
		$toolClass = $app->GetToolClass();
		$idColumnConfigured = FALSE;
		
		foreach ($props as $index => $prop) {
			$idColumn = NULL;
			/** @var ?bool $primaryKey */
			$primaryKey = NULL;
			/** @var ?string $uniqueKey */
			$uniqueKey = NULL;
			/** @var ?ColumnMeta $colMetaData */
			$colMetaData = NULL;
			if (isset($modelMetaData[$prop->name])) {
				$colMetaData = $modelMetaData[$prop->name];
				$primaryKey = $colMetaData->primaryKey;
				$uniqueKey = $colMetaData->uniqueKey;
			}
			$columnConfig = $this->parseConfigColumn(
				$prop, $index, $colMetaData, $attrsAnotations, $toolClass
			);
			if ($columnConfig === NULL) continue;
			
			$urlName = $columnConfig->GetUrlName();
			$columnIndex = $columnConfig->GetColumnIndex();
			if (isset($columnConfigs[$urlName])) {
				$propClass = $prop->getDeclaringClass()->getName();
				throw new \Exception(
					"There is already defined column with url "
					."name: `{$urlName}` (grid id: `{$this->id}`, class: `{$propClass}`)."
				);
			}

			$idColumnConfiguredLocal = $columnConfig->GetIdColumn() !== NULL;
			if ($idColumnConfiguredLocal) {
				$idColumnConfigured = $idColumnConfiguredLocal;
			} else if (!$idColumnConfigured) {
				if ($primaryKey) 
					$primaryKeyCols[] = $urlName;
				if ($uniqueKey !== NULL) {
					if (!isset($uniqueKeyCols[$uniqueKey]))
						$uniqueKeyCols[$uniqueKey] = [];
					$uniqueKeyCols[$uniqueKey][] = $urlName;
				}
			}

			$columnConfigs[$urlName] = $columnConfig;
			if ($columnIndex === NULL) {
				$naturalSort[] = $urlName;
			} else {
				if (!isset($indexSort[$columnIndex]))
					$indexSort[$columnIndex] = [];
				$indexSort[$columnIndex][] = $urlName;
			}
		}

		$result = $this->parseConfigColumnsSort($columnConfigs, $naturalSort, $indexSort);

		if (!$idColumnConfigured)
			$this->parseConfigColumnsIds($result, $primaryKeyCols, $uniqueKeyCols);
		
		return $result;
	}

	/**
	 * Return config columns sorted naturally or by column indexes.
	 * @param  array<string,ConfigColumn> $columnConfigs 
	 * @param  array<string>              $naturalSort 
	 * @param  array<int,array<string>>   $indexSort 
	 * @return array<string,ConfigColumn>
	 */
	protected function parseConfigColumnsSort (array $columnConfigs, array $naturalSort, array $indexSort) {
		$result = [];
		if (count($indexSort) === 0) {
			$result = $columnConfigs;
		} else {
			ksort($indexSort);
			foreach ($indexSort as $columnIndex => $indexedUrlNames) 
				array_splice($naturalSort, $columnIndex, 0, $indexedUrlNames);
			foreach ($naturalSort as $urlName) 
				$result[$urlName] = $columnConfigs[$urlName];
		}
		return $result;
	}

	/**
	 * Set up column configs with column ids booleans.
	 * @param  array<string,ConfigColumn>  $result 
	 * @param  array<string>               $primaryKeyCols 
	 * @param  array<string,array<string>> $uniqueKeyCols 
	 * @return void
	 */
	protected function parseConfigColumnsIds (array & $result, array $primaryKeyCols, array $uniqueKeyCols) {
		if (count($primaryKeyCols) > 0) {
			foreach ($primaryKeyCols as $urlName)
				$result[$urlName]->SetIdColumn(TRUE);
		} else if (count($uniqueKeyCols) > 0) {
			uasort($uniqueKeyCols, function ($a, $b) {
				$ac = count($a);
				$bc = count($b);
				if ($ac == $bc) return 0;
				return ($ac < $bc) ? -1 : 1;
			});
			$uniqueKeyColsKeys = array_keys($uniqueKeyCols);
			$uniqueKeyColsFirst = $uniqueKeyCols[$uniqueKeyColsKeys[0]];
			foreach ($uniqueKeyColsFirst as $urlName)
				$result[$urlName]->SetIdColumn(TRUE);
		}
	}
	
	/**
	 * Complete datagrid column config instance or `NULL`.
	 * @param  \ReflectionProperty  $prop
	 * @param  int                  $index
	 * @param  ?ColumnMeta          $colMetaData
	 * @param  bool                 $attrsAnotations
	 * @param  string|\MvcCore\Tool $toolClass
	 * @return ?ConfigColumn
	 */
	protected function parseConfigColumn (
		\ReflectionProperty $prop, $index, $colMetaData, $attrsAnotations, $toolClass
	) {
		if ($prop->isStatic()) return NULL;
		$attrClassFullName = static::$attrClassFullName;
		if ($attrsAnotations) {
			$attrClassNoFirstSlash = ltrim($attrClassFullName, '\\');
			$args = $toolClass::GetAttrCtorArgs($prop, $attrClassNoFirstSlash);
		} else {
			$tagName = $attrClassFullName::PHP_DOCS_TAG_NAME;
			$args = $toolClass::GetPhpDocsTagArgs($prop, $tagName);
			if (is_array($args) && count($args) === 2) {
				$attrClassName = basename(str_replace('\\', '/', $attrClassFullName));
				if ($args[0] === $attrClassName) 
					$args = (array) $args[1];
			}
		}

		if (!is_array($args)) $args = [];
		$propName = $prop->name;
		$args['propName'] = $propName;
		$allowNulls = NULL;
		$types = NULL;
		if ($colMetaData !== NULL) {
			$args['dbColumnName'] = $colMetaData->dbColumnName;
			$allowNulls = $colMetaData->allowNulls;
			if (!isset($args['types']) && $colMetaData->types !== NULL) 
				$args['types'] = $colMetaData->types;
			if (!isset($args['parserArgs']) && $colMetaData->parserArgs !== NULL) 
				$args['parserArgs'] = $colMetaData->parserArgs;
			if (!isset($args['formatArgs']) && $colMetaData->formatArgs !== NULL) 
				$args['formatArgs'] = $colMetaData->formatArgs;
		}
		if ($args === NULL || ($args !== NULL && !isset($args['dbColumnName']))) 
			return NULL;
		/** @var \ReflectionClass $columnType */
		/** @var \ReflectionParameter[] $ctorParams */
		list (
			$columnType, $ctorParams, $phpWithTypes, $phpWithUnionTypes
		) = $this->getAttrClassReflObjects();
		$typesNotSet = !isset($args['types']);
		if ($allowNulls === NULL || $typesNotSet) {
			list($types, $allowNulls) = $this->parseConfigColumnTypes(
				$prop, $phpWithTypes, $phpWithUnionTypes
			);
			if ($typesNotSet) $args['types'] = $types;
		}
		if (isset($args['filter'])) {
			$filter = $args['filter'];
			if ($allowNulls) {
				if (is_int($filter)) {
					//$filter = ~((~$filter) | self::FILTER_ALLOW_NOT_NULL); // remove allow not nulls flag if any
					$filter |= self::FILTER_ALLOW_NULL; // add allow nulls flag
				} else if ($filter === TRUE) {
					if (is_array($args['types']) && count($args['types']) > 0) {
						$firstType = $args['types'][0];
						if (strpos($firstType, '?') === FALSE)
							$args['types'][0] = '?' . $firstType;
					}
				}
			} else {
				if (is_int($filter)) {
					//$filter = ~((~$filter) | self::FILTER_ALLOW_NULL); // remove allow nulls flag if any
					$filter |= self::FILTER_ALLOW_NOT_NULL; // add allow not null flag
				} else if ($filter === TRUE) {
					if (is_array($args['types']) && count($args['types']) > 0) {
						$firstType = $args['types'][0];
						if (strpos($firstType, '?') === 0)
							$args['types'][0] = substr($firstType, 1);
					}
				}
			}
			$args['filter'] = $filter;
		}
		$ctorArgs = [];
		foreach ($ctorParams as $index => $ctorParam) 
			$ctorArgs[$index] = isset($args[$ctorParam->name])
				? $args[$ctorParam->name]
				: NULL;
		return $columnType->newInstanceArgs($ctorArgs);
	}

	/**
	 * Return database model metadata if row class
	 * implements extended model interface `\MvcCore\Ext\Models\Db\IModel`.
	 * @param  int $rowModelPropsFlags
	 * @return array<string,ColumnMeta>
	 */
	protected function getExtendedModelMetaData ($rowModelPropsFlags) {
		/** @var \MvcCore\Ext\Models\Db\Model $rowFullClassName */
		$rowFullClassName = $this->GetRowClass();
		$modelMetaData = [];
		list ($metaData) = $rowFullClassName::GetMetaData($rowModelPropsFlags);
		foreach ($metaData as $propData) {
			/** @var string $dbColumnName */
			$dbColumnName = $propData[4];
			/** @var bool $allowNulls */
			$allowNulls = $propData[1];
			/** @var array<string> $types */
			$types = $propData[2];
			/** @var array<mixed> $parserArgs */
			$parserArgs = $propData[5];
			/** @var array<mixed> $formatArgs */
			$formatArgs = $propData[6];
			/** @var bool $formatArgs */
			$primaryKey = $propData[7];
			/** @var bool|string|null $uniqueKey */
			$uniqueKey = $propData[9];
			if ($dbColumnName !== NULL) {
				$propertyName = $propData[3];
				$modelMetaData[$propertyName] = (object) [
					'dbColumnName'	=> $dbColumnName, 
					'allowNulls'	=> $allowNulls, 
					'types'			=> $types, 
					'parserArgs'	=> $parserArgs, 
					'formatArgs'	=> $formatArgs, 
					'primaryKey'	=> $primaryKey, 
					'uniqueKey'		=> $uniqueKey,
				];
			}
		}
		return $modelMetaData;
	}

	/**
	 * Get model reflection properties by model instance and access mod flags.
	 * @param  IGridRow|string $rowModelOrFullClassName 
	 * @param  int             $accesModFlags
	 * @return array<\ReflectionProperty>
	 */
	protected function parseConfigColumnsGetProps ($rowModelOrFullClassName, $accesModFlags) {
		$modelType = new \ReflectionClass($rowModelOrFullClassName);
		// `$accesModFlags` could contain foreing flags from model
		$localFlags = 0;
		if (($accesModFlags & \ReflectionProperty::IS_PRIVATE)	!= 0) $localFlags |= \ReflectionProperty::IS_PRIVATE;
		if (($accesModFlags & \ReflectionProperty::IS_PROTECTED)!= 0) $localFlags |= \ReflectionProperty::IS_PROTECTED;
		if (($accesModFlags & \ReflectionProperty::IS_PUBLIC)	!= 0) $localFlags |= \ReflectionProperty::IS_PUBLIC;
		return $localFlags === 0
			? $modelType->getProperties()
			: $modelType->getProperties($localFlags);
	}

	/**
	 * Get property types array and `TRUE` if property allow `NULL` values.
	 * @param  \ReflectionProperty $prop 
	 * @param  bool                $phpWithTypes 
	 * @param  bool                $phpWithUnionTypes 
	 * @return array{"0":array<string>,"1":bool}
	 */
	protected function parseConfigColumnTypes (\ReflectionProperty $prop, $phpWithTypes, $phpWithUnionTypes) {
		$phpWithTypes = PHP_VERSION_ID >= 70400;
		$phpWithUnionTypes = PHP_VERSION_ID >= 80000;
		$types = [];
		$allowNull = TRUE;
		if ($phpWithTypes && $prop->hasType()) {
			/** @var $reflType \ReflectionUnionType|\ReflectionNamedType */
			$refType = $prop->getType();
			if ($refType !== NULL) {
				if ($phpWithUnionTypes && $refType instanceof \ReflectionUnionType) {
					$refTypes = $refType->getTypes();
					/** @var \ReflectionNamedType $refTypesItem */
					$strIndex = NULL;
					foreach ($refTypes as $index => $refTypesItem) {
						$typeName = $refTypesItem->getName();
						if ($strIndex === NULL && $typeName === 'string')
							$strIndex = $index;
						if ($typeName !== static::NULL_STRING_VALUE)
							$types[] = $typeName;
					}
					if ($strIndex !== NULL) {
						unset($types[$strIndex]);
						$types = array_values($types);
						$types[] = 'string';
					}
				} else {
					$types = [$refType->getName()];
				}
				$allowNull = $refType->allowsNull();
			}
		} else {
			preg_match('/@var\s+([^\s]+)/', $prop->getDocComment(), $matches);
			if ($matches) {
				$rawTypes = '|'.$matches[1].'|';
				$nullPos = mb_stripos($rawTypes,'|null|');
				$qmPos = mb_strpos($rawTypes, '?');
				$qmMatched = $qmPos !== FALSE;
				$nullMatched = $nullPos !== FALSE;
				$allowNull = $qmMatched || $nullMatched;
				if ($qmMatched) 
					$rawTypes = str_replace('?', '', $rawTypes);
				if ($nullMatched)
					$rawTypes = (
						mb_substr($rawTypes, 0, $nullPos) . 
						mb_substr($rawTypes, $nullPos + 5)
					);
				$rawTypes = mb_substr($rawTypes, 1, mb_strlen($rawTypes) - 2);
				$types = explode('|', $rawTypes);
			}
		}
		return [$types, $allowNull];
	}

	/**
	 * Return cached reflection class for column config and it's constructor arguments array.
	 * @return array{"0":\ReflectionClass,"1":array<\ReflectionParameter>,"2":bool,"3":bool}
	 */
	protected function getAttrClassReflObjects () {
		static $__attrClassReflObjects = NULL;
		if ($__attrClassReflObjects === NULL) {
			$columnType = new \ReflectionClass(static::$attrClassFullName);
			$__attrClassReflObjects = [
				$columnType,
				$columnType->getConstructor()->getParameters(),
				PHP_VERSION_ID >= 70400, // $phpWithTypes
				PHP_VERSION_ID >= 80000, // $phpWithUnionTypes
			];
		}
		return $__attrClassReflObjects;
	}

}

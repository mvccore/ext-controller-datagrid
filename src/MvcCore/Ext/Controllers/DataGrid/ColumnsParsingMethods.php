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

/**
 * @mixin \MvcCore\Ext\Controllers\DataGrid
 */
trait ColumnsParsingMethods {
	
	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public function ParseConfigColumns () {
		$rowFullClassName = $this->GetRowClass();
		$rowClassPropsFlags = $this->rowClassPropsFlags !== 0
			? $this->rowClassPropsFlags
			: $rowFullClassName::GetDefaultPropsFlags();
		$rowModelMetaData = [];
		$this->initModelClasses(); // necessary to call for sure for completed property bellow
		if ($this->rowClassIsExtendedModel)
			$rowModelMetaData = $this->getExtendedModelMetaData($rowClassPropsFlags);
		$props = $this->parseConfigColumnsGetProps(
			$rowFullClassName, $rowClassPropsFlags
		);
		$app = \MvcCore\Application::GetInstance();
		$attrsAnotations = $app->GetAttributesAnotations();
		/** @var string|\MvcCore\Tool $toolClass */
		$toolClass = $app->GetToolClass();
		$columnConfigs = [];
		$naturalSort = [];
		$indexSort = [];
		foreach ($props as $index => $prop) {
			$columnConfig = $this->parseConfigColumn(
				$prop, $index, $rowModelMetaData, $attrsAnotations, $toolClass
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
			$columnConfigs[$urlName] = $columnConfig;
			if ($columnIndex === NULL) {
				$naturalSort[] = $urlName;
			} else {
				if (!isset($indexSort[$columnIndex]))
					$indexSort[$columnIndex] = [];
				$indexSort[$columnIndex][] = $urlName;
			}
		}
		if (count($indexSort) === 0) {
			return $columnConfigs;
		} else {
			return $this->parseConfigColumnSort(
				$columnConfigs, $naturalSort, $indexSort
			);
		}
	}

	/**
	 * Return database model metadata if row class
	 * implements extended model interface `\MvcCore\Ext\Models\Db\IModel`.
	 * @param  int $rowModelPropsFlags
	 * @return array
	 */
	protected function getExtendedModelMetaData ($rowModelPropsFlags) {
		/** @var \MvcCore\Ext\Models\Db\Model $rowFullClassName */
		$rowFullClassName = $this->GetRowClass();
		$modelMetaData = [];
		list ($metaData) = $rowFullClassName::GetMetaData($rowModelPropsFlags);
		foreach ($metaData as $propData) {
			$dbColumnName = $propData[4];
			$allowNulls = $propData[1];
			$types = $propData[2];
			$parserArgs = $propData[5];
			$formatArgs = $propData[6];
			if ($dbColumnName !== NULL) {
				$propertyName = $propData[3];
				$modelMetaData[$propertyName] = [
					$dbColumnName, $allowNulls, $types, $parserArgs, $formatArgs
				];
			}
		}
		return $modelMetaData;
	}

	/**
	 * Get model reflection properties by model instance and access mod flags.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridRow|string $rowModelOrFullClassName 
	 * @param  int                                                       $accesModFlags
	 * @return \ReflectionProperty[]
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
	 * Complete datagrid column config instance or `NULL`.
	 * @param  \ReflectionProperty  $prop
	 * @param  int                  $index
	 * @param  array                $modelMetaData
	 * @param  bool                 $attrsAnotations
	 * @param  string|\MvcCore\Tool $toolClass
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column|NULL
	 */
	protected function parseConfigColumn (
		\ReflectionProperty $prop, $index, $modelMetaData, $attrsAnotations, $toolClass
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
		$allowNull = NULL;
		$types = NULL;
		if (isset($modelMetaData[$propName])) {
			list(
				$args['dbColumnName'], $allowNull, $types, $parserArgs, $formatArgs
			) = $modelMetaData[$propName];
			if (!isset($args['types']) && $types !== NULL) 
				$args['types'] = $types;
			if (!isset($args['parserArgs']) && $parserArgs !== NULL) 
				$args['parserArgs'] = $parserArgs;
			if (!isset($args['formatArgs']) && $formatArgs !== NULL) 
				$args['formatArgs'] = $formatArgs;
		}
		if ($args === NULL || ($args !== NULL && !isset($args['dbColumnName']))) 
			return NULL;
		/** @var \ReflectionClass $columnType */
		/** @var \ReflectionParameter[] $ctorParams */
		list (
			$columnType, $ctorParams, $phpWithTypes, $phpWithUnionTypes
		) = $this->getAttrClassReflObjects();
		$typesNotSet = !isset($args['types']);
		if ($allowNull === NULL || $typesNotSet) {
			list($types, $allowNull) = $this->parseConfigColumnTypes(
				$prop, $phpWithTypes, $phpWithUnionTypes
			);
			if ($typesNotSet) $args['types'] = $types;
		}
		if (isset($args['filter'])) {
			$filter = $args['filter'];
			if ($allowNull) {
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
	 * Get property types array and `TRUE` if property allow `NULL` values.
	 * @param  \ReflectionProperty $prop 
	 * @param  bool                $phpWithTypes 
	 * @param  bool                $phpWithUnionTypes 
	 * @return array               [\string[], bool]
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
	 * @return array [\ReflectionClass, \ReflectionParameter[], bool, bool]
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

	/**
	 * Sort config colums by optional grid column index.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column[] $columnConfigs 
	 * @param  \string[]                                           $naturalSort 
	 * @param  array                                               $indexSort 
	 * @return array
	 */
	protected function parseConfigColumnSort (array $columnConfigs, array $naturalSort, array $indexSort) {
		$result = [];
		ksort($indexSort);
		foreach ($indexSort as $columnIndex => $indexedUrlNames) 
			array_splice($naturalSort, $columnIndex, 0, $indexedUrlNames);
		foreach ($naturalSort as $urlName) 
			$result[$urlName] = $columnConfigs[$urlName];
		return $result;
	}
}

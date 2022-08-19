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
	 * Try to parse decorated class properties atributes or PHPDocs tags
	 * to complete array of datagrid columns configuration.
	 * 
	 * First argument is datagrid model instance used to get all instance properties.
	 * 
	 * Second argument is used for automatic columns configuration completion
	 * by model class implementing `\MvcCore\Ext\Controllers\DataGrids\Models\IGridColumns`.
	 * Array keys are properties names, array values are arrays with three items:
	 * - `string`    - database column name 
	 * - `\string[]` - property type(s)
	 * - `array`     - format arguments
	 * 
	 * Third argument is access mod flags to load model instance properties.
	 * If value is zero, there are used all access mode flags - private, protected and public.
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel|string $rowModelOrFullClassName
	 * @param  array                                                       $rowModelMetaData
	 * @param  int                                                         $rowModelAccessModFlags
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public static function ParseConfigColumns (
		$rowModelOrFullClassName, 
		$rowModelMetaData = [], 
		$rowModelAccessModFlags = 0
	) {
		$props = static::parseConfigColumnsGetProps(
			$rowModelOrFullClassName, $rowModelAccessModFlags
		);
		$app = \MvcCore\Application::GetInstance();
		$attrsAnotations = $app->GetAttributesAnotations();
		/** @var string|\MvcCore\Tool $toolClass */
		$toolClass = $app->GetToolClass();
		$columnConfigs = [];
		$naturalSort = [];
		$indexSort = [];
		foreach ($props as $index => $prop) {
			$columnConfig = static::parseConfigColumn(
				$prop, $index, $rowModelMetaData, $attrsAnotations, $toolClass
			);
			if ($columnConfig === NULL) continue;
			$urlName = $columnConfig->GetUrlName();
			$columnIndex = $columnConfig->GetColumnIndex();
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
			return static::parseConfigColumnSort(
				$columnConfigs, $naturalSort, $indexSort
			);
		}
	}

	/**
	 * Get model reflection properties by model instance and access mod flags.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel|string $rowModelOrFullClassName 
	 * @param  int                                                         $accesModFlags
	 * @return \ReflectionProperty[]
	 */
	protected static function parseConfigColumnsGetProps ($rowModelOrFullClassName, $accesModFlags) {
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
	protected static function parseConfigColumn (
		\ReflectionProperty $prop, $index, $modelMetaData, $attrsAnotations, $toolClass
	) {
		if ($prop->isStatic()) NULL;
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
				$args['dbColumnName'], $allowNull, $types, $format
			) = $modelMetaData[$propName];
			if (!isset($args['types']) && $types !== NULL) $args['types'] = $types;
			if (!isset($args['format']) && $format !== NULL) $args['format'] = $format;
		}
		if ($args === NULL || ($args !== NULL && !isset($args['dbColumnName']))) return NULL;
		/** @var \ReflectionClass $columnType */
		/** @var \ReflectionParameter[] $ctorParams */
		list ($columnType, $ctorParams, $phpWithTypes, $phpWithUnionTypes) = static::getAttrClassReflObjects();
		$typesNotSet = !isset($args['types']);
		if ($allowNull === NULL || $typesNotSet) {
			list($types, $allowNull) = static::parseConfigColumnTypes($prop, $phpWithTypes, $phpWithUnionTypes);
			if ($typesNotSet) $args['types'] = $types;
		}
		if (isset($args['filter']) && $allowNull) {
			$filter = $args['filter'];
			if (is_int($filter)) {
				$filter |= self::FILTER_ALLOW_NULL;
			} else if ($filter === TRUE) {
				$filter = self::FILTER_ALLOW_ALL;
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
	protected static function parseConfigColumnTypes (\ReflectionProperty $prop, $phpWithTypes, $phpWithUnionTypes) {
		$phpWithTypes = PHP_VERSION_ID >= 70400;
		$phpWithUnionTypes = PHP_VERSION_ID >= 80000;
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
						if ($typeName !== 'null')
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
	protected static function getAttrClassReflObjects () {
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
	protected static function parseConfigColumnSort (array $columnConfigs, array $naturalSort, array $indexSort) {
		$result = [];
		ksort($indexSort);
		foreach ($indexSort as $columnIndex => $indexedUrlNames) 
			array_splice($naturalSort, $columnIndex, 0, $indexedUrlNames);
		foreach ($naturalSort as $urlName) 
			$result[$urlName] = $columnConfigs[$urlName];
		return $result;
	}
}
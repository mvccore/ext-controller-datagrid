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

namespace MvcCore\Ext\Controllers\DataGrids\Models;

trait GridRow {
	
	/**
	 * Cache for local instance properties to serialize in JSON data.
	 * @var array<string, bool>|NULL
	 */
	protected static $jsonPropsCache = NULL;

	/**
	 * Datagrid instance, always initialized by datagrid component automatically.
	 * @var \MvcCore\Ext\Controllers\DataGrid|NULL
	 */
	protected $grid = NULL;

	/**
	 * Return local instance properties not to serialize in JSON data.
	 * @return array<string, bool>
	 */
	public function GetJsonNonGridProps () {
		return [
			'grid'	=> TRUE,
		];
	}
	
	/**
	 * Set datagrid instance, always initialized by datagrid component automatically.
	 * @param  \MvcCore\Ext\Controllers\DataGrid|NULL $grid
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridRow
	 */
	public function SetGrid (\MvcCore\Ext\Controllers\IDataGrid $grid = NULL) {
		$this->grid = $grid;
		return $this;
	}

	/**
	 * Render value with by possible view helper as scalar value 
	 * into datagrid table cell (convertable into string).
	 * @param  \MvcCore\Ext\Controllers\DataGrid $grid
	 * @param  string                            $columnPropName 
	 * @param  \MvcCore\View|NULL                $view
	 * @return string
	 */
	public function RenderCellByPropName (
		\MvcCore\Ext\Controllers\IDataGrid $grid,
		$columnPropName,
		\MvcCore\IView $view = NULL 
	) {
		$columnConfig = $grid->GetConfigColumns(FALSE)->GetByPropName($columnPropName);
		return $this->RenderCell(
			$columnConfig, $view
		);
	}

	/**
	 * Render value with by possible view helper as scalar value 
	 * into datagrid table cell (convertable into string).
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $columnConfig 
	 * @param  \MvcCore\View|NULL                                $view
	 * @return string
	 */
	public function RenderCell (
		\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $columnConfig,
		\MvcCore\IView $view = NULL
	) {
		$propName = $columnConfig->GetPropName();
		$value = $this->{'Get' . ucfirst($propName)}();
		if ($value === NULL) 
			return '';
		$viewHelperName = $columnConfig->GetViewHelper();
		if ($viewHelperName) {
			$formatArgs = $columnConfig->GetFormatArgs() ?: [];
			return call_user_func_array(
				[$view, $viewHelperName], 
				// ipass into view helper only first format arg, 
				// the second is always used for db like formating
				array_merge([$value], count($formatArgs) > 0 ? [$formatArgs[0]] : [])
			);
		} else {
			return static::convertToScalar(
				$propName, $value, $columnConfig->GetParserArgs()
			);
		}
	}

	/**
	 * @inheritDocs
	 * @param  int $propsFlags
	 * @return array<string, mixed>
	 */
	#[\ReturnTypeWillChange]
	public function jsonSerialize ($propsFlags = 0) {
		if (static::$jsonPropsCache === NULL) {
			if ($propsFlags === 0) $propsFlags = static::$defaultPropsFlags;
			list ($metaData, $sourceCodeNamesMap) = static::GetMetaData(
				$propsFlags, [\MvcCore\Ext\Models\Db\Model\IConstants::METADATA_BY_CODE]
			);
			$jsonPropsCache = [];
			foreach ($sourceCodeNamesMap as $propertyName => $metaDataIndex) {
				list($propIsPrivate) = $metaData[$metaDataIndex];
				$jsonPropsCache[$propertyName] = $propIsPrivate;
			}
			static::$jsonPropsCache = $jsonPropsCache;
		}
		$phpWithTypes = PHP_VERSION_ID >= 70400;
		$result = [];
		foreach (static::$jsonPropsCache as $propertyName => $propIsPrivate) {
			$propValue = NULL;
			if ($propIsPrivate) {
				$prop = new \ReflectionProperty($this, $propertyName);
				$prop->setAccessible(TRUE);
				if ($phpWithTypes) {
					if ($prop->isInitialized($this))
						$propValue = $prop->getValue($this);
				} else {
					$propValue = $prop->getValue($this);
				}
			} else if (isset($this->{$propertyName})) {
				$propValue = $this->{$propertyName};
			}
			$result[$propertyName] = $propValue;
		}
		return array_diff_key($result, static::GetJsonNonGridProps());
	}
}
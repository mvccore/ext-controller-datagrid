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

/**
 * This mixin is always used in grid model class
 * to be able to complete columns automatically from
 * model class context or in any custom way from any 
 * custom row model class.
 * @mixin \MvcCore\Model|\MvcCore\Ext\Models\Db\Model
 */
trait GridColumns {

	/**
	 * Return array of datagrid columns config.
	 * This method is called by datagrid component to parse decorated 
	 * model properties to complete datagrid columns configuration automatically.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public function GetConfigColumns () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $this */
		$rowFullClassName = get_class($this); // row model is the same as grid model by default
		$gridClass = get_class($this->grid);
		list(
			$rowModelMetaData, $rowModelAccessModFlags
		) = static::getConfigColumnsModelMetaData($rowFullClassName);
		return $gridClass::ParseConfigColumns(
			$rowFullClassName, $rowModelMetaData, $rowModelAccessModFlags
		);
	}

	/**
	 * Return database model metadata and default access mod flags,
	 * if `$this` context implements `\MvcCore\Ext\Models\Db\IModel`.
	 * @param  \MvcCore\Ext\Models\Db\Model|string $rowContextOrFullClassName
	 * @return array
	 */
	protected static function getConfigColumnsModelMetaData ($rowContextOrFullClassName) {
		$accessModFlags = 0;
		$modelMetaData = [];
		$toolClass = \MvcCore\Application::GetInstance()->GetToolClass();
		$rowFullClassName = is_string($rowContextOrFullClassName) 
			? $rowContextOrFullClassName 
			: get_class($rowContextOrFullClassName);
		$implementsExtendedModel = $toolClass::CheckClassInterface(
			$rowFullClassName, 
			'MvcCore\\Ext\\Models\\Db\\IModel', FALSE, FALSE
		);
		if ($implementsExtendedModel) {
			/** @var \MvcCore\Ext\Models\Db\Model $rowContext */
			list($metaData) = $rowFullClassName::GetMetaData(0);
			foreach ($metaData as $propData) {
				$dbColumnName = $propData[4];
				$allowNulls = $propData[1];
				$types = $propData[2];
				$parserArgs = $propData[5];
				$formatArgs = $propData[6];
				if ($dbColumnName !== NULL) {
					$propertyName = $propData[3];
					$modelMetaData[$propertyName] = [$dbColumnName, $allowNulls, $types, $parserArgs, $formatArgs];
				}
			}
			if (static::$defaultPropsFlags !== 0)
				$accessModFlags = static::$defaultPropsFlags;
		}
		return [$modelMetaData, $accessModFlags];
	}
}

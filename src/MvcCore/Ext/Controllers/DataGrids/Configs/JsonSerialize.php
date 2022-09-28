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

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

/**
 * Define this param to serialize into JSON.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class JsonSerialize {
	
	const PHP_DOCS_TAG_NAME = '@jsonSerialize';

	/**
	 * Return array with class properties marked with 
	 * `#[JsonSerialize]` attribute or `@jsonSerialize` PHP Docs tag.
	 * @param  mixed $object 
	 * @param  int   $propsFlags 
	 * @return array
	 */
	public static function Serialize ($object, $propsFlags = \ReflectionProperty::IS_PROTECTED) {
		/** @var \ReflectionProperty[] $props */
		$props = (new \ReflectionClass($object))->getProperties($propsFlags);
		$jsonSerializeClass = get_called_class();
		$preferAttrs = \MvcCore\Application::GetInstance()->GetAttributesAnotations();
		$result = [];
		foreach ($props as $prop) {
			if ($prop->isStatic()) continue;
			$attrArgs = $preferAttrs
				? \MvcCore\Tool::GetAttrCtorArgs($prop, $jsonSerializeClass)
				: \MvcCore\Tool::GetPhpDocsTagArgs($prop, $jsonSerializeClass::PHP_DOCS_TAG_NAME);
			if ($attrArgs === NULL) continue;
			$getter = 'Get' . ucfirst($prop->name);
			if (method_exists($object, $getter)) {
				$result[$prop->name] = $object->{$getter}();
			} else {
				$prop->setAccessible(TRUE);
				$result[$prop->name] = $prop->getValue($object);
			}
		}
		return $result;
	}
}
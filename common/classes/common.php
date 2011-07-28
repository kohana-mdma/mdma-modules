<?php defined('SYSPATH') or die('No direct script access.');

class Common {

	public static function object2array($object) {
		if (!is_array($object) && !is_object($object))
			return $object;
		$array = array();
		if (method_exists($object, 'as_array'))
			$object = $object->as_array();
		foreach ($object as $key => $value) {
			if (is_object($value)) {
				$array [$key] = self::object2array($value);
			} else {
				$array [$key] = $value;
			}
		}
		return $array;
	}

}

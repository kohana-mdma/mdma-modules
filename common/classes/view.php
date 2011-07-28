<?php defined('SYSPATH') or die('No direct script access.');

class View extends Kohana_View {

	public function as_array() {
		$data = $this->_data + View::$_global_data;
		// Echo global data once - protect for included View
		$global_data = View::$_global_data;
		$array = View::$_global_data = array();
		foreach ($data as $key => $value) {
			$array [$key] = Common::object2array($value);
		}
		View::$_global_data = $global_data;
		return $array;
	}

}
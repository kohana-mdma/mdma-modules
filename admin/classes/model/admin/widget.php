<?php defined('SYSPATH') or die('No direct script access.');

class Model_Admin_Widget extends ORM {

	public function set($column, $value)
	{
		if($column == 'attributes') $value = serialize($value);
		return parent::set($column, $value);
	}

	public function __get($column)
	{
		$value = parent::__get($column);
		if($column == 'attributes') $value = unserialize($value);
		return $value;
	}

	function render(){
		return Request::factory(Route::get($this->route)->uri($this->attributes))->execute();
	}

	function active(){
		return $this->where('enable', '=', '1');
	}
}
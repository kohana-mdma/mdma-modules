<?php defined('SYSPATH') or die('No direct script access.');

class Model_Page extends ORM_Versioned {

	public function filters(){
		return array(
			'keywords' => array(
				array(array($this, 'unique_keywords'), array(':value'))
			),
		);
	}

	public function unique_keywords($keywords){
		return implode(', ', array_unique(array_map('UTF8::trim', explode(',', $keywords))));
	}
}

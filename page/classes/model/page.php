<?php defined('SYSPATH') or die('No direct script access.');

class Model_Page extends ORM_Versioned {

	public function rules() {
		return array(
			'title' => array(
				array('not_empty'),
			),
		);
	}
	
	public function filters(){
		return array(
			'keywords' => array(
				array('HTML::chars', array(':value')),
			),
			'description' => array(
				array('HTML::chars', array(':value')),
			),
		);
	}
}

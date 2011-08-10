<?php defined('SYSPATH') or die('No direct script access.');

class Model_Page extends ORM_Versioned {

	public function rules() {
		return array(
			'title' => array(
				array('not_empty'),
			),
		);
	}
	
}

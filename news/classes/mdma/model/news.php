<?php defined('SYSPATH') or die('No direct access allowed.');

class MDMA_Model_News extends ORM {
	
	protected $_has_many = array(
		'images' => array(
			'model' => 'news_image'
		),
	);
		
	function filters()
	{
		return array(
			'created_on' => array(
	            array('Date::formatted_time', array(':value', 'Y-m-d H:i:s')),
			),
		);
	}
}
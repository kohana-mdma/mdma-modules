<?php defined('SYSPATH') or die('No direct script access.');

class MDMA_Controller_Admin_News extends Controller_Admin_CRUD {

	protected function after_save(ORM $item = NULL)
	{
		if($item->saved()){
			$node = ORM::factory('node')
					->where('model', '=', 'news')
					->where('model_id', '=', $item->pk())
					->find()
					->values($_POST[$this->_model] + Arr::get($_POST, 'node', array()));
			$node->request = 'news/view/'.$item->pk();
			$node->model = 'news';
			$node->model_id = $item->pk();
			$node->type = 'leaf';
			$node->insert(ORM::factory('node', array('name'=>'news')));
		}
	}

	protected function after_load(array $data = NULL)
	{
		$node = ORM::factory('node')
					->where('model', '=', 'news')
					->where('model_id', '=', $data[$this->_model]['id'])
					->find();
		$data['node'] = $node->as_array();
		return $data;
	}
	
} // End Admin News
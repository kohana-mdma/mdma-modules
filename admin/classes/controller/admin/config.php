<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Config extends Controller_Admin_Template {

	public function action_index()
	{
		if(isset($_POST['config'])){
			Message::success("Конфигурация обновленна.");
			foreach($_POST['config'] as $key=>$value){
				Kohana::$config->_write_config('site', strtr($key, '_','.'), $value);
			}		
		}
		$items = Kohana::$config->load('site')->as_array();
		$this->template->content = View::factory('admin/config')->bind('items', $items);
    }
} // End Config
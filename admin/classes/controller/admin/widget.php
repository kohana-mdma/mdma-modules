<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Widget extends Controller_Admin_Template {

	public function action_index()
	{
		$items = ORM::factory('admin_widget')->find_all();
		$this->template->content = View::factory('admin/widget/list')
				->bind('items',$items);
	}

	public function action_update()
	{
		$all_widget =ORM::factory('admin_widget')->find_all()->as_array('title');
		$widgets = Kohana::$config->load('admin.widget');
		$error = array();
		if($widgets){
			uasort($widgets, function ($a, $b){return strnatcmp(Arr::get($a, 'sort', 0), Arr::get($b, 'sort', 0));});
			foreach($widgets as $widget){
				if(array_key_exists($widget['title'], $all_widget)){
					$new_widget = ORM::factory('admin_widget', $all_widget[$widget['title']]->id);
					$new_widget->attributes = $new_widget->attributes + $widget['attributes'];
				}
				else
				{
					$new_widget = ORM::factory('admin_widget');
				}
				$new_widget->values($widget);
				try{
					$new_widget->save();
					unset($all_widget[$widget['title']]);
				}
				catch (ORM_Validation_Exception $e)
				{
					$error[] = 'Виджет "'.$widget['title'].'" неудалось обновить.'; 	
				}
			}
		}
		foreach($all_widget as $widget){
			$widget->delete();
		}
		Message::error($error);
		$this->request->redirect('admin/widget');
    }

	public function action_enable()
	{
		$widget = ORM::factory('admin_widget', $this->request->param('id'));
		$widget->enable = ((int)(bool)$widget->enable) * (-1) +1;
		$widget->save();
		if($widget->enable == 1)
		{
			Message::success('Модуль включен');
		}
		else
		{
			Message::success('Модуль отключен');
		}
		$this->request->redirect('admin/widget');
	}


} // End widgit
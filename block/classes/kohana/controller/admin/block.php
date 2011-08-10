<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Admin_Block extends Controller_Admin_Template {


	public function action_index()
	{
        $blocks = ORM::factory('block')->find_all();
	    $this->template->content = View::factory('admin/block/list')->bind('blocks',$blocks);
	}

	public function action_edit()
	{
		$id = $this->request->param('id');
		$this->ajaxAllow = TRUE;
		$version = $this->request->param('version');

		$block = ORM::factory('block', $id);
		$this->template->content = View::factory('admin/block/form');
		$this->template->content->version = $version;
		$this->template->sidebar[] =
				View::factory('admin/block/sidebar')
				->set('history',$block->history())
				->set('block',$block)
				->set('version', $version)
				->render();
	    if ($_POST)
	    {
			if($version)
			{
				try
				{
					$block->restore($version);
					Message::set(Message::SUCCESS, 'Востановление версии прошло успешно');
					$this->request->redirect('admin/block/edit/'.$block->id);
				}
				catch (ORM_Validation_Exception $e)
				{
					Message::set(Message::ERROR, $e->errors('block'));
					$this->request->redirect('admin/block/edit/'.$block->id.'/version/'.$version);
				}
			}
			else
			{
				$block = $block->values($_POST, array('name', 'body'));
				if($id != Arr::get($_POST,'id') and ! $this->request->is_ajax()){
					$block->id = Arr::get($_POST,'id');
				}

				try
				{
					$block->save();
					Message::set(Message::SUCCESS, 'Изменения прошли удачно');
					if( ! $this->request->is_ajax()){
						$this->request->redirect('admin/block/edit/'.$block->id);
					}
					$this->template->content->data = $block->as_array();

				}
				catch (ORM_Validation_Exception $e)
				{
					Message::set(Message::ERROR, $e->errors('block'));
					$this->template->content->data = $_POST;
				}
			}
	    }
	    else
	    {
			if($version) $block = $block->previous($version);
			$this->template->content->data = $block->as_array();
	    }
	}

	public function action_add()
	{
	    if ($_POST)
	    {
			$block = ORM::factory('block')
					->values($_POST, array('id', 'name', 'body'));
			try
			{
				$block->save();
				Message::set(Message::SUCCESS, 'Добовление прошло успешно');
				$this->request->redirect('admin/block/edit/'.$block->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set(Message::ERROR, $e->errors('block'));
			}
	    }
	    $this->template->content = View::factory('admin/block/form')->set('data',$_POST);
	}

	public function action_delete()
	{
		$id = $this->request->param('id');
		$block = ORM::factory('block', $id);
		try
		{
		    $block->delete();
		    Message::set(Message::SUCCESS, 'Удаление прошло успешно');
		    if( ! $this->request->is_ajax())$this->request->redirect('admin/block/');
		}
		catch (ORM_Validation_Exception $e)
		{
		    Message::set(Message::ERROR, $e->errors('block'));
			if( ! $this->request->is_ajax())$this->request->redirect('admin/block/');
		}
	}
} // End Block
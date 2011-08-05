<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Page extends Controller_Admin_Template {

	protected $_model = 'page';

	public function action_add()
	{
		if ($_POST)
	    {
			$item = ORM::factory($this->_model)->values($_POST[$this->_model]);
			try
			{
				$item->save();
				if($item->check()){
					$node = ORM::factory('node');
					$node_title = Arr::path($_POST, 'node.title');
					$node->title = ($node_title)?$node_title:Arr::path($_POST, $this->_model.'.title');
					$node->menu_title = Arr::path($_POST, 'node.menu_title');
					$node->name = Arr::path($_POST, 'node.name');
					$node->request = 'page/'.$item->pk();
					$node->model = 'page';
					$node->model_id = $item->pk();
					if(arr::path($_POST, 'node.folder', 0))$node->type = 'folder';
					$node->insert($this->request->param('node'));
				}

				Message::success('Добовление прошло успешно');
				if($this->request->is_ajax()){
					$this->response->body(json_encode(Message::get()));
					return ;
				}else{
					$this->request->redirect('admin/'.$this->_model.'/edit/'.$item->pk());
				}
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::error(Arr::flatten($e->errors($this->_model)));
				if($this->request->is_ajax()){
					$this->response->body(json_encode(Message::get()));
					return ;
				}
			}
	    }
		$this->template->content = View::factory('admin/'.$this->_model.'/form')
				->set('_model', $this->_model)
				->set('data', $_POST);
	}

	public function action_edit()
	{
		$id = $this->request->param('id');
		$version = $this->request->param('version');

		$item = ORM::factory($this->_model, $id);
		if($version and $item instanceof ORM_Versioned){
			$data[$this->_model] = ORM::factory($this->_model, $id)->previous($version)->as_array();
		}else{
			$data[$this->_model] = $item->as_array();
		}
		$this->template->content = View::factory('admin/'.$this->_model.'/form');
		$this->template->content->_model  = $this->_model;
		$node = ORM::factory('node')->load_node($this->_model, $data[$this->_model]['id'])->find();
		$data['node'] = $node->as_array();
		if($item instanceof ORM_Versioned){
			$this->template->content->version = $version;
			$this->template->sidebar[] =
					View::factory('admin/page/sidebar/history')
					->set('history',$item->history())
					->set('item',$item)
					->set('version', $version)
					->set('_model', $this->_model)
					->render();
		}
	    if ($_POST)
	    {
			if($item instanceof ORM_Versioned and $version)
			{
				try
				{
					$item->restore($version);
					Message::success('Востановление версии прошло успешно');
					$this->request->redirect('admin/'.$this->_model.'/edit/'.$item->id);
				}
				catch (ORM_Validation_Exception $e)
				{
					Message::error(Arr::flatten($e->errors($this->_model)));
					$this->request->redirect('admin/'.$this->_model.'/edit/'.$item->id.'/version/'.$version);
				}
			}

			$item = $item->values($_POST[$this->_model]);
			try
			{
				$item->save();
				if($item->check()){
					$node_title = Arr::path($_POST, 'node.title');
					$node->title = ($node_title)?$node_title:Arr::path($_POST, $this->_model.'.title');
					$node->name = Arr::path($_POST, 'node.name');
					$node->menu_title = Arr::path($_POST, $this->_model.'.menu_title');
					if(arr::path($_POST, 'node.folder', 0))$node->type = 'folder';
					$node->save();
				}
				Message::success('Изменения прошли успешно');
				$this->request->redirect('admin/'.$this->_model.'/edit/'.$item->pk());
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::error(Arr::flatten($e->errors('node'), $e->errors($this->_model)));
				$this->template->content->data = $_POST;
			}
		}
	    else
	    {
			$this->template->content->data = $data;
	    }
	}
} // End Page
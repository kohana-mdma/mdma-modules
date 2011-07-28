<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Admin_Crud extends Controller_Admin_Template {

	protected $_model = NULL;
	protected $_name = NULL;

	public function before(){
		if( ! $this->_model){
			$this->_model = strtolower(substr(get_class($this), 17));
		}
		if( ! $this->_name){
			$this->_name = strtolower(substr(get_class($this), 17));
		}
		parent::before();
	}

	protected function view_path($file){

		// Detect if there was a file extension
		$_file = explode('.', $file);

		// If there are several components
		if (count($_file) > 1)
		{
			// Take the extension
			$ext = array_pop($_file);
			$file = implode('.', $_file);
		}
		// Otherwise set the extension to the standard
		else
		{
			$ext = ltrim(EXT, '.');
		}
		$path = 'admin/'.$this->_name.'/'.$file;
		if(Kohana::find_file('views', $path, $ext) === FALSE){
			$path = 'admin/crud/'.$file;
		}
		if (Kohana::find_file('views', $path, $ext) === FALSE)
		{
			throw new Kohana_View_Exception('The requested view :file could not be found', array(
				':file' => $file.'.'.$ext,
			));
		}
		return $path;
	}

	public function action_index()
	{
        $items = ORM::factory($this->_model)->find_all();
	    $this->template->content = View::factory($this->view_path('list'))
				->set('_name', $this->_name)
				->set('_model', $this->_model)
				->bind('items',$items);
	}

	public function action_show()
	{
		$id = $this->request->param('id');
		$item = ORM::factory($this->_model, $id);
	    $this->template->content = View::factory($this->view_path('show'))
				->set('_name', $this->_name)
				->set('_model', $this->_model)
				->bind('item',$item);
	}

	public function action_edit()
	{
		$id = $this->request->param('id');
		$version = $this->request->param('version');

		$data = array();
		$item = ORM::factory($this->_model, $id);
		if($version and $item instanceof ORM_Versioned){
			$data[$this->_model] = ORM::factory($this->_model, $id)->previous($version)->as_array();
		}else{
			$data[$this->_model] = $item->as_array();
		}
		$data = $this->after_load($data);
		$this->template->content = View::factory($this->view_path('form'));
		$this->template->content->_model  = $this->_model;
		$this->template->content->_name  = $this->_name;
		if($item instanceof ORM_Versioned){
			$this->template->content->version = $version;
			$this->template->sidebar[] =
					View::factory($this->view_path('sidebar/history'))
					->set('history',$item->history())
					->set('item',$item)
					->set('version', $version)
					->set('_model', $this->_model)
					->set('_name', $this->_name)
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
					$this->request->redirect('admin/'.$this->_name.'/edit/'.$item->id);
				}
				catch (ORM_Validation_Exception $e)
				{
					Message::error(Arr::flatten($e->errors($this->_model)));
					$this->request->redirect('admin/'.$this->_name.'/edit/'.$item->id.'/version/'.$version);
				}
			}

			$item = $item->values($_POST[$this->_model]);
			try
			{
				$valid = $this->before_save($item);
				$item->save($valid);
				foreach($item->has_many() as $k=>$elm){
					if(Arr::path($_POST, $this->_model.'.'.$k.'.form')==="1"){
						unset($_POST[$this->_model][$k]['form']);
						if(isset($elm['through'])){
							$item->remove($k, NULL);
							if(Arr::path($_POST, $this->_model.'.'.$k))
							{
								$item->add($k, Arr::path($_POST, $this->_model.'.'.$k, array()));
							}	
						}else{
							DB::update(ORM::factory($elm['model'])->table_name())
								->where($elm['foreign_key'], '=', $item->pk())
								->value($elm['foreign_key'], NULL)
								->execute();
							if(Arr::path($_POST, $this->_model.'.'.$k))
							{
								DB::update(ORM::factory($elm['model'])->table_name())
									->where(ORM::factory($elm['model'])->primary_key(), 'IN', Arr::path($_POST, $this->_model.'.'.$k, array()))
									->value($elm['foreign_key'], $item->pk())
									->execute();
							}
						}
					}
				}
				$this->after_save($item);
				Message::success('Изменения прошли успешно');
				$this->request->redirect('admin/'.$this->_name.'/edit/'.$item->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::error(Arr::flatten($e->errors($this->_model)));
				$this->template->content->data = $_POST;
			}
		}
	    else
	    {
			$this->template->content->data = $data;
	    }
	}

	public function action_add()
	{
		if ($_POST)
	    {
			$item = ORM::factory($this->_model)->values($_POST[$this->_model]);
			try
			{
				$valid = $this->before_save($item);
				$item->save($valid);
				foreach($item->has_many() as $k=>$elm){
					if(Arr::path($_POST, $this->_model.'.'.$k.'.form')==="1"){
						unset($_POST[$this->_model][$k]['form']);
						if(Arr::path($_POST, $this->_model.'.'.$k))
						{
							if(isset($elm['through'])){
								$item->add($k, Arr::path($_POST, $this->_model.'.'.$k, array()));
							}else{
								DB::update(ORM::factory($elm['model'])->table_name())
										->where(ORM::factory($elm['model'])->primary_key(), 'IN', Arr::path($_POST, $this->_model.'.'.$k, array()))
										->value($elm['foreign_key'], $item->pk())
										->execute();
							}
						}
					}

				}
				$this->after_save($item);
				Message::success('Добовление прошло успешно');
				$this->request->redirect('admin/'.$this->_name.'/edit/'.$item->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::error(Arr::flatten($e->errors($this->_model)));
			}
	    }
	    $this->template->content = View::factory($this->view_path('form'))
				->set('_model', $this->_model)
				->set('_name', $this->_name)
				->set('data',$_POST);
	}

	public function action_delete(){
		$id = $this->request->param('id');
		$item = ORM::factory($this->_model, $id);
		try
		{
			$this->before_delete($item);
		    $item->delete();
			$this->after_delete($item);
		    Message::success('Удаление прошло успешно');
		    if( ! $this->request->is_ajax())$this->request->redirect($this->request->referrer());
		}
		catch (ORM_Validation_Exception $e)
		{
		    Message::error($e->errors($this->_model));
			if( ! $this->request->is_ajax())$this->request->redirect($this->request->referrer());
		}
	}

	protected function before_save(ORM $item = NULL)
	{
		return NULL;
	}

	protected function after_save(ORM $item = NULL)
	{

	}

	protected function before_delete(ORM $item = NULL)
	{

	}

	protected function after_delete(ORM $item = NULL)
	{

	}

	protected function after_load(array $data = NULL)
	{
		return $data;
	}
} // End crud
<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Node extends Controller_Admin_Template {

	public function action_children(){
	    $this->auto_render = FALSE;
		$id = (int) Arr::get($_POST, 'id');
		$node = ORM::factory('node', $id);
		if($node->loaded()){
			foreach($node->load_tree()->children as $id=>$child){
				$result[]=array(
					"attr" => array(
						"id" => $id,
						"rel" => $child->model.'.'.$child->model_id,
						),
					"data" => $child->title,
					"state" => "",
				);
			}
			$this->response->body(json_encode(array('type'=>'success', 'data'=>$result)));
		}else{
			$this->response->body(json_encode(array('type'=>'error')));
		}
	}

	public function action_url(){
	    $this->auto_render = FALSE;
		$id = (int) Arr::get($_POST, 'id');
		$node = ORM::factory('node', $id);
		if($node->loaded()){
			$result['url'] = URL::site($node->url_path);
			$this->response->body(json_encode(array('type'=>'success', 'data'=>$result)));
		}else{
			$this->response->body(json_encode(array('type'=>'error')));
		}
	}

	public function action_rename(){
	    $this->auto_render = FALSE;
		$id = (int) Arr::get($_POST, 'id');
		$node = ORM::factory('node', $id);
		$title = Arr::get($_POST, 'title');
		if($node->loaded() and $title){
			$node->menu_title = $title;
			$node->save();
			$this->response->body(json_encode(array('type'=>'success')));
		}else{
			$this->response->body(json_encode(array('type'=>'error')));
		}
	}

	public function action_remove(){
		//@todo: сделать вариант с удалением нода и переносом всех его детей к родителю
	    $this->auto_render = FALSE;
		$id = (int) Arr::get($_POST, 'id');
		$node = ORM::factory('node', $id);
		if($node->loaded()){
			$items = ORM::factory('node')
				->where('path', 'like', $node->path . '%')
				->find_all();
			foreach($items as $item){
				if($item->model_id){
					$i = ORM::factory($item->model, $item->model_id);
					if($i->loaded()){
						$i->delete();
					}
				}
			}
			$node->delete_branch();
			$this->response->body(json_encode(array('type'=>'success')));
		}else{
			$this->response->body(json_encode(array('type'=>'error')));
		}
	}

	public function action_add(){
	    $this->auto_render = FALSE;
		$node = ORM::factory('node');
		$node->title = Arr::get($_POST, 'title');
		try{
			$node->insert(Arr::get($_POST, 'parent'));
			$node->save();
			if(URL::title(Arr::get($_POST, 'title')) != $node->name){
					Message::warn('Введенное название страницы уже существует. Сгенерированно новое название.');
			}
			$this->response->body(json_encode(array('type'=>'success','data'=>array('id'=>$node->id))));
		}catch (ORM_Validation_Exception $e)
		{
			$node->delete();
			$this->response->body(json_encode(array('type'=>'error', 'message' => $e->errors('node'))));
		}
	}

	public function action_move(){
	    $this->auto_render = FALSE;
		$id = (int) Arr::get($_POST, 'id');
		$node = ORM::factory('node', $id);
		if($node->loaded()){
			$parent = Arr::get($_POST, 'parent', $node->parent_id);
			$position = Arr::get($_POST, 'position', $node->position);
			try{
				if($parent != $node->parent_id)
				{
					$node->move($parent);
				}
				
				if($position != $node->position)
				{
					if($node->position < $position)
						$position--;
					
					$node->set_position($position);					
				}
				$this->response->body(json_encode(array('type'=>'success')));
			}catch (ORM_Validation_Exception $e)
			{
				$this->response->body(json_encode(array('type'=>'error', 'message' => $e->errors('node'))));
			}
		}
	}
	
	public function action_hide(){
	    $this->auto_render = FALSE;
		$id = (int) Arr::get($_POST, 'id');
		$node = ORM::factory('node', $id);
		if($node->loaded()){
			$node->hidden = (int) !((bool) $node->hidden);
			$node->save();
			$this->response->body(json_encode(array('type'=>'success', 'data'=>$node->as_array())));
		}else{
			$this->response->body(json_encode(array('type'=>'error')));
		}
	}
} // End node
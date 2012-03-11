<?php defined('SYSPATH') or die('No direct script access.');

class Model_Node extends ORM_MP {

	protected $_hidden = FALSE;
	
	public function hidden($flag = FALSE){
		$this->_hidden = (bool)$flag;
		return $this;
	}
	
	public function filters(){
		return array(
			'title' => array(
				array('HTML::entities', array(':value')),
			),
			'menu_title' => array(
				array('HTML::entities', array(':value')),
			),
			'name' => array(
				array('URL::title', array(':value')),
				array(array($this, 'generation_unique_name'), array(':value'))
			),
			'description' => array(
				array('strip_tags', array(':value')),
			),
			'keywords' => array(
				array('strip_tags', array(':value')),
				array(array($this, 'unique_keywords'), array(':value'))
			),
		);
	}
	
	public function update (Validation $validation = NULL) {
		if( ! $this->name) $this->name = $this->title;
		if( ! $this->menu_title) $this->menu_title = $this->title;
		
		if(Arr::get($this->_changed, 'name') or Arr::get($this->_changed, 'position') ){
			$old = ORM::factory($this->_object_name, $this->pk());
			$old_name= $old->name;
			$old_position = $this->position;
			$this->name = URL::title($this->name);
		}

		parent::update($validation);
		if($this->saved() and isset($old_position) and $old_position != $this->position){
			$this->set_position($this->position);
		}
		if($this->saved() and isset($old_name) and $old_name != $this->name){
			if($this->is_root()){
				$url_path = '/'.$this->name.'/';
			}else{
				$url_path = $this->parent->find()->url_path.$this->name.'/';
			}
			DB::update($this->_table_name)
				->where('url_path', 'like', $this->url_path."%")
				->set(array (
					'url_path' => DB::expr('CONCAT(\''.$url_path.'\', SUBSTRING(`url_path`, '.(utf8::strlen($this->url_path)+1).'))'),
				))
				->execute();
		}
		return $this;
	}

	public function insert ($target = null, Validation $validation= NULL) {
		if( ! $this->name) $this->name = URL::title($this->title);
		if( ! $this->menu_title) $this->menu_title = $this->title;
		return parent::insert($target, $validation);
	}

	public function move ($target = null, $new = false) {
		$target = $target ? $this->target($target) : null;
		$this->set_position(null);
		$children = $this->load_tree()->children;
		
		if ($target && $target->id) {
			if ($target->level == $this->max_level) {
				$target = $target->parent;
			}
			$this->level = $target->level + 1;
			$this->path  = $target->path . $this->id . '.';
			$this->name = $this->generation_unique_name($this->name);
			$this->url_path  = $target->url_path . $this->name . '/';
			$this->position = count($target->load_tree()->children) + 1;
			$target->add_child($this);
		} else {
			$roots = $this->roots->find_all();
			$this->level = 0;
			$this->path  = '.' . $this->id . '.';
			$this->name = $this->generation_unique_name($this->name);
			$this->url_path  = '/' . $this->name . '/';
			$this->position = $roots ? count($roots) + ($new ? 0 : 1) : 0;
		}
		parent::save();
		$this->_children = array();
		foreach ($children as $child) {
			$child->move($this);
		}
		return $this;
	}

	public function generation_unique_name($value){

		if($this->path){
			$total_count = $this->unique_name($value);
			if( ! $total_count){
				$count = 0;
				while(TRUE){
					$count++;
					if($this->unique_name($value.'-'.$count))
					{
						$value = $value.'-'.$count;
						//$this->title = $this->title. '('.$count.')';
						break;
					}
				}
			}
		}
		return $value;
	}

	public function unique_name($value){
		return (bool) ! DB::select(array('COUNT("*")', 'total_count'))
				->from($this->_table_name)
				->where('level', '=', $this->level)
				->where('name', '=', $value)
				->where($this->_primary_key, '!=', $this->pk())
				->where('path', 'LIKE', DB::expr("CONCAT(SUBSTRING_INDEX('".$this->path."','.',".(count(explode('.',$this->path))-2)."),'.%')"))
				->execute($this->_db)
				->get('total_count');
	}

	public function load_node($model, $id){
		$this->where('model','=',$model)->where('model_id','=',$id);
		return $this;
	}
	
	public function unique_keywords($keywords){
		return implode(', ', array_unique(array_map('UTF8::trim', explode(',', $keywords))));
	}
	
	public function load_tree ($id = null) {
		if ($id !== true && $this->tree_loaded) return $this;
		$this->tree_loaded = true;

		$cats = $this->_create();
		if (is_callable($id)) {
			$id($cats);
		}
		
		//Добавил возможность не вытаскивать скрытые
		if(!$this->_hidden){
			$cats->where('hidden', '!=', 1);
		}
		//а дальше все старое
		if ($this->path || $id) {
			$path = $id ? "%.$id.%" : $this->path . '%';
			$cats->where('path', 'like', $path);
		}
		$cats = $cats
			->order_by('position')
			->find_all();
		
		$levels = array();

		foreach($cats as $cat) {
			$L = $cat->level;

			if (empty($levels[$L])) {
				$levels[$L] = array();
			}
			$levels[$L][] = $cat;
		}

		ksort($levels);
		foreach ($levels as $level) {
			foreach ($level as $desc) {
				$this->add_descendant($desc);
			}
		}
		return $this;
	}
}
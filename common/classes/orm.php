<?php defined('SYSPATH') or die('No direct script access.');

class ORM extends Kohana_ORM {

	/**
	 * Proxy method to Database list_columns.
	 *
	 * @return array
	 */
	public function list_columns() {
		$_columns_data = Cache::instance()->get($this->_table_name . "structure", $this->_db->list_columns($this->_table_name));

		if (Kohana::$caching and $_columns_data === NULL) {
			Cache::instance()->set($this->_table_name . "structure", $_columns_data, Date::DAY);
		}

		return $_columns_data;
	}
	
	public function pull(array $column)
	{
		if($this->_loaded){
			//$indexed = array($this->pk()=>0);
			//$keys    = $this->pk();
			
		}else{
			$array   = $this->find_all()->as_array();
			$indexed = array();
			foreach($array as $key=>$item){
				$indexed[$item->pk()] = $key;
			}
			$keys    = array_keys($indexed);
 		}
		
		$args = func_get_args();
		
		if ($keys and isset($args[0]) and $args[0])
		{
			
			foreach ($args as $columns)
			{
				if(Arr::is_assoc($columns)){
					$pull   = current($columns);
					$column = key($columns);
				}else{
					$pull   = array();
					$column = $columns[0];
				}

				//if(isset($this->_has_one[$column]))
						//$array->with($target_path);

				if (isset($this->_has_many[$column]))
				{
					$model = ORM::factory($this->_has_many[$column]['model']);
					$foreign_key = $this->_has_many[$column]['foreign_key'];
					$col = $model->_object_name .'.'. $foreign_key;
					$model->where($col, 'in', $keys);
					foreach($array as $k=>$v){
						$array[$k]->_related[$column] = array();
					}
					foreach($model->pull($pull) as $item){
						$array[$indexed[$item->{$foreign_key}]]->_related[$column][] = $item;
					}
				}
			}
		}
        return $array;
    }
}


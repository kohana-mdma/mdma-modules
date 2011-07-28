<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Model_Block extends ORM_Versioned {

    public function rules()
    {
        return array(
            'id' => array(
                array('not_empty'),
                array(array($this, 'is_unique'), array(':value', ':validation')),
            ),
            'name' => array(
                array('not_empty'),
            ),
            'body' => array(
            ),
        );
    }

    public function is_unique($value, $validation)
    {
       $count = (bool) DB::select(array('COUNT("*")', 'total_count'))
						->from($this->_table_name)
						->where($this->_primary_key, '=', $value)
						->where($this->_primary_key, '!=', $this->pk())
						->execute($this->_db)
						->get('total_count');

       if ($count)
       {
          $validation->error('id', 'id is not unique');
       }
    }
	
	public function update(Validation $validation = NULL){
		if($this->loaded()){
			Cache::instance()->delete('block-'.$this->pk());
		}
		return parent::update($validation);
	}
}
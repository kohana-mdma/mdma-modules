<?php
/**
 * Object Relational Mapping (ORM) "versioned" extension. Allows ORM objects to
 * be revisioned instead of updated.
 *
 * $Id$
 *
 * @package    ORM
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @author     Maxim S. aka Big_Shark
 * @copyright  (c) 2011 Big_Shark
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_ORM_Versioned extends ORM {

	protected $_enable_versioned = TRUE;
	/**
	 * Overload ORM::save() to support versioned data
	 *
	 * @chainable
	 * @return  ORM
	 */
	public function save(Validation $validation = NULL)
	{
		if( ! $this->_enable_versioned) return parent::save($validation);
		
		if($this->loaded() and empty($this->_changed))
		{
			return $this;
		}

		$this->version = Arr::get($this->_object, 'version', 0) + 1;
		parent::save($validation);

		if ($this->_saved)
		{
			$data = $this->_object;
			$data['validation_time_modified'] = Date::formatted_time('now');
			if(in_array('auth', array_keys(Kohana::modules())))
			{
				$data['validation_autor_modified'] = Auth::instance()->get_user()->id;
			}
			$query = DB::insert($this->_table_name.'_versions', array_keys($data));
			$query->values($data);
			$query->execute($this->_db);
		}

		return $this;
	}

	/**
	 * Loads previous version from current object
	 *
	 * @chainable
	 * @return  ORM
	 */
	public function previous($version = NULL)
	{
		if ( ! $this->loaded())
			return $this;

		// Load the result as an associative array
		$query = DB::select()
			->from($this->_table_name.'_versions')
			->where($this->_table_name.'_versions.'.$this->_primary_key, '=', $this->pk())
			->where('version', '=', $version)
			->limit(1)
			->as_assoc()
			->execute($this->_db);

		$this->reset();

		if ($query->count() === 1)
		{
			// Load object values
			$this->_load_values($query->current());
		}
		else
		{
			// Clear the object, nothing was found
			$this->clear();
		}

		return $this;
	}

	/**
	 * Restores the object with data from stored version
	 *
	 * @param   integer  version number you want to restore
	 * @return  ORM
	 */
	public function restore($version)
	{
		if ( ! $this->_loaded)
			return $this;

		$query = DB::select()
			->select_array(array_diff(array_keys($this->_table_columns), array('version')))
			->from($this->_table_name.'_versions')
			->where($this->_table_name.'_versions.'.$this->_primary_key, '=', $this->pk())
			->where('version', '= ', $version)
			->limit(1)
			->as_assoc()
			->execute($this->_db);

		if ($query->count() === 1)
		{
			$this->_load_values($query->current());
			$this->_changed = array_keys($query->current());
			$this->save();
		}
		return $this;
	}

	/**
	 * Overloads ORM::delete() to delete all versioned entries of current object
	 * and the object itself
	 *
	 * @param   integer  id of the object you want to delete
	 * @return  ORM
	 */
	public function delete()
	{
		if ( ! $this->_loaded)
			throw new Kohana_Exception('Cannot delete :model model because it is not loaded.', array(':model' => $this->_object_name));

		// Use primary key value
		$id = $this->pk();
		$status = parent::delete();
		DB::delete($this->_table_name.'_versions')->where($this->_table_name.'_versions.'.$this->_primary_key, '=', $this->pk())->execute($this->_db);

		return $status;
	}


	public function history(){
		$query = DB::select()
			->from($this->_table_name.'_versions')
			->where($this->_table_name.'_versions.'.$this->_primary_key, '=', $this->pk())
			->order_by('version','DESC')
			->execute($this->_db);
		return $query;
	}

	public function versiones_enable(){
		$this->_enable_versioned = TRUE;
	}

	public function versiones_disable(){
		$this->_enable_versioned = FALSE;
	}

}
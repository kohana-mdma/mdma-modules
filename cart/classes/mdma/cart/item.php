<?php defined('SYSPATH') or die('No direct script access.');

abstract class MDMA_Cart_Item {
	
	 /**
	 * @var array id
	 */
	public $id;
	
	 /**
	 * @var integer quantity
	 */
	public $qty;
	
	 /**
	 * @var object cart object
	 */
	public $object;
	
	/**
	 * Constructs a new model and loads a record if given
	 *
	 * @param   mixed $id Parameter for find or object to load
	 * @return  void
	 */
	public function __construct($id, $qty, $object)
	{
		$this->id = $id;
		$this->qty = (int)$qty;
		$this->object = $object;
		if ( ! ($this->object instanceof ORM) OR ! $this->object->loaded())
		{
			throw new Cart_Exception('The object must be of class ORM and must be loaded');
		}
	}
	
	 /**
	 * @param   string $column Column name
	 * @return  mixed
	 */
	public function __get($column)
	{
		if(method_exists($this, $column))
				return $this->{$column}();
				
		return $this->{$column};
	}
	
	/**
	 * @return  integer
	 */
	public function price()
	{
		$this->object->price;
	}
	
	/**
	 * @return  title
	 */
	public function title()
	{
		$this->object->title;
	}
}

// End Cart

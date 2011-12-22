<?php defined('SYSPATH') or die('No direct script access.');

abstract class MDMA_Cart_Cookie extends Cart {
	
	/**
	 * @var array cookie
	 */
	protected $cookie = array();
	
	/**
	 * @var string cookie name
	 */
	public static $name = 'cart';
	
	/**
	 * @return  void
	 */
	public function __construct()
	{
		$this->cookie =  json_decode(Cookie::get('cart', '{}'), TRUE);
	}
	
	/**
	 * @param   mixed Primary key or Object
	 * @return  Cart
	 */
	public function add($id)
	{
		return $this->qty($id, 1, '+');;
	}
	
	/**
	 * @param   mixed Primary key or Object
	 * @return  Cart
	 */
	public function delete($id)
	{
		return $this->qty($id, 0, '=');
	}
	
	/**
	 * @param   mixed Primary key or Object
	 * @param   integer Value
	 * @param   string  Logic operator
	 * @return  Cart
	 */
	public function qty($id, $value, $op = '=')
	{
		$id = $this->target($id)->pk();
		if( ! array_key_exists($id, $this->cookie))
		{
			$this->cookie[$id] = 0;
		}
		
		switch ($op)
		{
			case '=':
				$this->cookie[$id] = $value;
			break;	
			case '+':
				$this->cookie[$id] += $value;
			break;	
			case '-':
				$this->cookie[$id] -= $value;
			break;	
			default:
				throw new Cart_Exception('Operator may be used only +, - or =');
			break;	
		}
		if($this->cookie[$id] <= 0)
			unset($this->cookie[$id]);
		
		return $this->set();
	}
	
	/**
	 * @return  array
	 */
	public function get_all()
	{
		$ids = array_keys($this->cookie);
		
		$products = $this->get_products($ids);
		$result = array();
		foreach ($products as $id => $product) {
			$result[] = new Cart_item($id, $this->cookie[$id], $product);
		}
		return $result;
		
	}
	
	/**
	 * @param   mixed Primary key or Object
	 * @return  Cart_Item
	 */
	public function get($id)
	{
		return new Cart_item($id, $this->cookie[$id], ORM::factory($model, $id));
	}
	
	/**
	 * @return  Cart
	 */
	protected function set()
	{
		Cookie::set(Cart_Cookie::$name, json_encode($this->cookie));
		return $this;
	}
	
}

// End Cart

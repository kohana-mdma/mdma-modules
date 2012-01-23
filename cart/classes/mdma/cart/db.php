<?php defined('SYSPATH') or die('No direct script access.');

abstract class MDMA_Cart_Db extends Cart {
	
	/**
	 * @var int id user name
	 */
	protected $user_id = NULL;
	
	/**
	 * @return  void
	 */
	public function __construct()
	{
		$this->user_id =  Auth::instance()->get_user()->pk();
	}
	
	/**
	 * @param   mixed Primary key or Object
	 * @return  Cart
	 */
	public function add($id)
	{
		return $this->qty($id, 1, '+');
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
	 * @return  integer
	 */
	public function count()
	{
		return (int) DB::select(array(DB::expr('SUM(qty)'), 'count'))->from('cart')->where('user_id','=', $this->user_id)->execute()->get('count', 0);
	}
	
	 /**
	 * @return  Cart
	 */
	public function delete_all()
	{
		DB::delete('cart')->where('user_id','=', $this->user_id)->execute();
		return $this;
	}
	
	/**
	 * @param   mixed Primary key or Object
	 * @param   integer Value
	 * @param   string  Logic operator
	 * @return  Cart
	 */
	public function qty($id, $value, $op = '=')
	{
		if(!is_int($value) or $value < 0 )
			throw new Cart_Exception('Value must be a number, and not be negative');
		
		if(!in_array($op, array('=', '+', '-')))
			throw new Cart_Exception('Operator may be used only +, - or =');	
			
		$id = $this->target($id)->pk();

		$item = $this->get($id);
		if($item and !($op == '-' and $item->qty-$value <= 0) and !($op == '=' and $value == 0))
		{
			$qty = $item->qty;
			$cart = DB::update('cart');
			$cart->where('user_id', '=', $this->user_id);
			$cart->where('product_id', '=', $id);
			$cart->value('qty', DB::expr('qty '.$op.' :value', array(':value' => $value)));
			$cart->execute();
		}
		elseif(($op == '=' or $op == '+') and $value > 0 )
		{
			$cart = DB::insert('cart', array('user_id', 'product_id', 'qty'));
			$cart->values(array($this->user_id, $id, $value));
			$cart->execute();
		}
		else
		{
			$this->_delete($id);
		}
		
		return $this;
	}
	
	/**
	 * @return  array
	 */
	public function get_all()
	{
		$items = DB::select('product_id', 'qty')
				->from('cart')
				->where('user_id', '=', $this->user_id)
				->execute()
				->as_array('product_id', 'qty');
		
		$products = $this->get_products(array_keys($items));
		$result = array();
		foreach ($products as $id => $product) {
			$result[] = new Cart_item($id, $items[$id], $product);
		}
		return $result;
		
	}
	
	/**
	 * @param   mixed Primary key or Object
	 * @return  Cart_Item
	 */
	public function get($id)
	{
		$item = DB::select('product_id', 'qty')
				->from('cart')
				->where('user_id', '=', $this->user_id)
				->where('product_id', '=', $id)
				->execute()->current();
		if(!$item)
			return NULL;
		
		return new Cart_item($item['product_id'], $item['qty'], ORM::factory(Cart::$model, $item['product_id']));
	}

	/**
	 * @param   mixed Primary key 
	 * @return  bool
	 */
	protected function _delete($id)
	{
		return DB::delete('cart')->where('user_id','=', $this->user_id)->where('product_id', '=', $id)->execute();
	}
	
	/**
	 * @return  bool
	 */
	protected function _clear()
	{
		return DB::delete('cart')->where('user_id','=', $this->user_id)->where('qty', '<=', 0)->execute();
	}
}

// End Cart

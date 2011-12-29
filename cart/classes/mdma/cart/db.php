<?php defined('SYSPATH') or die('No direct script access.');

abstract class MDMA_Cart_Db extends Cart {
	
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

		$cart = DB::update('cart')->where('user_id', '=', Auth::instance()->get_user()->pk());
		switch ($op)
		{
			case '=':
				$cart->set(array('qty' => DB::expr('qty = 1')));
			break;	
			case '+':
				$cart->set(array('qty' => DB::expr('qty + 1')));
			break;	
			case '-':
				$cart->set(array('qty' => DB::expr('qty - 1')));
			break;	
			default:
				throw new Cart_Exception('Operator may be used only +, - or =');
			break;	
		}
		$cart->save();
		
		return $this;
	}
	
	/**
	 * @return  array
	 */
	public function get_all()
	{
		$items = DB::update('cart')
				->where('user_id', '=', Auth::instance()->get_user()->pk())
				->find_all()
				->as_array('id', 'qty');
		
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
		$item = DB::update('cart')
				->where('user_id', '=', Auth::instance()->get_user()->pk())
				->where('product_id', '=', $id)
				->find();
				
		return new Cart_item($item['product_id'], $item['qty'], ORM::factory($model, $item['product_id']));
	}
	
}

// End Cart

<?php defined('SYSPATH') or die('No direct script access.');

abstract class MDMA_Cart {
	
	/**
	 * @var   Cart instances
	 */
	public static $instances = array();
	
	/**
	 * @var string Product model name
	 */
	public static $model = 'product';

	/**
	 * @param   string   the name of the cache group to use [Optional]
	 * @return  Cart
	 * @throws  Cart_Exception
	 */
	public static function instance()
	{
		$driver = 'cookie';
		if(Auth::instance()->logged_in()){
			//$driver = 'ORM';
		}
		// Create a new cache type instance
		$cache_class = 'Cart_'.ucfirst($driver);
		Cart::$instances[$driver] = new $cache_class();

		// Return the instance
		return Cart::$instances[$driver];
	}
	
	/**
	 * @param   mixed Primary key or Object
	 * @return  Cart
	 */
	abstract public function add($id);
	
	/**
	 * @param   mixed Primary key or Object
	 * @return  Cart
	 */
	abstract public function delete($id);
	
	/**
	 * @param   mixed Primary key or Object
	 * @param   integer Value
	 * @param   string  Logic operator
	 * @return  Cart
	 */
	abstract public function qty($id, $value, $op = '=');
	
	/**
	 * @return  array
	 */
	abstract public function get_all();
	
	/**
	 * @param   mixed Primary key or Object
	 * @return  array
	 */
	abstract public function get($id);
	
	/**
	 * @param  mixed Name of the view to use, or a Kohana_View object
	 * @return string cart output (HTML)
	 */
	public function render($view = 'cart')
	{
		print_r($this->get_all());
die('11');
		if ( ! $view instanceof Kohana_View)
		{
			// Load the view file
			$view = View::factory($view);
		}

		return $view->set('cart', $this->get_all())->render();
	}
	
	/**
	 * @param  array ids product
	 * @return array
	 */
	protected function get_products($ids = array())
	{
		if(is_array($ids) and count($ids))
		{
			$model = ORM::factory(self::$model);
			return $model->where($model->primary_key(), 'IN', $ids)
				->find_all()
				->as_array('id', NULL);
		}

		return array();
	}
	

	/**
	* @return string cart output (HTML)
	*/
	public function __toString()
	{
		return $this->render();
	}
	
	/**
	* @param  mixed Primary key or Object
	* @return object model
	*/
	protected function target ($target)
	{
		
		if(is_int($target) OR is_string($target))
		{
			$target = ORM::factory(self::$model, $target);
		}
		
		if ( ! ($target instanceof ORM) OR ! $target->loaded())
		{
			throw new Cart_Exception('The object must be of class ORM and must be loaded');
		}
		return $target;
	}
}

// End Cart

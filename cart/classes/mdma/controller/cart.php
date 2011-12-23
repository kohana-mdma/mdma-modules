<?php defined('SYSPATH') or die('No direct script access.');

abstract class MDMA_Controller_Cart extends Controller_Template_Cart {

	public function action_index()
	{
		$this->template->content = Cart::instance()->render();
	}

	public function action_add()
	{
		$this->auto_render = FALSE;
		$id = $this->request->param("id");
		try
		{
			Cart::instance()->add($id);
			$this->response->body(json_encode(array('type' => 'success')));
		}
		catch (Cart_Exception $e)
		{
			$this->response->body(json_encode(array('type' => 'error', 'message' => strip_tags($e->getMessage()))));
		}
	}
	
	public function action_delete()
	{
		$this->auto_render = FALSE;
		$id = $this->request->param("id");
		try
		{
			Cart::instance()->delete($id);
			$this->response->body(json_encode(array('type' => 'success')));
		}
		catch (Cart_Exception $e)
		{
			$this->response->body(json_encode(array('type' => 'error', 'message' => strip_tags($e->getMessage()))));
		}
	}
	
	public function action_qty()
	{
		$this->auto_render = FALSE;
		$id = $this->request->param("id");
		$qty = $this->request->param("qty");
		try
		{
			Cart::instance()->qty($id, $qty);
			$this->response->body(json_encode(array('type' => 'success')));
		}
		catch (Cart_Exception $e)
		{
			$this->response->body(json_encode(array('type' => 'error', 'message' => strip_tags($e->getMessage()))));
		}
	}

}

// End Cart

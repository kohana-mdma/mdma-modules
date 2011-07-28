<?php defined('SYSPATH') or die('No direct script access.');

class MDMA_Controller_Weather extends Controller {

	public function action_index()
	{
		if(Request::current() !== Request::initial()){
			$this->response->body(View::factory('weather', Weather::get()));
		}else{
			throw new HTTP_Exception_404();
		}
	}

} // End Weather
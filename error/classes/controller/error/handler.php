<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Error_Handler extends Controller_Error_Template {

	public $view = 'error/template';
        
    public function before()
	{
		if(Kohana::find_file('views', 'error/'.$this->request->action(), 'php'))
		{
			$this->view = 'error/'.$this->request->action();
		}
		$this->view = View::factory($this->view);
		
		parent::before();
		
		$this->view->page = URL::site(rawurldecode(Request::initial()->uri()));

		// Internal request only!
		if (Request::initial() !== Request::current())
		{
			if ($message = rawurldecode($this->request->param('message')))
			{
				$this->view->message = $message;
			}
		}
		else
		{
			$this->request->action(404);
		}

		$this->response->status((int) $this->request->action());
	}

	public function after()
	{
		if(Request::initial()->is_ajax()){
			$this->auto_render = FALSE;
			$this->response->body(
				json_encode(
					array(
						'status' => 'error',
						'code' => $this->request->action(),
						'message' => $this->template->message,
					)
				)
			);
		}

		if (is_object($this->template) and $this->template instanceof View  )
		{
			$this->template->content = $this->view->render();
			$this->response->body($this->template->render());
		}else{
			$this->response->body($this->view->render());
		}

		return parent::after();
	}

	public function action_404() {
		View::set_global('title', '404 Not Found');

		// Here we check to see if a 404 came from our website. This allows the
		// webmaster to find broken links and update them in a shorter amount of time.
		if (isset ($_SERVER['HTTP_REFERER']) AND strstr($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']) !== FALSE)
		{
			// Set a local flag so we can display different messages in our template.
			$this->view->local = TRUE;
		}

		// HTTP Status code.
		$this->response->status(404);
	}

	public function action_503() { View::set_global('title', 'Maintenance Mode'); }

	public function action_500() { View::set_global('title', 'Internal Server Error'); }

}
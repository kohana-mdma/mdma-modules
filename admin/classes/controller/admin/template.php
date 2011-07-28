<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Admin_Template extends Controller_Template {

    public $template = 'admin/template';

    public $ajaxAllow = false;

    public function before()
    {
        parent::before();
		if( ! A::logged_in('admin')) $this->request->redirect('admin/auth/login');
		if (($this->ajaxAllow and ($this->request->is_ajax() || isset ( $_GET ['ajax'] ))) or Request::initial() !== Request::current()) {
			$this->auto_render = false;
		}
		else
        {
			$top_menu = Kohana::$config->load('admin.top_menu');
			uasort($top_menu, function ($a, $b){return strnatcmp(Arr::get($a, 'sort', 0), Arr::get($b, 'sort', 0))*(-1);});
			$this->template->top_menu = $top_menu;
            $this->template->sidebar = array();
            $this->template->styles = array(
                'css/style.css' => 'screen',
            );

            $this->template->scripts = array(
            );

        }
    }

	public function after() 
	{
		$this->response->headers('cache-control', 'no-cache');
		$this->response->headers('Pragma', 'no-cache');
		if ($this->ajaxAllow && ($this->request->is_ajax() || isset ( $_GET ['ajax'] ))) {
			$this->auto_render = false;
			$this->response->headers('content-type', 'application/json');
			if(array_key_exists('message',Kohana::modules())){
				$this->template->content->message = Message::get();
				Message::clear();
			}
			$this->response->body(json_encode($this->template->content->as_array()));
		}
		parent::after ();
	}
} // End Template
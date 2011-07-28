<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Dashboard extends Controller_Admin_Template {
    public function action_index(){
        $this->template->content = View::factory('admin/dashboard');
		$this->template->content->widgets = ORM::factory('admin_widget')->active()->find_all();
    }
}
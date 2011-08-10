<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_User extends Controller_Admin_Template {


	public function action_index()
	{
        $users = ORM::factory('user')->find_all();
	    $this->template->content = View::factory('admin/user/list')->bind('users',$users);
	}

	public function action_show()
	{
		$id = $this->request->param('id');
        $user = ORM::factory('user', $id);
	    $this->template->content = View::factory('admin/user/show')->bind('user',$user);
	}

	public function action_edit()
	{
		$id = $this->request->param('id');
	    $this->template->content = View::factory('admin/user/form');
	    if ($_POST)
	    {
			$user = ORM::factory('user', $id)
					->values($_POST, array('email', 'username'));
			if(Arr::get($_POST,'password','') != '') $user->password = $_POST['password'];
			try
			{
				$user->save();
				$user_array = $user->as_array();
				$user->remove('roles')->add('roles', Arr::get($_POST, 'roles', array()));
				$user_array['roles'] = $user->roles->find_all()->as_array('id', 'id');
				Message::set(Message::SUCCESS, 'Изменения прошли удачно');
				$this->template->content->data = $user_array;
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set(Message::ERROR, $e->errors('user'));
				$this->template->content->data = $_POST;
			}
	    }
	    else
	    {
			$user = ORM::factory('user',$id);
			$user_array = $user->as_array();
			$user_array['roles'] = $user->roles->find_all()->as_array('id', 'id');
			$this->template->content->data = $user_array;
	    }
	}

	public function action_add()
	{
	    if ($_POST)
	    {
			$user = ORM::factory('user')
					->values($_POST, array('email', 'username', 'password'));
			try
			{
				$user->save();
				$user->add('roles', Arr::get($_POST, 'roles', array()));
				Message::set(Message::SUCCESS, 'Добовление прошло успешно');
				$this->request->redirect('admin/user/edit/'.$user->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set(Message::ERROR, $e->errors('user'));
			}
	    }
	    $this->template->content = View::factory('admin/user/form')->set('data',$_POST);
	}

	public function action_delete()
	{
		$id = $this->request->param('id');
		$user = ORM::factory('user',$id);
		try
		{
			$user->remove('roles')->delete();
		    Message::set(Message::SUCCESS, 'Удаление прошло успешно');
		    if( ! $this->request->is_ajax())$this->request->redirect('admin/user/');
		}
		catch (ORM_Validation_Exception $e)
		{
		    Message::set(Message::ERROR, $e->errors('user'));
			if( ! $this->request->is_ajax())$this->request->redirect('admin/user/');
		}
	}
} // End Admin
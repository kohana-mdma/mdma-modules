<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Auth extends Controller_Template {

   public $template = 'admin/auth';

   public function before()
   {
        parent::before();
		if ($this->auto_render)
        {
            $this->template->styles = array(
			'css/style.css' => 'screen',
        );

		}
    }

	public function action_login()
	{
        if (A::logged_in('admin'))
		{
			$this->request->redirect('admin');
		}
		else
		{
			$this->template->title = 'Вход';

			if ($_POST)
			{
                            $post = Validation::factory($_POST);
                            $post->rule('username', 'not_empty')
                                 ->rule('password', 'not_empty');
                            if($post->check()){
								if(Auth::instance()->login($post['username'], $post['password'], isset($_POST['autologin'])))
                                {
                                    $this->request->redirect('admin');
                                }
                                else
                                {
                                    Message::set(Message::ERROR, 'Неверный логин или пароль.');
                                }
                            }
                            else
                            {
                                Message::set(Message::ERROR, 'Вы не указали логин или пароль.');
                            }
			}

			$this->template->content = View::factory('admin/auth')->bind('post',$post);
		}

	}

    public function action_logout()
	{
            Auth::instance()->logout();
            $this->request->redirect('admin');
    }
} // End Login
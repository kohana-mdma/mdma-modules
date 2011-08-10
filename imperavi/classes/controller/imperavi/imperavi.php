<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Imperavi_Imperavi extends Controller {

	public $config = 'default';

	public function action_index(){
		if (Upload::valid($_FILES['file']) and Upload::not_empty($_FILES['file']))
		{
			$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
			if(Upload::type($_FILES['file'],array('jpg','gif','png'))){
				$file_name = Kohana::$config->load('imperavi.'.$this->config.'.images_path');
				$file_url = Kohana::$config->load('imperavi.'.$this->config.'.images_url');
			}else{
				$file_name = Kohana::$config->load('imperavi.'.$this->config.'.files_path');
				$file_url = Kohana::$config->load('imperavi.'.$this->config.'.files_url');
			}
			$file_name = str_replace($file_name, '', Upload::save($_FILES['file'], NULL, $file_name, 777));

			$file_ico = $this->get_ico($ext);

			//$this->response->body('<a href="javascript:void(null);" rel="'.$file_name.'" class="editor_file_link editor_file_ico_'.$file_ico.'">'.$file_name.'</a>');
			$this->response->body(URL::site($file_url.$file_name));
		}
	}

	public function action_delete($file = NULL){
		unlink(Kohana::config('imperavi.'.$this->config.'.file_path').$file);
	}

	public function action_download($file = NULL){
		Download::force($file,file_get_contents(Kohana::config('imperavi.'.$this->config.'.file_path').$file));
	}

	public function action_typo(){
		$this->response->body(Typograf::instance()->execute(urldecode(Arr::get($_POST, 'text'))));
	}

	function get_ico($type)
	{
		$fileicons = array('other' => 0, 'avi' => 'avi', 'doc' => 'doc', 'docx' => 'doc', 'gif' => 'gif', 'jpg' => 'jpg', 'jpeg' => 'jpg', 'mov' => 'mov', 'csv' => 'csv', 'html' => 'html', 'pdf' => 'pdf', 'png' => 'png', 'ppt' => 'ppt', 'rar' => 'rar', 'rtf' => 'rtf', 'txt' => 'txt', 'xls' => 'xls', 'xlsx' => 'xls', 'zip' => 'zip');

		if (isset($fileicons[$type])) return $fileicons[$type];
		else return 'other';
	}
} // End Imperavi
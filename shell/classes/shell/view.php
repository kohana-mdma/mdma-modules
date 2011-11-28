<?php defined('SYSPATH') or die('No direct script access.');

class Shell_View
{
	function run($name = NULL){
		if($name ===  NULL or ! Valid::alpha_dash($name)){
			Console::instance()->out(Console::format('Имя не указанно или указано не верно.', Console::ERROR));
			return;
		}
		$name = implode("_", array_map('UTF8::ucfirst', explode('_',$name)));
		if( ! class_exists('Model_'.$name)){
			Console::instance()->out(Console::format('Класс '.$name.' не сушествует.', Console::ERROR));
			return;
		}
		
		$name = UTF8::strtolower($name);
		$view = View::factory('shell/view/form');
		$view->_model = $name;
		$view->_name = $name;
		$view->data = array();
		$view->data[$name] = array();
		$view = $view->render();
		
		$directory = APPPATH.'views/admin/'.UTF8::strtolower($name).'/';
		
		// Open directory
		$dir = new SplFileInfo($directory);

		// If the directory path is not a directory
		if ( ! $dir->isDir())
		{
			// Create the directory 
			mkdir($directory, 0755, TRUE);
		}
		
		$resouce = new SplFileInfo($directory.'form.php');
		$file = $resouce->openFile('w');
		$file->fwrite($view, strlen($view));
		
		if((bool) $file->fflush()){
			Console::instance()->out(Console::format('Отображение формы создано'.PHP_EOL, Console::SUCCESS));
		}else{
			Console::instance()->out(Console::format('Отображение формы не создано'.PHP_EOL, Console::ERROR));
		}
		
		$view = View::factory('shell/view/list');
		$view->_model = $name;
		$view->_name = $name;
		$view = $view->render();
		
		$resouce = new SplFileInfo($directory.'list.php');
		$file = $resouce->openFile('w');
		$file->fwrite($view, strlen($view));
		
		if((bool) $file->fflush()){
			Console::instance()->out(Console::format('Отображение списка создано'.PHP_EOL, Console::SUCCESS));
		}else{
			Console::instance()->out(Console::format('Отображение списка не создано'.PHP_EOL, Console::ERROR));
		}
		
		$view = View::factory('shell/view/show');
		$view->_model = $name;
		$view->_name = $name;
		$view = $view->render();
		
		$resouce = new SplFileInfo($directory.'show.php');
		$file = $resouce->openFile('w');
		$file->fwrite($view, strlen($view));
		
		if((bool) $file->fflush()){
			Console::instance()->out(Console::format('Отображение "отображения" создано'.PHP_EOL, Console::SUCCESS));
		}else{
			Console::instance()->out(Console::format('Отображение "отображения" не создано'.PHP_EOL, Console::ERROR));
		}		
	}
}
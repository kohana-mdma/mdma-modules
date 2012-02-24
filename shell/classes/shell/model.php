<?php defined('SYSPATH') or die('No direct script access.');

class Shell_Model
{
	function run($name = NULL, $table = NULL){
		if($name ===  NULL or ! Valid::alpha_dash($name)){
			Console::instance()->out(Console::format('Имя не указанно или указано не верно.', Console::ERROR));
			return;
		}
		$name = implode("_", array_map('UTF8::ucfirst', explode('_',$name)));
		if(class_exists('Model_'.$name)){
			Console::instance()->out(Console::format('Класс '.$name.' уже сушествует.', Console::ERROR));
			return;
			//Console::instance()->out(Console::format('Класс уже сушествует. Обновить y/N?', Console::ERROR));
			/*while(($line = Console::instance()->readline()) !== FALSE){
				$line = UTF8::strtolower(UTF8::trim($line));
				Console::instance()->out('{'.$line.'}');
				if($line == '' or $line == 'n'){
					Console::instance()->out(Console::format('Создание модели остановлено.', Console::ERROR));
				}elseif($line !== 'y'){
					Console::instance()->out(Console::format('Класс уже сушествует. Обновить y/N?', Console::ERROR));
				}else{
					Console::instance()->out('не то');
					break;
				}
			}*/
		}
		
		if($table === NULL)
			$table = Inflector::plural(strtolower($name));
		
		if(! Database::instance()->list_tables($table)){
			Console::instance()->out(Console::format('Таблица '.$table.' не сешуствует.', Console::ERROR));
			return;
		}
		$columns = Database::instance()->list_columns($table);
		$labels = array();
		foreach($columns as $column){
			$labels[$column['column_name']] = ($column['comment'])?$column['comment']:$column['column_name'];
		} 
		
		
		/*return array(
			'create_on' => array(
	            array('Date::formatted_time', array(':value', 'Y-m-d H:i:s')),
			),
		);
		*/
		$date_format = array(
			'date' => 'Y-m-d',
			'datetime' => 'Y-m-d H:i:s',
			'time' => 'H:i:s',
			'timestamp' => 'U',
		);
		$filters = array();
		foreach($columns as $column){
			if(array_key_exists($column['data_type'], $date_format)){
				$filters[$column['column_name']] = array(array('Date::formatted_time', array(':value',  $date_format[$column['data_type']])));
			}
		} 
		/*
		return array(
            'username' => array(
                array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 32)),
                array(array($this, 'username_available')),
            ),
            'password' => array(
                array('not_empty'),
            ),
        );
		 
		 */
		$rules = array();
		foreach($rules as $rule){
			$rule[$column['column_name']] = array();
			
			if($column['type'] == 'int')
				//$rule[$column['column_name']][]=array('not_empty');
			
			if( ! $column['is_nullable'])
				$rule[$column['column_name']][]=array('not_empty');
			
			
			//if( ! $column['is_nullable'])
			//	$rule[$column['column_name']][]=array('not_empty');
			
			if(array_key_exists($column['data_type'], $date_format)){
			//	$filters[$column['column_name']] = array(array('Date::formatted_time', array(':value',  $date_format[$column['data_type']])));
			}
		} 
		$view = View::factory('shell/model');
		$view->name = $name;
		$view->table = $table;
		$view->columns = $columns;
		$view->labels = $labels;
		$view->filters = $filters;
		$view = $view->render();
		
		$filename = $name;
		$directory = APPPATH.'classes/model/';
		if(strpos($name, '_') !== FALSE){
			$directory .= substr(strtr(UTF8::strtolower($name), '_', '/'), 0, strrpos($name, '_') + 1);
			
			// Open directory
			$dir = new SplFileInfo($directory);

			// If the directory path is not a directory
			if ( ! $dir->isDir())
			{
				// Create the directory 
				mkdir($directory, 0755, TRUE);
			}
			$filename = substr($name, strrpos($name, '_') + 1);
		}
		$resouce = new SplFileInfo($directory.UTF8::strtolower($filename).'.php');
		$file = $resouce->openFile('w');
		$file->fwrite($view, strlen($view));
		
		if((bool) $file->fflush()){
			Console::instance()->out(Console::format('Модель создана', Console::SUCCESS));
		}else{
			Console::instance()->out(Console::format('Модель не создана', Console::ERROR));
		}
	}
}
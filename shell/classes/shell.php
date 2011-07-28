<?php defined('SYSPATH') or die('No direct script access.');

class Shell {
	public static function export_array($array, $offset = 1){
		$replace_array = array(
			//'  ' => chr(9), // меянем 2 пробела на таб
			//'=> '.PHP_EOL.str_repeat(chr(9), $offset + 1).'array(' => '=> array(', // переносим под массивы в соответствии с кодинг стайлом
			//str_repeat(chr(9), $offset).'array (' => 'array(', //убираем отступы у array
					);
		$f = function($str) use ($offset){ // Функция добовляет отступ к каждой строке
			
			if(UTF8::ltrim($str) == 'array (' and UTF8::ltrim($str) != $str)
				$str = '';
			elseif(UTF8::substr($str, -3) == '=> ')
				$str = UTF8::substr($str, 0, -3).'=> array(';
			
			if($str !== 'array (' and $str !== '')
				$str = str_repeat(chr(9), $offset + ((UTF8::strlen($str) - UTF8::strlen(UTF8::ltrim($str))) / 2)).UTF8::ltrim($str).PHP_EOL;
			elseif($str !== '')
				$str .= PHP_EOL;
			
			return $str;
		};
		return trim(implode('', array_map($f, explode(PHP_EOL, var_export($array, TRUE)))), PHP_EOL);
	}
}
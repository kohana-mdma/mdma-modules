<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date helper.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @author     Maxim S. aka Big_Shark
 * @copyright  (c) 2007-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class URL extends Kohana_URL {
	
	public static function title($title, $separator = '-', $ascii_only = FALSE)
	{
		$translation = array(
			"а" => "a", "А" => "a",
			"б" => "b", "Б" => "b",
			"в" => "v", "В" => "v",
			"г" => "g", "Г" => "g",
			"д" => "d", "Д" => "d",
			"е" => "e", "Е" => "e",
			"ё" => "jo", "Ё" => "jo",
			"ж" => "zh", "Ж" => "zh",
			"з" => "z", "З" => "z",
			"и" => "i", "И" => "i",
			"й" => "jj", "Й" => "jj",
			"к" => "k", "К" => "k",
			"л" => "l", "Л" => "l",
			"м" => "m", "М" => "m",
			"н" => "n", "Н" => "n",
			"о" => "o", "О" => "o",
			"п" => "p", "П" => "p",
			"р" => "r", "Р" => "r",
			"с" => "s", "С" => "s",
			"т" => "t", "Т" => "t",
			"у" => "u", "У" => "u",
			"ф" => "f", "Ф" => "f",
			"х" => "kh", "Х" => "kh",
			"ц" => "c", "Ц" => "c",
			"ч" => "ch", "Ч" => "ch",
			"ш" => "sh", "Ш" => "sh",
			"щ" => "shh", "Щ" => "shh",
			"ъ" => "\"", "Ъ" => "\"",
			"ы" => "y", "Ы" => "y",
			"ь" => "'", "Ь" => "'",
			"э" => "eh", "Э" => "eh",
			"ю" => "yu", "Ю" => "yu",
			"я" => "ya", "Я" => "ya",
			);
		$title = strtr($title, $translation);
		return parent::title($title, $separator, $ascii_only);
	}
}
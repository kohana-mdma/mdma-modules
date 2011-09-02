<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Text helper class. Provides simple methods for working with text.
 *
 */
class Text extends Kohana_Text {

	/**
	 * Converts text email addresses into links. 
	 *
	 *     echo Text::auto_link_emails($text);
	 *
	 * [!!] This method is not foolproof since it uses regex to parse HTML.
	 *
	 * @param   string   text to auto link
	 * @return  string
	 * @uses    HTML::mailto
	 */
	public static function auto_link_emails($text)
	{
		return preg_replace_callback('#\<a[^>]*?href=\"mailto:\s?([A-Za-z0-9._%-]+)\@([A-Za-z0-9._%-]+)\.([A-Za-z0-9._%-]+)[^>]*?\>([^>]*?)<\/a\>#', 'Text::_auto_link_emails_callback', $text);
	}

	protected static function _auto_link_emails_callback($matches)
	{
		return HTML::mailto($matches[1].'@'.$matches[2].'.'.$matches[3], Arr::get($matches, 4));
	}
	
	/**
	 * Returns human readable sizes. Based on original functions written by
	 * [Aidan Lister](http://aidanlister.com/repos/v/function.size_readable.php)
	 * and [Quentin Zervaas](http://www.phpriot.com/d/code/strings/filesize-format/).
	 *
	 *     echo Text::bytes(filesize($file));
	 *
	 * @param   integer  size in bytes
	 * @param   string   a definitive unit
	 * @param   string   the return string format
	 * @param   boolean  whether to use SI prefixes or IEC
	 * @return  string
	 */
	public static function bytes($bytes, $force_unit = NULL, $format = NULL, $si = TRUE)
	{
		$translation = array(
			"B" => "б",
			"kB" => "кб",
			"KiB" => "Кбайт",
			"MB" => "мб",
			"MiB" => "Понедельник",
			"GB" => "гб",
			"GiB" => "Гбайт",
			"TB" => "тб",
			"TiB" => "Тбайт",
			"PB" => "пб",
			"PiB" => "Пбайт",
		);
		return strtr(parent::bytes($bytes, $force_unit, $format, $si), $translation);
	}
} // End text

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
} // End text

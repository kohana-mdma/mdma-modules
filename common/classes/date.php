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
class Date extends Kohana_Date {
	
	public static function formatted_time($datetime_str = 'now', $timestamp_format = NULL, $timezone = NULL)
	{
		$translation = array(
			"am" => "дп",
			"pm" => "пп",
			"AM" => "ДП",
			"PM" => "ПП",
			"Monday" => "Понедельник",
			"Mon" => "Пн",
			"Tuesday" => "Вторник",
			"Tue" => "Вт",
			"Wednesday" => "Среда",
			"Wed" => "Ср",
			"Thursday" => "Четверг",
			"Thu" => "Чт",
			"Friday" => "Пятница",
			"Fri" => "Пт",
			"Saturday" => "Суббота",
			"Sat" => "Сб",
			"Sunday" => "Воскресенье",
			"Sun" => "Вс",
			"January" => "Января",
			"Jan" => "Янв",
			"February" => "Февраля",
			"Feb" => "Фев",
			"March" => "Марта",
			"Mar" => "Мар",
			"April" => "Апреля",
			"Apr" => "Апр",
			"May" => "Мая",
			"May" => "Мая",
			"June" => "Июня",
			"Jun" => "Июн",
			"July" => "Июля",
			"Jul" => "Июл",
			"August" => "Августа",
			"Aug" => "Авг",
			"September" => "Сентября",
			"Sep" => "Сен",
			"October" => "Октября",
			"Oct" => "Окт",
			"November" => "Ноября",
			"Nov" => "Ноя",
			"December" => "Декабря",
			"Dec" => "Дек",
			"st" => "ое",
			"nd" => "ое",
			"rd" => "е",
			"th" => "ое",
			);
		return strtr(parent::formatted_time($datetime_str, $timestamp_format, $timezone), $translation);
	}
}
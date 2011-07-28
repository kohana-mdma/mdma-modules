<?php defined('SYSPATH') or die('No direct script access.');

class Num extends Kohana_Num{


	/**
	 * Locale-aware number and monetary formatting.
	 *
	 *     // In English, "1,200.05"
	 *     // In Spanish, "1200,05"
	 *     // In Portuguese, "1 200,05"
	 *     echo Num::format(1200.05, 2);
	 *
	 *     // In English, "1,200.05"
	 *     // In Spanish, "1.200,05"
	 *     // In Portuguese, "1.200.05"
	 *     echo Num::format(1200.05, 2, TRUE);
	 *
	 * @param   float    number to format
	 * @param   integer  decimal places
	 * @param   boolean  monetary formatting?
	 * @return  string
	 * @since   3.0.2
	 */
	public static function format($number, $places, $monetary = FALSE)
	{
		$info = localeconv();

		if ($monetary)
		{
			$decimal   = $info['mon_decimal_point'];
			$thousands = $info['mon_thousands_sep'];
		}
		else
		{
			$decimal   = $info['decimal_point'];
			$thousands = $info['thousands_sep'];
		}
		
		if( ! UTF8::is_ascii($decimal) or ! UTF8::is_ascii($thousands))
		{
			$placeholders = array('@', '~');
			$number = $formatted = str_replace($placeholders, array($decimal, $thousands), number_format($number, $places, $placeholders[0], $placeholders[1]));
		}
		else
		{
			$number = number_format($number, $places, $decimal, $thousands);
		}
		
		return $number;
	}

} // End num
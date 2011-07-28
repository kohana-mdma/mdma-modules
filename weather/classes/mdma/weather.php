<?php defined('SYSPATH') or die('No direct script access.');

class MDMA_Weather {
	static function get($city = 'default'){
		$result = Cache::instance()->get('weather');
		if( ! $result and implode(':', Arr::get($result, 'date', array())) !== Date::formatted_time('now', 'j:n:Y', 'Asia/Vladivostok'))
		{
			$result = (array) simplexml_load_file('http://export.yandex.ru/weather/?city='.Kohana::$config->load('weather.'.$city));
			$result['date'] = (array)$result['date'];
			Cache::instance()->set('weather', $result, Date::HOUR * 4);
		}
		return $result;
	}
}
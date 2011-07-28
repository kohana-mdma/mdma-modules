<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'top_menu' => array(
		'user' => array(
			"label" => "Пользователи",
			"url" => "user",
			"sort" => -1,
			"childrens" => array(
				array(
					"label" => "Создать пользователя",
					"url" => "user/add",
				),
			),
		),
	),
);

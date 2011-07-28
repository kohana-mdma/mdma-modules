<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'top_menu' => array(
		'block' => array(
			"label" => "Блоки",
			"url" => "block/",
			"sort" => 50,
			"childrens" => array(
				array(
					"label" => "Создать блок",
					"url" => "block/add",
				),
			),
		),
	),
);

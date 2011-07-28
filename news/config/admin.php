<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'top_menu' => array(
		'news' => array(
			"label" => "Новости",
			"url" => "news",
			"childrens" => array( //Под меню
				array(
					"label" => "Добавить новость",
					"url" => "news/add",
				),
			),
		),
	),
	'widget' => array(
		/*'id' => array(
			"title" => "Заголовок",
			"description" => "Описание",
			"route" => "Имя роута",
			"attributes" => array( //Параметры для роута
					"controller" => "form",
					"action" => "statistic",
			),
		),*/
	),
);
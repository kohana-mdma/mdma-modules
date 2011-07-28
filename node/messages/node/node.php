<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'id' => array(
        'not_empty' => 'ID не должен быть пустым',
        'id is not unique' => 'ID Должен быть уникальным'
    ),
    'name' => array(
        'not_empty' => 'Название страницы не должно быть пустым',
		'unique_name' => 'Введенное название страницы уже существует.',
    ),
);


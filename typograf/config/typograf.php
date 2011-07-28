<?php defined('SYSPATH') or die('No direct script access.');

return array
(
	'artlebedev' => array
	(
		'driver'             => 'artlebedev',
		'encoding'           => Kohana::$charset,
		'setting'			 => array(
			'entity_type'        => Typograf_Lebedev::$HTMLEntities,
			'br'		         => FALSE,
			'p'                  => FALSE,
			'nobr'               => '3',
			'quot_a'             => 'laquo raquo',
			'quot_a'             => 'bdquo ldquo',
		)
	),
);
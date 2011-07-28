<?php defined('SYSPATH') or die('No direct script access.');

// Static file serving (CSS, JS, images)
Route::set('imperavi', 'imperavi(/<action>(/<file>))', array('file' => '.+'))
	->defaults(array(
		'directory'  => 'imperavi',
		'controller' => 'imperavi',
		'action'     => 'index',
	));


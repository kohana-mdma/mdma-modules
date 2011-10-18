<?php defined('SYSPATH') or die('No direct script access.');

if (Kohana::$errors)
{
	// Override Kohana exception handler
	set_exception_handler(array('MDMA_Exception', 'handler'));
}

Route::set('error', 'error/<action>(/<message>)', array('action' => '[0-9]++', 'message' => '.+'))
	->defaults(array(
		'directory'  => 'error',
		'controller' => 'handler',
	));
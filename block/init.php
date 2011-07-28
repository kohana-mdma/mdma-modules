<?php defined('SYSPATH') or die('No direct script access.');

Route::set('admin/block', 'admin/block/edit/<id>(/version/<version>)', array('action' => '[a-zA-Z0-9_]+'))
	->defaults(array(
		'directory'  => 'admin',
		'controller' => 'block',
		'action'     => 'edit',
	));

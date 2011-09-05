<?php

Route::set('media', 'media(/<action>)',
    array(
        'action'    => '(index|move)',
    ))->defaults(array(
        'controller'=> 'media',
        'action'    => 'index',
    ));

if(Kohana::$environment >= Kohana::DEVELOPMENT){
	Route::set('php_media', '<dir>(/<file>)',
			array(
				'dir' => '(css|images|js)',
				'file' => '.*'
		))
		->defaults(array(
			'controller' => 'media',
			'action'     => 'get',
			'directory'  => NULL,
			'file'       => NULL,
		));
}

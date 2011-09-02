<?php

if(Kohana::$environment >= Kohana::DEVELOPMENT){
	Route::set('media', '<dir>(/<file>)',
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
}else{
	Route::set('media', 'media(/<action>)',
    array(
        'action'    => '(index|move)',
    ))->defaults(array(
        'controller'=> 'media',
        'action'    => 'index',
    ));
}

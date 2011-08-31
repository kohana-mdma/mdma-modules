<?php

Route::set('media', 'media(/<action>)',
    array(
        'action'    => '(index|move)',
    ))->defaults(array(
        'controller'=> 'media',
        'action'    => 'index',
    ));

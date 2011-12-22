<?php defined('SYSPATH') or die('No direct script access.');

Route::set('cart', 'cart(/<action>(/<id>))', array('action' => '[a-zA-Z0-9_]+'))
   ->defaults(array(
       'controller' => 'cart',
       'action'     => 'index',
));

Route::set('cart.qty', 'cart/qty(/<id>)(/<qty>)', array('action' => '[a-zA-Z0-9_]+'))
   ->defaults(array(
       'controller' => 'cart',
       'action'     => 'qty',
));
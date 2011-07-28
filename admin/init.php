<?php defined('SYSPATH') or die('No direct script access.');

Route::set('admin.version', 'admin(/<controller>(/<action>(/<id>)(/version/<version>)))', array('directory' => '[a-zA-Z0-9_]+', 'action' => '[a-zA-Z0-9_]+'))
   ->defaults(array(
       'directory'  => 'admin',
       'controller' => 'dashboard',
       'action'     => 'index',
));
Route::set('admin.node', 'admin(/<controller>(/<action>(/<id>)(/node/<node>)))', array('directory' => '[a-zA-Z0-9_]+', 'action' => '[a-zA-Z0-9_]+'))
   ->defaults(array(
       'directory'  => 'admin',
       'controller' => 'dashboard',
       'action'     => 'index',
));

Route::set('admin', 'admin(/<controller>(/<action>(/<id>)(/node/<node>)(/version/<version>)))', array('directory' => '[a-zA-Z0-9_]+', 'action' => '[a-zA-Z0-9_]+'))
   ->defaults(array(
       'directory'  => 'admin',
       'controller' => 'dashboard',
       'action'     => 'index',
));

Route::set('admin.widgit', 'admin/<controller>(/<action>)', array('directory' => '[a-zA-Z0-9_]+', 'action' => '[a-zA-Z0-9_]+'))
   ->defaults(array(
       'directory'  => 'admin',
       'action'     => 'index',
));
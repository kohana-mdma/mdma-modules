<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Error_Template extends Controller_Template {
	
	public $auto_render = FALSE;
	
	public $view = 'error/template';
	
}

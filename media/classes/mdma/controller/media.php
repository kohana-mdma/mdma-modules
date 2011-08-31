<?php defined('SYSPATH') or die('No direct script access.');

class MDMA_Controller_Media extends Controller {
	
	private $_console = NULL;
	
	public function before(){
		$this->_console = Console::instance();
		parent::before();
	}
	
	public function action_index(){
		$this->_console->out(Console::format("No command.", Console::ERROR).PHP_EOL);
	}
	
	public function action_move()
	{
		MDMA_Media::factory()->move();
	}
			
}
// End MDMA Media
<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Shell extends Controller {

	/**
	 * Console object
	 * @var Console
	 */
	protected $_console;

	/**
	 * Constructor
	 */
	public function before()
	{
		if ( ! Kohana::$is_cli)
		{
			throw new HTTP_Exception_404('Page not found.');
		}
		$this->_console = Console::instance();
		$this->_console->out(Console::format("Kohana Shell", Console::HEADER));
		$this->_console->out("Please type 'help' for help. Type 'exit' to quit.");
		parent::before();
	}

	public function action_index()
	{
		while (($_line_ = $this->readline("\n>> ")) !== false)
		{
			$_line_ = trim($_line_);
			if ($_line_ === 'exit')
				return;
			try
			{
				$_args_ = preg_split('/[\s,]+/', rtrim($_line_, ';'), -1, PREG_SPLIT_NO_EMPTY);
				$this->_console->out(print_r($_args_, TRUE));
				if (isset($_args_[0]) and Kohana::find_file('classes', 'shell/'.$_args_[0], 'php'))
				{
					if (class_exists('shell_'.$_args_[0]) and method_exists('shell_'.$_args_[0], 'run'))
					{
						$class = new ReflectionClass('shell_'.$_args_[0]);
						$shell = $class->newInstance();
						$method = $class->getMethod('run');
						unset($_args_[0]);
						$method->invokeArgs($shell, $_args_);
					}
				}
			}
			catch (Exception $e)
			{
				$this->_console->out(Console::format($e->getMessage(), Console::ERROR));
				die();
			}
		}
	}

	protected function readline($prompt)
	{
		$this->_console->out($prompt);
		return $this->_console->readline();
	}
}
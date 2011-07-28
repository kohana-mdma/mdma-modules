<?php defined('SYSPATH') or die('No direct script access.');

abstract class Typograf
{

	/**
	 * @var   string     default driver to use
	 */
	public static $default = 'artlebedev';

	public static $instances = array();

	public static function instance($group = NULL)
	{
		// If there is no group supplied
		if ($group === NULL)
		{
			// Use the default setting
			$group = Typograf::$default;
		}

		if (isset(Typograf::$instances[$group]))
		{
			// Return the current group if initiated already
			return Typograf::$instances[$group];
		}

		$config = Kohana::config('typograf');

		if ( ! $config->offsetExists($group))
		{
			throw new Kohana_Exception('Failed to load Typograf group: :group', array(':group' => $group));
		}

		$config = $config->get($group);

		// Create a new cache type instance
		$typo_class = 'Typograf_Driver_'.ucfirst($config['driver']);
		Typograf::$instances[$group] = new $typo_class($config);

		// Return the instance
		return Typograf::$instances[$group];
	}

	protected $_config;

	protected function __construct($config)
	{
		$this->_config = $config;
	}

	abstract public function execute($str);
}
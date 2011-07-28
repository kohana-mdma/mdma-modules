<?php defined('SYSPATH') or die('No direct script access.');

class Typograf_Driver_Artlebedev extends Typograf {

	private $_class;


	protected function __construct(array $config)
	{
		parent::__construct($config);
		$this->_class = new Typograf_Lebedev(arr::get($this->_config, 'encoding'));
		foreach (Arr::get($this->_config, 'setting') as $key => $val){
			if(method_exists($this->_class, $key)){
				$this->_class->{$key}($val);
			}
		}
	}

	public function execute($str)
	{
		return $this->_class->process_text($str);
	}

}
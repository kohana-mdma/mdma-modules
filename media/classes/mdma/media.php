<?php defined('SYSPATH') or die('No direct script access.');

class MDMA_Media {

	protected $directory = '';

	public static function factory() {
		return new self();
	}
	
	public function __construct() {
		$this->directory = UTF8::rtrim(Kohana::$config->load('media.directory'), '/').'/';
	}

	public function move($modules = NULL) {
		if (!is_dir($this->directory)) {
			mkdir($this->directory);
		}

		if(is_null($modules))
			$modules = array_intersect_key(Kohana::modules(), array_flip((array) Kohana::$config->load('media.modules', array())));

		$modules[] = APPPATH;
		foreach ($modules as $name => $dir) {
			$dir = UTF8::rtrim($dir, '/') . DIRECTORY_SEPARATOR . 'media'. DIRECTORY_SEPARATOR;
			if (!is_dir($dir))
				continue;
			
			$this->_dir_copy($dir, $this->directory);
		}
	}

	protected function _dir_copy($from_path, $to_path) {
		if (is_dir($from_path)) {
			$handle = opendir($from_path);
			while (($file = readdir($handle)) !== false) {
				if (($file != ".") && ($file != "..")) {
					if (is_dir($from_path.$file)) {
						$this->_dir_copy($from_path . $file . "/", $to_path . $file . "/");
					}
					if (is_file($from_path.$file)){
						if( ! is_dir($to_path)){
							mkdir($to_path, 0755, true);	
						}
						if(file_exists($to_path.$file))
							unlink($to_path.$file);
						
						copy($from_path.$file, $to_path.$file);
					}
				}
			}
			closedir($handle);
		}
	}
}
	
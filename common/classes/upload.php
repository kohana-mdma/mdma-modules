<?php defined('SYSPATH') or die('No direct script access.');

class Upload extends Kohana_Upload {

	/**
	 * Save an uploaded file to a new location. If no filename is provided,
	 * the original filename will be used, with a unique prefix added.
	 *
	 * This method should be used after validating the $_FILES array:
	 *
	 *     if ($array->check())
	 *     {
	 *         // Upload is valid, save it
	 *         Upload::save($_FILES['file']);
	 *     }
	 *
	 * @param   array    uploaded file data
	 * @param   string   new filename
	 * @param   string   new directory
	 * @param   integer  chmod mask
	 * @return  string   on success, full path to new file
	 * @return  FALSE    on failure
	 */
	public static function save(array $file, $filename = NULL, $directory = NULL, $chmod = 0644)
	{
		if ($filename === NULL)
		{
			if(file_exists(realpath($directory).DIRECTORY_SEPARATOR.$file['name']))
			{
				$filename = uniqid().$file['name'];
			}
			else
			{
				$filename = $file['name'];
			}
		}
		return parent::save($file, $filename, $directory, $chmod);
	}
	
	/**
	 *
	 * @param array $_files
	 * @return array 
	 */
	public static function multiple(array $_files)
	{
		$files = array();
		foreach($_files as $name=>$file){
			if(is_array($file['name'])){
				foreach(array_keys($file['name']) as $key){
					$files[$name][$key] = array(
						'name'     => $file['name'][$key],
						'type'     => $file['type'][$key],
						'tmp_name' => $file['tmp_name'][$key],
						'error'    => $file['error'][$key],
						'size'     => $file['size'][$key],
					);
					$files[$name] = self::multiple($files[$name]);
				}
			}else{
				$files[$name] = $file;
			}
		}
		return $files;
	}

} // End upload

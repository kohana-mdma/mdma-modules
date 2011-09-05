<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
  'default'	  => array
  (
	'files_path'        => DOCROOT.'uploads/files/',
	'files_url'         => URL::base().'uploads/files/',
	'images_path'       => DOCROOT.'uploads/images/',
	'images_url'        => URL::base().'/uploads/images/',
	'mode'              => '640',
  ),
);
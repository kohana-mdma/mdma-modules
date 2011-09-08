<?php defined('SYSPATH') or die('No direct script access.');

class Node{

	public static $current = NULL;

	public static function route($uri) {
		if( ! $uri)$uri = 'index';
		$node = ORM::factory('node')->where('url_path', '=', '/'.trim($uri, '/').'/')->find();
		if($node->type == 'folder'){
			$children = $node->children->find_all();
			if(count($children)){
				$node = current($children);
			}else{
				$node = ORM::factory('node');
			}
		}
		if($node->loaded() and $node->request){
			Node::$current = $node;
			$array = Request::process_uri($node->request, array_diff_key(Route::all(), array('node'=>NULL)));
			return $array['params']; 		
		}
	}
}
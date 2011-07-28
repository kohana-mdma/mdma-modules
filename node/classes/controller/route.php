<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Route extends Controller {

	public function action_index() {
		$path = $this->request->param('path');
		$node = ORM::factory('node')->where('url_path', '=', '/'.trim($path, '/').'/')->find();
		if($node->type == 'folder'){
			$children = $node->load_tree()->children;
			if(count($children)){
				$node = current($children);
			}else{
				$node = ORM::factory('node');
			}
		}
		if($node->loaded() and $node->request){
			Node::$current = $node;
			$this->response->body(Request::factory($node->request,NULL,array_diff_key(Route::all(), array('node'=>NULL)))->execute());
		}else{
			throw new HTTP_Exception_404('Unable to find a route to match the URI: :uri', array(
					':uri' => $path,
				));
		}
	}

} // End Route

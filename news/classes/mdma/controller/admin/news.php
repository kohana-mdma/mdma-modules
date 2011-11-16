<?php defined('SYSPATH') or die('No direct script access.');

class MDMA_Controller_Admin_News extends Controller_Admin_CRUD {

	protected function after_save(ORM $item = NULL)
	{
		$config = Kohana::$config->load('news');
		$dir = DOCROOT.Kohana::$config->load('upload.news');
		if($item->saved()){
			$node = ORM::factory('node')
					->where('model', '=', 'news')
					->where('model_id', '=', $item->pk())
					->find()
					->values($_POST[$this->_model] + Arr::get($_POST, 'node', array()));
			$node->request = 'news/view/'.$item->pk();
			$node->model = 'news';
			$node->model_id = $item->pk();
			$node->type = 'leaf';
			$parent = ORM::factory('node', array('name'=>'news'));
			if( ! $parent->loaded()){
				$parent->title = 'Новости';
				$parent->name = 'news';
				$parent->request = 'news/index';
				$parent->model = 'news';
				$parent->type = 'root';
				$parent->save();
			}
			$node->insert($parent);
			
			$files = Upload::multiple($_FILES);
			
			foreach($files['images'] as $file){
				 $upload = Validation::factory(array('file'=>$file))
					->rule('file', 'Upload::valid')
					->rule('file', 'Upload::not_empty')
					->rule('file', 'Upload::type', array(':value', array('jpg', 'png', 'gif')))
					->rule('file', 'Upload::size', array(':value', '8M'));

				if ($upload->check()) {
					
					if(file_exists($dir.$file['name'])){
						$file_info = pathinfo($dir.uniqid().$file['name']);
					}else{
						$file_info = pathinfo($dir.$file['name']);
					}

					$image = ORM::factory('news_image');
					$image->file = $file_info['basename'];				
					$image->thumb = $file_info['filename'].'_thumb.'.$file_info['extension'];
					$image->news_id = $item->pk();

					$info = getimagesize($file['tmp_name']);

					$thumb_w = Arr::path($config, 'size_thumb.0', NULL);
					$thumb_h = Arr::path($config, 'size_thumb.1', NULL);
					$thumb = Image::factory($file['tmp_name'])->resize($thumb_w, $thumb_h,Image::INVERSE);
					if($thumb_w and $thumb_h)
					{
						$thumb->crop($thumb_w, $thumb_h);
					}
					$thumb->save($file_info['dirname'].DIRECTORY_SEPARATOR.$image->thumb, Arr::path($config, 'size_thumb.2', 100));
					
					
					$image_w = Arr::path($config, 'size.0', NULL);
					$image_h = Arr::path($config, 'size.1', NULL);
					
					if(($image_w and $info['0'] > $image_w) or ($image_h and $info['1'] > $image_h))
					{
						$img = Image::factory($file['tmp_name'])->resize($image_w, $image_h, Image::AUTO);
						if($image_w and $image_h)
						{
							$img->crop($image_w, $image_h);
						}
						$img->save($file_info['dirname'].DIRECTORY_SEPARATOR.$image->file, Arr::path($config, 'size.2', NULL));
					}
					else
					{
						Image::factory($file['tmp_name'])->save($file_info['dirname'].DIRECTORY_SEPARATOR.$image->file, Arr::path($config, 'size.2', 100));
					}

					$image->save();
				}
			}
		}
	}

	protected function after_load(array $data = NULL)
	{
		$node = ORM::factory('node')
					->where('model', '=', 'news')
					->where('model_id', '=', $data[$this->_model]['id'])
					->find();
		$data['node'] = $node->as_array();
		$data['images'] = ORM::factory('news_image')->where('news_id', '=', $data[$this->_model]['id'])->find_all()->as_array();
		return $data;
	}
	
	protected function before_delete(ORM $item = NULL)
	{
		foreach($item->images->find_all() as $image){
			$image->delete();
		}
	}
	
} // End Admin News
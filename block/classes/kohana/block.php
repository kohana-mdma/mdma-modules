<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Block helper.
 */
class Kohana_Block {
	public static function show($id, array $attributes = array())
    {
		$block = Cache::instance()->get('block-'.$id, (object) ORM::factory('block')->where('id', '=', $id)->find()->as_array());
		
		if(Kohana::$caching and $block)
		{
			Cache::instance()->set('block-'.$id, $block);
		}
		
        $attributes["class"] = Arr::get($attributes, 'class', '');
        $attributes["class"] .=" blockedit-".$id;
        //TODO create viewer for that
        return "<div ".HTML::attributes($attributes).">".$block->body."</div>";
    }
} // End Block

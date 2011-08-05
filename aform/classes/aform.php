<?php

class AForm {

    public static function input($name, $value = NULL, $title = NULL, $id = NULL)
	{
		return View::factory('aform/default/input', array('name' => $name, 'value' => $value, 'title' => ($title)?$title:$name, 'id'=>($id)?$id:'id'.time()));
	}
	
	public static function textarea($name, $value = NULL, $title = NULL, $id = NULL, $attr = array())
	{
		return View::factory('aform/default/textarea', array('name' => $name, 'value' => $value, 'title' => ($title)?$title:$name, 'id'=>($id)?$id:'id'.time(), 'attr'=> $attr));
	}
}
?>

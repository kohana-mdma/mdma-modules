<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Auth helper.
 */
class A {

    private static $_cache = array('logged_in'=>array());

    public static function logged_in($group = 'login')
    {
        $group = ($group instanceof ORM) ? $group->pk() : $group;
        $group_key = implode('|', (array) $group);
        if( ! array_key_exists($group_key,A::$_cache['logged_in']))
        {
			A::$_cache['logged_in'][$group_key] = Auth::instance()->logged_in($group);
		}
        return A::$_cache['logged_in'][$group_key];
    }
} // End a

<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('alex_stone_storage_get')) {
	function alex_stone_storage_get($var_name, $default='') {
		global $ALEX_STONE_STORAGE;
		return isset($ALEX_STONE_STORAGE[$var_name]) ? $ALEX_STONE_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('alex_stone_storage_set')) {
	function alex_stone_storage_set($var_name, $value) {
		global $ALEX_STONE_STORAGE;
		$ALEX_STONE_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('alex_stone_storage_empty')) {
	function alex_stone_storage_empty($var_name, $key='', $key2='') {
		global $ALEX_STONE_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($ALEX_STONE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($ALEX_STONE_STORAGE[$var_name][$key]);
		else
			return empty($ALEX_STONE_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('alex_stone_storage_isset')) {
	function alex_stone_storage_isset($var_name, $key='', $key2='') {
		global $ALEX_STONE_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($ALEX_STONE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($ALEX_STONE_STORAGE[$var_name][$key]);
		else
			return isset($ALEX_STONE_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('alex_stone_storage_inc')) {
	function alex_stone_storage_inc($var_name, $value=1) {
		global $ALEX_STONE_STORAGE;
		if (empty($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = 0;
		$ALEX_STONE_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('alex_stone_storage_concat')) {
	function alex_stone_storage_concat($var_name, $value) {
		global $ALEX_STONE_STORAGE;
		if (empty($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = '';
		$ALEX_STONE_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('alex_stone_storage_get_array')) {
	function alex_stone_storage_get_array($var_name, $key, $key2='', $default='') {
		global $ALEX_STONE_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($ALEX_STONE_STORAGE[$var_name][$key]) ? $ALEX_STONE_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($ALEX_STONE_STORAGE[$var_name][$key][$key2]) ? $ALEX_STONE_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('alex_stone_storage_set_array')) {
	function alex_stone_storage_set_array($var_name, $key, $value) {
		global $ALEX_STONE_STORAGE;
		if (!isset($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = array();
		if ($key==='')
			$ALEX_STONE_STORAGE[$var_name][] = $value;
		else
			$ALEX_STONE_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('alex_stone_storage_set_array2')) {
	function alex_stone_storage_set_array2($var_name, $key, $key2, $value) {
		global $ALEX_STONE_STORAGE;
		if (!isset($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = array();
		if (!isset($ALEX_STONE_STORAGE[$var_name][$key])) $ALEX_STONE_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$ALEX_STONE_STORAGE[$var_name][$key][] = $value;
		else
			$ALEX_STONE_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('alex_stone_storage_merge_array')) {
	function alex_stone_storage_merge_array($var_name, $key, $value) {
		global $ALEX_STONE_STORAGE;
		if (!isset($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = array();
		if ($key==='')
			$ALEX_STONE_STORAGE[$var_name] = array_merge($ALEX_STONE_STORAGE[$var_name], $value);
		else
			$ALEX_STONE_STORAGE[$var_name][$key] = array_merge($ALEX_STONE_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('alex_stone_storage_set_array_after')) {
	function alex_stone_storage_set_array_after($var_name, $after, $key, $value='') {
		global $ALEX_STONE_STORAGE;
		if (!isset($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = array();
		if (is_array($key))
			alex_stone_array_insert_after($ALEX_STONE_STORAGE[$var_name], $after, $key);
		else
			alex_stone_array_insert_after($ALEX_STONE_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('alex_stone_storage_set_array_before')) {
	function alex_stone_storage_set_array_before($var_name, $before, $key, $value='') {
		global $ALEX_STONE_STORAGE;
		if (!isset($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = array();
		if (is_array($key))
			alex_stone_array_insert_before($ALEX_STONE_STORAGE[$var_name], $before, $key);
		else
			alex_stone_array_insert_before($ALEX_STONE_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('alex_stone_storage_push_array')) {
	function alex_stone_storage_push_array($var_name, $key, $value) {
		global $ALEX_STONE_STORAGE;
		if (!isset($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($ALEX_STONE_STORAGE[$var_name], $value);
		else {
			if (!isset($ALEX_STONE_STORAGE[$var_name][$key])) $ALEX_STONE_STORAGE[$var_name][$key] = array();
			array_push($ALEX_STONE_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('alex_stone_storage_pop_array')) {
	function alex_stone_storage_pop_array($var_name, $key='', $defa='') {
		global $ALEX_STONE_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($ALEX_STONE_STORAGE[$var_name]) && is_array($ALEX_STONE_STORAGE[$var_name]) && count($ALEX_STONE_STORAGE[$var_name]) > 0) 
				$rez = array_pop($ALEX_STONE_STORAGE[$var_name]);
		} else {
			if (isset($ALEX_STONE_STORAGE[$var_name][$key]) && is_array($ALEX_STONE_STORAGE[$var_name][$key]) && count($ALEX_STONE_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($ALEX_STONE_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('alex_stone_storage_inc_array')) {
	function alex_stone_storage_inc_array($var_name, $key, $value=1) {
		global $ALEX_STONE_STORAGE;
		if (!isset($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = array();
		if (empty($ALEX_STONE_STORAGE[$var_name][$key])) $ALEX_STONE_STORAGE[$var_name][$key] = 0;
		$ALEX_STONE_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('alex_stone_storage_concat_array')) {
	function alex_stone_storage_concat_array($var_name, $key, $value) {
		global $ALEX_STONE_STORAGE;
		if (!isset($ALEX_STONE_STORAGE[$var_name])) $ALEX_STONE_STORAGE[$var_name] = array();
		if (empty($ALEX_STONE_STORAGE[$var_name][$key])) $ALEX_STONE_STORAGE[$var_name][$key] = '';
		$ALEX_STONE_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('alex_stone_storage_call_obj_method')) {
	function alex_stone_storage_call_obj_method($var_name, $method, $param=null) {
		global $ALEX_STONE_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($ALEX_STONE_STORAGE[$var_name]) ? $ALEX_STONE_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($ALEX_STONE_STORAGE[$var_name]) ? $ALEX_STONE_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('alex_stone_storage_get_obj_property')) {
	function alex_stone_storage_get_obj_property($var_name, $prop, $default='') {
		global $ALEX_STONE_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($ALEX_STONE_STORAGE[$var_name]->$prop) ? $ALEX_STONE_STORAGE[$var_name]->$prop : $default;
	}
}
?>
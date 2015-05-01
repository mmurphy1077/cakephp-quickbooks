<?php
class Creationsite extends Object {
	
	/**
	 * Prepend a key => value pair to the beginning of an array
	 * 		without losing numerical indexes
	 * PHP's array_unshift() will re-number numerical indexes and
	 * 		break Model::id => Model::name pairs that are used
	 * 		in form select boxes
	 * @see http://us.php.net/manual/en/function.array-unshift.php#106570
	 * 
	 * @param array $arr
	 * @param mixed $key any valid array key type
	 * @param mixed $val any valid array value type
	 * @return array the array with the new key => value paired prepended
	 */
	public static function array_unshift_assoc($arr, $key, $val) {
		$arr = array_reverse($arr, true);
		$arr[$key] = $val;
		$arr = array_reverse($arr, true);
		return $arr;
	}
	
	/**
	 * @see http://stackoverflow.com/a/18833479
	 * 
	 * @param string $str
	 * @return bool true if string has been PHP serialized
	 * 		false otherwise
	 */
	public static function is_serialized($str) {
		if ($str == 'b:0;' || @unserialize($str) !== false) {
			// 'b:0;' is the serialized value of (bool) false
			return true;
		}
		return false;
	}
	
}
?>
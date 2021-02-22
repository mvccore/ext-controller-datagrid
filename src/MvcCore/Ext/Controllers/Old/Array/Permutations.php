<?php

class Grid_Array_Permutations
{
	public static function get($arr) {

		$power_set = self::power_set($arr);
		$result = array();
		foreach($power_set as $set) {
			$perms = self::perms($set);
			$result = array_merge($result,$perms);
		}
		return $result;
	}

	protected static function power_set($in,$minLength = 1) {

	   $count = count($in);
	   $members = pow(2,$count);
	   $return = array(array());
	   for ($i = 0; $i < $members; $i++) {
		  $b = sprintf("%0".$count."b",$i);
		  $out = array();
		  for ($j = 0; $j < $count; $j++) {
			 if ($b{$j} == '1') $out[] = $in[$j];
		  }
		  if (count($out) >= $minLength) {
			 $return[] = $out;
		  }
	   }

	   //usort($return,"cmp");  //can sort here by length
	   return $return;
	}

	protected static function factorial($int){
	   if($int < 2) {
		   return 1;
	   }

	   for($f = 2; $int-1 > 1; $f *= $int--);

	   return $f;
	}

	protected static function perm($arr, $nth = null) {

		if ($nth === null) {
			return self::perms($arr);
		}

		$result = array();
		$length = count($arr);

		while ($length--) {
			$f = self::factorial($length);
			$p = floor($nth / $f);
			$result[] = $arr[$p];
			self::array_delete_by_key($arr, $p);
			$nth -= $p * $f;
		}

		$result = array_merge($result,$arr);
		return $result;
	}

	protected static function perms($arr) {
		$p = array();
		for ($i=0; $i < self::factorial(count($arr)); $i++) {
			$p[] = self::perm($arr, $i);
		}
		return $p;
	}

	protected static function array_delete_by_key(&$array, $delete_key, $use_old_keys = FALSE) {

		unset($array[$delete_key]);

		if(!$use_old_keys) {
			$array = array_values($array);
		}

		return TRUE;
	}

}
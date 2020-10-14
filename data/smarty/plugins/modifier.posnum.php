<?php
/*
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/*
 * Smarty cat modifier plugin
 *
 * Type:     modifier
 * Name:     posnum
 * Date:     Jun 11, 2014
 * Purpose:  Convert 10000 => 1K, 1000000 => 1M
 * Example:  {$number|posnum}
 * @author   einet
 * @version 1.0
 * @param int
 * @return string
 */
function smarty_modifier_posnum($number){
	$pre = 'KMG';
	if ($number >= 1000) {
		for ($i=-1; $number>=1000; ++$i) {
			$number /= 1000;
		}
		return round($number,1).$pre[$i];
	} else return $number;
}
?>

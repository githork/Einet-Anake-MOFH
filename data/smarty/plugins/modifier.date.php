<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty cat modifier plugin
 *
 * Type:     modifier<br>
 * Name:     fecha<br>
 * Date:     Feb 24, 2010
 * Purpose:  catenate a value to a variable
 * Input:    string to catenate
 * Example:  {$var|fecha}
 * @link http://smarty.php.net/manual/en/language.modifier.cat.php cat
 *          (Smarty online manual)
 * @author   Ivan Molina Pavana
 * @version 1.0
 * @param string
 * @return string
 */
function smarty_modifier_date($fecha, $format = false){
    $_meces = array('','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $_dias = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    // FORMATO?
        // VARS
        $dia = date("d",$fecha);
        $mes = date("m",$fecha);
        $mes_int = date("n",$fecha);
        $ano = date("Y",$fecha);
        $return = "The ".$dia." of ".$_meces[date("n",$fecha)]." from ".$ano;
        // REGRESAMOS
        return $return;
	}

?>

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
 * Name:     hace<br>
 * Date:     Feb 24, 2010
 * Purpose:  catenate a value to a variable
 * Input:    string to catenate
 * Example:  {$var|cat:"foo"}
 * @link http://smarty.php.net/manual/en/language.modifier.cat.php cat
 *          (Smarty online manual)
 * @author   Ivan Molina Pavana
 * @version 1.0
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_hace($fecha, $show = false){
     
    $ahora = time();
    $tiempo = $ahora - $fecha; 
    //
    $dias = round($tiempo / 86400);
    // HOY
    if($dias <= 0) {
        // HACE MENOS DE 1 HORA
        if(round($tiempo / 3600) <= 0){ 
            // HACE MENOS DE 1 MINUTO
            if(round($tiempo / 60) <= 0){ 
                if($tiempo <= 60){ $hace = "A few seconds ago"; }
            // HACE X MINUTOS 
            } else  { 
            	$can = round($tiempo / 60); 
            	if($can <= 1) {    $word = "minute"; } else { $word = "minutes"; } 
            	$hace = 'Ago '.$can. " ".$word; 
            }
        // HACE X HORAS
        } else { 
            $can = round($tiempo / 3600); 
            if($can <= 1) {    $word = "hour"; } else {    $word = "hours"; } 
            $hace = 'Ago '.$can. " ".$word; 
        }    
    }
    // MENOS DE 7 DIAS
    else if($dias <= 30){
        // AYER
        if($dias < 2){
            $hace = 'Yesterday';
        // HACE MENOS DE 5 DIAS
        } else {
            $hace = 'Ago '.$dias.' days';
        }
    // HACE MAS DE UNA SEMANA
    } else{
        $meses = round($tiempo / 2592000);
        if($meses == 1) $hace = 'More than 1 months';
        elseif($meses < 12) {
            $hace = 'More of '.$meses.' months';
        } else {
            $anos = round($tiempo / 31536000);
            if($anos == 1) $hace = 'More than a year';
            else $hace = 'More than '.$anos.' years';
        }
    }
    //
    return $hace;

}

/* vim: set expandtab: */

?>

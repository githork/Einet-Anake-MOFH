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
 * Name:     cat<br>
 * Date:     Feb 24, 2003
 * Purpose:  catenate a value to a variable
 * Input:    string to catenate
 * Example:  {$var|cat:"foo"}
 * @link http://smarty.php.net/manual/en/language.modifier.limit.php cat
 *          (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @version 1.0
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_limit($text) {	
$a = array(
  "/(?i)\[i\](.*?)\[\/i\]/i",
  "/(?i)\[b\](.*?)\[\/b\]/i",
  "/(?i)\[u\](.*?)\[\/u\]/i",
  "/(?i)\[s\](.*?)\[\/s\]/i",
  "/(?i)\[sub\](.*?)\[\/sub\]/i",
  "/(?i)\[sup\](.*?)\[\/sup\]/i",
  "/(?i)\[color=(.*?)\](.*?)\[\/color\]/i",
  "/(?i)\[size=(.*?)\](.*?)\[\/size\]/i",
  "/(?i)\[font=(.*?)\](.*?)\[\/font\]/i",
  "/(?i)\[quote\](.*?)\[\/quote\]/i",
  "/(?i)\[hr\]/i",
  "/(?i)\[list\](.*?)\[\/list\]/i",
  "/(?i)\[ol\](.*?)\[\/ol]/i",
  "/(?i)\[\*\](.*)/i",
  "/(?i)\[table\](.*?)\[\/table\]/i",
  "/(?i)\[tr\](.*?)\[\/tr\]/i",
  "/(?i)\[td\](.*?)\[\/td\]/i",	
  "/(?i)\[align=(.*?)\](.*?)\[\/align\]/i",
  "/(?i)\[img\](.*?)\[\/img\]/i",
  "/(?i)\[img=(.*?)\]/i",
  "/(?i)\[url\](.*?)\[\/url\]/i",
  "/(?i)\[url=(.*?)\](.*?)\[\/url\]/i",
  "/(?i)\[protect\](.*?)\[\/protect\]/i",
  "/(?i)\[video\](.*?)\[\/video\]/i",
  "/(?i)\[swf=(.*?)\]/i",
  "/(?i)\[pdf=(.*?)\]/i",
  "/(?i)\[code\](.*?)\[\/code\]/i",
  "/(?i)\[textarea\](.*?)\[\/textarea\]/i",
  "/(?i)\[onox=iframe\](.*?)\[\/onox\]/i",
);
$clean = str_replace("\n", '',$text);
$clean = preg_replace($a, '', $clean);
$data = substr($clean ,0 ,200).'...';//lo standar es 300

return $data;
}

?>

<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found.</h2>');
#+----------------------------------------------------------+
#| Realizamos algunas tareas para cargar la plantilla		|
#+----------------------------------------------------------+
 
 // Pagina solicitada
 $anAk->assign("tsPage", $tsPage);
 $anAk_next = false;
  
 // Verificamos si la plantilla existe, si no existe mostramos la que está en default
 if(!$anAk->templateExists("n.$tsPage.tpl")) {
 $anAk->setTemplateDir(EA_ROOT.DIRECTORY_SEPARATOR.'temas'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'tpl');
 $anAk->setCompileDir(EA_ROOT.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'compiled');
  
 if($anAk->templateExists("n.$tsPage.tpl")) $anAk_next = true;
 } else $anAk_next = true;

 // Cargamos la plantilla
 if($anAk_next == true) $anAk->display("n.$tsPage.tpl");
 else die('0: Sorry, the template for the website could not be loaded.');
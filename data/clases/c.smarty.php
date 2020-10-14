<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: c.smarty.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

require EA_ROOT.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'SmartyBC.class.php';

class tsSmarty extends Smarty {
	 var $_tpl_hooks;
	 var $_tpl_hooks_no_multi = TRUE;
	 
	 function __construct() {
     global $tsCore;
	 //
	 parent::__construct();
	 
	 $this->setTemplateDir(EA_ROOT.DIRECTORY_SEPARATOR.'temas'.DIRECTORY_SEPARATOR.EA_TEMA.DIRECTORY_SEPARATOR.'tpl');
	 $this->setCompileDir(EA_ROOT.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.EA_TEMA.DIRECTORY_SEPARATOR.'compiled');
     $this->setCacheDir(EA_ROOT.DIRECTORY_SEPARATOR.'cache');
	 $this->setConfigDir(array('url' => $tsCore->settings['url'], 'title' => $tsCore->settings['titulo'])); 
	 // smarty esta en modo depuracion SI|NO
     $this->debugging = false;
	 // configuracion de cache SI|NO cacheo completo no me sirve
     $this->caching = false;
     // define el tiempo de vida de cache, si esta activa 3600 = 1 hora
     $this->cache_lifetime = 3600;
	 //
     $this->_tpl_hooks = array();
	 //
     }
	 
     function assign_hook($hook, $include) {
     if(!isset($this->_tpl_hooks[$hook]))
     $this->_tpl_hooks[$hook] = array();
    
     if($this->_tpl_hooks_no_multi && in_array($include, $this->_tpl_hooks[$hook]))
     return;
    
     $this->_tpl_hooks[$hook][] = $include;
     }
	 
//
}
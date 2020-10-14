<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: req.panel.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

// NIVELES DE ACCESO Y PLANTILLAS PARA CADA ACCION.
$files = array(
	'panel-get_domain' => array('n' => 2, 'p' => ''),#Verificar disponibilidad de dominio.
	'panel-create_account' => array('n' => 2, 'p' => ''),#Crear cuenta hosting.
	'panel-tools_account' => array('n' => 2, 'p' => ''),#Herramientas cuentas.
);

#+----------------------------------------------+
#|   (VARIABLES LOCALES PARA ESTE ARCHIVO)		|
#+----------------------------------------------+
	
	// Re-definir variables.
	$tsPage = 'files_php/p.panel.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
#+--------------------------------------+
#|      (INSTRUCCIONES DE CODIGO)		|
#+--------------------------------------+

	// Depende del nivel de la plantilla.
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
    
	// La clase que vamos a utilizar.
	include EA_CLASS.'c.panel.php';
    $tsPanel = new tsPanel();
	
	// Accion.
    $anAk->assign("tsAction", $action);
	switch($action) {
	case 'panel-get_domain':
	//<-- Verificar dominio.
	echo $tsPanel->get_domain();
	//-->
	break;
	case 'panel-create_account':
	//<-- Crear cuenta hosting.
	echo $tsPanel->create_account();
	//-->
	break;
	case 'panel-tools_account':
	//<--
	echo $tsPanel->tools_account();
	//-->
	break;
	}
<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: req.live.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

// NIVELES DE ACCESO Y PLANTILLAS PARA CADA ACCION.
$files = array(
	'live-stream' => array('n' => 2, 'p' => 'stream'),
	'live-ajax' => array('n' => 2, 'p' => 'ajax'),
);

#+----------------------------------------------+
#|   (VARIABLES LOCALES PARA ESTE ARCHIVO)		|
#+----------------------------------------------+

	// Re-definir variables.
	$tsPage = 'files_php/p.live.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
#+--------------------------------------+
#|      (INSTRUCCIONES DE CODIGO)		|
#+--------------------------------------+

	// Depende del nivel de la plantilla.
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	
	switch($action) {
	case 'live-stream':
	// Notificaciones.
    if($_POST['ntf'] == 'ON') {
    $tsNotifications = $tsMonitor->get_notificaciones(true);
	$anAk->assign("tsStream", $tsNotifications);
	}
	// Mensajes.
	if($_POST['msg'] == 'ON') {
    $tsMensajes = $tsMP->get_mensajes(1, true, 'live');
    $anAk->assign("tsMensajes", $tsMensajes);
	//  
    }
	//-->
	break;
	case 'live-ajax':
	//<--
	if($_POST['action'] != 'last') $tsAjax = 1;#mostrar si es por ajax.
	$notificaciones = $tsMonitor->get_notificaciones();
	$anAk->assign("tsNotifications", $notificaciones);
	//-->
	break;
    default:
	//<--
    die('0: Sorry this page doesn\'t exist.');
    //-->
	break;
	}
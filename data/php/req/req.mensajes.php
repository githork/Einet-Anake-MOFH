<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: req.mensajes.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

// NIVELES DE ACCESO Y PLANTILLAS PARA CADA ACCION.
$files = array(
	'mensajes-lista' => array('n' => 2, 'p' => 'lista'),#notificaciones de mensajes.
	'mensajes-enviar' => array('n' => 2, 'p' => ''),#enviar mensaje.
	'mensajes-respuesta' =>array('n' => 2, 'p' => 'respuesta'),#enviar respuesta.
	'mensajes-tools' => array('n' => 2, 'p' => ''),#herramientas para mensajes.
);

#+----------------------------------------------+
#|   (VARIABLES LOCALES PARA ESTE ARCHIVO)		|
#+----------------------------------------------+

	// Re-definir variables.
	$tsPage = 'files_php/p.mensajes.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
#+--------------------------------------+
#|      (INSTRUCCIONES DE CODIGO)		|
#+--------------------------------------+

	// Depende del nivel de la plantilla.
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	
	switch($action) {
	case 'mensajes-lista':
	//<--
    $anAk->assign("tsMensajes", $tsMP->get_mensajes(1, 'monitor'));
    //-->
	break;
	case 'mensajes-enviar':
	//<--
	echo $tsMP->new_mensaje();
	//-->
	break;
	case 'mensajes-respuesta':
	//<--
	$tsData = $tsMP->new_respuesta();
	// Si se produce un error los mostramos..
	echo $tsData['type'];
	if((int)$tsData['type'] == 1) { $anAk->assign("tsMensajes", $tsData['data']); }
	//-->
	break;
	case 'mensajes-tools':
	//<--
	echo $tsMP->editar_mensajes();
	//-->
	break;
	}
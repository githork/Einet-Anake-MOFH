<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: req.update.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

// NIVELES DE ACCESO Y PLANTILLAS PARA CADA ACCION.
$files = array(
	'update-support' =>  array('n' => 0, 'p' => ''),
	'update-version' =>  array('n' => 0, 'p' => ''),
);

#+----------------------------------------------+
#|   (VARIABLES LOCALES PARA ESTE ARCHIVO)		|
#+----------------------------------------------+

	// Re-definir variables.
	$tsPage = 'files_php/p.update.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
#+--------------------------------------+
#|      (INSTRUCCIONES DE CODIGO)		|
#+--------------------------------------+

	// Depende del nivel de la plantilla.
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	
	// Variables.
    $code = array(
	'w' => $tsCore->settings['titulo'], 
	's' => $tsCore->settings['description'], 
	'u' => str_replace(array('http://','https://'), '', $tsCore->settings['url']), 
	'v' => $tsCore->settings['version_code'], 
	'a' => $tsUser->nick, 
	'i' => $tsUser->uid,
	);
	
	// Generamos una llave.
    $key = base64_encode(serialize($code));
	switch($action) {
	case 'update-support':
	//<-- Consultar actualizaciones oficiales.
	//$json = $tsCore->get_content('http://localhost/feed.php?type=support&key='.$key);
	if(!substr($json, 0, 1)) echo '';
	else echo $json;
	//--->
	break;
	case 'update-version':
	// Version actual del script.
	$tsVer = array(
	'version' => 'Anake v5.2.0.30',
	'version_code' => 'Anake_5_2_0_30',
	'date' => $tsCore->settings['date'],
	);
	// Hay que actualizar la version?
    if($tsCore->settings['version'] != $tsVer['version']) {
	// Extraemos datos para actualizar.
	$ext = unserialize($tsCore->settings['activos']);#extraemos datos.
	$ext['web_update'] = $tsVer['date'];#actualizamos el campo de la web.
	$ext = serialize($ext);#volvemos a guardar datos.
	
	// Enviamos datos para actualizar.
	$tsData = array(
	'activos' => $ext,
	'version' => $tsVer['version'],
	'version_code' => $tsVer['version_code'],
	);
	//echo $tsCore->update_version($tsData);
	//
    }
	// Mostramos si hay una version nueva a descargar.
	//$json = $tsCore->get_content('http://localhost/feed.php?type=version&key='.$key);
	if(!substr($json, 0, 1)) echo '';
	else echo $json;
	//--->
	break;
    default:
    //<--
    die('0: Cannot establish connection to the main server.');
    //-->
    break;
    }
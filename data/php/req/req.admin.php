<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: req.admin.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

// NIVELES DE ACCESO Y PLANTILLAS PARA CADA ACCION.
$files = array(
	'admin-save_config' => array('n' => 4, 'p' => ''),#guardar configuraciones.
	'admin-tema_tools' => array('n' => 4, 'p' => ''),#herramientas para temas.
	'admin-tema_edit' => array('n' => 4, 'p' => 'tools-tema'),#editar tema.
	'admin-tema_update' => array('n' => 4, 'p' => 'tools-tema'),#mostrar temas.
	'admin-upload_theme' => array('n' => 4, 'p' => ''),#subir un tema.
	'admin-noticia_tools' => array('n' => 4, 'p' => ''),#herramientas para noticias.
	'admin-noticia_edit' => array('n' => 4, 'p' => 'tools-noticia'),#editar noticia.
	'admin-noticia_update' => array('n' => 4, 'p' => 'tools-noticia'),#mostrar noticias.
	'admin-noticia_agregar' => array('n' => 4, 'p' => ''),#agregar noticia.
	'admin-save_publicidad' => array('n' => 4, 'p' => ''),#guardar publicidad.
	'admin-save_api' => array('n' => 4, 'p' => ''),#guardar cambios de api.
	'admin-blackList_edit' => array('n' => 4, 'p' => 'tools-blacklist'),#editar item blackList.
	'admin-blackList_tools' => array('n' => 4, 'p' => ''),#herramienta para blackList.
	'admin-blackList_update' => array('n' => 4, 'p' => 'tools-blacklist'),#actualizar blackList.
	'admin-blackList_agregar' => array('n' => 4, 'p' => ''),#agregar blackList.
	'admin-editar_plan' => array('n' => 4, 'p' => 'tools-reseller'),#Editar plan hosting.
	'admin-tools_plan' => array('n' => 4, 'p' => ''),#Herramientas reseller.
	'admin-update_plan' => array('n' => 4, 'p' => 'tools-reseller'),#Actualizar lista de planes hosting.
	'admin-editar_nameserver' => array('n' => 4, 'p' => 'tools-reseller'),#Editar servidores NS.
	'admin-tools_nameserver' => array('n' => 4, 'p' => ''),#Herramientas servidores NS.
	'admin-update_nameservers' => array('n' => 4, 'p' => 'tools-reseller'),#Actualizar lista de servidores NS.
	'admin-editar_reseller' => array('n' => 4, 'p' => 'tools-reseller'),#Editar cuenta reseller.
	'admin-tools_reseller' => array('n' => 4, 'p' => ''),#Herramientas cuentas reseller.
	'admin-update_resellers' => array('n' => 4, 'p' => 'tools-reseller'),#Actualizar lista de cuentas reseller.
);

#+----------------------------------------------+
#|   (VARIABLES LOCALES PARA ESTE ARCHIVO)		|
#+----------------------------------------------+
	
	// Re-definir variables.
	$tsPage = 'files_php/p.admin.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
#+--------------------------------------+
#|      (INSTRUCCIONES DE CODIGO)		|
#+--------------------------------------+

	// Depende del nivel de la plantilla.
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
    
	// La clase que vamos a utilizar.
	include EA_CLASS.'c.admin.php';
    $tsAdmin = new tsAdmin();
	
	// Accion.
    $anAk->assign("tsAction", $action);
	switch($action) {
	case 'admin-save_config':	
	//<--
	echo $tsAdmin->saved_config();
	//-->	
    break;
	case 'admin-tema_tools':
	//<--
	echo $tsAdmin->theme_tools();
	//-->	
    break;
	case 'admin-tema_edit':
	//<--
	$data = array('t_id' => $tsCore->setProtect((int)$_POST['tid']), 'type' => 1);
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsTema", $tsAdmin->get_temas($data));
	$anAk->assign("tsType", (int)$_POST['type']);
	//-->	
    break;
	case 'admin-tema_update':
	//<--
	$anAk->assign("tsAction", $action);
    $anAk->assign("tsTemas", $tsAdmin->get_temas());
	//-->	
    break;
	case 'admin-upload_theme':
	//<--
	include EA_CLASS.'c.upload.php';
	$tsUpload = new tsUpload();
	echo $tsUpload->upload_theme();
	//-->	
    break;
	case 'admin-noticia_tools':
	//<--
	echo $tsAdmin->noticia_tools();
	//-->
	break;
	case 'admin-noticia_edit':
	//<--
	$data = array('not_id' => $tsCore->setProtect((int)$_POST['nid']), 'type' => 1);
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsNoticia", $tsAdmin->get_noticias($data));
	$anAk->assign("tsType", (int)$_POST['type']);
	//-->
	break;
	case 'admin-noticia_update':
	//<--
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsNoticias", $tsAdmin->get_noticias());
	//-->
	break;
	case 'admin-noticia_agregar':
	//<--
	echo $tsAdmin->noticia_agregar();
	//-->
	break;
	case 'admin-save_publicidad':
	//<--
	echo $tsAdmin->saved_config();
	//-->
	break;
	case 'admin-save_api':
	//<--
	echo $tsAdmin->save_api_config();
	//-->
	break;
	case 'admin-blackList_edit':
	//<--
	$data = array('id' => $tsCore->setProtect((int)$_POST['bid']), 'type' => 1);
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsBlackType", $tsBlackList);#tipo en blackList.
    $anAk->assign("tsBlackList", $tsAdmin->get_blackList($data));
	$anAk->assign("tsType", (int)$_POST['type']);
	//-->
	break;
	case 'admin-blackList_tools':
	//<--
	echo $tsAdmin->blackList_tools();
	//-->
	break;
	case 'admin-blackList_update':
	//<--
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsBlackType", $tsBlackList);#tipo en blackList.
	$anAk->assign("tsBlackList", $tsAdmin->get_blackList());
	//-->
	break;
	case 'admin-blackList_agregar':
	//<--
	echo $tsAdmin->agregar_blackList();
	//-->
	break;
	case 'admin-editar_plan':
	//<--
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsPlan", array('pid' => (int)$_POST['pid'], 'type' => (int)$_POST['type']));
	//-->
	break;
	case 'admin-tools_plan':
	//<--
	echo $tsAdmin->tools_planes();
	//-->
	break;
	case 'admin-update_plan':
	//<--
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsPlanes", $tsAdmin->tools_planes());
	//-->
	break;
	case 'admin-editar_nameserver':
	//<--
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsServer", array('nid' => (int)$_POST['nid'], 'type' => (int)$_POST['type']));
	//-->
	break;
	case 'admin-tools_nameserver':
	//<--
	echo $tsAdmin->tools_nameservers();
	//-->
	break;
	case 'admin-update_nameservers':
	//<--
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsServers", $tsAdmin->tools_nameservers());
	//-->
	break;
	case 'admin-editar_reseller':
	//<--
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsCuenta", array('cid' => (int)$_POST['cid'], 'type' => (int)$_POST['type']));
	//-->
	break;
	case 'admin-tools_reseller':
	//<--
	echo $tsAdmin->tools_resellers();
	//-->
	break;
	case 'admin-update_resellers':
	//<--
	$anAk->assign("tsAction", $action);
	$anAk->assign("tsCuentas", $tsAdmin->tools_resellers());
	//-->
	break;
	}
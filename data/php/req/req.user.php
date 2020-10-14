<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: req.user.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

// NIVELES DE ACCESO Y PLANTILLAS PARA CADA ACCION.
$files = array(
	'user-login_user' =>  array('n' => 0, 'p' => ''),#iniciar sesion.
	'user-salir' => array('n' => 0, 'p' => ''),#cerrar sesion.
	'user-cuenta_saved' =>  array('n' => 2, 'p' => ''),#guardar datos del usuario.
	'user-imagen_original' => array('n' => 2, 'p' => ''),#subir imagen para el avatar user.
	'user-guardar_imagen' => array('n' => 2, 'p' => ''),#recortar imagen del avatar.
	'user-recover_pass' => array('n' => 1, 'p' => ''),#recuperar contraseña.
	'user-validation' => array('n' => 1, 'p' => ''),#validar cuenta.
	'user-follow_user' => array('n' => 2, 'p' => ''),#agregar|eliminar usuario.
	'user-guardar_filtros' => array('n' => 2, 'p' => ''),#guardar configuraciones de notificaciones.
	'user-notification_tools' => array('n' => 2, 'p' => ''),#herramientas para notificaciones.
	'user-registro_nick' => array('n' => 1, 'p' => ''),#chequear nick de usuario.
	'user-registro_email' => array('n' => 1, 'p' => ''),#chequear email de usuario.
	'user-registro_nuevo' => array('n' => 1, 'p' => ''),#crear cuenta de usuario.
	'user-login_social' => array('n' => 0, 'p' => ''),#iniciar sesion (con red social).
);

#+----------------------------------------------+
#|   (VARIABLES LOCALES PARA ESTE ARCHIVO)		|
#+----------------------------------------------+

	// Re-definir variables.
	$tsPage = 'files_php/p.user.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;
	
#+--------------------------------------+
#|      (INSTRUCCIONES DE CODIGO)		|
#+--------------------------------------+

	// Depende del nivel de la plantilla.
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg['mensaje']; die();}
	
	switch($action) {
	case 'user-login_user':
	//<--
	echo $tsUser->login_user();
	//-->
	break;
	case 'user-salir':
	//<--
	echo $tsUser->login_out();
	//-->
	break;
	case 'user-cuenta_saved':
	//<--
	include EA_CLASS.'c.panel.php';
	$tsPanel = new tsPanel();
    echo $tsUser->save_perfil();
	//-->
	break;
	case 'user-imagen_original':
	//<--
	include EA_CLASS.'c.upload.php';
	$tsUpload = new tsUpload();
	echo $tsUpload->upload_local($_FILES['img']['tmp_name'], 4, 1);#->1 para avatar.
	//-->
	break;
	case 'user-guardar_imagen':
	//<--
	include EA_CLASS.'c.upload.php';
	$tsUpload = new tsUpload();
	echo $tsUpload->upload_avatar();
	//-->
	break;
	case 'user-recover_pass':
	//<--
	// Realizamos la consulta.
	$tsData = $tsUser->get_validation();
	// El servicio de email esta activo?
	if($tsCore->settings['email_active'] == 1) 
	exit('0: Sorry, the service for sending email is disabled.<br/> Please contact the administrator to request access to your account <a href="mailto:'.$tsCore->settings['email_web'].'" id="url_str">'.$tsCore->settings['email_web'].'</a>');
	
	// Se genero un codigo hash?
	if(!$tsData['hash']) exit('0: <b>Error occurred while trying to process the request..</b>');
	
	// Si todo esta bien enviamos el mensaje.
	if($tsData['user_email'])
	// Validamos el email, preparamos la plantilla.
	$tsMessage = array(
	'from' => $tsCore->setProtect($tsCore->settings['email_web']),#email server
	'to' => $tsCore->setProtect($tsData['user_email']),#email usuario
	'subject' => 'User password recovery '.$tsCore->setProtect($tsData['user_nick']).'..',#titulo mensaje
	);
	
	// Cuerpo del mensaje.
	$message  = '<h2>Hi, '.$tsCore->setProtect($tsData['user_nick']).'.</h2>';
	$message .= '<span style="display:block;padding:10px 0;margin-bottom:10px;">';
	$message .= 'Reset your password in '.$tsCore->setProtect($tsCore->settings['titulo']).'.<br /><br />';
	$message .= 'Young, <b>'.$tsCore->setProtect($tsData['user_nick']).'</b> you have received this message because a password reset has been requested for your account, from the ip address: <b>'.$tsCore->setProtect($tsCore->settings['ip']).'</b><br /><br /> Please access the following link <a href="'.$tsCore->settings['url'].'/activar/1/'.$tsCore->setProtect($tsData['user_email']).'/'.$tsCore->setProtect($tsData['hash']).'" target="_blank">Update password</a> or click on the Update password button, to start the restoration process.';
	$message .= '</span><a href="'.$tsCore->settings['url'].'/activar/1/'.$tsCore->setProtect($tsData['user_email']).'/'.$tsCore->setProtect($tsData['hash']).'" target="_blank" style="padding:10px;background:#23D160;color:#FFFFFF;text-decoration:none;border-radius:3px;">Update password</a><br /><br />';
	$message .= '<span style="display:block;padding:10px 0;margin-bottom:10px;"><b>Note:</b><br /> If for any reason you have not requested to reset your password please delete this message.</span>';
	// Unimos el mensaje con la plantilla.
	$tsMessage['body'] = $tsCore->plantilla_email($message);
	
	// Enviamos el email.
	$tsOut = $tsCore->send_email($tsMessage);
	if(intval(substr($tsOut, 0, 1)) == 1) exit('1: Nice!! young, '.$tsCore->setProtect($tsData['user_nick']).' have sent a message to your email with the necessary data to reset your password...<br/> If it doesn\'t appear in your inbox remember to check your spam..');
	else exit($tsOut);// Algun error? lo mostramos..
	//-->
	break;
	case 'user-validation':
	//<--
	// Realizamos la consulta.
	$tsData = $tsUser->get_validation();
	// El servicio de email esta activo?
	if($tsCore->settings['email_active'] == 1) 
	exit('0: Sorry, the service for sending email is disabled.<br/> Please contact the administrator to request access to your account <a href="mailto:'.$tsCore->settings['email_web'].'" id="url_str">'.$tsCore->settings['email_web'].'</a>');
	
	// Se genero un codigo hash?
	if(!$tsData['hash']) exit('0: <b>Error occurred while trying to process the request..</b>');
	
	// Si todo esta bien enviamos el mensaje.
	if($tsData['user_email'])
	// Validamos el email, preparamos la plantilla.
	$tsMessage = array(
	'from' => $tsCore->setProtect($tsCore->settings['email_web']),#email server
	'to' => $tsCore->setProtect($tsData['user_email']),#email usuario
	'subject' => $tsCore->setProtect($tsData['user_nick']).', please activate your account now..',#titulo mensaje
	);
	
	// Cuerpo del mensaje.
	$message  = '<h2>Hi, '.$tsCore->setProtect($tsData['user_nick']).'.</h2>';
	$message .= '<span style="display:block;padding:10px 0;margin-bottom:10px;">';
	$message .= 'It\'s time to activate your account.<br /><br />, Young, '.$tsCore->setProtect($tsData['user_nick']).' as you will notice you are one step away from completing the activation process to your account at '.$tsCore->setProtect($tsCore->settings['titulo']).', This way you will be able to enjoy all the services offered '.$tsCore->setProtect($tsCore->settings['titulo']).'.<br /><br /> Please access the following link <a href="'.$tsCore->settings['url'].'/activar/2/'.$tsCore->setProtect($tsData['user_email']).'/'.$tsCore->setProtect($tsData['hash']).'" target="_blank">Account activation</a> or click on the Activate my account now button to complete the activation process.';
	$message .= '</span><a href="'.$tsCore->settings['url'].'/activar/2/'.$tsCore->setProtect($tsData['user_email']).'/'.$tsCore->setProtect($tsData['hash']).'" target="_blank" style="padding:10px;background:#23D160;color:#FFFFFF;text-decoration:none;border-radius:3px;">Activate my account now</a><br /><br />';
	$message .= '<span style="display:block;padding:10px 0;margin-bottom:10px;"><b>¿Problems activating your account?</b><br />
If for any reason you have problems with your account contact us from support <b>'.$tsCore->setProtect($tsCore->settings['email_web']).'</b> with details about the problems with your account.</span>';
    // Unimos el mensaje con la plantilla.
	$tsMessage['body'] = $tsCore->plantilla_email($message);
	
	// Enviamos el email.
	$tsOut = $tsCore->send_email($tsMessage);
	if(intval(substr($tsOut, 0, 1)) == 1) exit('1: Great we just sent a message to your email with the necessary data to complete the activation process ...<br/> If it does not appear in your inbox remember to check your spam..');
	else exit($tsOut);// Algun error? lo mostramos..
	//-->
	break;
	case 'user-follow_user':
	//<--
	// Verificamos si ya sigue al usuario.
	$val_data = $tsUser->is_follow($tsCore->setProtect($_POST['pid']), 1);#1 usuario.
	if($val_data == 1) {
	// Eliminamos al usuario de amigos con 1
	echo $tsUser->get_follow($tsCore->setProtect($_POST['pid']), 1, 1);#1 usuario.
	} else {
	// Agregamos al usuario a amigos con 0
	echo $tsUser->get_follow($tsCore->setProtect($_POST['pid']), 0, 1);#1 usuario.
	}
	//-->
	break;
	case 'user-guardar_filtros':
	//<--
	echo $tsUser->guardar_filtros();
	//-->
	break;
	case 'user-notification_tools':
	//<--
	echo $tsMonitor->tools_notificacion();
	//-->
	break;
	case 'user-registro_nick':
	case 'user-registro_email':
	//<--
	include EA_CLASS.'c.registro.php';
	$tsRegistro = new tsRegistro();
	echo $tsRegistro->check_user_email();
	//-->
	break;
	case 'user-registro_nuevo':
	//<--
	include EA_CLASS.'c.registro.php';
	$tsRegistro = new tsRegistro();
	echo $tsRegistro->register_user();
	//-->
	break;
	case 'user-login_social':
	//<--
	$tsData = $tsUser->login_social();
	$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->getTitle('login');
	$tsPage = 'aviso';
	$tsAjax = 0;
	if(intval(substr($tsData, 0, 1)) == 1) {
	$anAk->assign("tsAviso", array(
	'titulo' => 'Perfect! Congratulations.', 
	'mensaje' => substr($tsData, 2), 
	'but' => 'Login now', 'link' => $tsCore->settings['url'].'/social/'.$_GET['id']));
	//
	} else {
	$anAk->assign("tsAviso", array(
	'titulo' => 'Sorry, a problem occurred.', 
	'mensaje' => substr($tsData, 2), 
	'but' => 'Try again', 'link' => $tsCore->settings['url'].'/social/'.$_GET['id']));
	//
	}
	//-->
	break;
	}
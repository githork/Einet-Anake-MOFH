<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: c.registro.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

# TODAS LAS FUNCIONES:
#01. check_user_email()
#02. register_user()
#03. validation_code()

class tsRegistro {
    
	#01. NICK|EMAIL EXISTEN?
	function check_user_email() {
	global $tsCore;
	
	$nick = strtolower($_POST['nick']);
	$email = strtolower($_POST['email']);
    $which = empty($nick) ? 'email' : 'nick'; 
	// Mensaje de alerta.
	$return = '1: The '.$which.' is available.';
	if($nick || $email) {
    // Hacemos una verificacion sea email o nick.
	$query = anaK('query', 'SELECT user_id FROM '.$tsCore->table['08'].' WHERE user_nick = \''.$tsCore->setProtect($nick).'\' || user_email = \''.$tsCore->setProtect($email).'\' LIMIT 1', array(__FILE__, __LINE__));
	// Verificamos si existe.
	if(anaK('num_rows', $query) > 0) $return = '0: Sorry, but this '.$which.' is already registered..';
	// Nick|Email no permitido.
	if(anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE (b_type = \'2\' && b_value = \''.$tsCore->setProtect($email).'\') || (b_type = \'3\' && b_value = \''.$tsCore->setProtect(strstr($email, '@')).'\') || (b_type = \'4\' && b_value = \''.$tsCore->setProtect(strstr($email, '@', true)).'\') || (b_type = \'4\' && b_value = \''.$tsCore->setProtect($nick).'\') LIMIT 1', array(__FILE__, __LINE__)))) 
	$return = '0: Sorry, but this '.$which.' is not allowed on this server..';
	//
	} else $return = '0: This action cannot be executed. Error code: SW005/VL';
	//
	return $return;
	}
	
	
	#02. REGISTRAR USUARIO
	function register_user() {
	global $tsCore, $tsUser;
    // Datos necesarios.
	$tsData = array(
	'user_nick' => $tsCore->setProtect(strtolower($_POST['nick'])),
	'user_name' => $tsCore->setProtect($_POST['nombre']),
	'user_email' => $tsCore->setProtect(strtolower($_POST['email'])),
	'user_pass' => $tsCore->setProtect($_POST['pass']),
	'user_repass' => $tsCore->setProtect($_POST['re_pass']),
	'user_dia' => $tsCore->setProtect((int)$_POST['dia']),
	'user_mes' => $tsCore->setProtect((int)$_POST['mes']),
	'user_year' => $tsCore->setProtect((int)$_POST['year']),
	'user_sexo' => $tsCore->setProtect(($_POST['sexo'] == 'm') ? 0 : 1),
	'user_pais' => $tsCore->setProtect($_POST['pais']),
	'user_terminos' => $tsCore->setProtect(($_POST['terminos'] == 1) ? 1 : 0),
	'user_pin' => $tsCore->setProtect($tsUser->get_pin_user()),
	'user_date' => $tsCore->setProtect($tsCore->settings['date']),
	'user_ip' => $tsCore->setProtect($tsCore->settings['ip']),
	'user_plan' => $tsCore->setProtect((int)$_POST['plan']),
	're_captcha' => $tsCore->setProtect($_POST['re_captcha']),
	);
	
	// Captcha google.
	if($tsCore->settings['captcha_active'] == 0) {
	$secret_code = $tsCore->setProtect($tsCore->settings['api']['recaptcha']['secret']);
	// El servicio de captcha.
	if($tsCore->settings['api']['recaptcha']['name'] == 'google') $get_url = 'https://google.com/recaptcha/api';
	elseif($tsCore->settings['api']['recaptcha']['name'] == 'hcaptcha') $get_url = 'https://hcaptcha.com';
	// Enviamos la peticion si es valida.
	$robot = json_decode($tsCore->get_content($get_url.'/siteverify?secret='.$secret_code.'&remoteip='.$tsData['user_ip'].'&response='.$tsData['re_captcha']), true);
	//
	}
	
	// El registro esta activado?
	if($tsCore->settings['reg_active'] == 1) {
	return '0: The registration of new accounts in '.$tsCore->settings['titulo'].' is deactivated..';
	
	} else {
	// Llegamos al limite de registrados? contamos..
	$data['users_all'] = anaK('num_rows', anaK('query', 'SELECT user_id FROM '.$tsCore->table['08'], array(__FILE__, __LINE__)));
	// Comparamos datos.
	if($data['users_all'] >= $tsCore->settings['reg_limit']) {
	// SI LLEGAMOS AL LIMITE CERRAMOS EL REGISTRO []
	$int = unserialize($tsCore->settings['activos']);#extraemos datos.
	$int['reg_active'] = 1;#actualizamos el campo de registros.
	$int = serialize($int);#volvemos a guardar datos.
	
	// Verificamos el serialize.
	if($tsCore->is_serialized($int) == 1) {
	// Todo bien? actualizamos los datos..
	anaK('query', 'UPDATE '.$tsCore->table['00'].' SET activos = \''.$int.'\'', array(__FILE__, __LINE__));
	}
	//
	return '0: Sorry but we have reached the maximum limit in user registration..';
	//	
	} else {
	// Validamos de nuevo los campos por seguridad.
	if(!preg_match("/^[a-zA-Z0-9_-]{4,16}$/", $tsData['user_nick'])) 
	return '0: Please enter a valid nick or user name..';
	
	elseif(anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE b_type = \'4\' && b_value = \''.$tsCore->setProtect($tsData['user_nick']).'\' LIMIT 1', array(__FILE__, __LINE__)))) 
	return '0: This nick cannot be used because it is blocked..';
	
	// Volvemos a verificar el nick o email.
	$query = anaK('query', 'SELECT user_nick, user_email FROM '.$tsCore->table['08'].' WHERE user_nick = \''.$tsData['user_nick'].'\' || user_email = \''.$tsData['user_email'].'\' LIMIT 1', array(__FILE__, __LINE__));
	$now = anaK('num_rows', $query);
	
	if($now > 0 || !filter_var($tsData['user_email'], FILTER_VALIDATE_EMAIL) || $tsCore->settings['reg_active'] == 1) 
	return '0: Oops!! an error occurred while trying to register your account..';
	
	elseif(strlen($tsData['user_name']) < 4) return '0: The name and surname are required for registration..';
	
	elseif(strlen($tsData['user_email']) > 40) 
	return '0: The email must have a maximum of 40 characters..';
	
	elseif(!mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $tsData['user_email'])) 
	return '0: The email has no valid format..';
	
	elseif(anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE (b_type = \'2\' && b_value = \''.$tsCore->setProtect($tsData['user_email']).'\') || (b_type = \'3\' && b_value = \''.$tsCore->setProtect(strstr($tsData['user_email'], '@')).'\') || (b_type = \'4\' && b_value = \''.$tsCore->setProtect(strstr($tsData['user_email'], '@', true)).'\') LIMIT 1', array(__FILE__, __LINE__)))) 
	return '0: Sorry but this email is not allowed on this server..';
	
	elseif(strlen($tsData['user_pass']) < 5 || strlen($tsData['user_pass']) > 20)
	return '0: Eh! the password must have 5 to 20 characters maximum..';
	
	elseif(mb_ereg("[^a-zA-Z0-9_]", $tsData['user_pass'])) 
	return '0: The password only allows uppercase and lowercase letters, numbers and _';
	
	elseif($tsData['user_pass'] != $tsData['user_repass'])
	return '0: Sorry, but the two passwords don\'t match..';
	
	elseif(!$tsData['user_dia'] || !$tsData['user_mes'] || !$tsData['user_year']) 
	return '0: Remember to select the day, month and year of your birth..';
	
	elseif(!$tsData['user_pais']) 
	return '0: Remember to specify the country where you were born..';
	
	elseif(intval($robot['success']) != 1 && $tsCore->settings['captcha_active'] == 0)
	return '0: Excuse me, but we need to check that you\'re not a bot!';
	
	elseif(intval($tsData['user_terminos']) != 1)
	return '0: ¿I\'m sure you accepted the terms and conditions? O.o';
	
	// Si todo esta bien, encriptamos la contraseña y continuamos.
	$encrypt_pass = $tsCore->get_encrypt($tsData['user_pass']);
	
	// Creamos la cuenta del usuario...
	if(anaK('query', 'INSERT INTO '.$tsCore->table['08'].' (user_nick, user_name, user_password, user_email, user_ip, user_pin, user_registro) VALUES (\''.$tsData['user_nick'].'\', \''.$tsData['user_name'].'\', \''.$tsCore->setProtect($encrypt_pass).'\', \''.$tsData['user_email'].'\', \''.$tsData['user_ip'].'\', \''.$tsData['user_pin'].'\', \''.$tsData['user_date'].'\')', array(__FILE__, __LINE__))) {
	
	// Obtenemos el ID del usuario para las otras tablas.
	$user_id = (int)anaK('insert_id', array(__FILE__, __LINE__));
	
	// Agregamos el avatar por el sexo.
	if($tsData['user_sexo'] == 1) {
	$imagen = '1.jpg';
	$genero = 'h';#hombre
	} else {
	$imagen = '1.jpg';
	$genero = 'm';#mujer
	}
	// Movemos los avatar a las carpetas.
	copy(EA_FILES.'modelos/avatar/'.$genero.'/'.$imagen.'', EA_FILES.'perfiles/'.$user_id.'_120.jpg');
	copy(EA_FILES.'modelos/avatar/'.$genero.'/mini/'.$imagen.'', EA_FILES.'perfiles/'.$user_id.'_50.jpg');
	
	// Agregamos los datos del perfil para el usuario.
	anaK('query', 'INSERT INTO '.$tsCore->table['09'].' (user_id, p_dia, p_mes, p_year, p_pais, p_sexo, p_imagen) VALUES (\''.$tsCore->setProtect($user_id).'\', \''.$tsData['user_dia'].'\', \''.$tsData['user_mes'].'\', \''.$tsData['user_year'].'\', \''.$tsData['user_pais'].'\', \''.$tsData['user_sexo'].'\', \'1\')', array(__FILE__, __LINE__));
	
	// Tipo de bienvenida que le daremos al usuario.
	$type_welcome = (int)$tsCore->settings['bienvenida'];#0 = no dar, 1 = msg privado
	if($type_welcome > 0 && $type_welcome < 5) {
	$msg_bienvenida = $tsCore->settings['bienvenida_msg'];
	$sexo = 'we would like to welcome you';
	$b = array('[usuario]', '[welcome]', '[web]');
	$r = array($tsData['user_nick'], $sexo, $tsCore->settings['titulo']);
	$msg_bienvenida = str_ireplace($b, $r, $msg_bienvenida);
	// SI EL USUARIO SELECCIONO ALGUN PLAN LO PASAMOS CON COOKIE
	if(strlen($tsData['user_plan']) > 0) $_SESSION['pid'] = $tsData['user_plan'];
	
	// Tipo de bienvenida.
	switch($type_welcome) {
	case '1':# Mensaje privado.
	//<--
	$tsAdmin = $tsCore->get_admin();#1er admin.
	
	// Preparamos el mensaje.
	$tsMP = array(
	'mp_to' => $tsCore->setProtect($user_id), 
	'mp_from' => $tsCore->setProtect((int)$tsAdmin['user_id']),
	'mp_subject' => $tsCore->setProtect($sexo.' an ' .$tsCore->settings['titulo']), 
	'mp_preview' => $tsCore->setProtect($msg_bienvenida),
	'mp_ip' => $tsCore->setProtect($tsCore->settings['ip']),
	'mp_date' => $tsCore->setProtect($tsCore->settings['date']),
	);
	// Agregamos el mensaje.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['14'].' (mp_to, mp_from, mp_subject, mp_preview, mp_date) VALUES (\''.$tsMP['mp_to'].'\', \''.$tsMP['mp_from'].'\', \''.$tsMP['mp_subject'].'\', \''.substr($tsMP['mp_preview'], 0, 75).'\', \''.$tsMP['mp_date'].'\')', array(__FILE__, __LINE__))) {
		
	// ID del mensaje para respuesta.
	$id_mensaje = anaK('insert_id', array(__FILE__, __LINE__));
	// Agregamos la respuesta al mensaje.
	anaK('query', 'INSERT INTO '.$tsCore->table['15'].' (mp_id, mr_from, mr_body, mr_ip, mr_date) VALUES (\''.$tsCore->setProtect((int)$id_mensaje).'\', \''.$tsMP['mp_from'].'\', \''.$tsMP['mp_preview'].'\', \''.$tsMP['mp_ip'].'\', \''.$tsMP['mp_date'].'\')', array(__FILE__, __LINE__));
	//	
	}
	//-->
	break;	
	}
	//	
	}
	
	// Esta activa la validacion? SI.
	if($tsCore->settings['val_cuenta'] == 1) {
	
	// Esta activo el servicio de email?
	if($tsCore->settings['email_active'] == 1) 
	return '0: Sorry, the email service is currently disabled. Contact the administrator to request access to your account <a href="mailto:'.$tsCore->settings['email_web'].'" id="url_str" style="color:#FFFFFF;">'.$tsCore->settings['email_web'].'</a>';

	$tsData['user_id'] = $user_id;
	// Tipo de hash 2 para validacion.
	$tsData['hash_type'] = 2;
	// Generamos el hash y lo insertamos.
	$hash_code = $tsUser->get_code($tsData);
	
	// Insertamos y preparamos el email si el codigo es valido.
	if(strlen($hash_code) > 30) {
	// Preparamos el email.
	$tsMessage = array(
	'from' => $tsCore->setProtect($tsCore->settings['email_web']),#Email server
	'to' => $tsCore->setProtect($tsData['user_email']),#Email usuario
	'subject' => $tsCore->setProtect($tsData['user_nick']).', please activate your account now..',#titulo mensaje
	);
	
	// Cuerpo del mensaje.
	$message  = '<h2>Hi, '.$tsCore->setProtect($tsData['user_nick']).'.</h2>';
	$message .= '<span style="display:block;padding:10px 0;margin-bottom:10px;">';
	$message .= 'It\'s time to activate your account.<br /><br /> Young, '.$tsCore->setProtect($tsData['user_nick']).' as you will notice you are one step away from completing the activation process to your account at '.$tsCore->setProtect($tsCore->settings['titulo']).', This way you will be able to enjoy all the services offered '.$tsCore->setProtect($tsCore->settings['titulo']).'.<br /><br /> Please access the following link <a href="'.$tsCore->settings['url'].'/activar/2/'.$tsCore->setProtect($tsData['user_email']).'/'.$tsCore->setProtect($hash_code).'" target="_blank">Account activation</a> or click on the Activate my account now button to complete the activation process.';
	$message .= '</span><a href="'.$tsCore->settings['url'].'/activar/2/'.$tsCore->setProtect($tsData['user_email']).'/'.$tsCore->setProtect($hash_code).'" target="_blank" style="padding:10px;background:#23D160;color:#FFFFFF;text-decoration:none;border-radius:3px;">Activate my account now</a><br /><br />';
	$message .= '<span style="display:block;padding:10px 0;margin-bottom:10px;"><b>¿Problems activating your account?</b><br />
If for any reason you have problems with your account contact us from support <b>'.$tsCore->setProtect($tsCore->settings['email_web']).'</b> with details about the problems with your account.</span>';
	// Unimos el mensaje con la plantilla del email..
	$tsMessage['body'] = $tsCore->plantilla_email($message);
	
	// Todo listo? enviamos el email..
	$tsOut = $tsCore->send_email($tsMessage);
	if(intval(substr($tsOut, 0, 1)) == 1) exit('1: Great we just sent a message to your email with the necessary data to complete the activation process ...<br/> If it does not appear in your inbox remember to check your spam..');
	else exit($tsOut);// Algun error? lo mostramos.	
	//
	} else return '0: An error occurred when trying to send the activation message.';
	//	
	} else {
	// La activacion por email esta OFF, activamos automaticamente.
	$tsUser->user_activate($user_id, md5($tsData['user_date']));
	// Indicamos que activamos la cuenta.
	return '1: Hi, young, <b>'.$tsData['user_nick'].'</b> we want to welcome you to <b>'.$tsCore->settings['titulo'].'</b>, your account has been registered and activated, you can now log in.';
	}
	//	
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}#-> limite
	//
	}#-> reg_active
	//
	}
	
	
	#03. ACTIVAR CUENTA|ESTABLECER PASSWORD
	function validation_code($tsData = null) {
	global $tsCore;
	
	if(!$tsData['type'] || !$tsData['code'] || !$tsData['email']) 
	return array('msg' => '0: This action cannot be executed. Error code AC0H4/CM');
	
	$query = anaK('query', 'SELECT m.user_id, m.user_nick, c.user_email, c.hash_type, c.hash_code FROM '.$tsCore->table['08'].' AS m LEFT JOIN '.$tsCore->table['07'].' AS c ON m.user_email = c.user_email WHERE c.hash_code = \''.$tsCore->setProtect($tsData['code']).'\' && c.user_email = \''.$tsCore->setProtect($tsData['email']).'\' && c.hash_type = \''.$tsCore->setProtect((int)$tsData['type']).'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	
	// Eliminamos las keys que tengan mas de 1 dia.
	anaK('query', 'DELETE FROM '.$tsCore->table['07'].' WHERE hash_time < '.($tsCore->settings['date'] - 86400), array(__FILE__, __LINE__));
	// Existen codigos para esta cuenta?
	if(!$data['user_email']) 
	return array('msg' => '0: Sorry, this validation code is not associated with your account..');
	
	elseif($data['hash_code'] != $tsData['code']) 
	return array('msg' => '0: Sorry, this validation code is misspelled or does not exist..');
	
	// Doble para cambiar Password|Activar cuenta.
	switch($data['hash_type']) {
	case '1':
	//<--
	if(empty($tsData['new_pass']) && $data['user_id']) {
	// La solicitud es para este usuario? cargamos el formulario, con[2].
	return array('data' => $data, 'msg' => '2: Is everything okay? We uploaded the form..');
	
	} else {
	if(strlen($tsData['new_pass']) < 5 || strlen($tsData['new_pass']) > 40) 
	return array('msg' => '0: Sorry the password must be between 5 and 40 characters maximum..');
	
	elseif(mb_ereg("[^a-zA-Z0-9_]", $tsData['new_pass'])) 
	return array('msg' => '0: The password only allows uppercase and lowercase letters, numbers and _');
	
	elseif(strtolower($tsData['new_pass']) == $data['user_nick']) 
	return array('msg' => '0: Sorry, the password can\'t be your own username..');
	
	// Si todo esta bien actualizamos la contraseña.
	if(anaK('query', 'UPDATE '.$tsCore->table['08'].' SET user_password = \''.$tsCore->setProtect($tsCore->get_encrypt($tsData['new_pass'])).'\' WHERE user_id = \''.$tsCore->setProtect($data['user_id']).'\' && user_email = \''.$tsCore->setProtect($data['user_email']).'\'', array(__FILE__, __LINE__))) {
	// Eliminamos las solicitudes para este usuario.
	anaK('query', 'DELETE FROM '.$tsCore->table['07'].' WHERE user_id = '.$tsCore->setProtect($data['user_id']), array(__FILE__, __LINE__));
	return array('msg' => '1: Your password has been successfully updated, you can now log in..');
	}
	//
	}
	//-->
	break;
	case '2':
	//<--
	if(anaK('query', 'UPDATE '.$tsCore->table['08'].' SET user_activo = \'1\' WHERE user_id = '.$tsCore->setProtect($data['user_id']), array(__FILE__, __LINE__))) {
	// Eliminamos las solicitudes para este usuario.
	anaK('query', 'DELETE FROM '.$tsCore->table['07'].' WHERE user_id = '.$tsCore->setProtect($data['user_id']), array(__FILE__, __LINE__));
	return array('msg' => '1: Your account has been successfully activated, you can now log in..');
	//
	} else {
	return array('msg' => '0: Apparently your account could not be validated, please try again..');	
	}
	//-->
	break;
	}
	//
	}

}

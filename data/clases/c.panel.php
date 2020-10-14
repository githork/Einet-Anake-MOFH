<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: c.panel.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

# TODAS LAS FUNCIONES:
#01. get_accounts()
#02. info_account()
#03. get_domain()
#04. stat_account()
#05. tools_account()
#06. create_account()
#07. pin_account()
#08. search_account()


class tsPanel {
    
	#01. CUENTAS HOSTING USER.
	function get_accounts() {
	global $tsCore, $tsUser;
	
	// Contamos todas la cuentas que tenga este usuario.
	$todos = anaK('num_rows', anaK('query', 'SELECT cp_id FROM '.$tsCore->table['16'].' WHERE cp_client = '.(int)$tsUser->uid, array(__FILE__, __LINE__)));
	// Creamos el paginado
    $page = $tsCore->getPag($todos, $tsCore->settings['max_posts']);
	// Realizamos la consulta.
	$query = anaK('query', 'SELECT cp_id, cp_domain, cp_active, cp_date FROM '.$tsCore->table['16'].' WHERE cp_client = \''.(int)$tsUser->uid.'\' ORDER BY cp_date DESC LIMIT '.$page['limite'], array(__FILE__, __LINE__));
	$data = result_array($query);
	// Contamos cuantos tickets ha creado el usuario.
	$page['tickets'] = anaK('num_rows', anaK('query', 'SELECT mp_id FROM '.$tsCore->table['14'].' WHERE mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' && MATCH(mp_subject) AGAINST("Ticket (support)" IN BOOLEAN MODE) && mp_del_from = \'0\'', array(__FILE__, __LINE__)));
	//
    return array('data' => $data, 'page' => $page);
	}
	
	
	#02. DETALLES DE CUENTA.
	function info_account() {
	global $tsCore, $tsUser, $tsIdiomas;	
	
	$cp_id = $tsCore->setProtect(intval(empty($_GET['id']) ? $_POST['id'] : $_GET['id']));
	if(empty($cp_id)) return false;
	
	// Realizamos la consulta haber si existe.
	$query = anaK('query', 'SELECT c.*, u.user_id, u.user_nick, u.user_password FROM '.$tsCore->table['16'].' AS c LEFT JOIN '.$tsCore->table['08'].' AS u ON u.user_id = c.cp_client WHERE '.($tsUser->is_admod == 1 || $tsUser->is_admod && $tsCore->settings['access_mod'] == 1 ? '' : 'c.cp_client = \''.(int)$tsUser->uid.'\' && u.user_activo = \'1\' && u.user_baneado = \'0\' AND').' c.cp_id = \''.$tsCore->setProtect((int)$cp_id).'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	// Si se recibieron datos los pasamos..
	if($data['cp_id'] > 0) {
	$data['cp_idioma'] = $tsIdiomas[$tsUser->info['p_idioma']];
	$data['cp_dpass'] = $tsCore->get_decrypt($data['user_password']);
	$data['api'] = $this->stat_account($data['cp_id']);
	}
	//
	return $data;
	}
	
	
	#03. VERIFICAR DOMINIO
	function get_domain($get_data = null) {
	global $tsCore, $tsMP;
	
	$tsData = array(
	'domain' => strtolower(trim($tsCore->setProtect(empty($get_data['domain']) ? $_POST['domain'] : $get_data['domain']))),
	'extension' => strtolower(trim($tsCore->setProtect(empty($get_data['ext']) ? $_POST['ext'] : $get_data['ext']))),
	'type' => $tsCore->setProtect(empty($get_data['type']) ? $_POST['type'] : $get_data['type']),
	);
	
	// Si es sub-domain.
	if($tsData['type'] == 'ext_s') {
	$tsData['extension'] = strtolower(trim($tsCore->setProtect('.'.$tsCore->settings['cpanel']['domain_list'][$tsData['extension']]['name'])));
	}
	
	// Unimos el nombre + la extension.
	$dominio = $tsCore->setProtect(strtolower(trim($tsData['domain'].$tsData['extension'])));
	
	if(strlen($tsData['domain']) < 4) 
	return '0: The name for the domain must be at least 4 characters..';
	
	elseif(strlen($tsData['domain']) > 50) 
	return '0: The name for the domain must not exceed 50 characters..'; 
	
	elseif(!preg_match("/\.([a-zA-Z0-9]+)$/i", $tsData['extension'])) 
	return '0: You must add a valid extension for your domain..';
	
	elseif(!mb_ereg("^([a-zA-Z0-9]+).([a-zA-Z0-9-]+).([a-zA-Z]{2,4})$", $dominio)) 
	return '0: The domain does not have a valid extension...';
	
	elseif(preg_match("/([a-z0-9-]+)\.(tk|x3host.ml)$/i", $dominio)) 
	return '0: The domain extension is not allowed on this server...';
	
	elseif(strlen($dominio) > 65) 
	return '0: The domain is too long, should not exceed 65 characters..';
	
	// Conectamos a la api y seleccionamos que api vamos a usar.
	$api = $tsCore->reseller_api(array('cp_domain' => $dominio));
	// El api esta funcionando? si no lo hace detenemos todo...
	if(empty($api)) return '0: Connection to WHM API not working contact administration..';
	$sendTo = $api->availability(['domain' => $dominio]);
	$receive = $sendTo->send();
	
	// Algun error lo pasamos..
	if(($receive->isSuccessful() == 0 || $receive->isSuccessful() == false) && strlen($receive->getMessage()) > 1) 
	$error = substr(str_replace(array('\'', '"'), '', $receive->getMessage()), 0, 700);
	
	// Si se produce algun error (con el ip) lo notificamos al admin.
	if(strlen($error) > 2) {
	$tsAdmin = $tsCore->get_admin();
	$tsMensaje = array(
	'msg_user' => $tsAdmin['user_nick'], 
	'msg_title' => 'WHM API Connection Errors.',                      
	'msg_body' => 'Hi, '.$tsAdmin['user_nick'].' the site is presenting problems with the WHM API reseller, the error: [b]'.$error.'[/b]',
	'msg_ip' => $tsCore->settings['ip'],
	'msg_date' => $tsCore->settings['date'],
	);
	// Le enviamos el mensaje al admin que este online.
	$tsMP->new_mensaje($tsMensaje);
	return '0: No response could be obtained from the server. Try again in a few minutes..';
	
	} else {
	// Si todo esta bien continuamos con la verificacion del dominio.
	if(($receive->isSuccessful() == 0 || $receive->isSuccessful() == false) && $receive->getMessage() == 0) 
	return '0: Sorry, but the domain <b>'.$dominio.'</b> is already registered, try a different one..';
	
	elseif(($receive->isSuccessful() == 1 || $receive->isSuccessful() == true) && $receive->getMessage() == 1) 
	return '1: Perfect domain <b>'.$dominio.'</b> is available and you can use it..';
	//
	}
	//	
	}
	
	
	#04. ESTADO DE CUENTA HOSTING.
	function stat_account($get_id = null) {
	global $tsCore, $tsUser, $tsMP;
	
	$cp_id = $tsCore->setProtect(intval(empty($get_id) ? $_POST['id'] : $get_id));
	if(empty($cp_id)) return false;
	
	// Realizamos la consulta haber si existe.
	$query = anaK('query', 'SELECT cp_id, cp_client, cp_user, cp_active FROM '.$tsCore->table['16'].' WHERE '.($tsUser->is_admod == 1 || $tsUser->is_admod && $tsCore->settings['access_mod'] == 1 ? '' : 'cp_client = \''.(int)$tsUser->uid.'\' AND').' cp_id = \''.$cp_id.'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	
	if(!$data['cp_user']) return false;
	
	// Conectamos a la api y seleccionamos que api vamos a usar.
	$api = $tsCore->reseller_api(array('cp_user' => $data['cp_user']));
	// El api esta funcionando? si no lo hace detenemos todo...
	if(empty($api)) return false;
	// Si la conexion esta bien hacemos la consulta.
	$sendTo = $api->getUserDomains(['username' => $tsCore->setProtect($data['cp_user'])]);
	$receive = $sendTo->send();
	
	// Algun error lo pasamos..
	if(($receive->isSuccessful() == 0 || $receive->isSuccessful() == false) && strlen($receive->getMessage()) > 1) 
	$error = substr(str_replace(array('\'', '"'), '', $receive->getMessage()), 0, 700);
	
	// Si se produce algun error (con el ip) lo notificamos al admin.
	if(strlen($error) > 2) {
	$tsAdmin = $tsCore->get_admin();
	$tsMensaje = array(
	'msg_user' => $tsAdmin['user_nick'], 
	'msg_title' => 'WHM API Connection Errors.',                      
	'msg_body' => 'Hi, '.$tsAdmin['user_nick'].' the site is presenting problems with the WHM API reseller, the error: [b]'.$error.'[/b]',
	'msg_ip' => $tsCore->settings['ip'],
	'msg_date' => $tsCore->settings['date'],
	);
	// Le enviamos el mensaje al admin que este online.
	$tsMP->new_mensaje($tsMensaje);
	
	} else {
	// Si se recibio respuesta y algun dominio|sub-dominio los contamos..
	if(($receive->isSuccessful() == 1 || $receive->isSuccessful() == true) && is_array($receive->getDomains())) {
	// Verificamos el status de la cuenta y actualizamos.
	$rs_status = ($receive->getStatus() === 'ACTIVE' ? 0 : 1);
	// Si el status del reseller es diferente lo actualizamos en la DB.
	if($data['cp_active'] != $rs_status) {
	// Si es suspendida colocamos fecha de 30 dias y  sino 0 para no borrarla.
	$update_time = ($rs_status == 1 ? ($tsCore->settings['date'] + (30*24*60*60)) : 0);
	anaK('query', 'UPDATE '.$tsCore->table['16'].' SET cp_active = \''.$tsCore->setProtect((int)$rs_status).'\', cp_over = \''.$tsCore->setProtect($update_time).'\' WHERE cp_id = \''.$tsCore->setProtect($data['cp_id']).'\' && cp_client = \''.$tsCore->setProtect($data['cp_client']).'\'', array(__FILE__, __LINE__));	
	//	
	}
	//
	$domain_all = 0;
	$subdom_all = 0;
	// Hay dominios los contamos.
	foreach($receive->getDomains() as $all) {
	preg_match("/^([a-zA-Z0-9]+)\.([a-zA-Z]{2,4})$/i", $all, $domain);
	if($domain[0]) $domain_all++;// contamos....
	}
	// Hay sub-dominios los contamos.
	foreach($receive->getDomains() as $all) {
	preg_match("/^([a-zA-Z0-9]+)\.([a-zA-Z0-9-]+).([a-zA-Z]{2,4})$/i", $all, $sub_domain);
	if($sub_domain[0]) $subdom_all++;// contamos....
	}
	//
	}
	//
	}
	//
	return array(
	'list_domain' => $receive->getDomains(),
	'all_domain' => (intval($domain_all) > 0 ? $domain_all : 0),
	'all_subdomain' => (intval($subdom_all) > 0 ? $subdom_all : 0),
	);
	//
	}
	
	
	#05. HERRAMIENTAS PARA HOSTING
	function tools_account($getData = null) {
	global $tsCore, $tsUser, $tsMP;
	
	$tsData = array(
	'cpanel_id' => $tsCore->setProtect(intval(empty($getData['cp_id']) ? $_POST['id'] : $getData['cp_id'])),
	'cpanel_type' => $tsCore->setProtect(intval(empty($getData['cp_type']) ? $_POST['type'] : $getData['cp_type'])),
	);
	if(!$tsData['cpanel_id']) return '0: This action cannot be executed. Error code: CPN00/1D';
	elseif(!$tsData['cpanel_type']) return '0: This action cannot be executed. Error code: CPN00/2T';
	
	// Realizamos la consulta haber si existe.
	$query = anaK('query', 'SELECT cp_id, cp_client, cp_user, cp_name, cp_domain FROM '.$tsCore->table['16'].' WHERE '.($tsUser->is_admod == 1 || $tsUser->is_admod && $tsCore->settings['access_mod'] == 1 ? '' : 'cp_client = \''.(int)$tsUser->uid.'\' AND').' cp_id = \''.$tsData['cpanel_id'].'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	
	// Si la cuenta no existe mostramos un error..
	if(!$data['cp_id']) return '0: The account id does not exist or you cannot perform this action..';
	
	// Ejecutamos la orden por tipo.
	switch($tsData['cpanel_type']) {
	case '1':
	//<-- Actualizar contraseña.
	$tsData['cp_password'] = $tsCore->setProtect(empty($getData['cp_pass']) ? $_POST['password'] : $getData['cp_pass']);
	
	if(!$data['cp_name']) 
	return '0: The user does not exist or you cannot perform this action..';
	
	elseif(strlen($tsData['cp_password']) < 5 || strlen($tsData['cp_password']) > 20) 
	return '0: The password must be between 5 and 20 characters long..';
	
	// Conectamos a la api y seleccionamos que api vamos a usar.
	$api = $tsCore->reseller_api(array('cp_user' => $data['cp_user']));
	// El api esta funcionando? si no lo hace detenemos todo...
	if(empty($api)) return '0: Connection to WHM API not working contact administration..';
	$sendTo = $api->password([
	'username' => $tsCore->setProtect($data['cp_name']), 
	'password' => $tsCore->setProtect($tsData['cp_password']), 
	'enabledigest' => 1,
	]);
	$receive = $sendTo->send();
	// Algun error lo pasamos..
	if(($receive->isSuccessful() == 0 || $receive->isSuccessful() == false) && strlen($receive->getMessage()) > 1) 
	$error = substr(str_replace(array('\'', '"'), '', $receive->getMessage()), 0, 700);
	
	if(($receive->isSuccessful() == 1 || $receive->isSuccessful() == true) && strlen($receive->getMessage()) > 10) {
	// Se escribio la misma contraseña? mostramos el mensaje..
	if(preg_match('/An error occured changing this password./i', $receive->getMessage())) {
	return '0: Remember to write down a different password than the one above..';
	
	} else {
	// Si se cambio la contraseña la actualizamos en la base de datos..
	if(anaK('query', 'UPDATE '.$tsCore->table['16'].' SET cp_password = \''.$tsCore->setProtect($tsCore->get_encrypt($tsData['cp_password'])).'\' WHERE cp_id = \''.$tsCore->setProtect($data['cp_id']).'\' && cp_client = \''.$tsCore->setProtect($data['cp_client']).'\'', array(__FILE__, __LINE__))) {
	return '1: Perfect password for all hosting accounts have been successfully updated..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	//-->
	break;
	case '2':
	//<-- Habilitar cuenta.
	if(!$data['cp_name']) 
	return '0: The user does not exist or you cannot perform this action..';
	
	// Conectamos a la api y seleccionamos que api vamos a usar.
	$api = $tsCore->reseller_api(array('cp_user' => $data['cp_user']));
	// El api esta funcionando? si no lo hace detenemos todo...
	if(empty($api)) return '0: Connection to WHM API not working contact administration..';
	$sendTo = $api->unsuspend(['username' => $tsCore->setProtect($data['cp_name'])]);
	$receive = $sendTo->send();
	// Algun error lo pasamos..
	if(($receive->isSuccessful() == 0 || $receive->isSuccessful() == false) && strlen($receive->getMessage()) > 1) { 
	$error = substr(str_replace(array('\'', '"'), '', $receive->getMessage()), 0, 700);
	
	} elseif(($receive->isSuccessful() == 1 || $receive->isSuccessful() == true) && is_array($receive->getMessage())) {
	// Si se activo la cuenta, actualizamos en la base de datos.
	if(anaK('query', 'UPDATE '.$tsCore->table['16'].' SET cp_active = \'0\', cp_over = \'0\' WHERE cp_id = \''.$tsCore->setProtect($data['cp_id']).'\' && cp_client = \''.$tsCore->setProtect($data['cp_client']).'\'', array(__FILE__, __LINE__))) {
	return '1: The account <b>'.$data['cp_user'].'</b> has been enabled, please wait 5 minutes before entering the cPanel..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//-->
	break;
	case '3':
	//<-- Suspender cuenta.
	if(!$data['cp_name']) 
	return '0: The user does not exist or you cannot perform this action..';
	
	// Verificamos si tiene algun dominio|subdiminio agregado a la cuenta que va eliminar.
	$stat_account = $this->stat_account($data['cp_id']);
	if(is_array($stat_account['list_domain']) && count($stat_account['list_domain']) > 0) {
	return '0: Please remove <b>domains, sub-domains, email, database</b> in your account\'s cPanel before deleting it..';
	
	} else {
	// Si todo va bien conectamos a la api y seleccionamos que api vamos a usar.
	$api = $tsCore->reseller_api(array('cp_user' => $data['cp_user']));
	// El api esta funcionando? si no lo hace detenemos todo...
	if(empty($api)) return '0: Connection to WHM API not working contact administration..';
	$sendTo = $api->suspend(['username' => $tsCore->setProtect($data['cp_name']), 'reason' => $tsCore->setProtect('User disabled account')]);
	$receive = $sendTo->send();
	// Algun error lo pasamos..
	if(($receive->isSuccessful() == 0 || $receive->isSuccessful() == false) && strlen($receive->getMessage()) > 1) { 
	$error = substr(str_replace(array('\'', '"'), '', $receive->getMessage()), 0, 700);
	
	} elseif(($receive->isSuccessful() == 1 || $receive->isSuccessful() == true) && is_array($receive->getMessage())) { 
	// Sumamos 30 dias a la fecha actual..
	$update_time = ($tsCore->settings['date'] + (30*24*60*60));
	
	// Si se desactivo la cuenta, actualizamos en la base de datos.
	if(anaK('query', 'UPDATE '.$tsCore->table['16'].' SET cp_active = \'1\', cp_over = \''.$tsCore->setProtect($update_time).'\' WHERE cp_id = \''.$tsCore->setProtect($data['cp_id']).'\' && cp_client = \''.$tsCore->setProtect($data['cp_client']).'\'', array(__FILE__, __LINE__))) {
	return '1: The account <b>'.$data['cp_user'].'</b> has been deactivated and will be completely removed within 30 days, with no possibility of recovery..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	//-->
	break;	
	}
	// Si se produce algun error (con el ip) lo notificamos al admin.
	if(strlen($error) > 2) {
	$tsAdmin = $tsCore->get_admin();
	$tsMensaje = array(
	'msg_user' => $tsAdmin['user_nick'], 
	'msg_title' => 'WHM API Connection Errors.',                      
	'msg_body' => 'Hi, '.$tsAdmin['user_nick'].' the site is presenting problems with the WHM API reseller, the error: [b]'.$error.'[/b]',
	'msg_ip' => $tsCore->settings['ip'],
	'msg_date' => $tsCore->settings['date'],
	);
	// Le enviamos el mensaje al admin que este online.
	$tsMP->new_mensaje($tsMensaje);
	return '0: No response could be obtained from the server. Try again in a few minutes..';
	}
	//
	}
	
	
	#06. CREAR HOSTING
	function create_account() {
	global $tsCore, $tsUser, $tsMP;
	
	$tsData = array(
	'domain' => $tsCore->setProtect(strtolower(trim($_POST['domain']))),
	'extension' => $tsCore->setProtect(strtolower(trim($_POST['ext']))),
	'plan' => $tsCore->setProtect(intval(empty($_POST['plan']) ? 1 : $_POST['plan'])),
	're_captcha' => $tsCore->setProtect($_POST['captcha']),
	'type' => $tsCore->setProtect($_POST['type']),
	'get_pin' => $tsCore->setProtect($this->get_pin_cpanel()),#Pin para la cuenta
	'user_email' => $tsCore->setProtect($tsUser->info['user_email']),
	'user_pass' => $tsCore->setProtect($tsCore->get_decrypt($tsUser->info['user_password'])),
	'user_date' => $tsCore->setProtect($tsCore->settings['date']),
	'user_ip' => $tsCore->setProtect($tsCore->settings['ip']),
	);
	
	// Si es sub-domain.
	if($tsData['type'] == 'ext_s') {
	$tsData['extension'] = strtolower(trim($tsCore->setProtect('.'.$tsCore->settings['cpanel']['domain_list'][$tsData['extension']]['name'])));
	}
	// Unimos el nombre + la extension.
	$dominio = $tsCore->setProtect(strtolower(trim($tsData['domain'].$tsData['extension'])));
	
	// Validamos algunos campos, antes de continuar.
	if(!$dominio) return '0: This action cannot be executed. Error code: CDM-N/00';
	elseif(!$tsData['plan']) return '0: This action cannot be executed. Error code: CPL-N/01';
	elseif(!$tsUser->uid) return '0: This action cannot be executed. Error code: IDUS/NO';
	
	// Contamos las cuentas que tiene el usuario.
	$accounts = $this->get_accounts();
	if($tsUser->info['p_limite'] == $accounts['page']['todas']) {
	return '0: Sorry but you have reached the maximum limit for the creation of hosting accounts..';	
	
	} else {
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
	
	// Verificamos de nuevo el dominio.
	$val_domain = $this->get_domain($tsData); 
	if(substr($val_domain, 0, 1) == 0) 
	return $val_domain;
	
	elseif(intval($robot['success']) != 1 && $tsCore->settings['captcha_active'] == 0) 
	return '0: Excuse me, but we need to check that you\'re not a bot!';
	// Verificamos si el usuario puede solicitar ese plan.
	if($tsUser->is_admod != 1 && $tsData['plan'] > 2) { 
	$tsData['plan'] = 2;// Si no es un admin, no puede activar mas del 2 plan solo.
	}
	
	// Pasamos los otros datos.
	$tsData += array(
	'account_ip' => $tsCore->setProtect($tsCore->settings['ip']),
	'account_plan' => $tsCore->setProtect(strtolower(str_replace(' ', '_', $tsCore->settings['cpanel']['name_plan'][$tsData['plan']]))),
	);
	
	// Si todo va bien conectamos a la api y seleccionamos que api vamos a usar.
	$api = $tsCore->reseller_api(array('cp_domain' => $dominio));
	// El api esta funcionando? si no lo hace detenemos todo...
	if(empty($api)) return '0: Connection to WHM API not working contact administration..';
	// Si la conexion esta bien creamos la cuenta.
	$sendTo = $api->createAccount([
	'username' => $tsData['get_pin'], 
	'password' => $tsData['user_pass'], 
	'domain' => $tsCore->setProtect($dominio), 
	'email' => $tsData['user_email'], 
	'plan' => $tsData['account_plan'],
	]);
	$receive = $sendTo->send();
	
	// Algun error lo pasamos..
	if(($receive->isSuccessful() == 0 || $receive->isSuccessful() == false) && strlen($receive->getMessage()) > 1) 
	$error = substr(str_replace(array('\'', '"'), '', $receive->getMessage()), 0, 700);
	// Si se produce algun error (con el ip) lo notificamos al admin.
	if(strlen($error) > 2) {
	$tsAdmin = $tsCore->get_admin();
	$tsMensaje = array(
	'msg_user' => $tsAdmin['user_nick'], 
	'msg_title' => 'WHM API Connection Errors.',                      
	'msg_body' => 'Hi, '.$tsAdmin['user_nick'].' the site is presenting problems with the WHM API reseller, the error: [b]'.$error.'[/b]',
	'msg_ip' => $tsCore->settings['ip'],
	'msg_date' => $tsCore->settings['date'],
	);
	// Le enviamos el mensaje al admin que este online.
	$tsMP->new_mensaje($tsMensaje);
	return '0: No response could be obtained from the server. Try again in a few minutes..';
	
	} else {
	// Si se creo la cuenta en el reseller guardamos los datos.
	if(($receive->isSuccessful() == 1 || $receive->isSuccessful() == true) && strlen($receive->getMessage()) > 10 && strlen($receive->getVpUsername()) >= 13) {
	
	// Agregamos los datos a la base de datos y enviamos el email|mensaje al usuario.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['16'].' (cp_client, cp_user, cp_password, cp_name, cp_email, cp_domain, cp_plan, cp_ip, cp_date) VALUES (\''.$tsCore->setProtect($tsUser->uid).'\', \''.$tsCore->setProtect($receive->getVpUsername()).'\', \''.$tsCore->setProtect($tsCore->get_encrypt($tsData['user_pass'])).'\', \''.$tsData['get_pin'].'\', \''.$tsData['user_email'].'\', \''.$tsCore->setProtect($dominio).'\', \''.$tsData['plan'].'\', \''.$tsData['account_ip'].'\', \''.$tsData['user_date'].'\')', array(__FILE__, __LINE__))) {
	
	// Si dejo algun plan hosting en la cookie lo borramos.	
	($_SESSION['pid'] > 0) ? $_SESSION['pid'] = NULL : '';
	
	// Esta activo el servicio de email?..
	if($tsCore->settings['email_active'] == 0) {
	// Preparamos el email.
	$tsMessage = array(
	'from' => $tsCore->setProtect($tsCore->settings['email_web']),#Email server
	'to' => $tsData['user_email'],#Email usuario
	'subject' => 'Information about your new hosting account..',#titulo mensaje
	);
	
	// Cuerpo del mensaje.
	$message  = '<h2>¡Cool, '.$tsCore->setProtect($tsUser->nick).'!</h2>';
	$message .= '<span style="display:block;padding:10px 0;margin-bottom:10px;">';
	$message .= '<b>PLEASE READ THIS EMAIL AND PRINT IT OUT FOR FUTURE REFERENCE</b><br /><br />';
	$message .= '¡Thank you for creating an account at <b>'.$tsCore->settings['titulo'].'</b>, your account was successfully created and in this email you will find all the information you need to start using it now.<br /><br />';
	$message .= 'If you have ordered a domain name in your order, please note that it may not be immediately accessible. The process of propagating the NS may take up to 48 hours to complete and until the process is complete, your account may not be accessible through the registered domain.<br /><br />';
	$message .= '<b>Your account information:</b><br />';
	$message .= 'Hosting package: '.$tsData['account_plan'].'<br />';
	$message .= 'Domain: '.$dominio.'<br />';
	$message .= 'Account type: Gratis/Free<br /><br />';
	$message .= '<b>Details about [cPanel • FTP • MySQL]:</b><br />';
	$message .= 'Username: '.$tsCore->setProtect($receive->getVpUsername()).'<br />';
	$message .= 'Password: '.$tsData['user_pass'].'<br />';                
	$message .= 'CPANEL: '.$tsCore->settings['cpanel']['cpanel'].'<br />';
	$message .= 'FTP: '.$tsCore->settings['cpanel']['ftp'].'<br />';
	$message .= 'MySQL: '.$tsCore->settings['cpanel']['sql'].'<br />';
	$message .= 'WEB Mail: '.$tsCore->settings['cpanel']['mail'].'<br /><br />';
	$message .= '<b>Name servers:</b><br />';
	$message .= $tsCore->settings['cpanel']['ns'][1].'<br /> '.$tsCore->settings['cpanel']['ns'][2].'<br /><br />';
	$message .= 'Thank you very much for choosing us as your preferred service provider, have a great day.<br />';
	$message .= '</span>';
	// Unimos el mensaje con la plantilla del email..
	$tsMessage['body'] = $tsCore->plantilla_email($message);
	
	// Todo listo? enviamos el email..
	$tsOut = $tsCore->send_email($tsMessage);
	if(intval(substr($tsOut, 0, 1)) == 1) return '1: Your hosting account has been successfully created. Check your email for more information about it..';
	else return $tsOut;// Algun error? lo mostramos.
	
	// Si todo esta bien mandamos el email y le indicamos al usuario sino enviamos un mensaje privado..
	} else {
	// El admin que esta on o estuvo.
	$tsAdmin = $tsCore->get_admin();
	// El mensaje para el usuario.
	$message .= '[b]PLEASE READ AND SAVE THIS MESSAGE FOR FUTURE REFERENCE[/b]\n';
	$message .= '¡Thank you for creating your account. In this message you will find all the details to log in.\n';
	$message .= 'If you have ordered a domain name in your order, please note that it may not be immediately accessible. It can take up to 48 hours for the NS propagation process to be completed.\n';
	$message .= '[b]Your account information:[/b]\n';
	$message .= 'Hosting package: '.$tsData['account_plan'].'\n';
	$message .= 'Domain: '.$dominio.'\n';
	$message .= 'Account type: Gratis/Free\n';
	$message .= '[b]Details about [cPanel • FTP • MySQL]:[/b]\n';
	$message .= 'Username: '.$tsCore->setProtect($receive->getVpUsername()).'\n';
	$message .= 'Password: '.$tsData['user_pass'].'\n';               
	$message .= 'CPANEL: '.$tsCore->settings['cpanel']['cpanel'].'\n';
	$message .= 'FTP: '.$tsCore->settings['cpanel']['ftp'].'\n';
	$message .= 'MySQL: '.$tsCore->settings['cpanel']['sql'].'\n';
	$message .= 'WEB Mail: '.$tsCore->settings['cpanel']['mail'].'\n';
	$message .= '[b]Name servers:[/b]\n';
	$message .= $tsCore->settings['cpanel']['ns'][1].'\n'.$tsCore->settings['cpanel']['ns'][2].'\n';
	$message .= 'Thank you very much for choosing us as your preferred service provider, have a great day.';
	// ----- //
	$tsMensaje = array(
	'msg_user' => $tsCore->setProtect($tsAdmin['user_nick']), 
	'msg_title' => 'Information about your new hosting account..',
	'msg_body' => $message,
	'msg_ip' => $tsData['user_ip'],
	'msg_date' => $tsData['user_date'],
	);
	// Le enviamos el mensaje al usuario con los datos de su cuenta.
	$tsOut = $tsMP->new_mensaje($tsMensaje);
	if(intval(substr($tsOut, 0, 1)) == 1) return '1: Your hosting account has been successfully created. We have left you a private message with the data..';
	else return $tsOut;// Algun error? lo mostramos.
	//
	}
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	//
	}
	//
	}
	
	
	#07. GENERAR CODIGO PIN.
	function get_pin_cpanel() {
    $caracteres = '0a1b2c3d4e5f6l7m8q9';
    $code = '';
    $max = strlen($caracteres)-1;
    for($i=0;$i <8; $i++){
    $code .= $caracteres[mt_rand(0,$max)];
    }
    return $code;
    }
	
	
	#08. BUSCAR CUENTA HOSTING (solo para el admin)
	function search_account() {
	global $tsCore, $tsUser;
	
	// Recibimos la palabra|username|nameclient|email|domain|nick etc
	$buscar = $tsCore->setProtect(empty($_GET['search']) ? $_POST['search'] : $_GET['search']);
	
	// Ejecutamos la orden si es administrador
	if($tsUser->is_admod == 1) {
	// Contamos todos las cuentas que existan.
    $todos = anaK('num_rows', anaK('query', 'SELECT cp_id FROM '.$tsCore->table['16'].' AS c LEFT JOIN '.$tsCore->table['08'].' AS u ON u.user_id = c.cp_client WHERE MATCH (c.cp_user, c.cp_name, c.cp_email, c.cp_domain, u.user_nick) AGAINST (\''.$buscar.'\' IN BOOLEAN MODE)', array(__FILE__, __LINE__)));
	
	// Creamos el paginado
    $page = $tsCore->getPag($todos, 99);
	
	// Realizamos la consulta, buscar cuenta con esa palabra.
    $query = anaK('query', 'SELECT DISTINCT c.cp_id, c.cp_user, c.cp_domain, c.cp_active, c.cp_date FROM '.$tsCore->table['16'].' AS c LEFT JOIN '.$tsCore->table['08'].' AS u ON u.user_id = c.cp_client WHERE MATCH (c.cp_user, c.cp_name, c.cp_email, c.cp_domain, u.user_nick) AGAINST (\''.$buscar.'\' IN BOOLEAN MODE) ORDER BY c.cp_date DESC LIMIT '.$page['limite'], array(__FILE__, __LINE__));
	$data = result_array($query);
	//
	}
	//
    return array('data' => $data, 'page' => $page);
	}
}
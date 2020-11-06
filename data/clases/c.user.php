<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: c.user.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

# TODAS LAS FUNCIONES:
#01. load_user()
#02. user_data()
#03. get_rango_user()
#04. is_follow()
#05. anti_flood()
#06. get_pin_user()
#07. user_banned()
#08. get_users()
#09. login_user()
#10. login_out()
#11. val_email()
#12. val_pin()
#13. get_validation()
#14. get_code()
#15. user_activate()
#16. save_perfil()
#17. ult_users()
#18. get_visita()
#19. get_follow()
#20. guardar_filtros()
#21. get_follow_birthday()
#22. birthday()
#23. login_social()
#24. delete_user_inactive()
#-01. read()
#-02. create()
#-03. update()
#-04. destroy()
#-05. set_cookie()
#-06. gen_session_id()
#-07. sess_gc()

class tsUser {
    var $info = array();// Datos de la tabla.
	var $is_member = 0;// Es miembro?
	var $is_admod = 0;// Es admin?
	var $is_banned = 0;
	var $uid = 0;// ID del user.
	var $nick = 'Guest';// Nombre de visitante.
	var $sesion;
	
	function __construct() {
	$this->sesion = new tsSesion();
	// No existe una sesion? la creamos.
	if(!$this->sesion->read()) {
	$this->sesion->create();
	
	} else {
	// Ya existe una la actualizamos.
	$this->sesion->update();
	// Cargamos datos del user.
	$this->load_user();
	}
	//	
	}
	
	
	#01. DATOS DEL USUARIO
	function load_user() {
	global $tsCore, $tsPanel;
	$query = anaK('query', 'SELECT * FROM '.$tsCore->table['08'].' AS m LEFT JOIN '.$tsCore->table['09'].' AS p ON p.user_id = m.user_id LEFT JOIN '.$tsCore->table['10'].' AS r ON r.id_rango = m.user_rango LEFT JOIN '.$tsCore->table['11'].' AS s ON s.session_user_id = m.user_id WHERE s.session_id = \''.$tsCore->setProtect($this->sesion->ID).'\' LIMIT 1', array(__FILE__, __LINE__));
    $this->info = anaK('fetch_assoc', $query);
	$this->permisos = unserialize($this->info['r_allows']);
	$this->filtros = unserialize($this->info['p_monitor']);
	$this->p_cover = unserialize($this->info['p_cover']);
	$this->birthday = $this->birthday();// Mensaje e imagen si esta cumpliendo años el user logueado.
	// No existe?
    if(!isset($this->info['user_id'])) {
    return false;
    }
	
	// Es admin.
	if($this->permisos['oa_mo'] == 0 && $this->permisos['oa_ad'] == 'on') {
	$this->is_admod = 1;
	
	// Es moderador.
	} elseif($this->permisos['oa_mo'] == 'on' && $this->permisos['oa_ad'] == 0) {
	$this->is_admod = 2;
	
	// Tiene permisos especiales.
	} elseif($this->permisos['oa_mo'] == 'on' || $this->permisos['oa_ad'] == 'on') {
	$this->is_admod = true;
		
	// No tiene permisos.
	} else {
	$this->is_admod = 0;
	}
	
	// Es miembro.
	$this->is_member = 1;
	// Algunas variables para el usuario.
	$this->uid = $this->info['user_id'];
    $this->nick = $this->info['user_nick'];
    $this->is_banned = $this->info['user_baneado'];
	$this->get_follow_birthday();// Notificar cumpleaños de mis seguidores.
	($this->birthday['p_cover']) ? array($this->p_cover['url'] = $this->birthday['p_cover'], $this->p_cover['position'] = $this->birthday['p_position']) : '';
	
	// Datos que vamos actualizar.
	$val = array(
	'user_online' => $tsCore->settings['date'], 
	'user_ip' => $tsCore->settings['ip'],
	);
	// Actualizamos el tiempo online y la fecha.
	anaK('query', 'UPDATE '.$tsCore->table['08'].' SET '.$tsCore->get_value($val).' WHERE user_id = '.$tsCore->setProtect((int)$this->uid), array(__FILE__, __LINE__));
	
	// Borramos la variable de la sesion
	unset($this->sesion);
	//
	}
	
	
	#02. USER ID|NICK
	function user_data($id_obj, $type) {
	global $tsCore;
	
    if(!$id_obj) return '0: The logged user does not have a valid ID..';
    elseif(!(int)$type) return '0: This action cannot be executed. Error code: US100/TP';
	// Status de conexion del usuario.
	$online = ($tsCore->settings['date'] - ($tsCore->settings['user_activo'] * 60));
	$absent = ($tsCore->settings['date'] - (($tsCore->settings['user_activo'] * 60) * 2));
	
	switch($type) {
    case '1':// ID
	//<--
	if(!(int)$id_obj) return '0: This action cannot be executed. Error code: US101/ID';
	
	$query = anaK('query', 'SELECT u.user_id, u.user_nick, u.user_name, u.user_activo, u.user_baneado, u.user_online, u.user_rango, p.p_config, p.p_monitor FROM '.$tsCore->table['08'].' AS u LEFT JOIN '.$tsCore->table['09'].' AS p ON p.user_id = u.user_id WHERE u.user_id = \''.$tsCore->setProtect((int)$id_obj).'\' LIMIT 1', array(__FILE__, __LINE__));
    $data = anaK('fetch_assoc', $query);
	// Estado del usuario
	if($data['user_online'] > $online) $data['status'] = 'online';
	elseif($data['user_online'] > $absent) $data['status'] = 'absent';
	else $data['status'] = 'offline';
	//-->
	break;
	case '2':// NICK
	//<--
	if(!$id_obj) return '0: This action cannot be executed. Error code: US102/NC';
	
	$query = anaK('query', 'SELECT u.user_id, u.user_nick, u.user_name, u.user_activo, u.user_baneado, u.user_online, u.user_rango, p.p_config, p.p_monitor FROM '.$tsCore->table['08'].' AS u LEFT JOIN '.$tsCore->table['09'].' AS p ON p.user_id = u.user_id WHERE u.user_nick = \''.$tsCore->setProtect($id_obj).'\' LIMIT 1', array(__FILE__, __LINE__));
    $data = anaK('fetch_assoc', $query);
	// Estado del usuario
	if($data['user_online'] > $online) $data['status'] = 'online';
	elseif($data['user_online'] > $absent) $data['status'] = 'absent';
	else $data['status'] = 'offline';
	break;
	}
	//-->
	return $data;
	}
	
	
	#03. RANGOS DEL USUARIO
	function get_rango_user($id_obj, $type = 1) {
	global $tsCore;
	
	if(!intval($id_obj) && $type == 1) return '0: This action cannot be executed. Error code: US001/RN';
	switch((int)$type) {
    case '1': // Rango individual.
	//<--
	$query = anaK('query', 'SELECT id_rango, r_nombre, r_color, r_imagen FROM '.$tsCore->table['10'].' WHERE id_rango = \''.$tsCore->setProtect((int)$id_obj).'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	//-->
	break;
	case '2':// Todos los rangos.
	//<--
	$query = anaK('query', 'SELECT id_rango, r_nombre, r_color, r_imagen FROM '.$tsCore->table['10'].' ORDER BY id_rango', array(__FILE__, __LINE__));
	$data = result_array($query);
	//-->
	break;
	}
	//
	return $data;	
	}
	
	
	#04. SIGO AL USER
    function is_follow($id_obj, $type = null) {
    global $tsCore;
   
    if(!(int)$id_obj) return '0: This action cannot be executed. Error code: US103/IF';
    elseif(!(int)$type) return '0: This action cannot be executed. Error code: US104/TF';
    switch($type) {
    case '1':// Id user
    //<--
    $data = anaK('num_rows', anaK('query', 'SELECT cont_id FROM '.$tsCore->table['12'].' WHERE s_sigue = \''.$tsCore->setProtect((int)$id_obj).'\' AND s_user = \''.$tsCore->setProtect((int)$this->uid).'\' AND s_type = \'1\' LIMIT 1', array(__FILE__, __LINE__)));
    $result = ($data > 0) ? true : false;
    //-->
    break;
	case '5':// Lo sigo|me sigue para usuarios.
	//<--
	$result = anaK('fetch_assoc', anaK('query', 'SELECT 
	(SELECT COUNT(cont_id) FROM '.$tsCore->table['12'].' WHERE s_user = \''.$tsCore->setProtect((int)$this->uid).'\' && s_sigue = \''.$tsCore->setProtect((int)$id_obj).'\' AND s_type = \'1\' LIMIT 1) AS lo_sigo,
	(SELECT COUNT(cont_id) FROM '.$tsCore->table['12'].' WHERE s_user = \''.$tsCore->setProtect((int)$id_obj).'\' && s_sigue = \''.$tsCore->setProtect((int)$this->uid).'\' AND s_type = \'1\' LIMIT 1) AS me_sigue', array(__FILE__, __LINE__)));
	//-->
	break;
    }
    //
    return $result;
    }
	
	
	#05. ANTIFLOOD GLOBAL
	function anti_flood($print = true, $type = 'post', $msg = '') {
	global $tsCore;
	
	$now = $tsCore->settings['date'];// Tiempo actual.
    $msg = empty($msg) ? 'You can\'t do that many actions in a row.<br/>' : $msg;
	
	// Activar|desactivar antiFlood para todos o para uno.
    if($tsCore->settings['antiFlood'] == 1 || $this->info['user_antiFlood'] == 1) { 
	$limit = 0;// Desactiva el limite.
	} else { 
	$limit = $this->permisos['goaf'];// Limite en segundos segun el rango.
	}
	//
	$resta = $now - $_SESSION['flood'][$type];// Tiempo actual - Tiempo del flood
	// Si el tiempo del flood es menor al del rango.
	if($resta < $limit) {
    $msg = '0: '.$msg.' Try again at '.($limit - $resta).' seconds.';
    // Termina o muestra la alerta.
    if($print) die($msg);
    else return $msg;
    
	} else {
    // Si no tiene session el flood la creamos.
    if(empty($_SESSION['flood'][$type])) {
    $_SESSION['flood'][$type] = $tsCore->settings['date'];
    
	} else $_SESSION['flood'][$type] = $now;
    // Si ya tiene sesion el flood actualizamos.
    return true;
    }
	//
    }
	
	
	#06. GENERAR PIN
	function get_pin_user() {
    $caracteres = '0A1B2C3D4E5F6L7M8Q9';
    $code = '';
    $max = strlen($caracteres)-1;
    for($i=0;$i <8; $i++){
    $code .= $caracteres[mt_rand(0,$max)];
    }
    return $code;
    }
	
	
	#07. USUARIO BANEADO
    function user_banned() {
    global $tsCore;
	
	// Suspender usuario. type_obj = 1
	$query = anaK('query', 'SELECT s_date, s_extra, s_termina FROM '.$tsCore->table['06'].' WHERE id_obj = \''.$tsCore->setProtect((int)$this->uid).'\' && type_obj = \'1\' LIMIT 1', array(__FILE__, __LINE__));
    $data = anaK('fetch_assoc', $query);
    
	// Verificamos si cumplio la condena.
	if($data['s_termina'] > 1 && $data['s_termina'] < $tsCore->settings['date']) {
	// Si la fecha final es mayor a la actual quitamos el baneo
	anaK('query', 'UPDATE '.$tsCore->table['08'].' SET user_baneado = \'0\' WHERE user_id = '.$tsCore->setProtect((int)$this->uid), array(__FILE__, __LINE__));
	
	// Eliminamos el baneo de la lista.
	anaK('query', 'DELETE FROM '.$tsCore->table['06'].' WHERE id_obj = \''.$tsCore->setProtect((int)$this->uid).'\' AND type_obj = \'1\'', array(__FILE__, __LINE__));
	return false;
    } else return $data;
	//
	}
	
	
	#08. TODOS LOS USUARIOS
	function get_users() {
	global $tsCore, $tsPaises, $tsSexo;
	
	// Variables para status de usuarios.
	$online = ($tsCore->settings['date'] - ($tsCore->settings['user_activo'] * 60));
	$absent = ($tsCore->settings['date'] - (($tsCore->settings['user_activo'] * 60) * 2));
	
	$tsData = array(
	'online' => $tsCore->setProtect($_GET['online']),#1
	'imagen' => $tsCore->setProtect($_GET['imagen']),#1
	'sexo' => $tsCore->setProtect($_GET['sexo']),#h|m
	'pais' => $tsCore->setProtect($_GET['pais']),#ES
	'rango' => $tsCore->setProtect($_GET['rango']),#1,2,3,4,5
	);
	
	// Filtros para usuarios.
	if($tsData['online'] == 1) {
	$ONLINE = '&& u.user_online > '.$online.'';
	//
	}
	if($tsData['imagen'] == 1) {
	$IMAGEN = '&& p.p_imagen = \'1\'';
	//
	}
	if($tsData['sexo']) {
    $SEXO = '&& p.p_sexo = \''.(($_GET['sexo'] == 'm') ? 0 : 1).'\'';
	//	
	}
	if($tsData['pais']) {
	$PAIS = '&& p.p_pais = \''.$tsData['pais'].'\'';
	//	
	}
	if($tsData['rango']) {
	$RANGO = '&& u.user_rango = \''.$tsData['rango'].'\'';
	//
	}
	
	// Contamos todos los usuarios
	$todos = anaK('num_rows', anaK('query', 'SELECT u.user_id FROM '.$tsCore->table['08'].' AS u LEFT JOIN '.$tsCore->table['09'].' AS p ON u.user_id = p.user_id WHERE u.user_activo = \'1\' AND u.user_baneado = \'0\' '.$ONLINE.' '.$IMAGEN.' '.$SEXO.' '.$PAIS.' '.$RANGO.'', array(__FILE__, __LINE__)));
	
	// Creamos el paginado.
    $page = $tsCore->getPag($todos, $tsCore->settings['max_posts']);
	
	// Hacemos la consulta.
	$query = anaK('query', 'SELECT u.user_id, u.user_nick, u.user_name, u.user_online, u.user_rango, u.user_pin, p.p_pais, p.p_sexo, p.p_mensaje, r.id_rango, r.r_nombre, r.r_color, r.r_imagen FROM '.$tsCore->table['08'].' AS u LEFT JOIN '.$tsCore->table['09'].' AS p ON u.user_id = p.user_id LEFT JOIN '.$tsCore->table['10'].' AS r ON u.user_rango = r.id_rango WHERE u.user_activo = \'1\' AND u.user_baneado = \'0\' '.$ONLINE.' '.$IMAGEN.' '.$SEXO.' '.$PAIS.' '.$RANGO.' ORDER BY u.user_id DESC LIMIT '.$page['limite'], array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
	// Status de conexion del usuario.
	if($row['user_online'] > $online) $row['status'] = 'online';
	elseif($row['user_online'] > $absent) $row['status'] = 'absent';
	else $row['status'] = 'offline';
	$row['pais'] = $tsPaises[$row['p_pais']];#pais
	$row['sexo'] = $tsSexo[$row['p_sexo']];#sexo
	// El usuario logueado a quien sigue?
	$row['user_follow'] = $this->is_follow($row['user_id'], 1);
	// Pasamos algunos datos.
	$data[] = $row;
	//
	}
	//
	return array('data' => $data, 'page' => $page, 'filtros' => $tsData);
	//
	}
	
	
	#09. INICIAR SESION
	function login_user() {
	global $tsCore;
	
	// Armamos variables.
	$user_nick = $tsCore->setProtect(strtolower($_POST['nick']));
	$user_pass = $tsCore->setProtect($tsCore->get_encrypt($_POST['pass']));
	$redirect =  $tsCore->setProtect($_POST['r']);
    $log = ($_POST['log'] == 'true') ? true : false;
	//
    if(empty($user_nick) or empty($user_pass)) return '0: You must write data that is valid.';
    $user_pin = str_replace("PIN:", "", strtoupper($user_nick));
	
	// El usuario existe?
	if($this->val_email($user_nick)) {
	$ITEM = 'm.user_email = \''.$user_nick.'\'';	
	} elseif($this->val_pin(strtoupper($user_nick))) {
    $ITEM = 'm.user_pin = \''.$user_pin.'\'';	
	} else {
    $ITEM = 'm.user_nick = \''.$user_nick.'\'';
	}
	
	$data = anaK('fetch_assoc', anaK('query', 'SELECT m.user_id, m.user_password, m.user_activo, p.p_monitor FROM '.$tsCore->table['08'].' AS m LEFT JOIN '.$tsCore->table['09'].' AS p ON p.user_id = m.user_id WHERE '.$ITEM.' LIMIT 1', array(__FILE__, __LINE__)));
	
	// El usuario no existe?
    if(empty($data)) return '0: Please enter a valid user name.';
	
	// Escribe bien la contraseña?
    if($data['user_password'] != $user_pass) {
    return '0: Sorry, the password is misspelled..';
    
	} else {
	// Continuamos..
	if($data['user_activo'] == 1) {
	// Actualizamos la sesion.
    $this->sesion->update(array('user_id' => $data['user_id'], 'auto_login' => $log, 'force_update' => true));
	// Creamos cookies para notificaciones.
	$getLive = unserialize($data['p_monitor']);
	setcookie('live_ntfs', ($getLive['ntfs'] == 0) ? 'ON' : 'OFF', $tsCore->settings['date'] + (60*60*24*90));
	setcookie('live_msgs', ($getLive['msgs'] == 0) ? 'ON' : 'OFF', $tsCore->settings['date'] + (60*60*24*90));
	setcookie('live_sound', ($getLive['sound'] == 0) ? 'ON' : 'OFF', $tsCore->settings['date'] + (60*60*24*90));
	
	// Si selecciono algun plan al crear la cuenta lo re-direccionamos al panel..
	($_SESSION['pid'] > 0) ? $redirect = $tsCore->settings['url'].'/panel/?action=create&pid='.$_SESSION['pid'] : '';
	
	// Redireccionamos.....
	if($redirect) return '1: '.$redirect;//Si se recibio un enlace redireccionamos.
	else return '1: '.$tsCore->settings['url'].'/panel/';// Si no solo mandamos al panel.
	} else return '0: Sorry your account is not active yet, please activate it.';
	//
	}
	//
	}
	
	
	#10. CERRAR SESION
    function login_out() {
    global $tsCore;
	$redirect = empty($_GET['redirect']) ? './' : $_GET['redirect'];
    
	if(!$this->uid) $tsCore->redirect($tsCore->settings['url']);
	// Borramos la sesion.
    $this->sesion = new tsSesion();
    $this->sesion->read();
    $this->sesion->destroy();
	
    // Actualizamos algunos datos del usuario.
    $user_online = ($tsCore->settings['date'] - (($tsCore->settings['user_activo'] * 60) * 3));
	anaK('query', 'UPDATE '.$tsCore->table['08'].' SET user_online = \''.$tsCore->setProtect($user_online).'\' WHERE user_id = '.$tsCore->setProtect((int)$this->uid), array(__FILE__, __LINE__));
	
	// Limpiamos variables.
    $this->info = '';
    $this->is_member = 0;
	// Limpiamos las cookies de la notificaciones.
	setcookie('live_ntfs', null, $tsCore->settings['date'] - 3600);
	setcookie('live_msgs', null, $tsCore->settings['date'] - 3600);
	setcookie('live_sound', null, $tsCore->settings['date'] - 3600);
	// Limpiamos datos de la sesion [redes sociales].
	session_destroy();
    // Redirigimos...
    if($redirect) $tsCore->redirect($redirect);
    else return true;
	//
    }
	
	
	#11. VALIDAR EMAIL
    function val_email($obj) { 
    if(!mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $obj)) return false; 
	else return true; 
    //
    }
	
	
	#12. VALIDAR PIN
    function val_pin($obj) {
    if(!mb_ereg("^PIN:([a-zA-Z0-9._]+)$", $obj)) return false; 
	else return true; 
    //
    }
	
	
	#13. CREAR HASH PASSWORD|VALIDACION
	function get_validation() {
	global $tsCore;
	$email = $tsCore->setProtect(strtolower($_REQUEST['rew_email']));
	if(!mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) 
	exit('0: <b>Write an email that is valid..</b>');
	
	// Todo bien? realizamos la consulta y mostramos los datos.
	$query = anaK('query', 'SELECT user_id, user_activo, user_nick, user_email, user_registro FROM '.$tsCore->table['08'].' WHERE user_email = \''.$email.'\'', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	$data['hash_type'] = ($_REQUEST['type'] ? $_REQUEST['type'] : 2);#[1]->contraseña, #[2]->validar cuenta.
	
	if(empty($data['user_email'])) {
	exit('0: <b>The email cannot be found, please write the address.</b>');
	} elseif($data['user_activo'] == 1 && $data['hash_type'] == 2) {
	exit('0: <b>Sorry but this account is already activated.</b>');
	} else {
	$data['hash'] = $this->get_code($data);#Generamos el hash code
	}
	//
	return $data;
	//	
	}
	
	
	#14. GENERAMOS CODIGO DE ACTIVACION
	function get_code($data) {
	global $tsCore;
	
	if(!(int)$data['user_id']) 
	exit('0: This action cannot be executed. Error code: GC001/ID');
	
	elseif(!(int)$data['hash_type']) 
	exit('0: This action cannot be executed. Error code: GC002/HT');
	
	// Generamos un codigo hash.
	$code = md5(sha1(mt_rand().time().mt_rand().$data['user_email'].md5($tsCore->settings['ip'])));
	
	// Lo agregamos a la base de datos.
	if(!anaK('query', 'INSERT INTO '.$tsCore->table['07'].' (user_id, user_email, hash_time, hash_type, hash_code) VALUES (\''.$tsCore->setProtect((int)$data['user_id']).'\', \''.$tsCore->setProtect($data['user_email']).'\', \''.$tsCore->setProtect($tsCore->settings['date']).'\', \''.$tsCore->setProtect((int)$data['hash_type']).'\', \''.$tsCore->setProtect($code).'\')', array(__FILE__, __LINE__))) exit('0: Error code. '.anaK('error', array(__FILE__, __LINE__)));
	//
	return $code;
	}
	
	
	#15. ACTIVAR CUENTA DIRECTA.
	function user_activate($user_id = null, $obj = null) {
	global $tsCore;
	
	if($user_id) $id_user = empty($user_id) ? $tsCore->setProtect($_GET['user_id']) : $user_id;
	if($obj) $key = empty($obj) ? $tsCore->setProtect($_GET['key']) : $obj;
	
	$query = anaK('query', 'SELECT user_nick, user_registro FROM '.$tsCore->table['08'].' WHERE user_id = \''.(int)$id_user.'\' LIMIT 1', array(__FILE__, __LINE__));
	$tsData = anaK('fetch_assoc', $query);
	$tsUserKey = md5($tsData['user_registro']);
	
	if(anaK("num_rows", $query) == 0 || $key != $tsUserKey) {
	// Si no hay datos retornamos un false.
	return false;
	} else {
	//
	if(anaK('query', 'UPDATE '.$tsCore->table['08'].' SET user_activo = \'1\' WHERE user_id = \''.$id_user.'\'', array(__FILE__, __LINE__))) {
    return $tsData;
	}
	//
	else return false;
	}
	//
	}
	
	
	#16. ACTUALIZAR DATOS DE USUARIO
	function save_perfil() {
	global $tsCore, $tsPanel;
	$type = intval($_POST['section']);
	if(empty($type)) return '0: This action cannot be executed. Error code: US001/SD';
	
	$tsData = array(
	'user_nick' => $tsCore->setProtect(strtolower($_POST['nick'])),
	'user_name' => $tsCore->setProtect($_POST['nombre']),
	'user_email' => $tsCore->setProtect(strtolower($_POST['email'])),
	'user_pass_actual' => $tsCore->setProtect($_POST['pass_act']),
	'user_pass' => $tsCore->setProtect($_POST['pass']),
	'user_repass' => $tsCore->setProtect($_POST['re_pass']),
	'user_dia' => $tsCore->setProtect((int)$_POST['dia']),
	'user_mes' => $tsCore->setProtect((int)$_POST['mes']),
	'user_year' => $tsCore->setProtect((int)$_POST['year']),
	'user_sexo' => $tsCore->setProtect(($_POST['sexo'] == 'm') ? 0 : 1),
	'user_pais' => $tsCore->setProtect($_POST['pais']),
	'user_web' => $tsCore->setProtect($_POST['web']),
	'user_mensaje' => $tsCore->setProtect($_POST['mensaje']),
	'user_idioma' => $tsCore->setProtect($_POST['idioma']),
	);
	
	// Actualizamos datos por partes.
	switch($type) {
	case '1':
	//<-- Configuracion.
	// Verificamos si el email lo usa alguien mas.
	$data = anaK('num_rows', anaK('query', 'SELECT user_id FROM '.$tsCore->table['08'].' WHERE user_email = \''.$tsData['user_email'].'\' && user_id != \''.$tsCore->setProtect((int)$this->uid).'\' LIMIT 1', array(__FILE__, __LINE__)));
	
	if(!mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $tsData['user_email'])) 
	return '0: The email has no valid format..';
	
	elseif(anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE (b_type = \'2\' && b_value = \''.$tsCore->setProtect($tsData['user_email']).'\') || (b_type = \'3\' && b_value = \''.$tsCore->setProtect(strstr($tsData['user_email'], '@')).'\') || (b_type = \'4\' && b_value = \''.$tsCore->setProtect(strstr($tsData['user_email'], '@', true)).'\') LIMIT 1', array(__FILE__, __LINE__)))) 
	return '0: Sorry but this email is not allowed on this server..';
	
	elseif(strlen($tsData['user_email']) > 40) 
	return '0: The email must have a maximum of 40 characters..';
	
	elseif($data == 1)
	return '0: This email is already being used, write a different one..';
	
	elseif($tsData['user_sexo'] < 0) 
	return '0: Sorry you must specify your gender, if you are male or female..';
	
	elseif(!$tsData['user_idioma']) 
	return '0: Sorry you must select a language for the cPanel is required..';
	
	else {
	// Todo bien? seleccionamos los campos que vamos a actualizar en esta seccion.	
	$update_miembros = array(
	'user_email' => $tsData['user_email'],
	);
	$update_perfil = array(
	'p_pais' => $tsData['user_pais'], 
	'p_sexo' => $tsData['user_sexo'], 
	'p_dia' => $tsData['user_dia'], 
	'p_mes' => $tsData['user_mes'], 
	'p_year' => $tsData['user_year'], 
	'p_idioma' => $tsData['user_idioma'],
	);
	//	
	}
	//-->
	break;
	case '2':
	//<-- Perfil.
	if(strlen($tsData['user_name']) > 35) 
	return '0: The name and surname must not exceed 35 characters..'; 
	
	elseif(strlen($tsData['user_web']) > 35) 
	return '0: Your website should not exceed 35 characters..';
	
	elseif(strlen($tsData['user_mensaje']) > 200) 
	return '0: Your personal message should not exceed 200 characters..';
	
	else {
	// Todo bien? seleccionamos los campos que vamos a actualizar en esta seccion.
	$update_miembros = array(
	'user_name' => $tsData['user_name'],
	);
	$update_perfil = array(
	'p_web' => $tsData['user_web'], 
	'p_mensaje' => $tsData['user_mensaje'], 
	);
	//	
	}
	//-->
	break;
	case '3':
	//<-- Seguridad.
	$encrypt_password = $tsCore->get_encrypt($tsData['user_pass_actual']);
	if($tsData['user_pass_actual']) {
	// Si se recibio alguna clave la comparamos..
	if($encrypt_password != $this->info['user_password']) 
	return '0: Sorry but your current password is misspelled..';
	
	elseif(empty($tsData['user_pass'])) 
	return '0: Remember to write down a password before saving..';
	
	elseif(mb_ereg("[^a-zA-Z0-9_]", $tsData['user_pass'])) 
	return '0: The password only allows uppercase and lowercase letters, numbers and _';
	
	elseif(strlen($tsData['user_pass']) < 5 || strlen($tsData['user_pass']) > 20) 
	return '0: The password must be between 5 and 20 characters long..';
	
	elseif($tsData['user_pass'] != $tsData['user_repass']) 
	return '0: Sorry the two new passwords are not the same, you must verify them..';
	
	else {
	$password_encrypt = $tsCore->get_encrypt($tsData['user_pass']);
	// Todo bien? seleccionamos los campos que vamos a actualizar en esta seccion.
	$update_miembros = array(
	'user_password' => $tsCore->setProtect($password_encrypt),
	);
	// Contamos las cuentas hosting que tenga el usuario..
	$accounts = $tsPanel->get_accounts();
	if($accounts['page']['todas'] > 0) {
	foreach($accounts['data'] as $i => $data) {
	// Si hay cuentas activas cambiamos las contraseña de todas....
	if($data['cp_id'] && $data['cp_active'] == 0) {
	$out_account = $tsPanel->tools_account(array('cp_id' => $data['cp_id'], 'cp_pass' => $tsData['user_pass'], 'cp_type' => 1));
	if(substr($out_account, 0, 1) == 0) return $out_account;// Si ocurre un error lo muestro..
	}
	//
	}
	//
	}
	//
	}
	//	
	} else return '0: What\'s up <b>baby</b> remember to write down your password before continuing..';
	//-->
	break;
	case '4':
	//<-- Cambio de nick.
	if((strlen($tsData['user_nick']) < 5 || strlen($tsData['user_nick']) > 20) && $this->is_admod != 1) 
	return '0: Sorry, but the nick must be between 5 and 20 characters..';
	
	elseif(mb_ereg("[^a-zA-Z0-9_.]", $tsData['user_nick'])) 
	return '0: Only underscores and dots are allowed in the nick (_ .)';
	
	elseif(!mb_ereg("[^0-9]", $tsData['user_nick'])) 
	return '0: Sorry the nick can\'t have just numbers..';
	
	elseif(anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE b_type = \'4\' && b_value = \''.$tsCore->setProtect($tsData['user_nick']).'\' LIMIT 1', array(__FILE__, __LINE__)))) 
	return '0: Sorry this nick cannot be used because it is blocked..';
	
	elseif(anaK('num_rows', anaK('query', 'SELECT user_id FROM '.$tsCore->table['08'].' WHERE user_nick = \''.$tsCore->setProtect($tsData['user_nick']).'\' LIMIT 1', array(__FILE__, __LINE__)))) 
	return '0: Sorry this nick is already being used by another user..';
	
	elseif($tsCore->get_encrypt($tsData['user_pass_actual']) != $this->info['user_password']) 
	return '0: Sorry, but your current password is misspelled. Check it out at..';
	
	else {
	// Actualizamos el nick.
	$update_miembros = array('user_nick' => $tsCore->setProtect($tsData['user_nick']));
	//
	}
	//-->
	break;
	}
	// Si recibimos datos procedemos a actualizarlos..
	if(is_array($update_miembros)) {
	if(anaK('query', 'UPDATE '.$tsCore->table['08'].' SET '.$tsCore->get_value($update_miembros).' WHERE user_id = '.$tsCore->setProtect((int)$this->uid), array(__FILE__, __LINE__))) {
	// Mostramos el mensaje.
    $return = '1: Cool "'.$this->nick.'" all your details have been successfully updated..';
	//
	} else $return = '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	if(is_array($update_perfil)) {
	if(anaK('query', 'UPDATE '.$tsCore->table['09'].' SET '.$tsCore->get_value($update_perfil).' WHERE user_id = '.$tsCore->setProtect((int)$this->uid), array(__FILE__, __LINE__))) {
	// Mostramos el mensaje.
    $return = '1: Cool "'.$this->nick.'" all your details have been successfully updated..';
	//
	} else $return = '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//	
	}
	return $return;
	//	
	}
	
	
	#17. ULTIMOS USUARIOS|EL STAFF
	function ult_users($type = null) {
	global $tsCore;
	
	if(!(int)$type) return '0: This action cannot be executed. Error code: PR001/US';
	// Cargamos los usuarios segun el tipo a mostrar
	$query = anaK('query', 'SELECT m.user_id, m.user_nick, m.user_name, m.user_online, m.user_registro, r.r_nombre FROM '.$tsCore->table['08'].' AS m LEFT JOIN '.$tsCore->table['10'].' AS r ON r.id_rango = m.user_rango WHERE '.($this->is_admod == 1 || $this->is_admod && $tsCore->settings['access_mod'] == 1 ? 'm.user_id > 0' : 'm.user_activo = \'1\' && m.user_baneado = \'0\'').' '.($type == 2 ? '&& m.user_rango = \'1\' OR m.user_rango = \'2\'' : 'ORDER BY m.user_registro DESC').' LIMIT 5', array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
	// Status de conexion del usuario.
	$online = ($tsCore->settings['date'] - ($tsCore->settings['user_activo'] * 60));
	$absent = ($tsCore->settings['date'] - (($tsCore->settings['user_activo'] * 60) * 2));
	if($row['user_online'] > $online) $row['status'] = 'online';
	elseif($row['user_online'] > $absent) $row['status'] = 'absent';
	else $row['status'] = 'offline';
	$data[] = $row;
	}
	//
	return $data;	
	}
	
	
	#18. AGREGAR VISITA
	function get_visita($obj = null, $type = null) {
	global $tsCore;
	if(!(int)$type) die('0: This action cannot be executed. Error code: PRA001/V');
	switch($type) {
	case '1':// Visita global.
	//<--
	$go_visit = anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['04'].' WHERE ip_type = \'1\' && ip_user = \''.$tsCore->setProtect($tsCore->settings['ip']).'\' LIMIT 1', array(__FILE__, __LINE__)));
	// No ha visitado?
	if($go_visit == 0) {
	// Sino ha visitado agregamos en la lista por un dia para global.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['04'].' (id_user, user_log, user_info, ip_type, ip_user, ip_date) VALUES (\''.$tsCore->setProtect((int)$this->uid).'\', \''.$tsCore->setProtect($this->is_member).'\', \''.$tsCore->setProtect($_SERVER['HTTP_USER_AGENT']).'\', \'1\', \''.$tsCore->setProtect($tsCore->settings['ip']).'\', \''.$tsCore->setProtect($tsCore->settings['date']).'\')', array(__FILE__, __LINE__))) {
	// +1 a lista de visitas en total.
	anaK('query', 'UPDATE '.$tsCore->table['00'].' SET total_visitas = (total_visitas + 1) WHERE id = '.$tsCore->setProtect((int)$tsCore->settings['id']), array(__FILE__, __LINE__));
	}
	//
	} else {
	// Si ya visito actualizamos algunos datos.
	$val = array(
	'id_user' => (int)$this->uid,
	'user_log' => $this->is_member,
	'user_position' => $tsCore->settings['getLink'],
	'ip_date' => $tsCore->settings['date'],
	);
	
	anaK('query', 'UPDATE '.$tsCore->table['04'].' SET '.$tsCore->get_value($val).' WHERE ip_type = \'1\' && ip_user = \''.$tsCore->setProtect($tsCore->settings['ip']).'\' LIMIT 1', array(__FILE__, __LINE__));
	}
	//-->
	break;
	}
	//
	}
	
	
	#19. AGREGAR USUARIO
    function get_follow($id_obj, $int = 0, $type = null) {
	global $tsCore, $tsMonitor, $tsActividad;
	
	if(!(int)$id_obj) return '0: This action cannot be executed. Error code: US106/ID';
    elseif(!(int)$type) return '0: This action cannot be executed. Error code: US107/GF';
	$this->anti_flood();
	
	switch($type) {
	case '1':// Usuario seguir.
	// Verificamos si el usuario existe.
	$data = $this->user_data($id_obj, 1);
	
	if(!$data['user_id']) return '0: An error has occurred and it appears that the user does not exist or has already been deleted.';
	elseif($data['user_id'] == $this->uid) return '0: Excuse me, but you can\'t follow yourself.';
	
	else {
	switch((int)$int) {
	case '0':
	//<-- Agregamos al usuario a la lista.
    if(anaK('query', 'INSERT INTO '.$tsCore->table['12'].' (s_user, s_sigue, s_type, s_date) VALUES (\''.$tsCore->setProtect((int)$this->uid).'\', \''.$tsCore->setProtect((int)$data['user_id']).'\', \'1\', \''.$tsCore->setProtect($tsCore->settings['date']).'\')', array(__FILE__, __LINE__))) {
	// Agregamos un +1 a la lista de los que segui.
	anaK('query', 'UPDATE '.$tsCore->table['08'].' SET user_seguidores = (user_seguidores + 1) WHERE user_id = \''.$tsCore->setProtect((int)$data['user_id']).'\' AND user_baneado = \'0\'', array(__FILE__, __LINE__));
	
	// Agregar al monitor de notificaciones.
	$getNotificacion = array(
	'type' => 1, 
	'user_id' => $data['user_id'], 
	'obj_user' => $this->uid, 
	);
	$tsMonitor->set_notificacion($getNotificacion);
	
	return '1: Now you follow this user..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//-->
	break;
	
	case '1':
	//<-- Eliminamos al usuario de la lista.
	if(anaK('query', 'DELETE FROM '.$tsCore->table['12'].' WHERE s_sigue = \''.$tsCore->setProtect((int)$data['user_id']).'\' AND s_user = \''.$tsCore->setProtect((int)$this->uid).'\' AND s_type = \'1\'', array(__FILE__, __LINE__))) {
	// Restamos un -1 a la lista de los que segui.
    anaK('query', 'UPDATE '.$tsCore->table['08'].' SET user_seguidores = (user_seguidores - 1) WHERE user_id = \''.$tsCore->setProtect((int)$data['user_id']).'\' AND user_baneado = \'0\'', array(__FILE__, __LINE__));
	
    return '2: You no longer follow this user..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//-->
	break;
	}
	//
	}
	//-->
	break;
	}
	//
	}
	
	
	#20. GUARDAR DATOS DE FILTROS EN NOTIFICACIONES
	function guardar_filtros() {
	global $tsCore;
	$tsData = array(
	'name' => $tsCore->setProtect($_POST['name']),
	'value' => ($_POST['value'] == 'false') ? 0 : 1,
	);
	if(strlen($tsData['name']) <= 1) return '0: This action cannot be executed. Error code: UF001/NM';
	elseif($this->is_member != 1) return '0: You must login to save these settings.';
	
	// Actualizamos la opcion del filtro seleccionada.
	$this->filtros[$tsData['name']] = $tsData['value'];
	// Creamos el serialize de nuevo.
	$new_filters = serialize($this->filtros);
	// Verificamos si ha sido serializado correctamente.
	if($tsCore->is_serialized($new_filters) == 1) {
		
	if($tsData['name'] == 'ntfs') 
	setcookie('live_ntfs', ($tsData['value'] == 0) ? 'ON' : 'OFF', $tsCore->settings['date'] + (60*60*24*90));
	
	elseif($tsData['name'] == 'msgs') 
	setcookie('live_msgs', ($tsData['value'] == 0) ? 'ON' : 'OFF', $tsCore->settings['date'] + (60*60*24*90));
	
	elseif($tsData['name'] == 'sound') 
	setcookie('live_sound', ($tsData['value'] == 0) ? 'ON' : 'OFF', $tsCore->settings['date'] + (60*60*24*90));
	
	// Si todo esta bien guardamos los cambios.
	if(anaK('query', 'UPDATE '.$tsCore->table['09'].' SET p_monitor = \''.$new_filters.'\' WHERE user_id = '.$tsCore->setProtect((int)$this->uid), array(__FILE__, __LINE__))) {
	return true;
	
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	
	
	#21. CUMPLEAÑOS DE USUARIOS
	function get_follow_birthday() {
	global $tsCore;
	$query = anaK('query', 'SELECT s.s_sigue, u.user_id FROM '.$tsCore->table['12'].' AS s LEFT JOIN '.$tsCore->table['08'].' AS u ON u.user_id = s.s_sigue LEFT JOIN '.$tsCore->table['09'].' AS p ON p.user_id = u.user_id WHERE s.s_user = \''.$tsCore->setProtect((int)$this->uid).'\' && s.s_type = \'1\' && p.p_dia = \''.$tsCore->setProtect(date('j', $tsCore->settings['date'])).'\' && p.p_mes = \''.$tsCore->setProtect(date('n', $tsCore->settings['date'])).'\' && s.s_sigue NOT IN(SELECT obj_uno FROM '.$tsCore->table['13'].' WHERE user_id = \''.$tsCore->setProtect((int)$this->uid).'\' && not_type = \'2\')', array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
	// Le notificamos a los usuarios que su amigo esta de cumpleaños.
	anaK('query', 'INSERT INTO '.$tsCore->table['13'].' (user_id, obj_user, obj_uno, not_type, not_date) VALUES (\''.$tsCore->setProtect((int)$this->uid).'\', \''.$tsCore->setProtect((int)$row['s_sigue']).'\', \''.$tsCore->setProtect((int)$row['s_sigue']).'\', \'2\', \''.$tsCore->setProtect($tsCore->settings['date']).'\')');
	//	
	}
	//
	}
	
	
	#22. PARA EL CUMPLEAÑERO
	function birthday() {
	global $tsCore;
	// Dia y mes actual.
	$tsData = array(
	'dia' => $tsCore->setProtect(date('j', $tsCore->settings['date'])),
	'mes' => $tsCore->setProtect(date('n', $tsCore->settings['date'])),
	);
	// Si es el mismo dia y mes para el cumpleañero mostramos el mensaje y la imagen de cumpleaños.
	if($tsData['dia'] == $this->info['p_dia'] && $tsData['mes'] == $this->info['p_mes']) {
	$data = array(
	'p_cover' => $tsCore->settings['img'].'/feliz_cumple.jpg', 
	'p_position' => '0% -22%',
	'anuncio' => 'Hi <b>'.(($this->info['user_name']) ? $this->info['user_name'] : $this->info['user_nick']).'</b> today is an excellent day to celebrate and wish you a happy birthday, best wishes and the greatest of successes to you. Have a great time and lots of happiness, peace and love. Wish you all the family and the team we are <b>'.$tsCore->settings['titulo'].'.</b>',
	);
	//
	}
	// Le mostramos un mensaje global al cumpleañero felicitandolo :)
	(strlen($data['anuncio']) > 10) ? $tsCore->settings['news'][0] = array('not_body' => $data['anuncio']) : '';
	//
	return $data;
	}
	
	
	#23. INICIAR SESION CON RED SOCIAL.
	function login_social() {
	global $tsCore;
	// ID de la red social.
	$id = $tsCore->setProtect(intval(empty($_GET['id']) ? $_POST['id'] : $_GET['id']));
	// La red social..
	$red_social = array(
	'1' => 'Facebook', 'pf_1' => 'fb_active',
	'2' => 'Google',   'pf_2' => 'gl_active',
	'3' => 'Twitter',  'pf_3' => 'tw_active',
	'4' => 'GitHub',   'pf_4' => 'gi_active',
	'5' => 'WindowsLive',  'pf_5' => 'wl_active',
	);
	// Selecciono una red social? sino indicamos la primera facebook.
	if(empty($id) || empty($red_social[$id])) $id = 1;
	// La red social tiene configurada el api?
	if(strlen($tsCore->settings['api'][strtolower($red_social[$id])]['id']) < 10 || strlen($tsCore->settings['api'][strtolower($red_social[$id])]['secret']) < 10 || $tsCore->settings['api'][strtolower($red_social[$id])][$red_social['pf_'.$id]] == 1) return '0: El api de <b>'.$red_social[$id].'</b> is not configured correctly, contact the administration.';
	
	// Datos del api.
	$tsApi = array(
	'callback' => $tsCore->settings['url'].'/social/'.$id,
	'providers' => array(
	$red_social[$id] => array(
	'enabled' => true, 
	'keys' => array(
	'id' => $tsCore->settings['api'][strtolower($red_social[$id])]['id'], 
	'secret' => $tsCore->settings['api'][strtolower($red_social[$id])]['secret']),
	)));
	
	// Adicionales depende de la red social.
	if($red_social[$id] == 'Facebook') {
	$tsApi['providers']['Facebook'] += array('scope' => 'email', 'trustForwarded' => false);
	} elseif($red_social[$id] == 'Twitter') {
	$tsApi['providers']['Twitter'] +=  array('includeEmail' => true);
	}
	
	// Todo bien? enviamos datos al api.
	include EA_EXTRA.'social-login/autoload.php';
	$getApi = new \Hybridauth\Hybridauth($tsApi);
	$outApi = $getApi->authenticate($red_social[$id]);
	$tokens = $outApi->getAccessToken();
	// Recibimos datos del usuario.
    $tsData = $outApi->getUserProfile();
	
	// Recibimos respuesta para este usuario? continuamos.
	if($tsData && isset($tsData->identifier)) {
	// Formato de email y prefix.
	if($red_social[$id] == 'Facebook') {
	$pref   = 'fa_';
	$domain = '@facebook.com';
	} elseif($red_social[$id] == 'Google') {
	$pref   = 'go_';
	$domain = '@google.com';
	} elseif($red_social[$id] == 'Twitter') {
	$pref   = 'tw_';
	$domain = '@twitter.com';
	} elseif($red_social[$id] == 'GitHub') {
	$pref   = 'gi_';
	$domain = '@github.com';
	} elseif($red_social[$id] == 'WindowsLive') {
	$pref   = 'wl_';
	$domain = '@hotmail.com';
	}
	
	// No se obtuvo un email del api? usamos uno formateado.
    if(empty($tsData->email)) {
    $pref_email  = $pref.$tsData->identifier;
    $tsData->email = $pref_email.$domain;
    }
	
	// Datos que vamos a usar del api.
	$dataApi = array(
	'user_id' => $tsCore->setProtect($tsData->identifier),
	'user_name' => $tsCore->setProtect(ucwords($tsData->firstName.' '.$tsData->lastName)),
	'user_email' => $tsCore->setProtect(strtolower($tsData->email)),
	'user_avatar' => $tsCore->setProtect($tsData->photoURL),
	'user_sexo' => $tsCore->setProtect((strtolower($tsData->gender) == 'male') ? 1 : 0),
	'user_social' => $tsCore->setProtect($pref),
	'user_pin' => $tsCore->setProtect($this->get_pin_user()),
	'user_ip' => $tsCore->setProtect($tsCore->settings['ip']),
	'user_plan' => $tsCore->setProtect(intval(empty($_GET['plan']) ? $_POST['plan'] : $_GET['plan'])),
	'user_registro' => $tsCore->setProtect($tsCore->settings['date']),
	);
	
	// Un nick para usuario por medio de su email.
	preg_match("/([_a-z0-9.]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $dataApi['user_email'], $nick);
	$dataApi['user_nick'] = $tsCore->setProtect(strtolower($nick[1]));
	// Nombre para usuario de GitHub.
	($red_social[$id] == 'GitHub') ? $dataApi['user_name'] = $tsCore->setProtect(ucwords($tsData->displayName)) :'';
	
	// Hacemos la consulta haber si exite algun usuario.
	$query = anaK('query', 'SELECT u.user_id, u.user_activo, u.user_social_login, p.p_monitor FROM '.$tsCore->table['08'].' AS u LEFT JOIN '.$tsCore->table['09'].' AS p ON p.user_id = u.user_id WHERE MATCH (u.user_social_login) AGAINST (\''.$dataApi['user_id'].'\' IN BOOLEAN MODE) && u.user_email = \''.$dataApi['user_email'].'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	if($data['user_id'] > 0) {
	//--> SI EL USUARIO YA VINCULO UNA CUENTA, LE CONCEDEMOS LA SESION.
	$data['social'] = unserialize($data['user_social_login']);#extraemos datos.
	$dataApi['user_hash'] = $tsCore->setProtect($tsCore->get_encrypt($dataApi['user_social'].$dataApi['user_id'].'_'.$data['user_id']));#code hash
	
	// Todos los datos coinciden?
	if(($data['social'][$pref]['client'] != $dataApi['user_id']) || ($data['social'][$pref]['email'] != $dataApi['user_email']) || ($data['social'][$pref]['hash'] != $dataApi['user_hash']) || ($data['social'][$pref]['id_'] != '_'.$data['user_id']) || ($data['user_activo'] != 1)) 
	return '0: Your account data couldn\'t be processed. <b>Try again</b>, if you are still having problems contact the administration.';
	//
	else {
	// Creamos la variable para la sesion.
	$this->sesion = new tsSesion();	
	// Existe alguna sesion?
	if(!$this->sesion->read()) { 
	$this->sesion->create();#si no existe, la creamos.
	} else {
	$this->sesion->update(array('user_id' => $data['user_id'], 'auto_login' => false, 'force_update' => true));#si existe, la actualizamos.
	}
	
	// Creamos las cookies para notificaciones.
	$getLive = unserialize($data['p_monitor']);
	setcookie('live_ntfs', ($getLive['ntfs'] == 0) ? 'ON' : 'OFF', $tsCore->settings['date'] + (60*60*24*90));
	setcookie('live_msgs', ($getLive['msgs'] == 0) ? 'ON' : 'OFF', $tsCore->settings['date'] + (60*60*24*90));
	setcookie('live_sound', ($getLive['sound'] == 0) ? 'ON' : 'OFF', $tsCore->settings['date'] + (60*60*24*90));
	
	// Si selecciono algun plan al crear la cuenta, lo re-direccionamos al panel..
	($_SESSION['pid'] > 0) ? $redirect = $tsCore->settings['url'].'/panel/?action=create&pid='.$_SESSION['pid'] : '';
	
	// Redireccionamos...
	if($redirect) $tsCore->redirect($redirect);
	else $tsCore->redirect($tsCore->settings['url'].'/panel/');
	//
	}
	//	
	} else {
	//--> SI EL USUARIO NO HA VINCULADO UNA CUENTA, LA VINCULAMOS.
	$query = anaK('query', 'SELECT user_id, user_social_login FROM '.$tsCore->table['08'].' WHERE user_email = \''.$dataApi['user_email'].'\' && user_activo = \'1\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	// Existe el email? actualizamos.
	if($data['user_id'] > 0) {
	// Extraemos datos, si ya ha configurado con otra red social.
	$data_social = unserialize($data['user_social_login']);
	$data_social[$pref]['id_'] = '_'.$data['user_id'];
	$data_social[$pref]['social'] = $pref;
	$data_social[$pref]['client'] = $dataApi['user_id'];
	$data_social[$pref]['email'] = $dataApi['user_email'];
	$data_social[$pref]['hash'] = $tsCore->get_encrypt($dataApi['user_social'].$dataApi['user_id'].'_'.$data['user_id']);
	$data_social[$pref]['registro'] = $dataApi['user_registro'];
	$data_social = serialize($data_social);#unimos datos de nuevo.

	// Verificamos el serialize.
	if($tsCore->is_serialized($data_social) == 1) {
	if(anaK('query', 'UPDATE '.$tsCore->table['08'].' SET user_social_login = \''.$data_social.'\' WHERE user_id = \''.$tsCore->setProtect((int)$data['user_id']).'\' && user_email = \''.$tsCore->setProtect($dataApi['user_email']).'\' LIMIT 1', array(__FILE__, __LINE__))) { 
	$tsCore->redirect($tsApi['callback']);#la vinculamos? redireccionamos..
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	} else return '0: An error occurred when trying to link your account. <b>Try again</b>, if you are still having problems contact the administration.';
	//
	} else {
	//--> SI NO EXISTE EL EMAIL DE LA RED SOCIAL - CREAMOS LA CUENTA PARA EL USUARIO..
	if($tsCore->settings['reg_active'] == 1) { 
	return '0: Creating accounts in <b>'.$tsCore->settings['titulo'].'</b> is off. Try in a couple of days.';
	
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
	return '0: Sorry but we have reached the maximum registration limit for users.';
	//
	} else {
	// Validamos de nuevo los campos por seguridad.
	if(!preg_match("/^[_a-z0-9.]{4,16}$/", $dataApi['user_nick'])) 
	$dataApi['user_nick'] = $dataApi['user_nick'].'_'.mt_rand(0, 99);
	
	elseif(anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE b_type = \'4\' && b_value = \''.$dataApi['user_nick'].'\' LIMIT 1', array(__FILE__, __LINE__)))) 
	$dataApi['user_nick'] = $dataApi['user_nick'].'_'.mt_rand(0, 99);
	
	// Volvemos a verificar el nick.
	if(anaK('num_rows', anaK('query', 'SELECT user_id FROM '.$tsCore->table['08'].' WHERE user_nick = \''.$dataApi['user_nick'].'\' LIMIT 1', array(__FILE__, __LINE__)))) 
	$dataApi['user_nick'] = $dataApi['user_nick'].'_'.mt_rand(0, 99);
	
	elseif(!mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $dataApi['user_email'])) 
	return '0: Sorry, the email does not have a valid format.';
	
	elseif(anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE (b_type = \'2\' && b_value = \''.$dataApi['user_email'].'\') || (b_type = \'3\' && b_value = \''.strstr($dataApi['user_email'], '@').'\') || (b_type = \'4\' && b_value = \''.strstr($dataApi['user_email'], '@', true).'\') LIMIT 1', array(__FILE__, __LINE__)))) 
	return '0: Sorry but this email is not allowed on this server.';
	
	// Si todo esta bien, creamos la cuenta.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['08'].' (user_activo, user_nick, user_name, user_email, user_ip, user_pin, user_registro) VALUES (\'1\', \''.$dataApi['user_nick'].'\', \''.$dataApi['user_name'].'\', \''.$dataApi['user_email'].'\', \''.$dataApi['user_ip'].'\', \''.$dataApi['user_pin'].'\', \''.$dataApi['user_registro'].'\')', array(__FILE__, __LINE__))) {
	// Obtenemos el ID de registro para las otras tablas.
	$new_data['user_id'] = anaK('insert_id', array(__FILE__, __LINE__));
	// Agregamos a social.
	$social_ = array(
	$pref => array(
	'id_' => '_'.$new_data['user_id'], 
	'social' => $pref, 
	'client' => $dataApi['user_id'], 
	'email' => $dataApi['user_email'],
	'hash' => $tsCore->setProtect($tsCore->get_encrypt($dataApi['user_social'].$dataApi['user_id'].'_'.$new_data['user_id'])), 
	'registro' => $dataApi['user_registro'],
	));
	$social__ = serialize($social_);#creamos el serialize.
	$new_data['user_password'] = $tsCore->get_encrypt($social_[$pref]['id_'].'_'.$dataApi['user_nick']);#contraseña
	
	// Actualizamos social y la contraseña.
	if($tsCore->is_serialized($social__) == 1) {
	anaK('query', 'UPDATE '.$tsCore->table['08'].' SET user_social_login = \''.$social__.'\', user_password = \''.$tsCore->setProtect($new_data['user_password']).'\' WHERE user_id = \''.$tsCore->setProtect((int)$new_data['user_id']).'\' && user_email = \''.$tsCore->setProtect($dataApi['user_email']).'\' LIMIT 1', array(__FILE__, __LINE__));
	//
	}
	
	// Creamos los avatar para el usuario desde el url si tiene uno.
	if(strlen($dataApi['user_avatar']) > 10) {
	copy($dataApi['user_avatar'], EA_FILES.'perfiles/'.$new_data['user_id'].'_120.jpg');		
	copy($dataApi['user_avatar'], EA_FILES.'perfiles/'.$new_data['user_id'].'_50.jpg');
	}
	
	// Agregamos datos al perfil del usuario.
	anaK('query', 'INSERT INTO '.$tsCore->table['09'].' (user_id, p_sexo, p_imagen) VALUES (\''.$tsCore->setProtect((int)$new_data['user_id']).'\', \''.$tsCore->setProtect($dataApi['user_sexo']).'\', \''.$tsCore->setProtect((strlen($dataApi['user_avatar']) > 10) ?  1 : 0).'\')', array(__FILE__, __LINE__));
	
	// Si el usuario agrego algun plan lo pasamos con la cookie.
	if(strlen($dataApi['user_plan']) > 0) $_SESSION['pid'] = $dataApi['user_plan'];
	
	//--> LE ENVIAMOS UN MENSAJE DE BIENVENIDA AL USUARIO.
	$tsAdmin = $tsCore->get_admin();#1er admin.
	$msg_bienvenida = str_ireplace(array('[usuario]', '[welcome]', '[web]'), array($dataApi['user_nick'], 'we want to welcome you', $tsCore->settings['titulo']), $tsCore->settings['bienvenida_msg']);
	// El mensaje.
	$tsMP = array(
	'mp_to' => $tsCore->setProtect((int)$new_data['user_id']), 
	'mp_from' => $tsCore->setProtect((int)$tsAdmin['user_id']),
	'mp_subject' => $tsCore->setProtect('we want to welcome you'.' an '.$tsCore->settings['titulo']), 
	'mp_preview' => $tsCore->setProtect($msg_bienvenida),
	'mp_ip' => $tsCore->setProtect($tsCore->settings['ip']),
	'mp_date' => $tsCore->setProtect($tsCore->settings['date']),
	);
	
	// Agregamos el mensaje a la base de datos.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['14'].' (mp_to, mp_from, mp_subject, mp_preview, mp_date) VALUES (\''.$tsMP['mp_to'].'\', \''.$tsMP['mp_from'].'\', \''.$tsMP['mp_subject'].'\', \''.substr($tsMP['mp_preview'], 0, 75).'\', \''.$tsMP['mp_date'].'\')', array(__FILE__, __LINE__))) {	
	// ID del mensaje para respuesta.
	$id_mensaje = anaK('insert_id', array(__FILE__, __LINE__));
	// Agregamos la respuesta al mensaje.
	anaK('query', 'INSERT INTO '.$tsCore->table['15'].' (mp_id, mr_from, mr_body, mr_ip, mr_date) VALUES (\''.$tsCore->setProtect((int)$id_mensaje).'\', \''.$tsMP['mp_from'].'\', \''.$tsMP['mp_preview'].'\', \''.$tsMP['mp_ip'].'\', \''.$tsMP['mp_date'].'\')', array(__FILE__, __LINE__));
	//
	}
	//--> HASTA AQUI EL MENSAJE.
	return '1: Great young guy, <b>'.$dataApi['user_nick'].'</b> your account has been successfully created. You can now log in.';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//	
	}#-> limite
	//
	}#-> reg_active
	//
	}
	//
	}
	//
	} else return '0: Apparently you couldn\'t get a response from your <b>'.$red_social[$id].'.</b>';
	//
	}
	
	
	#24. ELIMINAR USUARIO SIN VALIDAR O INACTIVO.
	function delete_user_inactive() {
	global $tsCore;
	// Tiempo programable.
	$time = array('-3dias' => $tsCore->settings['date'] - (3*24*60*60));# RESTAMOS 3 DIAS
	// Cuales son los usuarios que tienen mas de 3 dias sin validar?
    $query = anaK('query', 'SELECT user_id FROM '.$tsCore->table['08'].' WHERE user_activo = \'0\' && user_registro < '.$tsCore->setProtect($time['-3dias']), array(__FILE__, __LINE__));
	while($data = anaK('fetch_assoc', $query)) {
    // Eliminamos los usuario con user_activo en 0.
	if(anaK('query', 'DELETE FROM '.$tsCore->table['08'].' WHERE user_id = \''.$tsCore->setProtect((int)$data['user_id']).'\' && user_activo = \'0\'', array(__FILE__, __LINE__))) {
	// Eliminamos el perfil.
	anaK('query', 'DELETE FROM '.$tsCore->table['09'].' WHERE user_id = '.$tsCore->setProtect((int)$data['user_id']), array(__FILE__, __LINE__));
	// Eliminamos archivos subidos.
	anaK('query', 'DELETE FROM '.$tsCore->table['05'].' WHERE u_control = '.$tsCore->setProtect((int)$data['user_id']), array(__FILE__, __LINE__));
	// Eliminamos solicitudes de activacion.
	anaK('query', 'DELETE FROM '.$tsCore->table['07'].' WHERE user_id = '.$tsCore->setProtect((int)$data['user_id']), array(__FILE__, __LINE__));
	// Eliminamos a los que sigue y de los que lo siguen.
	anaK('query', 'DELETE FROM '.$tsCore->table['12'].' WHERE (s_user = \''.$tsCore->setProtect((int)$data['user_id']).'\' || s_sigue = \''.$tsCore->setProtect((int)$data['user_id']).'\') && s_type = \'1\'', array(__FILE__, __LINE__));
	// Eliminamos notificacion recibidas y enviadas.
	anaK('query', 'DELETE FROM '.$tsCore->table['13'].' WHERE (user_id = \''.$tsCore->setProtect((int)$data['user_id']).'\' || obj_user = \''.$tsCore->setProtect((int)$data['user_id']).'\')', array(__FILE__, __LINE__));
	// Eliminamos las cuentas hosting que tenga.
	anaK('query', 'DELETE FROM '.$tsCore->table['16'].' WHERE cp_client = '.$tsCore->setProtect((int)$data['user_id']), array(__FILE__, __LINE__));
	// Eliminamos el avatar del usuario.
	$tsCore->delete_folder(EA_FILES.'perfiles/'.(int)$data['user_id'].'_120.jpg', 1);
	$tsCore->delete_folder(EA_FILES.'perfiles/'.(int)$data['user_id'].'_50.jpg', 1);
	//
	}
	// Hay mensajes privados?
	$query = anaK('query', 'SELECT mp_id FROM '.$tsCore->table['14'].' WHERE (mp_to = \''.$tsCore->setProtect((int)$data['user_id']).'\' || mp_from = \''.$tsCore->setProtect((int)$data['user_id']).'\')', array(__FILE__, __LINE__));
	while($msg = anaK('fetch_assoc', $query)) {	
	// Eliminamos todos los mensajes.
	if(anaK('query', 'DELETE FROM '.$tsCore->table['14'].' WHERE mp_id = '.$tsCore->setProtect((int)$msg['mp_id']), array(__FILE__, __LINE__))) {
	// Eliminamos las respuestas de los mensajes.
	anaK('query', 'DELETE FROM '.$tsCore->table['15'].' WHERE mp_id = '.$tsCore->setProtect((int)$msg['mp_id']), array(__FILE__, __LINE__));	
	}
	//
	}
	//
    }
	//
	}
	

//  
}
#-00. PARA SESION DE USUARIOS
class tsSesion {
	var $ID                 = '';
    var $sess_expiration    = 7200;
    var $sess_match_ip      = false;
    var $sess_time_online   = 300;
    var $cookie_prefix      = 'einet_';
    var $cookie_name        = '';
    var $cookie_path        = '/';
    var $cookie_domain      = '';
    var $userdata;

	function __construct() {
	global $tsCore;	
	// Obtener el dominio o subdominio para la cookie.
	$host = parse_url($tsCore->settings['url']);
	$host = str_replace('www.', '' , strtolower($host['host']));
	// Establecer variables.
	$this->cookie_domain = ($host == 'localhost') ? '' : '.' . $host;
	$this->cookie_name = $this->cookie_prefix.substr(md5($host), 0, 8);
	
	// Cada que un usuario cambie de IP, requerir nueva sesion?
	$this->sess_match_ip = empty($tsCore->settings['allow_sess_ip']) ? false : true;
	
	// Cada cuanto actualizar la sesión? && Expires.
	$this->sess_time_online = empty($tsCore->settings['user_activo']) ? $this->sess_time_online : ($tsCore->settings['user_activo'] * 60);
	}
	
	
	#-01. LEER SESION DE USUARIO
	function read() {
	global $tsCore;
	
	$this->ID = $_COOKIE[$this->cookie_name.'_anK'];
	
	// Es un id valido?
	if(!$this->ID || strlen($this->ID) != 32) {
	return false;
	}
	
	// Hacemos la consulta.
	$query = anaK('query', 'SELECT * FROM '.$tsCore->table['11'].' WHERE session_id = \''.$tsCore->setProtect($this->ID).'\' LIMIT 1', array(__FILE__, __LINE__));
    $sesion = anaK('fetch_assoc', $query);
	
	// Existe la sesion?
	if(!isset($sesion['session_id'])) {
	$this->destroy();
	return false;
	}
	
	// Es la sesion actual?
	if(($sesion['session_time'] + $this->sess_expiration) < $tsCore->settings['date'] && empty($sesion['session_autologin'])) {
	$this->destroy();
	return false;
	}
	
	// Si cambió de IP creamos una nueva sesion.
	if($this->sess_match_ip == true && $sesion['session_ip'] != $tsCore->settings['ip']) {
	$this->destroy();
	return false;
	}
	
	// Genial guardamos y retornamos.
	$this->userdata = $sesion;
	unset($sesion);
	return true;
	//
	}
	
	
	#-02. CREAR SESION
	function create() {
	global $tsCore;
	// Generar ID
	$this->ID = $this->gen_session_id();
	
	// Guardamos en la BD, en [session_user_id] siemrpe será [0] si inicia sesión "actualiza"
	anaK('query', 'INSERT INTO '.$tsCore->table['11'].' (session_id, session_ip, session_time) VALUES (\''.$tsCore->setProtect($this->ID).'\', \''.$tsCore->setProtect($tsCore->settings['ip']).'\', \''.$tsCore->setProtect($tsCore->settings['date']).'\')', array(__FILE__, __LINE__));
	
	// Establecemos la cookie.
	$this->set_cookie(array('name' => 'anK', 'id' => $this->ID, 'time' => $this->sess_expiration));
	//
	}
	
	
	#-03. ACTUALIZAR COOKIE EXISTENTE
	function update($tsData = null) {
	global $tsCore;
	// Actualizar la sesión cada x tiempo, esto es configurado en el panel de Admin.
	if(($this->userdata['session_time'] + $this->sess_time_online) >= $tsCore->settings['date'] && $tsData['force_update'] == false) {
	return;
	}
	// Datos para actualizar.
	$this->userdata['session_user_id'] = empty($tsData['user_id']) ? $this->userdata['session_user_id'] : $tsData['user_id'];
	// Autologin requiere una comprobación doble.
	$tsData['auto_login'] = ($tsData['auto_login'] == false) ? 0 : 1;
	$this->userdata['session_autologin'] = empty($this->userdata['session_autologin']) ? $tsData['auto_login'] : $this->userdata['session_autologin'];
	
	// Actualizar en la DB.
	$val = array(
	'session_user_id' => $this->userdata['session_user_id'], 
	'session_ip' => $tsCore->settings['ip'], 
	'session_time' => $tsCore->settings['date'], 
	'session_autologin' => $this->userdata['session_autologin'],
	);
	anaK('query', 'UPDATE '.$tsCore->table['11'].' SET '.$tsCore->get_value($val).' WHERE session_id = \''.$tsCore->setProtect($this->ID).'\'', array(__FILE__, __LINE__));
	
	// Limpiar sesiones.
	$this->sess_gc();
	
	// Actualizamos la cookie.
	if(!empty($this->userdata['session_autologin'])) {
	// Si el usuario quiere recordar su sesión, se guardará por 1 año.
	$expiration = 31500000;
	
	} else $expiration = $this->sess_expiration;
	//
	$this->set_cookie(array('name' => 'anK', 'id' => $this->ID, 'time' => $expiration));
	}
	
	
	#-04. DESTRUIR SESION
	function destroy() {
	global $tsCore;
	anaK('query', 'DELETE FROM '.$tsCore->table['11'].' WHERE session_id = \''.$tsCore->setProtect($this->ID).'\'', array(__FILE__, __LINE__));
	// Reiniciamos la cookie.
	$this->set_cookie(array('name' => 'anK', 'id' => '', 'time' => -31500000));
	//
	}
	
	
	#-05. GENERAR COOKIE
	function set_cookie($tsData) {
	global $tsCore;
	// Si no recibimos datos detenemos todo.
	if(!is_array($tsData)) return;
	
	// Si recibimos los datos generamos la cookie.
	$cookiename = rawurlencode($this->cookie_name.'_'.$tsData['name']);
	$cookieid = rawurlencode($tsData['id']);
	// Establecer la cookie.
	setcookie($cookiename, $cookieid, ($tsCore->settings['date'] + $tsData['time']), '/', $this->cookie_domain);
	//
	}
	
	
	#-06. GENERAR UN ID PARA SESION.
	function gen_session_id() {
	global $tsCore;
	$sessid = '';
	while(strlen($sessid) < 32) {
	$sessid .= mt_rand(0, mt_getrandmax());
	}
	// Para que el ID de sesión sea más seguro, lo combinamos con la IP del usuario mas los algoritmos de la web.
	$sessid .= $tsCore->setProtect($tsCore->settings['ip'].md5(ILL_SEC));
	return md5(uniqid(md5(KEY_SEC).$sessid, true));
	//
	}
	
	
	#-07. ELIMINAR SESIONES CADUCADAS
	function sess_gc() {
	global $tsCore;
	// Para no eliminar con cada llamada de la función, sólo si se cumple la sentencia se eliminan las sesiones.
	if((rand() % 100) < 30) {
	// Usuario sin actividad.
	$expire = $tsCore->settings['date'] - $this->sess_time_online;
	anaK('query', 'DELETE FROM '.$tsCore->table['11'].' WHERE session_time < '.$expire.' && session_autologin = \'0\'', array(__FILE__, __LINE__));
	}
	//
	}	
}

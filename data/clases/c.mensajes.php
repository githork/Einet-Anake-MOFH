<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: c.mensajes.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

# TODAS LAS FUNCIONES:
#01. get_mensajes()
#02. read_mensaje()
#03. new_mensaje()
#04. new_respuesta()
#05. editar_mensajes()

class tsMensajes {
	var $mensajes = 0;# MENSAJES SIN LEER
	
	function __construct() {
	global $tsCore, $tsUser;
	
	// No esta logueado? detenemos el script.
	if(empty($tsUser->is_member)) return false;
	
	// Cuantos mensajes tenemos.
	$query = anaK('query', 'SELECT 
	(SELECT COUNT(mp_id) FROM '.$tsCore->table['14'].' WHERE mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' && mp_read_mon_to < \'2\' && mp_del_to = \'0\') AS msgs_all, 
	(SELECT COUNT(mp_id) FROM '.$tsCore->table['14'].' WHERE mp_answer = \'1\' && mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' && mp_read_mon_from < \'2\' && mp_del_from = \'0\') AS resp_all', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	// Sumamos y mostramos mensajes + respuestas en total.
	$this->mensajes = $data['msgs_all'] + $data['resp_all'];
	//
	}
	
	
	#01. MENSAJES|RECIBIDOS|ENVIADOS|RESPONDIDOS|BUSCADOR
	function get_mensajes($type = 1, $method = false, $where = 'normal') {
	global $tsCore, $tsUser;

	switch($type) {
	case '1':// Monitor solo si hay mas de 5 mensajes.
	//<--
	// Si hay mas de 5 mensajes, leemos solo los 5 nuevos.
    if($this->mensajes > 0 || $method == true) {
    $fnoread = "AND mp_read_mon_to ".($where != 'live' ? '< \'2\'' : '= \'0\'');
    $snoread = "AND mp_read_mon_from ".($where != 'live' ? '< \'2\'' : '= \'0\'');
    $limit = "";
    
	} else {
    $limit = "LIMIT 5";
    }
	//
	$query = anaK('query', 'SELECT m.mp_id, m.mp_to, m.mp_from, m.mp_read_to, m.mp_read_mon_to, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick, u.user_online FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON m.mp_from = u.user_id WHERE m.mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' AND m.mp_del_to = \'0\' '.$fnoread.' UNION (SELECT m.mp_id, m.mp_to, m.mp_from, m.mp_read_from, m.mp_read_mon_from, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick, u.user_online FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON m.mp_to = u.user_id WHERE m.mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' AND m.mp_del_from = \'0\' AND m.mp_answer = \'1\' '.$snoread.') ORDER BY mp_id DESC '.$limit, array(__FILE__, __LINE__));
	
	$page['total'] = 0;#contamos mensajes.
	//
	while($row = anaK('fetch_assoc', $query)) {
	$row['mp_from'] = ($row['mp_from'] == $tsUser->uid) ? $row['mp_to'] : $row['mp_from'];
	
	// Status del usuario.
	$online = ($tsCore->settings['date'] - ($tsCore->settings['user_activo'] * 60));
	$absent = ($tsCore->settings['date'] - (($tsCore->settings['user_activo'] * 60) * 2));
	if($row['user_online'] > $online) $row['status'] = 'online';
	elseif($row['user_online'] > $absent) $row['status'] = 'absent';
	else $row['status'] = 'offline';
	
	// Pasamos los datos.
	$data[$row['mp_date']] = $row;
	
	// Los campos que vamos actualizar.
	if($tsUser->uid == $row['mp_to']) $update = 'mp_read_mon_to = '.($where == 'live' ? '\'1\'' : '\'2\'');
    else $update = 'mp_read_mon_from = '.($where == 'live' ? '\'1\'' : '\'2\'');
	
    // Atualizamos para que no vuelvan a salir en monitor.
	anaK('query', 'UPDATE '.$tsCore->table['14'].' SET '.$update.' WHERE mp_id = '.$tsCore->setProtect((int)$row['mp_id']), array(__FILE__, __LINE__));
    //
	$page['total']++;
	}
	//-->
	break;
	
	case '2':// Mensajes recibidos.
	//<--
	// Solo no leidos.
	if($method == true) {
    $fnoread = 'AND mp_read_to = \'0\'';
    $snoread = 'AND mp_read_from = \'0\'';
    }
	
	$items = 'SELECT m.mp_id, m.mp_to, m.mp_from, m.mp_read_to, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON m.mp_from = u.user_id WHERE m.mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' AND m.mp_del_to = \'0\' '.$fnoread.' UNION (SELECT m.mp_id, m.mp_to, m.mp_from, m.mp_read_from, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON m.mp_to = u.user_id WHERE m.mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' AND m.mp_del_from = \'0\' AND m.mp_answer = \'1\' '.$snoread.') ORDER BY mp_id DESC';
	$query = anaK('query', $items, array(__FILE__, __LINE__));
	$todos = anaK('num_rows', $query);
	// Creamos el paginado.
    $page = $tsCore->getPag($todos, 15);
	
	// Re-utilizamos items para mostrar los mensajes.
	$query = anaK('query', $items.' LIMIT '.$page['limite'], array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
    // Es respuesta o mensaje?
    $row['mp_type'] = ($row['mp_from'] != $tsUser->uid) ? 1 : 2;
    $row['mp_from'] = ($row['mp_from'] == $tsUser->uid) ? $row['mp_to'] : $row['mp_from'];
	$data[$row['mp_date']] = $row;
    }
	//-->
	break;
	
	case '3': // Mensajes enviados.
	//<--
	$query = anaK('query', 'SELECT m.mp_id, m.mp_to, m.mp_read_to, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON m.mp_to = u.user_id WHERE m.mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' AND m.mp_del_from = \'0\' ORDER BY m.mp_id DESC', array(__FILE__, __LINE__));
	$todos = anaK('num_rows', $query);
	
	// Creamos el paginado.
    $page = $tsCore->getPag($todos, 15);
	
	$query = anaK('query', 'SELECT m.mp_id, m.mp_to, m.mp_read_to, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON m.mp_to = u.user_id WHERE m.mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' AND m.mp_del_from = \'0\' ORDER BY m.mp_id DESC LIMIT '.$page['limite'], array(__FILE__, __LINE__));
	// Mostramos los mensajes.
    while($row = anaK('fetch_assoc', $query)) {
    $row['mp_type'] = 2;
    $row['mp_from'] = $row['mp_to'];
    $row['mp_read_to'] = 1;
	$data[$row['mp_date']] = $row;
    }
	//-->
	break;
	
	case '4': // Mensajes respondidos.
	//<--
	$query = anaK('query', 'SELECT m.mp_id, m.mp_from, m.mp_read_from, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON m.mp_from = u.user_id WHERE m.mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' && m.mp_del_to = \'0\' AND m.mp_answer = \'1\' ORDER BY m.mp_id DESC', array(__FILE__, __LINE__));
	$todos = anaK('num_rows', $query);
	
	// Creamos el paginado.
    $page = $tsCore->getPag($todos, 15);
	
	$query = anaK('query', 'SELECT m.mp_id, m.mp_from, m.mp_read_from, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON m.mp_from = u.user_id WHERE m.mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' && m.mp_del_to = \'0\' AND m.mp_answer = \'1\' ORDER BY m.mp_id DESC LIMIT '.$page['limite'], array(__FILE__, __LINE__));
	// Mostramos los mensajes.
	while($row = anaK('fetch_assoc', $query)) {
	$row['mp_type'] = 1;
    $row['mp_read_to'] = 1;
	$data[$row['mp_date']] = $row;
	}
	//-->
	break;
	
	case '5': // Buscador de mensajes.
	//<--
	$buscar = $tsCore->setProtect($_GET['search']);
	
	$items = 'SELECT m.mp_id, m.mp_to, m.mp_from, m.mp_read_to, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON u.user_id = m.mp_from WHERE m.mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' AND m.mp_del_to = \'0\' UNION (SELECT m.mp_id, m.mp_to, m.mp_from, m.mp_read_from, m.mp_subject, m.mp_preview, m.mp_date, u.user_nick FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON u.user_id = m.mp_to WHERE MATCH (u.user_nick, m.mp_subject, m.mp_preview) AGAINST (\''.$buscar.'\' IN BOOLEAN MODE) && m.mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' AND m.mp_del_from = \'0\' && m.mp_answer = \'1\') ORDER BY mp_id DESC';
	$query = anaK('query', $items, array(__FILE__, __LINE__));
	$todos = anaK('num_rows', $query);
	// Creamos el paginado.
    $page = $tsCore->getPag($todos, 15);
	
	// Re-utilizamos items para mostrar los mensajes.
	$query = anaK('query', $items.' LIMIT '.$page['limite'], array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
    // Es respuesta o mensaje?
    $row['mp_type'] = ($row['mp_from'] != $tsUser->uid) ? 1 : 2;
    $row['mp_from'] = ($row['mp_from'] == $tsUser->uid) ? $row['mp_to'] : $row['mp_from'];
    $data[$row['mp_date']] = $row;
    }
	//-->
	break;
	}
	
	// Ordenamos y pasamos los array()
	if($data) krsort($data);
	$page['met'] = $method;# Mensajes leidos|sin leer.
	//
	return $datos = array('data' => $data, 'page' => $page);
	//	
	}
	
	
	#02. LEER MENSAJE
	function read_mensaje() {
	global $tsCore, $tsUser;
    
	$id_obj = $tsCore->setProtect((int)$_GET['obj_id']);
	if(!$id_obj) return array('error' => 'Sorry but the message id is not valid..');
    
	$query = anaK('query', 'SELECT m.*, u.user_nick FROM '.$tsCore->table['14'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON m.mp_from = u.user_id WHERE m.mp_id = \''.$tsCore->setProtect($id_obj).'\' '.($tsUser->is_admod ? '' : '&& ((m.mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' && m.mp_del_to = \'0\') || (m.mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' && m.mp_del_from = \'0\'))').' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	
	if(!$data['mp_id']) return array('error' => 'Sorry but the message doesn\'t exist or was already deleted.');
	 
	// NO PUEDE LEER MENSAJES DE OTROS SI ES MODERADOR SOLO SI ESTAN REPORTADOS, SI ES ADMINISTRADOR SI PUEDE VERLOS.
	$is_report = anaK('num_rows', anaK('query', 'SELECT id_obj FROM '.$tsCore->table['06'].' WHERE id_obj = \''.$tsCore->setProtect($data['mp_id']).'\' && type_susp = \'2\' LIMIT 1', array(__FILE__, __LINE__)));
	
	// Esta reportado el mensaje?
	if($is_report == 1 && $tsUser->is_admod) $canview = true;
	else $canview = false;
	
	// Redirigir si no tiene permiso.
	if($data['mp_to'] != $tsUser->uid && $data['mp_from'] != $tsUser->uid && !$canview && $tsUser->is_admod != 1) 
	$tsCore->redirect($tsCore->settings['url'].'/mensajes/');
	
	// Las respuestas del mensaje.
    $query = anaK('query', 'SELECT r.*, u.user_nick FROM '.$tsCore->table['15'].' AS r LEFT JOIN '.$tsCore->table['08'].' AS u ON r.mr_from = u.user_id WHERE r.mp_id = \''.$tsCore->setProtect($data['mp_id']).'\' ORDER BY mr_id', array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
    $row['mr_body'] = $tsCore->parse_censurar($tsCore->parse_BBCode($row['mr_body'], 'news'));
	$data['resp'][] = $row;
	}
	
	// Contamos las respuestas.
	$resp = count($data['resp']);
	$from = $data['resp'][$resp-1]['mr_from']; // El ultimo en responder.
	
	if($tsUser->uid == $data['mp_to']) {
	$update = 'mp_read_to = \'1\', mp_read_mon_to = \'2\'';
	$data['msg']['mp_type'] = 1;

	// Para mi.
	} elseif($from == $data['mp_to'] && $data['mp_from'] == $tsUser->uid) {
	$update = 'mp_read_from = \'1\', mp_read_mon_from = \'2\'';
	$data['msg']['mp_type'] = 2;
	
	// Me respondieron.
	} elseif($from == $data['mp_from']) {
	$update = 'mp_read_from = \'1\', mp_read_mon_from = \'2\'';
	$data['msg']['mp_type'] = 2;
	//
	}
	
	// Actualizamos que ya se ha leido.
	if(isset($update)) anaK('query', 'UPDATE '.$tsCore->table['14'].' SET '.$update.' WHERE mp_id = '.$tsCore->setProtect($data['mp_id']), array(__FILE__, __LINE__));
	
	// Verificamos si esta bloqueado.
	$user_id = intval(($data['mp_from'] != $tsUser->uid) ? $data['mp_from'] : $data['mp_to']);
	
	// Puedo responder? o estoy bloqueado.
	$is_block = anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE (b_author = \''.$tsCore->setProtect($data['mp_to']).'\' && b_value = \''.$tsCore->setProtect($data['mp_from']).'\' && b_type = \'2\') || (b_author = \''.$tsCore->setProtect($data['mp_from']).'\' && b_value = \''.$tsCore->setProtect($data['mp_to']).'\' && b_type = \'2\') LIMIT 1', array(__FILE__, __LINE__)));
	
	// Cargamos datos del otro usuario.
	$tsData = $tsUser->user_data($user_id, 1);
	
	// Psamos datos.
	$data['ext']= array(
	'can_read' => (!$tsUser->is_admod && $is_block > 0) ? 0 : 1,
	'uid' => $user_id,
	'user' => $tsData['user_nick'],
	);
	//
	return $data;
    }
	
	
	#03. NUEVO MENSAJE
	function new_mensaje($tsData = null) {
	global $tsCore, $tsUser;
	
	// Esta activa la cuenta y no esta baneada?
	if($tsUser->is_member && $tsUser->info['user_baneado'] == 0 && $tsUser->info['user_activo'] == 1) {
	$espera = $tsCore->settings['date'] - ($tsUser->permisos['goaf'] * 5);# Tiempo para volver a escribir.
	
	// Los datos del mensaje.
	if(!is_array($tsData)) {
	$tsData = array(
	'msg_user' => $tsCore->setProtect(strtolower($_POST['usuario'])),
	'msg_title' => $tsCore->setProtect($tsCore->parse_censurar(empty($_POST['titulo']) ? '(untitled)' : $_POST['titulo'])),
	'msg_body' => $tsCore->setProtect($tsCore->parse_censurar($_POST['mensaje'])),
	'msg_ip' => $tsCore->setProtect($tsCore->settings['ip']),
	'msg_date' => $tsCore->setProtect($tsCore->settings['date']),
	);
	//
	}
	
	// Validamos campos antes de enviar.
	if(!$tsData['msg_user']) 
	return '0: Add the user you are sending the message to..';
	
	elseif(strlen($tsData['msg_title']) > 50) 
	return '0: Sorry but the title field should not exceed 50 characters..';
		
	elseif(!$tsData['msg_body'] || str_replace(array("\n","\t",' '), '', $tsData['msg_body']) == '') 
	return '0: Remember to write a message before you send it..';
	
	elseif(strlen($tsData['msg_body']) > 1000) 
	return '0: Sorry but the message field must not exceed 1000 characters..';

	// Validamos el tiempo si ya se envio un mensaje ahorita.
	$is_now = anaK('num_rows', anaK('query', 'SELECT mp_id FROM '.$tsCore->table['14'].' WHERE (mp_date > \''.$espera.'\' && mp_from = \''.$tsCore->setProtect($tsUser->uid).'\') OR (mp_date > \''.($espera * 3600).'\' && mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' && mp_subject = \''.$tsData['msg_title'].'\' && mp_preview = \''.$tsData['msg_body'].'\') ORDER BY mp_id DESC LIMIT 1', array(__FILE__, __LINE__)));
	//
	if($is_now) return '0: Wait '.($tsUser->permisos['goaf'] * 5).' seconds to retry.';
	
	// AntiFlood.
	$tsUser->anti_flood(true, 'mps');
	// El usuario al que le enviaremos existe?
	$info = $tsUser->user_data($tsData['msg_user'], 2);
	//
	if($info['user_id'] && $tsData['msg_body']) {
	// Esta bloqueado?
	if(!$tsUser->is_admod) {
	$is_block = anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE (b_author = \''.$tsCore->setProtect($info['user_id']).'\' && b_value = \''.$tsCore->setProtect($tsUser->uid).'\' && b_type = \'2\') || (b_author = \''.$tsCore->setProtect($tsUser->uid).'\' && b_value = \''.$tsCore->setProtect($info['user_id']).'\' && b_type = \'2\') LIMIT 1', array(__FILE__, __LINE__)));
	
	if($is_block == 1) return '0: We\'re sorry, but you cannot send messages to '.$info['user_nick'];
	//
	}
	
	// El usuario esta baneado o sin validar la cuenta?
	if($info['user_activo'] != 0 && $info['user_baneado'] != 1) {
	
	// Sigo al usuario y el me sigue?
	$follow = $tsUser->is_follow($info['user_id'], 5);
	
	// Si no es admin o moderador ejecutamos esta orden.
	if($info['user_rango'] > 2) {
	// Extraemos los datos de configuracion del usuario.
	$info['conf'] = unserialize($info['p_config']);
	// Comprobamos si tiene los permisos necesarios.
	switch((int)$info['conf']['rmp']) {
	case '0':
	case '8':
	//<--
	if($info['conf']['rmp'] == 0 && !$tsUser->is_admod) {
	return '0: I\'m sorry, but '.$info['user_nick'].' cannot receive messages.';
	
	} elseif($info['conf']['rmp'] == 8 && $tsUser->is_admod)
	return '0: I\'m sorry, but '.$info['user_nick'].' you cannot use the message service at this time.';
	//-->
	break;
	
	case '1':
	case '2':
	case '3':
	case '4':
	//<--
	if($follow['me_sigue'] == 0 && $follow['lo_sigo'] == 0) $together = false; 
	else $together = true;
	
	if($follow['me_sigue'] == 1 && $follow['lo_sigo'] == 1) $together = true; 
	else $together = false;
	
	if($info['conf']['rmp'] == 1 && !$together && !$tsUser->is_admod) {
	return '0: I\'m sorry, but you and '.$info['user_nick'].' must be followed to send you a message.';
	
	} elseif($info['conf']['rmp'] == 2 && !$together && !$tsUser->is_admod) {
	return '0: I\'m sorry, but you and '.$info['user_nick'].' must be followed to send you a message.';
	
	} elseif($info['conf']['rmp'] == 3 && !$follow['lo_sigo'] && !$tsUser->is_admod) {
	return '0: Hey, I\'m sorry, but you have to follow '.$info['user_nick'].' to be able to send messages.';
	
	} elseif($info['conf']['rmp'] == 4 && !$follow['me_sigue'] && !$tsUser->is_admod)
	return '0: Eh! '.$info['user_nick'].' must follow you so you can send him messages.';
	//-->
	break;
	}
	//
	}
	
	// Si todo esta bien agregamos el mensaje.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['14'].' (mp_to, mp_from, mp_subject, mp_preview, mp_date) VALUES (\''.$tsCore->setProtect($info['user_id']).'\', \''.$tsCore->setProtect($tsUser->uid).'\', \''.$tsData['msg_title'].'\', \''.substr($tsData['msg_body'], 0, 75).'\', \''.$tsData['msg_date'].'\')', array(__FILE__, __LINE__))) {
	
	// El ID del mensaje para respuesta.
	$mp_id = anaK('insert_id', array(__FILE__, __LINE__));
	if(!$mp_id) return '0: This action cannot be executed. Error code: MS001/NW';
	
	// Agregamos la respuesta al mensaje.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['15'].' (mp_id, mr_from, mr_body, mr_ip, mr_date) VALUES (\''.$tsCore->setProtect($mp_id).'\', \''.$tsCore->setProtect($tsUser->uid).'\', \''.$tsData['msg_body'].'\', \''.$tsData['msg_ip'].'\', \''.$tsData['msg_date'].'\')', array(__FILE__, __LINE__))) {
	
	// Indicamos que el mensaje fue enviado.
	return '1: Perfect your message has been successfully sent to '.$info['user_nick'];
	//	
	} else return '0: Code error. '.anaK('error', array(__FILE__, __LINE__));
	
	} else return '0: There\'s been a mistake. Try again..';
	//
	} else return '0: This user cannot receive new messages.';
	//
	} else return '0: This user does not exist, please try again or check.';
	//
	} else return '0: Sorry, but your account must be active to send messages.';
	//
	}
	
	
	#04. NUEVA RESPUESTA
	function new_respuesta() {
	global $tsCore, $tsUser;
	
	$tsData = array(
	'msg_id' => $tsCore->setProtect((int)$_POST['id']),
	'msg_respuesta' => $tsCore->setProtect($tsCore->parse_censurar($_POST['mensaje'])),
	'msg_ip' => $tsCore->setProtect($tsCore->settings['ip']),
	'msg_date' => $tsCore->setProtect($tsCore->settings['date']),
	);
	
	if(!$tsData['msg_id']) 
	return array('type' => '0: This action cannot be executed. Error code: MS002/NR');
	
	elseif(!$tsData['msg_respuesta'] || strlen($tsData['msg_respuesta']) < 2) 
	return array('type' => '0: Please write a message before sending it..');
	
	$query = anaK('query', 'SELECT mp_id, mp_to, mp_from, mp_answer FROM '.$tsCore->table['14'].' WHERE mp_id = \''.$tsData['msg_id'].'\' '.($tsUser->is_admod ? '' : '&& mp_del_to = \'0\' && mp_del_from = \'0\'').' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);

	if($data['mp_id']) {
	// AntiFlood.
	$tsUser->anti_flood(true, 'mps');
	
	// Esta bloqueado?
	if(!$tsUser->is_admod) {
	$is_block = anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].' WHERE (b_author = \''.$tsCore->setProtect($data['mp_to']).'\' && b_value = \''.$tsCore->setProtect($data['mp_from']).'\' && b_type = \'2\') || (b_author = \''.$tsCore->setProtect($data['mp_from']).'\' && b_value = \''.$tsCore->setProtect($data['mp_to']).'\' && b_type = \'2\') LIMIT 1', array(__FILE__, __LINE__)));
	
	if($is_block == 1) return array('type' => '0: We\'re sorry but you can\'t reply to this message..');
	//
	}
	
	// Agregamos la respuesta.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['15'].' (mp_id, mr_from, mr_body, mr_ip, mr_date) VALUES (\''.$tsData['msg_id'].'\', \''.$tsCore->setProtect($tsUser->uid).'\', \''.$tsData['msg_respuesta'].'\', \''.$tsData['msg_ip'].'\', \''.$tsData['msg_date'].'\')', array(__FILE__, __LINE__))) {
	
	// Actualizamos datos cuando responda el destinatatio.
	if($data['mp_from'] != $tsUser->uid) {
    if($data['mp_answer'] == 0) $update = ', mp_answer = \'1\'';
	$update .= ', mp_read_to = \'1\', mp_read_mon_to = \'2\'';
    $update .= ', mp_read_from = \'0\', mp_read_mon_from = \'0\'';
    $update .= ', mp_del_from = \'0\'';
	} else {
    $update .= ', mp_read_to = \'0\', mp_read_mon_to = \'0\'';
    $update .= ', mp_read_from = \'1\', mp_read_mon_from = \'2\'';
    $update .= ', mp_del_to = \'0\'';
    }
	
	// Actualizamos el mensaje.
    anaK('query', 'UPDATE '.$tsCore->table['14'].' SET mp_preview = \''.substr($tsData['msg_respuesta'], 0, 75).'\', mp_date = \''.$tsData['msg_date'].'\' '.$update.' WHERE mp_id = '.$tsData['msg_id'], array(__FILE__, __LINE__));
	
	// Pasamos datos.
	$info = array(
	'mp_date' => $tsData['msg_date'],
	'mp_ip' => $tsData['msg_ip'],
	'mp_body' => $tsCore->parse_BBCode(str_replace(array("\\n", "\\t"), '<br>', $tsData['msg_respuesta']), 'news'),
	);
	//
	return array('type' => '1: ', 'data' => $info);
	}
	//
	} else return array('type' => '0: Sorry but the message doesn\'t exist or was already deleted.');
	//	
	}
	
	
	#05. EDITAR MENSAJE
	function editar_mensajes() {
	global $tsCore, $tsUser;
	
	$id_msg = $tsCore->setProtect((int)$_POST['id']);
	$type = $tsCore->setProtect($_POST['act']);
	$is_load = $tsCore->setProtect((int)$_POST['stat']);	
	
	if(!$id_msg) return '0: This action cannot be executed. Error code: ML003/ED';
	elseif(!$type) return '0: Sorry you must select some kind of action..';
	elseif(!anaK('num_rows', anaK('query', 'SELECT mp_id FROM '.$tsCore->table['14'].' WHERE mp_id = \''.$id_msg.'\' LIMIT 1', array(__FILE__, __LINE__)))) return '0: Sorry but the message doesn\'t exist or was already deleted.';
	
	switch($type) {
	case 'load':
	//<--
	if($is_load == 1) {
	// Marcar mensaje como leido.
	$data_1 = 'mp_read_to = \'1\', mp_read_mon_to = \'2\'';
    $data_2 = 'mp_read_from = \'1\', mp_read_mon_from = \'2\'';
	 
	} else {
	// Marcar mensaje como no leido.	
	$data_1 = 'mp_read_to = \'0\', mp_read_mon_to = \'1\'';
	$data_2 = 'mp_read_from = \'0\', mp_read_mon_from = \'1\'';	
	}
	
	// Actualizamos datos para el que recibe.
	if(anaK('query', 'UPDATE '.$tsCore->table['14'].' SET '.$data_1.' WHERE mp_id = \''.$id_msg.'\' AND mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' LIMIT 1', array(__FILE__, __LINE__))) {
	
	// Actualizamos datos para el que lo envio.
	anaK('query', 'UPDATE '.$tsCore->table['14'].' SET '.$data_2.' WHERE mp_id = \''.$id_msg.'\' AND mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' LIMIT 1', array(__FILE__, __LINE__));
	
	return '1: Changes have been made..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//-->
	break;
	
	case 'del':
	//<-- Actualizamos datos para el que recibe.
	anaK('query', 'UPDATE '.$tsCore->table['14'].' SET mp_del_to = \'1\' WHERE mp_id = \''.$id_msg.'\' AND mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' LIMIT 1', array(__FILE__, __LINE__));
	
	// Actualizamos datos para el que lo envio.
    anaK('query', 'UPDATE '.$tsCore->table['14'].' SET mp_del_from = \'1\' WHERE mp_id = \''.$id_msg.'\' AND mp_from = \''.$tsCore->setProtect($tsUser->uid).'\' LIMIT 1', array(__FILE__, __LINE__));
	
	// Verificamos si los dos han dado la orden de eliminar el mensaje.
	$query = anaK('query', 'SELECT mp_id FROM '.$tsCore->table['14'].' WHERE (mp_to = \''.$tsCore->setProtect($tsUser->uid).'\' OR mp_from = \''.$tsCore->setProtect($tsUser->uid).'\') AND mp_del_to = \'1\' && mp_del_from = \'1\'', array(__FILE__, __LINE__));
	
	while($row = anaK('fetch_assoc', $query)) {
	// Si todo esta bien eliminamos los mensajes.
	if(anaK('query', 'DELETE FROM '.$tsCore->table['14'].' WHERE mp_id = '.$tsCore->setProtect($row['mp_id']), array(__FILE__, __LINE__))) {
	
	// Y eliminamos las respuestas de esos mensajes.
	anaK('query', 'DELETE FROM '.$tsCore->table['15'].' WHERE mp_id = '.$tsCore->setProtect($row['mp_id']), array(__FILE__, __LINE__));
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	return '1: Message has been deleted..';
	//-->
	break;
	}
	//
	}

}
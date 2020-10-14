<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: c.monitor.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

# TODAS LAS FUNCIONES:
#01. make_monitor()
#02. set_notificacion()
#03. get_notificaciones()
#04. arm_notificaciones()
#05. make_consulta()
#06. make_oracion()
#07. tools_notificacion()

class tsMonitor {
	public $notificaciones = 0;
	private $monitor = array();
	public $show_type = 1;
	
	function __construct() {
	global $tsCore, $tsUser;
	
	// No esta logueado? detenemos el script.
	if(empty($tsUser->is_member)) return false;
	
	// Cuantas notificaciones tenemos
	$query = anaK('query', 'SELECT 
	(SELECT COUNT(not_id) FROM '.$tsCore->table['13'].' WHERE user_id = \''.$tsCore->setProtect((int)$tsUser->uid).'\' AND not_menubar > 0) AS ntf_all', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	// Pasamos las notificaciones y alertas.
	$this->notificaciones = $data['ntf_all'];
	}
	
	
	#01. ORACIONES PARA MONITOR
	private function make_monitor() {
	$this->monitor = array(
	'1' => array('text' => 'is following you', 'ln_text' => 'Follow this user', 'css' => 'follow'), 
	'2' => array('text' => 'it\'s his birthday <b>¡Wish him well for his day!</b>', 'css' => 'cake'),
	);
	//
	}
	
	
	#02. CREAR NOTIFICACION
	function set_notificacion($tsData = null) {
	global $tsCore, $tsUser;
	
	if(empty(is_array($tsData))) return false;
	$notData = array(
	'not_type' => $tsCore->setProtect((int)$tsData['type']), 
	'user_id' => $tsCore->setProtect((int)$tsData['user_id']), 
	'obj_user' => $tsCore->setProtect((int)$tsData['obj_user']), 
	'obj_uno' => $tsCore->setProtect((int)($tsData['obj_uno']) ? $tsData['obj_uno'] : 0),
	'obj_dos' => $tsCore->setProtect((int)($tsData['obj_dos']) ? $tsData['obj_dos'] : 0),
	'obj_tres' => $tsCore->setProtect((int)($tsData['obj_tres']) ? $tsData['obj_tres'] : 0),
	'not_date' => $tsCore->setProtect($tsCore->settings['date']),
	);
	
	if(empty($notData['not_type']) || empty($notData['user_id']) || empty($notData['obj_user'])) return false;
	
	// No se mostrara mi propia actividad.
	if($notData['user_id'] != $tsUser->uid) {
	// El usuario admite notificaciones de este tipo? $notData['not_type']
	$userData = $tsUser->user_data($notData['user_id'], 1);
    $filtro_id = 'f'.$notData['not_type'];
    $filtros = unserialize($userData['p_monitor']);
    // No admite este tipo de notificacion? detenemos el script!!
	if($filtros[$filtro_id] == 1) return false;
	
	// Las notificaciones del mismo tipo y en poco tiempo tenemos?
	$not_data = anaK('fetch_assoc', anaK('query', 'SELECT not_id FROM '.$tsCore->table['13'].' WHERE user_id = \''.$notData['user_id'].'\' && obj_uno = \''.$notData['obj_uno'].'\' && obj_dos = \''.$notData['obj_dos'].'\' && not_type = \''.$notData['not_type'].'\' && not_date > ('.$notData['not_date'].' - 3600) && not_menubar > \'0\' ORDER BY not_id DESC LIMIT 1', array(__FILE__, __LINE__)));
	
	// Existe alguna notificacion? si existe la actualizamos sino la creamos.
	if(!empty($not_data['not_id']) && $notData['not_type'] != 4) $not_db_type = 'update';
	else $not_db_type = 'insert';
	
	// Verificamos el limite de notificaciones.
	$query = anaK('query', 'SELECT not_id FROM '.$tsCore->table['13'].' WHERE user_id = \''.$notData['user_id'].'\' ORDER BY not_id DESC', array(__FILE__, __LINE__));
	$data = result_array($query);
	$ntotal = count($data);
	$delid = $data[$ntotal-1]['not_id'];// ID de ultima notificacion.
	
	// Eliminamos la ultima notificacion.
	if($ntotal > $tsCore->settings['max_ntfs']) {
	anaK('query', 'DELETE FROM '.$tsCore->table['13'].' WHERE not_id = '.$tsCore->setProtect((int)$delid), array(__FILE__, __LINE__));
	}
	
	// Actualizamos|agregamos la notificacion.
	switch($not_db_type) {
	case 'update':
	//<--
	if(anaK('query', 'UPDATE '.$tsCore->table['13'].' SET obj_user = \''.$notData['obj_user'].'\', not_date = \''.$notData['not_date'].'\', not_total = (not_total + 1) WHERE not_id = '.$tsCore->setProtect((int)$not_data['not_id']), array(__FILE__, __LINE__))) return true;
	//-->
	break;
	case 'insert':
	//<--
	if(anaK('query', 'INSERT INTO '.$tsCore->table['13'].' (user_id, obj_user, obj_uno, obj_dos, obj_tres, not_type, not_date) VALUES (\''.$notData['user_id'].'\', \''.$notData['obj_user'].'\', \''.$notData['obj_uno'].'\', \''.$notData['obj_dos'].'\', \''.$notData['obj_tres'].'\', \''.$notData['not_type'].'\', \''.$notData['not_date'].'\')', array(__FILE__, __LINE__))) return true;
	//-->
	break;
	}
	//
	}
	//
	}
	
	
	#03. MOSTRAMOS NOTIFICACIONES EN MONITOR
	function get_notificaciones($unread = false) {
	global $tsCore, $tsUser;
	
	// Hay mas de 5 notificaciones no leidas? las mostramos.
	if($this->show_type == 1) {
	// Tipo de vista.
	$not_view = ($unread == true) ? '= \'2\'' : ' > 0';
    $not_del = ($unread == true) ? 1 : 0;
	
	if($this->notificaciones > 5 || $unread == true) {
	// Preparamos la consulta.
	$select = 'SELECT m.*, u.user_nick AS usuario FROM '.$tsCore->table['13'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON u.user_id = m.obj_user WHERE m.user_id = \''.$tsCore->setProtect((int)$tsUser->uid).'\' AND m.not_menubar '.$not_view.' ORDER BY m.not_id DESC';
	} else {
    // Preparamos la consulta.
    $select = 'SELECT m.*, u.user_nick AS usuario FROM '.$tsCore->table['13'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON u.user_id = m.obj_user WHERE m.user_id = \''.$tsCore->setProtect((int)$tsUser->uid).'\' ORDER BY m.not_id DESC LIMIT 5';
    }
	
	// Si va al monitor entonces actualizamos para que ya no se vean en la barra del menu.
	} elseif($this->show_type == 2) {
	// Preparamos la consulta.
	$select = 'SELECT m.*, u.user_nick AS usuario FROM '.$tsCore->table['13'].' AS m LEFT JOIN '.$tsCore->table['08'].' AS u ON u.user_id = m.obj_user WHERE m.user_id = \''.$tsCore->setProtect((int)$tsUser->uid).'\' ORDER BY m.not_id DESC';
	//	
	}
	// Ejecutamos la consulta.
	$query = anaK('query', $select, array(__FILE__, __LINE__));
	$data = result_array($query);
	
	// Actualizamos datos de las notificaciones.
	if($this->show_type == 1) anaK('query', 'UPDATE '.$tsCore->table['13'].' SET not_menubar = \''.$tsCore->setProtect((int)$not_del).'\' WHERE user_id = \''.$tsCore->setProtect((int)$tsUser->uid).'\' && not_menubar > 0', array(__FILE__, __LINE__));
	
	else anaK('query', 'UPDATE '.$tsCore->table['13'].' SET not_menubar = \'0\', not_monitor = \'0\' WHERE user_id = \''.$tsCore->setProtect((int)$tsUser->uid).'\' && not_monitor = \'1\'', array(__FILE__, __LINE__));
	
	// Armamos textos, link para las notificaciones.
	$datos['data'] = $this->arm_notificaciones($data);
	// Todas las notificaciones.
	$datos['total'] = (is_array($datos['data'])) ? count($datos['data']) : 0;
	//
	return $datos;
	}
	
	
	#04. ARMAMOS NOTIFICACIONES
	private function arm_notificaciones($obj) {
	// Si no se recibe ningun dato detenemos el script.
	if(!is_array($obj)) return false;
	// Armamos oraciones.
    $this->make_monitor();
	// Crear una consulta para cada valor.
	foreach($obj as $key => $val) {
	// Crear consulta.
	$select = $this->make_consulta($val);
	// Consultemos.
	if(is_array($select)) {
	$data = $select;	
	} else {
	$query = anaK('query', $select, array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);	
	}
	$data = array_merge($data, $val);
	// Si aun existe lo que vamos a notificar..
    if($data) $result[] = $this->make_oracion($data);
	}
	//
	return $result;
	}
	
	
	#05. RETORNA UNA CONSULTA DEPENDIENDO EL TIPO DE NOTIFICACION
	function make_consulta($obj) {
	global $tsCore, $tsUser;
	// Seleccionamos la consulta apropiada.
	switch((int)$obj['not_type']) {
	case '1':
	//<-- Lo estamos siguiendo?
    $data = $tsUser->is_follow($obj['obj_user'], 1);
	return array('follow' => $data);
	//-->
	break;
	case '2':
	//<-- Algun usuario de cumpleaños? pasamos los datos.
	$data['user_nick'] = $tsUser->user_data($obj['obj_uno'], 1);
	return $data;
	//-->
	break;
	}
	//
	}
	
	
	#06. RETORNA UNA ORACION EN MONITOR
	private function make_oracion($obj) {
	global $tsCore, $tsUser;
	
	$no_type = (int)$obj['not_type'];
	$txt_extra = ($this->show_type == 1) ? '' : ' '.$this->monitor[$no_type]['ln_text'];
	$ln_text = $this->monitor[$no_type]['ln_text'];
	$ln_text = is_array($ln_text) ? $ln_text[$obj['obj_dos']-1] : $ln_text;
	//
	$oracion['nid'] = $obj['not_id'];
	$oracion['unread'] = ($this->show_type == 1) ? $obj['not_menubar'] : $obj['not_monitor'];
	$oracion['style'] = $this->monitor[$no_type]['css'];
	$oracion['date'] = $obj['not_date'];
	$oracion['user'] = $obj['usuario'];
	$oracion['avatar'] = $obj['obj_user'].'_50.jpg';
	$oracion['total'] = $obj['not_total'];
	
	// Seleccionamos la oracion que vamos a construir.
	switch($no_type) {
	case '1':
	//<-- Lo estamos siguiendo?
	$oracion['text'] = $this->monitor[$no_type]['text'];
	$oracion['link'] = $tsCore->settings['url'].'/perfil/'.$obj['usuario'];
	if($obj['follow'] != true && $this->show_type == 2) {
	$oracion['getdata'] = 'id="t2-user" act="2" pid="'.$obj['obj_user'].'"';
	$oracion['link'] = "javascript:location.reload();";
    $oracion['ltext'] = $this->monitor[$no_type]['ln_text'];    
    }
	//-->
	break;
	case '2':
	//<-- Hay usuarios de cumpleaños? mostramos las notificaciones.
	$oracion['text'] = $this->monitor[$no_type]['text'];
	$oracion['link'] = $tsCore->settings['url'].'/mensajes/nuevo/?user='.$obj['usuario'];
	//-->
	break;
	}
	return $oracion;
	}
	
	
	#07. HERRAMIENTAS PARA NOTIFICACIONES
	function tools_notificacion() {
	global $tsCore, $tsUser;
	
	$tsData = array(
	'nid' => $tsCore->setProtect((int)$_POST['id']),
	'type' => $tsCore->setProtect($_POST['act']),
	'load' => $tsCore->setProtect((int)$_POST['stat']),
	);
	
	if($tsData['type'] != 'ntf' && $tsData['type'] != 'mon') {
	//
	if(!$tsData['nid']) return '0: This action cannot be executed. Error code: NF001/ID';
	elseif(!$tsData['type']) return '0: Sorry you must select some kind of action..';
	elseif(!anaK('num_rows', anaK('query', 'SELECT not_id FROM '.$tsCore->table['13'].' WHERE not_id = \''.$tsData['nid'].'\' AND user_id = \''.$tsCore->setProtect((int)$tsUser->uid).'\' LIMIT 1', array(__FILE__, __LINE__)))) return '0: Excuse me, but this notice does not exist or has already been removed..';
	}
	
	switch($tsData['type']) {
	case 'load':
	//<--
	if($tsData['load'] == 1) {
	// Marcar notificacion como no leida.
	$UPDATE = 'not_menubar = \'2\', not_monitor = \'1\'';
	$WHERE = 'AND not_monitor = \'0\'';
	} else {
	// Marcar notificacion como leida..
	$UPDATE = 'not_menubar = \'0\', not_monitor = \'0\'';
	$WHERE = 'AND not_monitor = \'1\'';	
	}

	if(anaK('query', 'UPDATE '.$tsCore->table['13'].' SET '.$UPDATE.' WHERE not_id = \''.$tsData['nid'].'\' AND user_id = \''.$tsCore->setProtect((int)$tsUser->uid).'\' '.$WHERE, array(__FILE__, __LINE__))) {
	return '1: The notification has been successfully updated..';	
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//-->
	break;
	case 'del':
	//<--
	if(anaK('query', 'DELETE FROM '.$tsCore->table['13'].' WHERE not_id = \''.$tsData['nid'].'\' AND user_id = '.$tsCore->setProtect((int)$tsUser->uid), array(__FILE__, __LINE__))) {
	return '1: Notification has been removed..';	
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//-->
	break;
	case 'ntf':
	case 'mon':
	//<-- Contamos si hay notificaciones para el usuario.
	if(anaK('num_rows', anaK('query', 'SELECT not_id FROM '.$tsCore->table['13'].' WHERE user_id = '.$tsCore->setProtect((int)$tsUser->uid), array(__FILE__, __LINE__)))) {
	// Si tiene notificaciones las marcamos como leidas..
	if(anaK('query', 'UPDATE '.$tsCore->table['13'].' SET not_menubar = \'0\', not_monitor = \'0\' WHERE user_id = '.$tsCore->setProtect((int)$tsUser->uid), array(__FILE__, __LINE__))) {
	return '1: Notifications have been marked as read..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//-->
	break;
	}
	//
	}

}
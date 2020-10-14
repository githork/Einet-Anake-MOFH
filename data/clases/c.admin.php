<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: c.admin.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

# TODAS LAS FUNCIONES:
#01. get_versiones()
#02. estadisticas_globales()
#03. get_admins()
#04. saved_config()
#05. get_temas()
#06. theme_tools()
#07. get_noticias()
#08. noticia_tools()
#09. noticia_agregar()
#10. save_api_config()
#11. get_blackList()
#12. blackList_tools()
#13. agregar_blackList()
#14. get_ipInfo()
#15. get_badwords()
#16. tools_planes()
#17. tools_nameservers()
#18. tools_resellers()

class tsAdmin {
    #01. VERSIONES
	function get_versiones() {
	// Version mysql.
	$mysql = anaK('fetch_row', anaK('query', 'SELECT VERSION()', array(__FILE__, __LINE__)));
	// GD Graphics Library version.
	$GD = @gd_info();
		
	$tsData = array(
	'php' => PHP_VERSION,
	'mysql' => $mysql,
	'server' => $_SERVER['SERVER_SOFTWARE'],
	'gd' => $GD['GD Version'],
	'ip' => $_SERVER['REMOTE_ADDR'],
	);
	//
	return $tsData;
	}
	
	
	#02. ESTADISTICAS
	function estadisticas_globales() {
	global $tsCore;
	
	$query = anaK('query', 'SELECT 
	(SELECT count(user_id) FROM '.$tsCore->table['08'].' WHERE user_activo = \'1\') AS users_activos,
	(SELECT count(user_id) FROM '.$tsCore->table['08'].' WHERE user_activo = \'0\') AS users_inactivos,
	(SELECT count(not_id) FROM '.$tsCore->table['02'].') AS noticias,
	(SELECT count(mp_id) FROM '.$tsCore->table['14'].') AS mensajes,
	(SELECT count(mp_id) FROM '.$tsCore->table['14'].' WHERE mp_del_to = \'1\') AS mensajes_de_eliminados,
    (SELECT count(mp_id) FROM '.$tsCore->table['14'].' WHERE mp_del_from = \'1\') AS mensajes_para_eliminados,
	(SELECT count(mr_id) FROM '.$tsCore->table['15'].') AS users_respuestas,
	(SELECT count(id) FROM '.$tsCore->table['06'].') AS suspendidos,
	(SELECT count(id) FROM '.$tsCore->table['03'].') AS blacklist, 
	(SELECT count(cp_id) FROM '.$tsCore->table['16'].' WHERE cp_active = \'0\') AS hosts_active, 
	(SELECT count(cp_id) FROM '.$tsCore->table['16'].' WHERE cp_active = \'1\') AS hosts_inactive', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	// Todos los usuarios.
	$data['all_visitas'] = $tsCore->settings['total_visitas'];
	$data['all_online'] = $tsCore->settings['total_online'];
	$data['all_users'] = ($data['users_activos'] + $data['users_inactivos']);
	$data['all_hosting'] = ($data['hosts_active'] + $data['hosts_inactive']);
	//
	return $data;
	}
	
	#03. ADMINISTRADORES
	function get_admins() {
	global $tsCore;
	$query = anaK('query', 'SELECT user_id, user_nick FROM '.$tsCore->table['08'].' WHERE user_rango = \'1\' && user_activo = \'1\' ORDER BY user_id', array(__FILE__, __LINE__));
	$data = result_array($query);
	return $data;	
	}
	
	
	#04. GUARDAR CONFIGURACIONES
	function saved_config() {
	global $tsCore, $tsUser;
	// Recibimos datos.
	$tsData = array(
	// SECCION 1
	'titulo' => $tsCore->setProtect($_POST['titulo']),
	'description' => $tsCore->setProtect($_POST['description']),
	'url' => $tsCore->setProtect($_POST['url']),
	'meta_desc' => $tsCore->setProtect($_POST['meta-desc']),
	'meta_tags' => $tsCore->setProtect($_POST['meta-tags']),
	'email_web' => $tsCore->setProtect($_POST['email-web']),
	'cod_type' => $tsCore->setProtect($_POST['cod']),
	'web_on' => (($_POST['web-on'] == 'on') ? 1 : 0),
	'web_mensaje' => $tsCore->setProtect($_POST['web-msg']),
	'web_over' => $tsCore->setProtect($_POST['web-over']),
	'email_active' => (($_POST['service-email'] == 'on') ? 0 : 1),
	'pub_active' => (($_POST['pub'] == 'on') ? 0 : 1),
	'val_cuenta' => (($_POST['val-cuenta'] == 'on') ? 1 : 0),
	'antiFlood' => (($_POST['flood'] == 'on') ? 0 : 1),
	'log_active' => (($_POST['log'] == 'on') ? 0 : 1),
	'reg_active' => (($_POST['reg'] == 'on') ? 0 : 1),
	'access_mod' => (($_POST['vista-mod'] == 'on') ? 1 : 0),
	'max_posts' => $tsCore->setProtect($_POST['max-posts']),
	'user_activo' => $tsCore->setProtect($_POST['online']),
	'reg_limit' => $tsCore->setProtect($_POST['limit-reg']),
	'allow_edad' => $tsCore->setProtect($_POST['allow-edad']),
	'bienvenida' => $tsCore->setProtect($_POST['welcome-type']),
	'bienvenida_msg' => $tsCore->setProtect($_POST['welcome-msg']),
	// SECCION 2
	'pub_300' => $tsCore->setProtect($_POST['pub-300']),
	'pub_468' => $tsCore->setProtect($_POST['pub-468']),
	'pub_160' => $tsCore->setProtect($_POST['pub-160']),
	'pub_728' => $tsCore->setProtect($_POST['pub-728']),
	// SECCION 4
	'live_active' => (($_POST['live'] == 'on') ? 1 : 0),
	'live_time' => intval($_POST['live-time'] * 60000),
	'live_hide' => intval($_POST['live-hide'] * 60000),
	// SECCION 5
	'cp_panel' => $tsCore->setProtect($_POST['cp-panel']),
	'client_domain' => $tsCore->setProtect($_POST['domain']),
	'client_ftp' => $tsCore->setProtect($_POST['ftp']),
	'client_cpanel' => $tsCore->setProtect($_POST['cpanel']),
	'client_sql' => $tsCore->setProtect($_POST['sql']),
	'client_mail' => $tsCore->setProtect($_POST['mail']),
	);
	
	// Actualizamos datos por secciones.
	switch((int)$_POST['step']) {
	case '1':
	//<--
	if(strlen($tsData['titulo']) < 4) 
	return '0: Write a name for your website is important to identify your site..';
	
	elseif(strlen($tsData['titulo']) > 50) 
	return '0: Your site name cannot exceed 50 characters..';
	
	elseif(strlen($tsData['description']) > 60) 
	return '0: The title of your site cannot exceed 60 characters..';
	
	elseif(!$tsData['url']) 
	return '0: Indicate the link of the directory where your website is located..';
	
	elseif(strlen($tsData['meta_desc']) > 300) 
	return '0: Keywords in description cannot exceed 300 characters..';
	
	elseif(strlen($tsData['meta_tags']) > 300) 
	return '0: Words in tags cannot exceed 300 characters..';
	
	elseif(!mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $tsData['email_web'])) 
	return '0: The email has no valid format..';
	
	elseif(strlen($tsData['email_web']) > 50) 
	return '0: The email must have a maximum of 50 characters..';
	
	elseif(!$tsData['cod_type']) 
	return '0: Select an encoding type for your website, enhance the characters in the text..';
	
	elseif(strlen($tsData['web_mensaje']) > 300) 
	return '0: The maintenance message cannot exceed 300 characters..';
	
	elseif(!is_numeric($tsData['web_over'])) 
	return '0: The estimated time must be in numbers, expressed in seconds..';
	
	elseif(strlen($tsData['web_over']) > 9) 
	return '0: The estimated time cannot exceed 9 characters..';
	
	elseif($tsData['max_posts'] < 1 || $tsData['max_posts'] > 99)
	return '0: Set a post limit for the website, between 1 minimum and 99 maximum in numbers..';
	
	elseif($tsData['user_activo'] < 1 || $tsData['user_activo'] > 99)
	return '0: Sets the time for online users. between 1 and 99 seconds in numbers..';
	
	elseif($tsData['reg_limit'] < 1 || $tsData['reg_limit'] > 9999999)
	return '0: Sets a limit on user registration between 1 and 9999999 in numbers..';
	
	elseif(!is_numeric($tsData['allow_edad'])) 
	return '0: The minimum age required must be expressed in numbers..';
	
	elseif(strlen($tsData['allow_edad']) > 2) 
	return '0: The minimum age required must not exceed 2 characters..';
	
	elseif(strlen($tsData['bienvenida_msg']) > 500) 
	return '0: The welcome message cannot exceed 500 characters..';
	
	elseif($tsUser->is_admod != 1) 
	return '0: Sorry but you do not have the necessary permissions to perform this action..';
	
	else {
	// Todo bien? continuamos..
	$activos = unserialize($tsCore->settings['activos']);// Extraemos datos.
	$activos['meta_desc'] = $tsData['meta_desc'];
	$activos['meta_tags'] = $tsData['meta_tags'];
	$activos['email_web'] = $tsData['email_web'];
	$activos['cod_type'] = $tsData['cod_type'];
	$activos['email_active'] = $tsData['email_active'];
	$activos['pub_active'] = $tsData['pub_active'];
	$activos['val_cuenta'] = $tsData['val_cuenta'];
	$activos['log_active'] = $tsData['log_active'];
	$activos['reg_active'] = $tsData['reg_active'];
	$activos['allow_edad'] = $tsData['allow_edad'];
	$activos['max_posts'] = $tsData['max_posts'];
	$activos['reg_limit'] = $tsData['reg_limit'];
	$activos = serialize($activos);// Unimos todo de nuevo.
	
	// Verificamos el url del diretorio.
	if(strcasecmp($tsData['url'], $tsCore->settings['url']) != 0) {
	// Explodeamos el directorio.
	$dir_act = explode('/', $tsCore->settings['url']);	
	$old_end = '/'.$dir_act[(count($dir_act)-1)];// Final del url.
	// Explodeamos el directorio nuevo
	$dir_new = explode('/', $tsData['url']);
	$new_end = '/'.$dir_new[(count($dir_new)-1)];// Final del url.
	// Todo bien? leemos vars.php
	$load_php = file_get_contents(EA_ROOT.'/vars.php');
	// Existe? hacemos una copia de seguridad.
	if($load_php) copy(EA_ROOT.'/vars.php', EA_ROOT.'/vars-backup.php');
	// Actualizamos el url del directorio.
	$link_old = 'define(\'EA_URL\', $is_ssl.$_SERVER[\'HTTP_HOST\'].\''.$old_end.'\');';
	$link_new = 'define(\'EA_URL\', $is_ssl.$_SERVER[\'HTTP_HOST\'].\''.$new_end.'\');';
	// Remplazamos las lineas del archivo.
	$load_php = str_replace($link_old, $link_new, $load_php);
	// Guardamos el achivo y borramos el respaldo.
	if(file_put_contents(EA_ROOT.'/vars.php', $load_php) > 1) $tsCore->delete_folder(EA_ROOT.'/vars-backup.php', 1);
	//
	}
	
	// Datos que vamos actualizar.
	$array = array(
	'antiFlood' => $tsData['antiFlood'], 
	'titulo' => $tsData['titulo'], 
	'description' => $tsData['description'],
	'user_activo' => $tsData['user_activo'], 
	'web_on' => $tsData['web_on'], 
	'web_mensaje' => $tsData['web_mensaje'],
	'web_over' => (($tsData['web_over'] > 0) ? ($tsCore->settings['date'] + $tsData['web_over']) : 0),
	'access_mod' => $tsData['access_mod'],
	'bienvenida' => $tsData['bienvenida'], 
	'bienvenida_msg' => $tsData['bienvenida_msg'],
	);
	//
	$UPDATE  = $tsCore->get_value($array);
	$UPDATE .= ($tsCore->is_serialized($activos) == 1) ? ', activos = \''.$activos.'\'' : '';
	}
	//-->
	break;
	case '2':
	//<--
	if($tsUser->is_admod != 1) return '0: Sorry but you do not have the necessary permissions to perform this action..';
	else $UPDATE = 'pub_300 = \''.html_entity_decode($tsData['pub_300']).'\', pub_468 = \''.html_entity_decode($tsData['pub_468']).'\', pub_160 = \''.html_entity_decode($tsData['pub_160']).'\', pub_728 = \''.html_entity_decode($tsData['pub_728']).'\'';
	//-->
	break;
	case '4':
	//<--
	if(strlen($tsData['live_time']) < 4) 
	return '0: You must set a numeric value in update live..';
	
	if(strlen($tsData['live_hide']) < 4) 
	return '0: You must set a numerical value in hide live..';
	
	elseif($tsUser->is_admod != 1) 
	return '0: Sorry but you do not have the necessary permissions to perform this action..';
	
	else {
	// Todo bien? Continuamos..
	$activos = unserialize($tsCore->settings['activos']);// Extraemos datos.
	$activos['live_active'] = $tsData['live_active'];
	$activos['live_time'] = $tsData['live_time'];
	$activos['live_hide'] = $tsData['live_hide'];
	$activos = serialize($activos);// Unimos todo de nuevo.
	//
	$UPDATE = ($tsCore->is_serialized($activos) == 1) ? 'activos = \''.$activos.'\'' : '';
	}
	//-->
	break;
	case '5':
	//<--
	if(strlen($tsData['cp_panel']) > 60) 
	return '0: The url of the reseller server must not exceed 60 characters..';
	
	elseif(strlen($tsData['client_domain']) > 60) 
	return '0: The domain url for customers must not exceed 60 characters..';
	
	elseif(strlen($tsData['client_ftp']) > 60) 
	return '0: The FTP url for clients should not exceed 60 characters..';
	
	elseif(strlen($tsData['client_cpanel']) > 60) 
	return '0: The cPanel url for customers should not exceed 60 characters..';
	
	elseif(strlen($tsData['client_sql']) > 60) 
	return '0: The MySQL url for clients should not exceed 60 characters..';
	
	elseif(strlen($tsData['client_mail']) > 60) 
	return '0: The Web Mail url for clients should not exceed 60 characters..';
	
	else {
	// Todo bien? continuamos..
	$cpanel = unserialize($tsCore->settings['cp']);// Extraemos datos.
	$cpanel['cp_panel'] = $tsData['cp_panel'];
	$cpanel['domain'] = $tsData['client_domain'];
	$cpanel['ftp'] = $tsData['client_ftp'];
	$cpanel['cpanel'] = $tsData['client_cpanel'];
	$cpanel['sql'] = $tsData['client_sql'];
	$cpanel['mail'] = $tsData['client_mail'];
	$cpanel = serialize($cpanel);// Unimos todo de nuevo.
	//
	$UPDATE = ($tsCore->is_serialized($cpanel) == 1) ? 'cp = \''.$cpanel.'\'' : '';
	}
	//-->
	break;
	}
	
	if(is_null($UPDATE) ===  false) {
	// Todo en orden? si es asi guardamos datos.
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET '.$UPDATE.' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: Perfect all settings have been successfully saved..';
	
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	
	
	#05. TODOS LOS TEMAS|TEMA
	function get_temas($obj = null) {
	global $tsCore;
	switch((int)$obj['type']) {
	case '':// Todos los temas.
	default:
	//<--
	$query = anaK('query', 'SELECT * FROM '.$tsCore->table['01'].' WHERE t_id > 0', array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
	if(is_file(EA_ROOT.'/temas/'.$row['t_path'].'/cover.png')) $row['t_cover'] = $tsCore->settings['url'].'/temas/'.$row['t_path'].'/cover.png';
	else $row['t_cover'] = $tsCore->settings['url'].'/default/cover.png';
	$data[] = $row;
	}
	break;
	case '1':// Un tema.
	$query = anaK('query', 'SELECT * FROM '.$tsCore->table['01'].' WHERE t_id = \''.(int)$obj['t_id'].'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	break;
	}
	//
	return $data;
	}
	
	
	#06. HERRAMIENTAS TEMAS
	function theme_tools() {
	global $tsCore, $tsUser;	
	
	$tsData = array(
	'tema_id' => $tsCore->setProtect((int)$_POST['tid']),
	'tema_nombre' => $tsCore->setProtect($_POST['name']),
	'tema_link' => $tsCore->setProtect(str_replace(' ', '_', strtolower($_POST['link']))),
	'tema_path' =>$tsCore->setProtect(str_replace(' ', '_', explode('/temas/', strtolower($_POST['link']))[1])),
	'tema_type' => $tsCore->setProtect((int)$_POST['type']),
	);
	if(!$tsData['tema_id']) return '0: This action cannot be executed. Error code: CT001T/ID';
	elseif(!$tsData['tema_type']) return '0: This action cannot be executed. Error code: CT002/TTP';
	
	// El tema existe?
	$query = anaK('query', 'SELECT * FROM '.$tsCore->table['01'].' WHERE t_id = \''.$tsData['tema_id'].'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	// Tiene permisos?
	if($tsUser->is_admod != 1) 
	return '0: Sorry but you do not have the necessary permissions to perform this action..';
	
	if($data['t_id'] > 0) {
	// Todo bien? continuamos.
	switch($tsData['tema_type']) {	
	case '1':// Cambiar tema
	//<--
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET tema_id = \''.$data['t_id'].'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	// Limpiamos el directorio, cache.
	$tsCore->delete_files(EA_ROOT.'/cache/');
	return '1: Perfect the subject has been changed..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//-->
	break;
	case '2':// Guardar cambios
	//<--
	if(!$tsData['tema_nombre']) 
	return '0: Remember to add a name to theme..';
	
	elseif(strlen($tsData['tema_nombre']) > 15) 
	return '0: The theme name should not exceed 15 characters..';
	
	elseif(!$tsData['tema_link']) 
	return '0: The theme directory can\'t be empty, fill it out!..';
	
	elseif(strlen($tsData['tema_link']) > 18) 
	return '0: The theme directory cannot exceed 18 characters..';
	
	// Todo bien? continuamos.
	if(anaK('query', 'UPDATE '.$tsCore->table['01'].' SET t_name = \''.$tsData['tema_nombre'].'\', t_url = \''.$tsData['tema_link'].'\', t_path = \''.$tsData['tema_path'].'\' WHERE t_id = '.$data['t_id'], array(__FILE__, __LINE__))) {
	// Renombramos la carpeta del tema.
	rename(EA_ROOT.'/temas/'.$data['t_path'], EA_ROOT.'/temas/'.$tsData['tema_path']);
	
	return '1: The theme configuration has been successfully updated..';
	//
	} else return '0: Error when trying to update topic information \''.$data['t_name'].'\'..';
	//-->
	break;
	case '3':// Eliminar tema
	//<--
	if($tsCore->settings['tema_id'] == $data['t_id']) 
	return '0: Sorry but you can\'t delete theme you\'re using..';
	
	if(anaK('query', 'DELETE FROM '.$tsCore->table['01'].' WHERE t_id = '.$data['t_id'], array(__FILE__, __LINE__))) {
	// Eliminamos la carpeta del tema.
	$tsCore->delete_folder(EA_ROOT.'/temas/'.$data['t_path']);
	return '1: Perfect the subject has been successfully removed..';
	//
	} return '0: Error while trying to remove this topic, please try again..';
	//-->
	break;
	}
	//
	} else return '0: Sorry but the subject does not exist or has already been removed..';
	//
	}
	
	
	#07. NOTICIAS ADMIN
	function get_noticias($obj = null) {
	global $tsCore;
	
	switch((int)$obj['type']) {
	case '':// Todas.
    default:
	//<--
    $todas = anaK('num_rows', anaK('query', 'SELECT not_id FROM '.$tsCore->table['02'], array(__FILE__, __LINE__)));
    $page = $tsCore->getPag($todas, $tsCore->settings['max_posts']); 
	
	$query = anaK('query', 'SELECT u.user_id, u.user_nick, n.* FROM '.$tsCore->table['02'].' AS n LEFT JOIN '.$tsCore->table['08'].' AS u ON n.not_autor = u.user_id ORDER BY n.not_date DESC LIMIT '.$page['limite'], array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
	$row['not_body'] = $tsCore->parse_BBCode($row['not_body'], 'news');
	$data[] = $row;
	}
	//-->
	break;
	case '1':// Una sola.
	//<--
	$query = anaK('query', 'SELECT * FROM '.$tsCore->table['02'].' WHERE not_id = \''.(int)$obj['not_id'].'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	//-->
	break;
	}
	//
	return array('data' => $data, 'page' => $page);
	}
	
	
	#08. HERRAMIENTAS NOTICIAS
	function noticia_tools() {
	global $tsCore, $tsUser;	
	
	$tsData = array(
	'not_id' => $tsCore->setProtect((int)$_POST['nid']),
	'not_msg' => $tsCore->setProtect($_POST['not-msg']),
	'not_type' => $tsCore->setProtect((int)$_POST['type']),
	);
	
	if(!$tsData['not_id']) 
	return '0: This action cannot be executed. Error code: NT001/NID';
	
	elseif(!$tsData['not_type']) 
	return '0: This action cannot be executed. Error code: NT002/NTP';
	
	// La noticia existe?
	$query = anaK('query', 'SELECT * FROM '.$tsCore->table['02'].' WHERE not_id = \''.$tsData['not_id'].'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	// Tiene permiso?
	if($tsUser->is_admod != 1) 
	return '0: Sorry but you do not have the necessary permissions to perform this action..';
	
	if($data['not_id'] > 0) {
	// Todo bien? continuamos.
	switch($tsData['not_type']) {
	case '1':// Activar|Desactivar noticia.
	//<--
	if($data['not_active'] == 1) {
	// Activada? desactivamos.
	if(anaK('query', 'UPDATE '.$tsCore->table['02'].' SET not_active = \'0\' WHERE not_id = \''.(int)$data['not_id'].'\' AND not_active = \'1\'', array(__FILE__, __LINE__))) { 
	return '1: Perfect the news has been Deactivated..';
	
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	} else {
	// Desactivada? activamos
	if(anaK('query', 'UPDATE '.$tsCore->table['02'].' SET not_active = \'1\' WHERE not_id = \''.(int)$data['not_id'].'\' AND not_active = \'0\'', array(__FILE__, __LINE__))) { 
	return '1: Perfect the news has been activated..';
	
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//		
	}
	//-->
	break;
	case '2':// Guardar noticia
	//<--
	if(!$tsData['not_msg']) 
	return '0: Sorry, but you have to write a message before you save it..';
	
	elseif(strlen($tsData['not_msg']) > 300) 
	return '0: Sorry but the message cannot exceed 300 characters..';
	
	// Actualizamos la noticia.
	if(anaK('query', 'UPDATE '.$tsCore->table['02'].' SET not_body = \''.$tsData['not_msg'].'\' WHERE not_id = '.(int)$data['not_id'], array(__FILE__, __LINE__))) {
	return '1: Great news has been successfully updated..';
	//	
	} else return '0: Sorry, an error occurred while trying to update the news, please try again..';
	//-->
	break;
	case '3':// Eliminar noticia
	//<--
	if(anaK('query', 'DELETE FROM '.$tsCore->table['02'].' WHERE not_id = '.(int)$data['not_id'], array(__FILE__, __LINE__))) {
	return '1: Perfect, the news has been successfully eliminated..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//-->
	break;
	}
	//
	} else return '0: Excuse me, but the news doesn\'t exist or has already been eliminated..';
	//
	}
	
	
	#09. AGREGAR NOTICIA
	function noticia_agregar() {
	global $tsCore, $tsUser;
	
	$tsData = array(
	'not_autor' => $tsCore->setProtect((int)$tsUser->uid),
	'not_msg' => $tsCore->setProtect($_POST['not-msg']),
	'not_active' => (($_POST['not-active'] == 'on') ? 1 : 0),
	'not_date' => $tsCore->setProtect($tsCore->settings['date']),
	);
	
	if(!$tsData['not_autor']) 
	return '0: This action cannot be executed. Error code: NW001/IDA';
	
	elseif($tsUser->is_admod != 1) 
	return '0: Sorry but you do not have the necessary permissions to perform this action..';
	
	elseif(!$tsData['not_msg']) 
	return '0: Sorry but you must write a message before adding the news..';
	
	elseif(strlen($tsData['not_msg']) > 300) 
	return '0: Sorry but the message cannot exceed 300 characters..';
	
	// Todo bien? continuamos.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['02'].' (not_body, not_autor, not_date, not_active) VALUES (\''.$tsData['not_msg'].'\', \''.$tsData['not_autor'].'\', \''.$tsData['not_date'].'\', \''.$tsData['not_active'].'\')', array(__FILE__, __LINE__))) {
	return '1: Perfect news has been successfully added..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	
	
	#10. GUARDAR CONFIG API
	function save_api_config() {
	global $tsCore, $tsUser;
	// Recibimos datos.
	$tsData = array(
	'fb_id' => $tsCore->setProtect($_POST['fb-id']),
	'fb_secret' => $tsCore->setProtect($_POST['fb-sec']),
	'fb_client' => $tsCore->setProtect($_POST['fb-client']),
	'fb_active' => (($_POST['fb-active'] == 'on') ? 0 : 1),
	'gl_id' => $tsCore->setProtect($_POST['gl-id']),
	'gl_secret' => $tsCore->setProtect($_POST['gl-sec']),
	'gl_active' => (($_POST['gl-active'] == 'on') ? 0 : 1),
	'tw_id' => $tsCore->setProtect($_POST['tw-id']),
	'tw_secret' => $tsCore->setProtect($_POST['tw-sec']),
	'tw_active' => (($_POST['tw-active'] == 'on') ? 0 : 1),
	'gi_id' => $tsCore->setProtect($_POST['gi-id']),
	'gi_secret' => $tsCore->setProtect($_POST['gi-sec']),
	'gi_active' => (($_POST['gi-active'] == 'on') ? 0 : 1),
	'wl_id' => $tsCore->setProtect($_POST['wl-id']),
	'wl_secret' => $tsCore->setProtect($_POST['wl-sec']),
	'wl_active' => (($_POST['wl-active'] == 'on') ? 0 : 1),
	'up_id' => $tsCore->setProtect($_POST['up-id']),
	'up_secret' => $tsCore->setProtect($_POST['up-sec']),
	'upload_server' => $tsCore->setProtect($_POST['up-svr']),
	'rp_id' => $tsCore->setProtect($_POST['rp-id']),
	'rp_secret' => $tsCore->setProtect($_POST['rp-sec']),
	'rp_name' => $tsCore->setProtect($_POST['rp-name']),
	'smtp_email' => $tsCore->setProtect($_POST['smtp-email']),
	'smtp_pass' => $tsCore->setProtect($tsCore->get_encrypt($_POST['smtp-pass'])),
	'smtp_host' => $tsCore->setProtect($_POST['smtp-host']),
	'smtp_port' => $tsCore->setProtect($_POST['smtp-port']),
	'smtp_secure' => $tsCore->setProtect($_POST['smtp-secure']),
	'smtp_debug' => $tsCore->setProtect($_POST['smtp-debug']),
	'smtp_cod' => $tsCore->setProtect($_POST['smtp-cod']),
	'smtp_auth' => (($_POST['smtp-auth'] == 'on') ? 1 : 0),
	'smtp_html' => (($_POST['smtp-html'] == 'on') ? 1 : 0),
	'captcha_active' => (($_POST['captcha'] == 'on') ? 0 : 1),
	'upload_type' => (($_POST['up-type'] == 'on') ? 1 : 0),
	'smtp_type' => (($_POST['smtp-type'] == 'on') ? 1 : 0),
	'step' => $tsCore->setProtect((int)$_POST['step']),
	);
	// Tiene los permisos necesarios?
	if($tsUser->is_admod != 1 && $tsData['step'] != 3) 
	return '0: Sorry but you do not have the necessary permissions to perform this action..';
	
	elseif(strlen($tsData['fb_id']) > 20) 
	return '0: The facebook API ID cannot exceed 20 characters..';
	
	elseif(strlen($tsData['fb_secret']) > 40) 
	return '0: The facebook API secret key cannot exceed 40 characters..';
	
	elseif(strlen($tsData['fb_client']) > 20) 
	return '0: The customer\'s facebook ID cannot exceed 20 characters..';
	
	elseif(strlen($tsData['gl_id']) > 80) 
	return '0: The google API ID cannot exceed 80 characters..';
	
	elseif(strlen($tsData['gl_secret']) > 30) 
	return '0: The google API secret key cannot exceed 30 characters..';
	
	elseif(strlen($tsData['tw_id']) > 30) 
	return '0: The twitter API ID cannot exceed 30 characters..';
	
	elseif(strlen($tsData['tw_secret']) > 55) 
	return '0: The twitter API secret key cannot exceed 55 characters..';
	
	elseif(strlen($tsData['gi_id']) > 25) 
	return '0: The github API ID cannot exceed 25 characters..';
	
	elseif(strlen($tsData['gi_secret']) > 45) 
	return '0: The github API secret key cannot exceed 45 characters..';
	
	elseif(strlen($tsData['wl_id']) > 42) 
	return '0: The windows live API ID cannot exceed 42 characters..';
	
	elseif(strlen($tsData['wl_secret']) > 45) 
	return '0: The windows live API secret key cannot exceed 45 characters..';
	
	elseif(strlen($tsData['up_id']) > 45) 
	return '0: The imgur API ID cannot exceed 45 characters..';
	
	elseif(strlen($tsData['up_secret']) > 45) 
	return '0: The secret key of the imgur API cannot exceed 45 characters..';
	
	elseif(strlen($tsData['upload_server']) > 20) 
	return '0: The name of the upload server cannot exceed 20 characters..';
	
	elseif(strlen($tsData['rp_id']) > 45) 
	return '0: The reCaptcha API ID cannot exceed 45 characters..';
	
	elseif(strlen($tsData['rp_secret']) > 45) 
	return '0: The reCaptcha API secret key cannot exceed 45 characters..';
	
	elseif((strlen($tsData['rp_id']) || strlen($tsData['rp_secret'])) && empty($tsData['rp_name'])) 
	return '0: You must select the service to which the reCaptcha API belongs..';
	
	elseif(strlen($tsData['smtp_email']) > 50) 
	return '0: The email cannot exceed 50 characters..';
	
	elseif(strlen($tsData['smtp_pass']) > 40) 
	return '0: The password cannot exceed 40 characters..';
	
	elseif(strlen($tsData['smtp_host']) > 50) 
	return '0: The host address cannot exceed 50 characters..';
	
	elseif(strlen($tsData['smtp_port']) > 4) 
	return '0: The email port cannot exceed 4 characters, numeric..';
	
	else {
	// Si todo esta bien, continuamos.
	$tsCore->settings['api']['facebook']['id'] = $tsData['fb_id'];
	$tsCore->settings['api']['facebook']['secret'] = $tsData['fb_secret'];
	$tsCore->settings['api']['facebook']['client'] = $tsData['fb_client'];
	$tsCore->settings['api']['facebook']['fb_active'] = $tsData['fb_active'];
	$tsCore->settings['api']['google']['id'] = $tsData['gl_id'];
	$tsCore->settings['api']['google']['secret'] = $tsData['gl_secret'];
	$tsCore->settings['api']['google']['gl_active'] = $tsData['gl_active'];
	$tsCore->settings['api']['twitter']['id'] = $tsData['tw_id'];
	$tsCore->settings['api']['twitter']['secret'] = $tsData['tw_secret'];
	$tsCore->settings['api']['twitter']['tw_active'] = $tsData['tw_active'];
	$tsCore->settings['api']['github']['id'] = $tsData['gi_id'];
	$tsCore->settings['api']['github']['secret'] = $tsData['gi_secret'];
	$tsCore->settings['api']['github']['gi_active'] = $tsData['gi_active'];
	$tsCore->settings['api']['windowslive']['id'] = $tsData['wl_id'];
	$tsCore->settings['api']['windowslive']['secret'] = $tsData['wl_secret'];
	$tsCore->settings['api']['windowslive']['wl_active'] = $tsData['wl_active'];
	$tsCore->settings['api']['imgur']['id'] = $tsData['up_id'];
	$tsCore->settings['api']['imgur']['secret'] = $tsData['up_secret'];
	$tsCore->settings['api']['recaptcha']['id'] = $tsData['rp_id'];
	$tsCore->settings['api']['recaptcha']['secret'] = $tsData['rp_secret'];
	$tsCore->settings['api']['recaptcha']['name'] = $tsData['rp_name'];
	$tsCore->settings['api'] = serialize($tsCore->settings['api']);// Unimos todo de nuevo.
	
	// Para SMTP.
	$tsCore->settings['smtp']['type'] = $tsData['smtp_type'];
	$tsCore->settings['smtp']['host'] = $tsData['smtp_host'];
	$tsCore->settings['smtp']['email'] = $tsData['smtp_email'];
	$tsCore->settings['smtp']['password'] = $tsData['smtp_pass'];
	$tsCore->settings['smtp']['port'] = $tsData['smtp_port'];
	$tsCore->settings['smtp']['debug'] = $tsData['smtp_debug'];
	$tsCore->settings['smtp']['html'] = $tsData['smtp_html'];
	$tsCore->settings['smtp']['cod'] = $tsData['smtp_cod'];
	$tsCore->settings['smtp']['secure'] = $tsData['smtp_secure'];
	$tsCore->settings['smtp']['auth'] = $tsData['smtp_auth'];
	$tsCore->settings['smtp'] = serialize($tsCore->settings['smtp']);// Unimos todo de nuevo.
	
	// Activos.
	$activos = unserialize($tsCore->settings['activos']);// Extraemos datos.
	$activos['upload_type'] = $tsData['upload_type'];
	$activos['upload_server'] = $tsData['upload_server'];
	$activos['captcha_active'] = $tsData['captcha_active'];
	$activos = serialize($activos);// Unimos todo de nuevo.
	
	// Verificamos los serilize antes de guardar datos.
	$UPDATE  = ($tsCore->is_serialized($activos) == 1) ? 'activos = \''.$activos.'\'' : '';
	$UPDATE .= ($tsCore->is_serialized($tsCore->settings['api']) == 1) ? ', api_services = \''.$tsCore->settings['api'].'\'' : '';
	$UPDATE .= ($tsCore->is_serialized($tsCore->settings['smtp']) == 1) ? ', smtp = \''.$tsCore->settings['smtp'].'\'' : '';
	
	if(is_null($UPDATE) ===  false) {
	// Todo bien? guardamos datos.
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET '.$UPDATE.' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: Perfect all settings have been successfully saved..';
	
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	//
	}
	
	
	#11. LISTA NEGRA
	function get_blackList($obj = null) {
	global $tsCore;
	
	switch((int)$obj['type']) {	
	case '':// Todo lista negra.
	default:
	//<--
	$todos = anaK('num_rows', anaK('query', 'SELECT id FROM '.$tsCore->table['03'].'', array(__FILE__, __LINE__)));
	$page = $tsCore->getPag($todos, $tsCore->settings['max_posts']);
	
	$query = anaK('query', 'SELECT u.user_id, u.user_nick, u.user_name, b.* FROM '.$tsCore->table['03'].' AS b LEFT JOIN '.$tsCore->table['08'].' AS u ON b.b_author = u.user_id ORDER BY b.b_date DESC LIMIT '.$page['limite'], array(__FILE__, __LINE__));
	$data = result_array($query);
	//-->
	break;
	case '1':// Un elemento.
	//<--
	$query = anaK('query', 'SELECT * FROM '.$tsCore->table['03'].' WHERE id = \''.(int)$obj['id'].'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	//-->
	break;
	}
	//
	return array('data' => $data, 'page' => $page);
	}
	
	
	#12. TOOLS LISTA NEGRA
	function blackList_tools() {
	global $tsCore, $tsUser;
	$tsData = array(
	'id' => $tsCore->setProtect((int)$_POST['bid']),
	'type' => $tsCore->setProtect((int)$_POST['b-type']),
	'value' => $tsCore->setProtect($_POST['value']),
	'razon' => $tsCore->setProtect($_POST['razon']),
	'met_type' => $tsCore->setProtect((int)$_POST['type']),// Tipo de entrada.
	'date' => $tsCore->setProtect($tsCore->settings['date']),
	);
	
	if(!$tsData['id']) 
	return '0: This action cannot be executed. Error code: BL0001/ID';
	
	elseif(!$tsData['met_type']) 
	return '0: This action cannot be executed. Error code: BL0002/MT';
	
	// Existe alguno?
	$query = anaK('query', 'SELECT * FROM '.$tsCore->table['03'].' WHERE id = \''.$tsData['id'].'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	// Tiene los permisos necesarios?
	if($tsUser->is_admod != 1) 
	return '0: Sorry but you do not have the necessary permissions to perform this action..';
	
	if($data['id'] > 0) {
	// Todo bien? continuamos.
	switch($tsData['met_type']) {
	case '1':// Guardar cambios
	//<--
	// Validamos algunos campos.
	if(!$tsData['type']) 
	return '0: Sorry you must select a type of entry, before adding to the list..'; 
	
	elseif(($tsData['type'] == 1) && filter_var($tsData['value'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) === false) 
	return '0: Remember to write a valid IP address, IPV4 or IPV6..'; 
	
	elseif(($tsData['type'] == 2) && !mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $tsData['value'])) 
	return '0: Remember to write an email that is valid..'; 
	
	elseif(($tsData['type'] == 3) && !mb_ereg("^@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $tsData['value'])) 
	return '0: Remember to write a valid email provider..'; 
	
	elseif(($tsData['type'] == 4) && strlen($tsData['value']) < 4) 
	return '0: Please enter a username before saving..'; 
	
	elseif(strlen($tsData['value']) > 50) 
	return '0: Sorry, the maximum address limit must be 50 characters..'; 
	
	elseif(strlen($tsData['razon']) < 6) 
	return '0: Excuse me, but you must specify a reason before you save it..';
	
	elseif(strlen($tsData['razon']) > 120) 
	return '0: Sorry the maximum limit in reason must be 120 characters..';
	
	else {
	// Todo bien? actualizamos los datos.
	$update = array('b_type' => $tsData['type'], 'b_value' => $tsData['value'], 'b_reason' => $tsData['razon'], 'b_date' => $tsData['date']);
	
	if(anaK('query', 'UPDATE '.$tsCore->table['03'].' SET '.$tsCore->get_value($update).' WHERE id = '.(int)$data['id'], array(__FILE__, __LINE__))) {
	return '1: Perfect element has been successfully updated..';
	//	
	} else return '0: Sorry, an error occurred while trying to update the blackList data..';
	//
	}
	//-->
	break;
	case '2':// Eliminar elemento
	//<--
	if(anaK('query', 'DELETE FROM '.$tsCore->table['03'].' WHERE id = '.(int)$data['id'], array(__FILE__, __LINE__))) {
	return '1: Perfect, the item has been removed from the blacklist..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//-->
	break;
	}
	//
	} else return '0: Sorry this item does not exist or has already been removed from blackList..';
	//	
	}
	
	
	#13. AGREGAR BLACKLIST
	function agregar_blackList() {
	global $tsCore, $tsUser;
	
	$tsData = array(
	'type' => $tsCore->setProtect((int)$_POST['type']),
	'value' => $tsCore->setProtect($_POST['value']),
	'razon' => $tsCore->setProtect($_POST['razon']),
	'autor' => $tsCore->setProtect((int)$tsUser->uid),
	'date' => $tsCore->setProtect($tsCore->settings['date']),
	);
	
	// Validamos algunos campos.
	if(!$tsData['type']) 
	return '0: Sorry you must select a type of entry, before adding to the list..'; 
	
	elseif(($tsData['type'] == 1) && filter_var($tsData['value'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) === false) 
	return '0: Remember to write a valid IP address, IPV4 or IPV6..'; 
	
	elseif(($tsData['type'] == 2) && !mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $tsData['value'])) 
	return '0: Remember to write an email that is valid..'; 
	
	elseif(($tsData['type'] == 3) && !mb_ereg("^@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $tsData['value'])) 
	return '0: Remember to write a valid email provider..'; 
	
	elseif(($tsData['type'] == 4) && strlen($tsData['value']) < 4) 
	return '0: Please enter a username before saving..'; 
	
	elseif(strlen($tsData['value']) > 50) 
	return '0: Sorry, the maximum address limit must be 50 characters..'; 
	
	elseif(strlen($tsData['razon']) < 6) 
	return '0: Excuse me, but you must specify a reason before you save it..';
	
	elseif(strlen($tsData['razon']) > 120) 
	return '0: Sorry the maximum limit in reason must be 120 characters..';
	
	elseif($tsUser->is_admod != 1) 
	return '0: Sorry but you do not have the necessary permissions to perform this action..';
	
	else {
	// Todo bien? agregamos datos.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['03'].' (b_type, b_value, b_reason, b_author, b_date) VALUES (\''.$tsData['type'].'\', \''.$tsData['value'].'\', \''.$tsData['razon'].'\', \''.$tsData['autor'].'\', \''.$tsData['date'].'\')', array(__FILE__, __LINE__))) {
	return '1: Perfect the item has been added to the blacklist..';
	//	
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	
	
	#14. MOSTRAR DETALLES DEL IP
	function get_ipInfo() {
	global $tsCore;
	
	// Recibimos la direccion IP por post|get.
	$tsIP = $tsCore->setProtect(trim(($_GET['ip']) ? $_GET['ip'] : $_POST['ip']));
	
	// Verificamos si se ingreso una ip valida.
	if(filter_var($tsIP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)!== false) {
	$data = json_decode($tsCore->get_content('https://ipapi.co/'.$tsIP.'/json'), true);
	
	// Se recibieron datos? los pasamos..
	if(is_array($data)) return array('data' => $data); 
	else return array('error' => 'No results for the IP address "'.$tsIP.'"');
	//
	} else return array('error' => 'The entered IP address is invalid or mistyped.');
	//
	}
	
	
	#15. MOSTRAR PALABRAS CENSURADAS
	function get_badwords() {
	global $tsCore, $tsUser;
	
	// Realizamos la consulta
	$getData = unserialize($tsCore->settings['web_censurar']);
	if(is_array($getData)) {
		
		
	//saco el numero de elementos
	$longitud = count($getData);
	//Recorro todos los elementos
	# $i  de  tantos $longitud
	
	for ($i = 1; $i <= 10; $i++) {
    //echo $getData[$i]['p_id'];
	}
	
	/*for($i = 0; $i < $longitud; $i++) {
      //saco el valor de cada elemento
	  echo $getData['p_id'];
	  echo "<br>";
      }	*/
		
	/*foreach($getData as $row) {
	$row['p_autor'] = $tsUser->user_data($row['p_autor'], 1);
	$row['p_type'] = ($row['p_type'] == 1) ? 'Texto' :'Smiley';
	$data[] = $row;
	}*/
	//echo array_count_values($getData, 1);
	//	
	}
	return $data;
	//
	}
	
	
	#16. PLANES HOSTING AGREGAR|EDITAR|ELIMINAR.
	function tools_planes() {
	global $tsCore, $tsUser;
	
	$tsData = array(
	'id' => $tsCore->setProtect((int)$_POST['pid']), 
	'type' => $tsCore->setProtect((int)$_POST['type']), 
	'name_plan' => $tsCore->setProtect($_POST['name_plan']),
	);
	
	switch($tsData['type']) {
	case '':
	default:
	//<--
	if(strlen($tsData['name_plan']) < 1) 
	return '0: Write a name for the plan that is valid..';
	
	elseif(strlen($tsData['name_plan']) > 20) 
	return '0: The plan name should not exceed 20 characters..';
	
	elseif($tsUser->is_admod != 1) 
	return '0: You do not have the necessary permissions to perform this action..';
	
	else {
	// Contamos los planes existentes y agregamos el nuevo.
	$i = count($tsCore->settings['cpanel']['name_plan']) + 1;
	$tsCore->settings['cpanel']['name_plan'][$i] = $tsData['name_plan'];
	$cpanel__ = serialize($tsCore->settings['cpanel']);// Unimos todo de nuevo.
	// Si se serializo bien guardamos los cambios.
	if($tsCore->is_serialized($cpanel__) == 1) {	
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET cp = \''.$cpanel__.'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: Great hosting plan has been successfully added..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	//-->
	break;
	case '1':
	//<-- editar
	if(!$tsData['id']) 
	return '0: This action cannot be executed. Error code: P001E/ID';
	
	elseif(strlen($tsData['name_plan']) < 1) 
	return '0: Write a name for the plan that is valid..';
	
	elseif(strlen($tsData['name_plan']) > 20) 
	return '0: The plan name should not exceed 20 characters..';
	
	elseif($tsUser->is_admod != 1) 
	return '0: You do not have the necessary permissions to perform this action..';
	
	else {
	// Todo bien consultamos haber si existe el plan.
	if($tsCore->settings['cpanel']['name_plan'][$tsData['id']]) {
	// Si existe actualizamos datos..
	$tsCore->settings['cpanel']['name_plan'][$tsData['id']] = $tsData['name_plan'];
	$cpanel__ = serialize($tsCore->settings['cpanel']);// Unimos todo de nuevo.
	// Si se serializo bien guardamos los cambios.
	if($tsCore->is_serialized($cpanel__) == 1) {	
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET cp = \''.$cpanel__.'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: The plan configuration was successfully saved..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	} else return '0: The hosting plan you are trying to edit does not exist..';
	//	
	}
	//-->
	break;
	case '2':
	//<-- delete
	if(!$tsData['id']) 
	return '0: This action cannot be executed. Error code: P001E/ID'; 
	
	elseif($tsUser->is_admod != 1) 
	return '0: You do not have the necessary permissions to perform this action..';
	
	else {
	// Todo bien consultamos si existe el plan, lo eliminamos.
	if($tsCore->settings['cpanel']['name_plan'][$tsData['id']]) {
	unset($tsCore->settings['cpanel']['name_plan'][$tsData['id']]);
	$cpanel__ = serialize($tsCore->settings['cpanel']);// Unimos todo de nuevo.
	// Si se serializo bien guardamos los cambios.
	if($tsCore->is_serialized($cpanel__) == 1) {
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET cp = \''.$cpanel__.'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: The hosting plan has been successfully removed..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//	
	}
	//
	} else return '0: The hosting plan you are trying to remove does not exist..';
	//
	}
	//-->
	break;
	case '3':
	//<-- update lista.
	return $tsCore->settings['cpanel'];
	//-->
	break;
	}
	//	
	}
	
	
	
	#17. SERVIDORES NS AGREGAR|EDITAR|ELIMINAR.
	function tools_nameservers() {
	global $tsCore, $tsUser;
	
	$tsData = array(
	'id' => $tsCore->setProtect((int)$_POST['nid']), 
	'type' => $tsCore->setProtect((int)$_POST['type']), 
	'ns' => $tsCore->setProtect($_POST['ns']),
	);
	
	switch($tsData['type']) {
	case '':
	default:
	//<--
	if(!mb_ereg("^ns+([0-9])\.([a-zA-Z0-9-]+).([a-zA-Z]{2,4})$", $tsData['ns'])) 
	return '0: Write a name for the nameserver that is valid..';
	
	elseif(strlen($tsData['ns']) > 50) 
	return '0: The nameserver name must not exceed 50 characters..';
	
	elseif($tsUser->is_admod != 1) 
	return '0: You do not have the necessary permissions to perform this action..';
	
	else {
	// Contamos los planes existentes y agregamos el nuevo.
	$i = count($tsCore->settings['cpanel']['ns']) + 1;
	$tsCore->settings['cpanel']['ns'][$i] = $tsData['ns'];
	$cpanel__ = serialize($tsCore->settings['cpanel']);// Unimos todo de nuevo.
	// Si se serializo bien guardamos los cambios.
	if($tsCore->is_serialized($cpanel__) == 1) {
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET cp = \''.$cpanel__.'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: Great the nameserver has been successfully added..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	//-->
	case '1':
	//<-- editar
	if(!$tsData['id']) 
	return '0: This action cannot be executed. Error code: NS001/ID';
	
	elseif(!mb_ereg("^ns+([0-9])\.([a-zA-Z0-9-]+).([a-zA-Z]{2,4})$", $tsData['ns'])) 
	return '0: Write a name for the nameserver that is valid..';
	
	elseif(strlen($tsData['ns']) > 50) 
	return '0: The nameserver name must not exceed 50 characters..';
	
	elseif($tsUser->is_admod != 1) 
	return '0: You do not have the necessary permissions to perform this action..';
	
	else {
	// Todo bien consultamos si existe el nameserver, lo guardamos.
	if($tsCore->settings['cpanel']['ns'][$tsData['id']]) {
	$tsCore->settings['cpanel']['ns'][$tsData['id']] = $tsData['ns'];
	$cpanel__ = serialize($tsCore->settings['cpanel']);// Unimos todo de nuevo.
	// Si se serializo bien guardamos los cambios.
	if($tsCore->is_serialized($cpanel__) == 1) {
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET cp = \''.$cpanel__.'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: The nameserver configuration was successfully saved..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	} else return '0: The nameserver you are trying to edit does not exist..';
	//	
	}
	//-->
	break;
	case '2':
	//<-- delete
	if(!$tsData['id']) 
	return '0: This action cannot be executed. Error code: NS001/ID'; 
	
	elseif($tsUser->is_admod != 1) 
	return '0: You do not have the necessary permissions to perform this action..';
	
	else {
	// Todo bien consultamos si existe el nameserver, lo eliminamos.
	if($tsCore->settings['cpanel']['ns'][$tsData['id']]) {
	unset($tsCore->settings['cpanel']['ns'][$tsData['id']]);
	$cpanel__ = serialize($tsCore->settings['cpanel']);// Unimos todo de nuevo.
	// Si se serializo bien guardamos los cambios.
	if($tsCore->is_serialized($cpanel__) == 1) {
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET cp = \''.$cpanel__.'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: The nameserver has been successfully removed..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//	
	}
	//
	} else return '0: The nameserver you are trying to remove does not exist..';
	//
	}
	//-->
	break;
	case '3':
	//<-- update lista.
	return $tsCore->settings['cpanel'];
	//-->
	break;
	}
	//
	}
	
	
	#18. CUENTAS RESELLERS AGREGAR|EDITAR|ELIMINAR.
	function tools_resellers() {
	global $tsCore, $tsUser;
	
	$tsData = array(
	'id' => $tsCore->setProtect((int)$_POST['cid']), 
	'type' => $tsCore->setProtect((int)$_POST['type']), 
	'cp_user' => $tsCore->setProtect($_POST['cp_user']), 
	'cp_pass' => $tsCore->setProtect($_POST['cp_pass']), 
	'name' => $tsCore->setProtect($_POST['name']), 
	'prefix' => $tsCore->setProtect($_POST['prefix']), 
	'on' => $tsCore->setProtect(($_POST['on'] == 'on') ? 0 : 1),
	);
	
	switch($tsData['type']) {
	case '':
	default:
	//<--
	if(strlen($tsData['cp_user']) < 10) 
	return '0: Write a valid reseller user..';
	
	elseif(strlen($tsData['cp_user']) > 280) 
	return '0: The reseller user must not exceed 280 characters..';
	
	elseif(strlen($tsData['cp_pass']) < 10) 
	return '0: Enter a valid reseller password..';
	
	elseif(strlen($tsData['cp_pass']) > 280) 
	return '0: The reseller password must not exceed 280 characters..';
	
	elseif(!mb_ereg("^([a-zA-Z0-9]+).([a-zA-Z0-9-]+).([a-zA-Z]{2,4})$", $tsData['name']))
	return '0: Write a domain name that is valid for the reseller..';
	
	elseif(strlen($tsData['name']) > 50) 
	return '0: The domain name must not exceed 50 characters..';
	
	elseif(strlen($tsData['prefix']) < 4) 
	return '0: Write a valid reseller user prefix..';
	
	elseif(strlen($tsData['prefix']) > 6) 
	return '0: The user reseller prefix must not exceed 6 characters..';
	
	elseif($tsUser->is_admod != 1) 
	return '0: You do not have the necessary permissions to perform this action..';
	
	else {
	// Contamos los planes existentes y agregamos el nuevo.
	$i = count($tsCore->settings['cpanel']['account']) + 1;
	$tsCore->settings['cpanel']['account'][$i]['cp_user'] = $tsData['cp_user'];
	$tsCore->settings['cpanel']['account'][$i]['cp_pass'] = $tsData['cp_pass'];
	$tsCore->settings['cpanel']['domain_list'][$i]['name'] = $tsData['name'];
	$tsCore->settings['cpanel']['domain_list'][$i]['prefix'] = $tsData['prefix'];
	$tsCore->settings['cpanel']['domain_list'][$i]['on'] = $tsData['on'];
	$cpanel__ = serialize($tsCore->settings['cpanel']);// Unimos todo de nuevo.
	// Si se serializo bien guardamos los cambios.
	if($tsCore->is_serialized($cpanel__) == 1) {
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET cp = \''.$cpanel__.'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: Great reseller account was successfully added..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	//-->
	break;
	case '1':
	//<-- editar.
	if(!$tsData['id']) 
	return '0: This action cannot be executed. Error code: RC001/ID';
	
	elseif(strlen($tsData['cp_user']) < 10) 
	return '0: Write a valid reseller user..';
	
	elseif(strlen($tsData['cp_user']) > 280) 
	return '0: The reseller user must not exceed 280 characters..';
	
	elseif(strlen($tsData['cp_pass']) < 10) 
	return '0: Enter a valid reseller password..';
	
	elseif(strlen($tsData['cp_pass']) > 280) 
	return '0: The reseller password must not exceed 280 characters..';
	
	elseif(!mb_ereg("^([a-zA-Z0-9]+).([a-zA-Z0-9-]+).([a-zA-Z]{2,4})$", $tsData['name']))
	return '0: Write a domain name that is valid for the reseller..';
	
	elseif(strlen($tsData['name']) > 50) 
	return '0: The domain name must not exceed 50 characters..';
	
	elseif(strlen($tsData['prefix']) < 4) 
	return '0: Write a valid reseller user prefix..';
	
	elseif(strlen($tsData['prefix']) > 6) 
	return '0: The user reseller prefix must not exceed 6 characters..';
	
	elseif($tsUser->is_admod != 1) 
	return '0: You do not have the necessary permissions to perform this action..';
	
	else {
	// Todo bien consultamos si existe la cuenta reseller, la editamos.
	if(is_array($tsCore->settings['cpanel']['account'][$tsData['id']])) {
	$tsCore->settings['cpanel']['account'][$tsData['id']]['cp_user'] = $tsData['cp_user'];
	$tsCore->settings['cpanel']['account'][$tsData['id']]['cp_pass'] = $tsData['cp_pass'];
	$tsCore->settings['cpanel']['domain_list'][$tsData['id']]['name'] = $tsData['name'];
	$tsCore->settings['cpanel']['domain_list'][$tsData['id']]['prefix'] = $tsData['prefix'];
	$tsCore->settings['cpanel']['domain_list'][$tsData['id']]['on'] = $tsData['on'];
	$cpanel__ = serialize($tsCore->settings['cpanel']);// Unimos todo de nuevo.
	// Si se serializo bien guardamos los cambios.
	if($tsCore->is_serialized($cpanel__) == 1) {
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET cp = \''.$cpanel__.'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: Reseller account settings were successfully saved..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//	
	}
	//
	} else return '0: The reseller account you are trying to edit does not exist..';
	//	

	}
	//-->
	break;
	case '2':
	//<-- delete.
	if(!$tsData['id']) 
	return '0: This action cannot be executed. Error code: RC001/ID'; 
	
	elseif($tsUser->is_admod != 1) 
	return '0: You do not have the necessary permissions to perform this action..';
	
	else {
	// Todo bien consultamos si existe la cuenta reseller, la eliminamos.
	if(is_array($tsCore->settings['cpanel']['account'][$tsData['id']])) {
	unset($tsCore->settings['cpanel']['account'][$tsData['id']]);
	unset($tsCore->settings['cpanel']['domain_list'][$tsData['id']]);
	$cpanel__ = serialize($tsCore->settings['cpanel']);// Unimos todo de nuevo.
	// Si se serializo bien guardamos los cambios.
	if($tsCore->is_serialized($cpanel__) == 1) {
	if(anaK('query', 'UPDATE '.$tsCore->table['00'].' SET cp = \''.$cpanel__.'\' WHERE id = '.(int)$tsCore->settings['id'], array(__FILE__, __LINE__))) {
	return '1: The reseller account has been successfully deleted..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//	
	}
	//
	} else return '0: The reseller account you are trying to delete does not exist..';
	//
	}
	//-->
	break;
	case '3':
	//<-- update lista.
	return $tsCore->settings['cpanel'];
	//-->
	break;
	}
	//
	}
	
}
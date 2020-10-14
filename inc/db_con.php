<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: db_con.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#| Conector mySQLi para la base de datos.
#+----------------------------------------------------------+
	
	# VARIABLES[]
	define('METODO', 'AES-256-CBC');
	define('KEY_SEC', '');
	define('ILL_SEC', '');
    define('AE_SERVER', '');
    define('AE_USER', '');
    define('AE_PASS', '');
    define('AE_NAME', '');
	define('AE_PREFIX', '');
	define('tables', array(
	'00' => AE_PREFIX.'w_config', 
	'01' => AE_PREFIX.'w_temas', 
	'02' => AE_PREFIX.'w_noticias', 
	'03' => AE_PREFIX.'w_blacklist', 
	'04' => AE_PREFIX.'w_ips', 
	'05' => AE_PREFIX.'w_upload', 
	'06' => AE_PREFIX.'w_suspendido', 
	'07' => AE_PREFIX.'w_contacts', 
	'08' => AE_PREFIX.'u_miembros', 
	'09' => AE_PREFIX.'u_perfil', 
	'10' => AE_PREFIX.'u_rangos', 
	'11' => AE_PREFIX.'u_sessions', 
	'12' => AE_PREFIX.'u_seguidores', 
	'13' => AE_PREFIX.'u_monitor', 
	'14' => AE_PREFIX.'u_mensajes', 
	'15' => AE_PREFIX.'u_respuestas', 
	'16' => AE_PREFIX.'u_cpanel',
	));
	// Verificamos si esta instalada la web.
	if(is_dir(EA_ROOT.'/install/') && (AE_SERVER == '' || AE_USER == '' || AE_PASS == '' || AE_NAME == '')) 
	header('Location: ./install/index.php');
	
	// Conectamos al servidor?
    $anaK = @mysqli_connect(AE_SERVER, AE_USER, AE_PASS, AE_NAME);
	
	// Probamos la conexion con el servidor mysqli
    if(!@mysqli_connect(AE_SERVER, AE_USER, AE_PASS)) {
	exit(error_show('<p>Apparently an error occurred when trying to connect to the MySQL server.</p>', 'other'));
	
    // Probamos si hay conexion con la base de datos
    } elseif(!@mysqli_select_db($anaK, AE_NAME)) {
    exit(error_show('<p>The database is badly connected or the name is misspelled.</p>', 'other'));
	
    // Probamos el tipo de codificacion
    } elseif(!mysqli_set_charset($anaK, 'utf8')) {
    exit(error_show('Oops!! The type of coding in the database could not be established.', 'db'));	
    }
	
    // Ejecucion de consulta
    function anaK($type, $data, $info = '') {#query, la consulta, la linea.
    global $anaK, $tsUser, $tsAjax, $screen;
	
    if(is_array($info)) {# Si la primera variable contiene un string, la consulta se ejecuta.
    if($tsUser->is_admod != 1 && $screen['active'] != 2) { $info[0] = explode('\\', $info[0]); }

    $info['file'] = $tsUser->is_admod == 1 || $screen['active'] == 2 ? $info[0] : end($info[0]);
    $info['line'] = $info[1];
    $info['query'] = $data;
	
    } else {
    if($type == 'query') { $info = array(); $info['query'] = $data; }
    }
	
    // Ejecutamos la accion que queremos
    if($type === 'query' && !empty($data)) {
    $query = mysqli_query($anaK, $data);
    
    if(!$query && !$tsAjax && $screen['active'] && ($info['file'] || $info['line'] || ($info['query'] && $tsUser->is_admod == 1))) exit(error_show('Could not run a database query.', 'db', $info));
    return $query;
    
    } elseif($type === 'real_escape_string') {
    return mysqli_real_escape_string($anaK, $data);
    
    } elseif($type === 'num_rows') {
    return mysqli_num_rows($data);
	
	} elseif($type === 'fetch_assoc') {
    return mysqli_fetch_assoc($data);
    
    } elseif($type === 'fetch_array') {
    return mysqli_fetch_array($data);
    
    } elseif($type === 'fetch_row') {
    return mysqli_fetch_row($data);
    
    } elseif($type === 'free_result') {
    return mysqli_free_result($data);
	
	} elseif($type === 'fetch_fields') {
	return mysqli_fetch_fields($data);
	
	} elseif($type === 'affected_rows') {
	return mysqli_affected_rows($anaK);
	
	} elseif($type === 'field_count') {
	return mysqli_field_count($anaK);
	
	} elseif($type === 'insert_id') {
    return mysqli_insert_id($anaK);
	
    } elseif($type === 'host_info') {
	return mysqli_get_host_info($anaK);
	
	} elseif($type === 'server_info') {
	return mysqli_get_server_info($anaK);
	
	} elseif($type === 'error') {
    return mysqli_error($anaK);
	}
    //
    }
	
    // Resultados con array()
    function result_array($result) {
    $result instanceof mysqli_result;
    if(!is_a($result, 'mysqli_result')) return false;
    while($row = anaK('fetch_assoc', $result)) $array[] = $row;
    return $array;
    }
	
    // Mensaje de errores
    function error_show($error = 'Undefined', $type = 'db', $info = array()) {
    global $anaK, $tsUser, $screen;

    // Tipo de mensaje
    if($type === 'db') {
	
    // Definir bloques HTML
    $extra['file'] = isset($info['file']) ? '<tr><td>Archive</td><td>'.$info['file'].'</td></tr>' : '';
    $extra['line'] = isset($info['line']) ? '<tr><td>Line</td><td>'.$info['line'].'</td></tr>' : '';
    $extra['query'] = isset($info['query']) && ($tsUser->is_admod == 1 || $screen['active'] == 2) ? '<tr><td>Sentence</td><td>'.$info['query'].'</td></tr>' : '';

    $extra['error'] = mysqli_error($anaK) && ($tsUser->is_admod == 1 || $screen['active'] == 2) ? '<tr><td colspan="2"><p class="warning">'.mysqli_error($anaK).'</p></td></tr>' : '';

    // Definir tabla HTML
    $table = '<table border="0"><tbody>'.$extra['file'].$extra['line'].$extra['query'].$extra['error'].'</tbody></table>';
    }
	
	// Plantilla de carga para errores.
	return '0:
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="UTF-8">
	<link rel="icon" href="'.EA_URL.'/default/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="'.EA_URL.'/default/css/sql.css"/>
	<title>Einet Anake › Configuration error..</title>
	</head>
	<body>
	<h1 id="logo"><a href="https://www.x3host.ml" target="_blank">Einet Anake</a></h1>
	<div id="error-page">
	<h1>Oops!! Error.</h1><p>'.$error.'</p>'.($type === 'db' ? $table : '').'
	</div>
	</body>
	</html>';
	}
	
    $screen['active'] = 2;# Define si enseñamos los errores con detalles [2], o simple [1] para admins.
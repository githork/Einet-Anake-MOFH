|<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
ini_set('memory_limit', '-1');
set_time_limit(0);
$ServerErrors = array();
$config_file_name = '../inc/db_con.php';
$config_file_vars = '../vars.php';

function key_generator() {
	$caracteres = 'A*8o.7-sZx$@!9lxT?s';
    $code = '';
    $max = strlen($caracteres)-1;
    for($i=0;$i <9; $i++){
    $code .= $caracteres[mt_rand(0,$max)];
    }
    return $code;
}
function get_encrypt($obj) {
	global $tsData;
	$key = hash('sha512', $tsData['key_sec']);
	$ill = substr(hash('sha512', $tsData['ill_sec']), 0, 16);
	$out = openssl_encrypt($obj, 'AES-256-CBC', $key, 0, $ill);
	$encrypt_pass = base64_encode($out);
	return $encrypt_pass;
}
function get_IP() {
	$tsIPKey = array(
	'HTTP_CLIENT_IP', 
	'HTTP_X_FORWARDED_FOR', 
	'HTTP_X_FORWARDED', 
	'HTTP_X_CLUSTER_CLIENT_IP', 
	'HTTP_FORWARDED_FOR', 
	'HTTP_FORWARDED', 
	'X_FORWARDED_FOR', 
	'REMOTE_ADDR',
    );
	foreach($tsIPKey as $key) {
	if(array_key_exists($key, $_SERVER) === true) {
	foreach(explode(',', $_SERVER[$key]) as $tsIP) {
	if(filter_var($tsIP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)!== false) {
    return trim($tsIP);
	} elseif(trim($tsIP) === '::1') {
	return trim($tsIP);
	}
	}
	}
    }
    return '';
}
function get_pin() {
    $caracteres = '0A1B2C3D4E5F6L7M8Q9';
    $code = '';
    $max = strlen($caracteres)-1;
    for($i=0;$i <8; $i++){
    $code .= $caracteres[mt_rand(0,$max)];
    }
    return $code;
}

if(!empty($_POST['install'])) {
	$tsData = array(
	'sql_server' => $_POST['sql_host'],
	'sql_username' => $_POST['sql_user'],
	'sql_password' => $_POST['sql_pass'],
	'sql_database' => $_POST['sql_name'],
	'sql_prefix' => $_POST['sql_prefix'],
	'key_sec' => key_generator(),
	'ill_sec' => key_generator(),
	'web_title' => $_POST['site_title'],
	'web_description' => $_POST['site_desc'],
	'web_email' => $_POST['site_email'],
	'admin_username' => $_POST['admin_username'],
	'admin_password' => $_POST['admin_password'],
	'admin_email' => $_POST['admin_email'],
	);
    $dB = mysqli_connect($tsData['sql_server'], $tsData['sql_username'], $tsData['sql_password'], $tsData['sql_database']);
    if(mysqli_connect_errno()) {
    $ServerErrors[] = 'Error connecting to MySQL: '.mysqli_connect_error();
    }
    if($dB) {
	if(mysqli_fetch_row(mysqli_query($dB, "SHOW TABLES LIKE {$tsData['sql_prefix']}w_config")) == true) {
	header('Location: ../upgrade/index.php');
	}
	$query = mysqli_query($dB, 'SELECT @@sql_mode as modes;');
	$sql_sql = mysqli_fetch_assoc($query);
	
	if(count($sql_sql) > 0) {
    $results = @explode(',', $sql_sql['modes']);
	
	if(in_array('STRICT_TRANS_TABLES', $results)) {
    $ServerErrors[] = 'The sql-mode <b>STRICT_TRANS_TABLES</b> is enabled on your mysql server, contact your host provider to disable it.';
    }
	
	if(in_array('STRICT_ALL_TABLES', $results)) {
	$ServerErrors[] = 'The sql-mode <b>STRICT_ALL_TABLES</b> is enabled on your mysql server, contact your host provider to disable it.';
	}
	//	
	}
	//
	}
	
	if(empty($tsData['web_title'])) {
	$ServerErrors[] = 'Write a title for your website.';
	}
	if(empty($tsData['web_description'])) {
	$ServerErrors[] = 'Write a description for your website.';
	}
	if(!mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $tsData['web_email'])) {
	$ServerErrors[] = 'Write an email for your website.';
	}
	if(empty($tsData['admin_username']) || empty($tsData['admin_password'])) {
	$ServerErrors[] = 'Enter the username and password for the administrator.';
	}
	if(!mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $tsData['admin_email'])) {
	$ServerErrors[] = 'Write the email for the administrator.';
	}
	
	$p = array();
	$p['status'] = 'SUCCESS';
	
    if(isset($p['status'])) {
	if($p['status'] == 'ERROR') {
	$ServerErrors[] = $p['ERROR_NAME'];
	}
	//
    } else {
    $ServerErrors[] = 'Unable to connect to the server, try again later or contact us.';
    }
	
    if(empty($ServerErrors)) {
	$success = '';	
	$r_1 = array(
	"'KEY_SEC', ''", 
	"'ILL_SEC', ''", 
	"'AE_SERVER', ''", 
	"'AE_USER', ''", 
	"'AE_PASS', ''", 
	"'AE_NAME', ''", 
	"'AE_PREFIX', ''",
	);
	$r_2 = array(
	"'KEY_SEC', '{$tsData['key_sec']}'", 
	"'ILL_SEC', '{$tsData['ill_sec']}'", 
	"'AE_SERVER', '{$tsData['sql_server']}'", 
	"'AE_USER', '{$tsData['sql_username']}'", 
	"'AE_PASS', '{$tsData['sql_password']}'", 
	"'AE_NAME', '{$tsData['sql_database']}'", 
	"'AE_PREFIX', '{$tsData['sql_prefix']}'",
	);
	$file_content = file_get_contents($config_file_name);
	$file_content = str_replace($r_1, $r_2, $file_content);
	$config_file = file_put_contents($config_file_name, $file_content);
	if($config_file) {
	$filename = './einet_anake.sql';
	// Variable temporal, utilizada para almacenar la consulta actual.
	$templine = '';
	// Leemos todo el archivo.
	$lines = file($filename);
	// Bucle a través de cada línea.
	foreach($lines as $line) {
	// Omitimos si es un comentario.
	if(substr($line, 0, 2) == '--' || $line == '')
	continue;
	// Agregar esta línea al segmento actual.
	$templine .= $line;
	$query = false;
	// Si tiene un punto y coma al final, es el final de la consulta.
	if(substr(trim($line), -1, 1) == ';') {
	$templine = str_replace('anak_', $tsData['sql_prefix'], $templine);// Modificamos el prefijo.
	// Realizamos la consulta.
	$query = mysqli_query($dB, $templine);
	// Restablecer la variable.
	$templine = '';
	}
	//
	}
	
	if($query) {
	$p2 = array();
	$p2['status'] = 'SUCCESS';
	if(isset($p2['status'])) {
	if($p2['status'] == 'SUCCESS') {
	$can = 1;
	}
	//
	}
	// Consultamos para leer algunos campos.
	$g_data = mysqli_fetch_assoc(mysqli_query($dB, "SELECT activos FROM {$tsData['sql_prefix']}w_config WHERE id = '1'"));
	$activos = unserialize($g_data['activos']);
	$activos['email_web'] = $tsData['web_email'];
	$activos['web_create'] = time();
	$activos['web_update'] = time();
	$activos = serialize($activos);
	// Comprimimos y actualizamos los datos de configuracion.
	$query_one = mysqli_query($dB, "UPDATE {$tsData['sql_prefix']}w_config SET titulo = '".mysqli_real_escape_string($dB, $tsData['web_title'])."', description = '".mysqli_real_escape_string($dB, $tsData['web_description'])."', activos = '".mysqli_real_escape_string($dB, $activos)."' WHERE id = '1'");
	// Creamos la cuenta del administrador y añadimos datos.
	if(mysqli_query($dB, "INSERT INTO {$tsData['sql_prefix']}u_miembros (user_activo, user_nick, user_password, user_email, user_online, user_ip, user_rango, user_antiFlood, user_pin, user_registro) VALUES ('1', '".mysqli_real_escape_string($dB, $tsData['admin_username'])."', '".mysqli_real_escape_string($dB, get_encrypt($tsData['admin_password']))."', '".mysqli_real_escape_string($dB, $tsData['admin_email'])."', '".time()."', '".mysqli_real_escape_string($dB, get_IP())."', '1', '1', '".mysqli_real_escape_string($dB, get_pin())."', '".time()."')")) {
	// Obtenemos el id del usuario.
	$user_id = mysqli_insert_id($dB);
	// Creamos datos para el perfil.
	mysqli_query($dB, "INSERT INTO {$tsData['sql_prefix']}u_perfil (user_id, p_sexo, p_limite) VALUES ('{$user_id}', '1', '99')");
	//
	}
	//
	$success = 'Einet Anake is settling in, please wait..';
	} else {
	$ServerErrors[] = 'An error occurred during installation, please contact us.';
	}
	//
	}
	//
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Einet Anake | Installation</title>
<link rel="shortcut icon" type="image/png" href="icon.png"/>
<link rel="stylesheet" href="bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="jquery-1.11.3.js"></script>
<script type="text/javascript" src="jquery.form.min.js"></script>
</head>
<?php
$page = 'terms';
$pages_array = array('req', 'terms', 'installation', 'finish');
if(!empty($_GET['page'])) {
if(in_array($_GET['page'], $pages_array)) {
$page = $_GET['page'];
}
//
}

$page_name = '';
if($page == 'terms') {
$page_name = 'Terms of use';
} else if($page == 'req') {
$page_name = 'Requirements';
} else if($page == 'installation') {
$page_name = 'Installation';
}else if($page == 'finish') {
$page_name = 'Your website is ready!';
}

$cURL = true;
$php = true;
$gd = true;
$disabled = false;
$mysqli = true;
$is_writable = true;
$mbstring = true;
$is_htaccess = true;
$is_mod_rewrite = true;
$is_sql = true;
$zip = true;
$allow_url_fopen = true;
$exif_read_data = true;

if(!function_exists('curl_init')) {
    $cURL = false;
    $disabled = true;
}
if(!function_exists('mysqli_connect')) {
    $mysqli = false;
    $disabled = true;
}
if(!extension_loaded('mbstring')) {
    $mbstring = false;
    $disabled = true;
}
if(!extension_loaded('gd') && !function_exists('gd_info')) {
    $gd = false;
    $disabled = true;
}
if(!version_compare(PHP_VERSION, '5.6.0', '>=')) {
    $php = false;
    $disabled = true;
}
if(!is_writable('../inc/db_con.php')) {
    $is_writable = false;
    $disabled = true;
}
if(!file_exists('../.htaccess')) {
    $is_htaccess = false;
    $disabled = true;
}
if(!file_exists('./einet_anake.sql')) {
    $is_sql = false;
    $disabled = true;
}
if(!extension_loaded('zip')) {
    $zip = false;
    $disabled = true;
}
if(!ini_get('allow_url_fopen') ) {
    $allow_url_fopen = false;
    $disabled = true;
}
?>
<body class="<?php if($page == 'req') { ?>step_one_done<?php } ?><?php if($page == 'installation') { ?>step_two_done<?php } ?><?php if($page == 'finish') { ?>step_three_done finished<?php } ?>">
<div class="content-container container">
<div class="row admin-panel">
<div class="col-md-1"></div>
<div class="col-md-10">
<div class="wo_install_step">
<ul class="install_steps">
<li class="step-one <?php echo ($page == 'terms') ? 'active': '';?>">
<span>1<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" /></svg></span>Terms</li>
<li class="step-two <?php echo ($page == 'req') ? 'active': '';?>">
<span>2<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" /></svg></span>Requirements</li>
<li class="step-three <?php echo ($page == 'installation') ? 'active': '';?>">
<span>3<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" /></svg></span>Installation</li>
<li class="step-four <?php echo ($page == 'finish') ? 'active': '';?>">
<span>4<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" /></svg></span>Finish</li>
</ul>
<div class="line"><div class="line_sec"></div></div>
</div>
</div>
<div class="col-md-1"></div>
</div>
<div class="row admin-panel">
<div class="col-md-1"></div>
<div class="col-md-10">
<div class="wo_install_wiz">
<div>
<h2 class="light"><?php echo $page_name; ?></h2>
<div class="setting-well">
<?php if($page == 'terms') { ?>
<div class="terms">
<h5>LICENSE AGREEMENT: a domain or site to be installed.</h5>
<br>
<b class="bold">YOU CAN:</b><br> 
1) Use only in one domain, you need to buy an additional license for each domain .<br> 
2) Make all the modifications as you prefer.<br> 
3) Remove all sections and things that are to your liking. <br>
4) Change or translate into the language you need.<br><br>

<b class="bold">YOU CAN'T:</b> <br>
1) Resell, distribute, give away or trade by any means to third parties or individuals without permission. <br>
2) Use in more than one domain or hosting.<br><br>

Unlimited licenses are available.
<hr>
<form action="?page=req" method="post">
<div class="wo_terms">
<input type="checkbox" name="agree" id="agree">
<label for="agree">I agree to the terms and conditions of the privacy policy.</label>
</div>
<br><br>
<button type="submit" class="btn btn-main" id="next-terms" disabled>Continue <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
<div class="setting-saved-update-alert milinglist"></div>
</form>
</div>
<?php } else if($page == 'req') { ?>
<div class="req">
<table class="table table-hover">
<thead>
<tr>
<th class="bold">Name</th>
<th class="bold">Description</th>
<th class="bold">Status</th>
</tr>
</thead>
<tbody>
<tr>
<td>PHP 5.6+</td>
<td>A version <b>PHP 5.6</b> or higher is required.</td>
<td><?php echo ($php == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> No Installed</font>'?></td>
</tr>
<tr>
<td>cURL</td>
<td>Extension required <b>cURL</b>.</td>
<td><?php echo ($cURL == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> No Installed</font>'?></td>
</tr>
<tr>
<td>MySQLi</td>
<td>Extension required <b>MySQLi</b>.</td>
<td><?php echo ($mysqli == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> No Installed</font>'?></td>
</tr>
<tr>
<td>GD Library</td>
<td>The library <b>GD</b> is required for image cropping.</td>
<td><?php echo ($gd == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> No Installed</font>'?></td>
</tr>
<tr>
<td>Mbstring</td>
<td>The extension <b>Mbstring</b> is required for chains <b>UTF-8</b>.</td>
<td><?php echo ($mbstring == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> No Installed</font>'?></td>
</tr>
<tr>
<td>ZIP</td>
<td>The <b>ZIP</b> extension is required to make backups.</td>
<td><?php echo ($zip == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Installed</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> No Installed</font>'?></td>
</tr>
<tr>
<td>allow_url_fopen</td>
<td>The function is required <b>allow_url_fopen</b>.</td>
<td><?php echo ($allow_url_fopen == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Activate</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Deactivated</font>'?></td>
</tr>
<tr>
<td>.htaccess</td>
<td>The <b>.htaccess</b> file is required for security of the <small> script (located at ./script).</small></td>
<td><?php echo ($is_htaccess == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Added</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not added</font>'?></td>
</tr>
<tr>
<td>einet_anake.sql</td>
<td>The file <b>einet_anake.sql</b> is required for the installation <small>(located at ./script).</small></td>
<td><?php echo ($is_sql == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Added</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not added</font>'?></td>
</tr>
<tr>
<td>db_con.php</td>
<td>It is required that the file <b>db_with.php</b> has editing permissions.</td>
<td><?php echo ($is_writable == true) ? '<font color="green"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9.707 17.707l10-10-1.414-1.414L9 15.586l-4.293-4.293-1.414 1.414 5 5a.997.997 0 0 0 1.414 0z"/></svg> Editable</font>' : '<font color="red"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M6.707 18.707L12 13.414l5.293 5.293 1.414-1.414L13.414 12l5.293-5.293-1.414-1.414L12 10.586 6.707 5.293 5.293 6.707 10.586 12l-5.293 5.293z"/></svg> Not editable</font>'?></td>
</tr>
</tbody>
</table>
<br>
<form action="?page=installation" method="post">
<button type="submit" class="btn btn-main" id="next-terms" <?php echo ($disabled == true) ? 'disabled': '';?>>Next <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
<div class="setting-saved-update-alert milinglist"></div>
</form>
</div>
<?php } else if($page == 'finish') { ?>
<div class="req">
<p>¡Congratulations! <b>Einet Anake</b> has been successfully installed, your website is ready.</p>
<p>It's time to log in and go to the administrator's panel to make additional settings.</p>
<br>
<p>If you have any questions <a href="mailto:eiinet@hotmail.com" class="main">Let us know</a>.</p>
<br><br>
<a href="../login" class="btn btn-main" style="line-height: 50px;">Ready! Let's do it. <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></a>
</div>
<?php } else if($page == 'installation') { ?>
<div class="req">
<?php
if (!empty($ServerErrors)) {
?>
<div class="alert alert-danger">
<?php
foreach ($ServerErrors as $value) {
echo '- '.$value."<br>";
}
?>
</div>
<?php } else if(!empty($success)) { ?>
<div class="alert alert-success">
<i class="fa fa-check"></i> <?php echo $success;?>
<script type="text/javascript">
var URL = '?page=finish';
var delay = 1000;
setTimeout(function(){ window.location = URL; }, delay);
</script>
</div>
<?php } ?>
<form action="?page=installation" method="post" class="form-horizontal install-site-setting">
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">SQL Server</label>
<div class="col-md-6">
<input type="text" class="form-control" name="sql_host" value="<?php echo (!empty($_POST['sql_host'])) ? $_POST['sql_host']: '';?>" placeholder="sqlxxx.domain.com">
</div>
</div>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">SQL Username</label>
<div class="col-md-6">
<input type="text" class="form-control" name="sql_user" value="<?php echo (!empty($_POST['sql_user'])) ? $_POST['sql_user']: '';?>" placeholder="username">
</div>
</div>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">SQL Password</label>
<div class="col-md-6">
<input type="password" class="form-control" name="sql_pass" value="<?php echo (!empty($_POST['sql_pass'])) ? $_POST['sql_pass']: '';?>" placeholder="password">
</div>
</div>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">SQL Data base</label>
<div class="col-md-6">
<input type="text" class="form-control" name="sql_name" value="<?php echo (!empty($_POST['sql_name'])) ? $_POST['sql_name']: '';?>" placeholder="einet_anake">
</div>
</div>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">SQL Prefix</label>
<div class="col-md-6">
<input type="text" class="form-control" name="sql_prefix" value="<?php echo (!empty($_POST['sql_prefix'])) ? $_POST['sql_prefix']: 'ea_';?>" placeholder="ea_">
</div>
</div>
<hr>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">Site Name</label>
<div class="col-md-6">
<input type="text" class="form-control" name="site_title" value="<?php echo (!empty($_POST['site_title'])) ? $_POST['site_title']: '';?>" placeholder="Anake">
</div>
</div>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">Site Description</label>
<div class="col-md-6">
<input type="text" class="form-control" name="site_desc" value="<?php echo (!empty($_POST['site_desc'])) ? $_POST['site_desc']: '';?>" placeholder="WHM">
</div>
</div>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">Site Email</label>
<div class="col-md-6">
<input type="text" class="form-control" name="site_email" value="<?php echo (!empty($_POST['site_email'])) ? $_POST['site_email']: '';?>" placeholder="email@domain.com">
</div>
</div>
<hr>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">Admin Username</label>
<div class="col-md-6">
<input type="text" class="form-control" name="admin_username" maxlength="20" value="<?php echo (!empty($_POST['admin_username'])) ? $_POST['admin_username']: '';?>" placeholder="username">
</div>
</div>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">Admin Password</label>
<div class="col-md-6">
<input type="password" class="form-control" name="admin_password" maxlength="64" value="<?php echo (!empty($_POST['admin_password'])) ? $_POST['admin_password']: '';?>" placeholder="password">
</div>
</div>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3">Admin Email</label>
<div class="col-md-6">
<input type="text" class="form-control" name="admin_email" maxlength="64" value="<?php echo (!empty($_POST['admin_email'])) ? $_POST['admin_email']: '';?>" placeholder="email@domain.com">
</div>
</div>
<div class="form-group">
<div class="col-md-1"></div>
<label class="col-md-3"></label>
<div class="col-md-6"><b>Note:</b> The installation process may take some time.</div>
</div>

<input type="hidden" name="install" value="install">
<br>
<div class="form-group last-btn">
<label class="col-md-4"></label>
<div class="col-md-8">
<button type="submit" onClick="SubmitButton();" class="btn btn-main" <?php echo ($disabled == true) ? 'disabled': '';?>>Install <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"></path></svg></button>
</div>
</div>
</form>
</div>
<?php } ?>
</div>
</div>
</div>
</div>
<div class="col-md-1"></div>
</div>
</div>

<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 800 800" class="finish_confetti"> <g class="confetti-cone"> <path class="conf0" d="M131.5,172.6L196,343c2.3,6.1,11,6.1,13.4,0l65.5-170.7L131.5,172.6z"/> <path class="conf1" d="M131.5,172.6L196,343c2.3,6.1,11,6.1,13.4,0l6.7-17.5l-53.6-152.9L131.5,172.6z"/> <path class="conf2" d="M274.2,184.2c-1.8,1.8-4.2,2.9-7,2.9l-129.5,0.4c-5.4,0-9.8-4.4-9.8-9.8c0-5.4,4.4-9.8,9.9-9.9l129.5-0.4 c5.4,0,9.8,4.4,9.8,9.8C277,180,275.9,182.5,274.2,184.2z"/> <polygon class="conf3" points="231.5,285.4 174.2,285.5 143.8,205.1 262.7,204.7 "/> <path class="conf4" d="M166.3,187.4l-28.6,0.1c-5.4,0-9.8-4.4-9.8-9.8c0-5.4,4.4-9.8,9.9-9.9l24.1-0.1c0,0-2.6,5-1.3,10.6 C161.8,183.7,166.3,187.4,166.3,187.4z"/> <ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -89.8523 231.0278)" class="conf2" cx="233.9" cy="224" rx="5.6" ry="5.6"/> <path class="conf5" d="M143.8,205.1l5.4,14.3c6.8-2.1,14.4-0.5,19.7,4.8c7.7,7.7,7.6,20.1-0.1,27.8c-1.7,1.7-3.7,3-5.8,4l11.1,29.4 l27.7,0l-28-80.5L143.8,205.1z"/> <path class="conf2" d="M169,224.2c-5.3-5.3-13-6.9-19.7-4.8l13.9,36.7c2.1-1,4.1-2.3,5.8-4C176.6,244.4,176.6,231.9,169,224.2z"/> <ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -119.0946 221.1253)" class="conf6" cx="207.4" cy="254.3" rx="11.3" ry="11.2"/> </g> <rect x="113.7" y="135.7" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -99.5348 209.1582)" class="conf7" width="178" height="178"/> <line class="conf7" x1="76.8" y1="224.7" x2="328.6" y2="224.7"/> <polyline class="conf7" points="202.7,350.6 202.7,167.5 202.7,98.9 "/><circle class="conf2" id="b1" cx="195.2" cy="232.6" r="5.1"/> <circle class="conf0" id="b2" cx="230.8" cy="219.8" r="5.4"/> <circle class="conf0" id="c2" cx="178.9" cy="160.4" r="4.2"/> <circle class="conf6" id="d2"cx="132.8" cy="123.6" r="5.4"/> <circle class="conf0" id="d3" cx="151.9" cy="105.1" r="5.4"/> <path class="conf0" id="d1" d="M129.9,176.1l-5.7,1.3c-1.6,0.4-2.2,2.3-1.1,3.5l3.8,4.2c1.1,1.2,3.1,0.8,3.6-0.7l1.9-5.5 C132.9,177.3,131.5,175.7,129.9,176.1z"/> <path class="conf6" id="b5" d="M284.5,170.7l-5.4,1.2c-1.5,0.3-2.1,2.2-1,3.3l3.6,3.9c1,1.1,2.9,0.8,3.4-0.7l1.8-5.2 C287.4,171.9,286.1,170.4,284.5,170.7z"/> <circle class="conf6" id="c3"cx="206.7" cy="144.4" r="4.5"/> <path class="conf2" id="c1" d="M176.4,192.3h-3.2c-1.6,0-2.9-1.3-2.9-2.9v-3.2c0-1.6,1.3-2.9,2.9-2.9h3.2c1.6,0,2.9,1.3,2.9,2.9v3.2 C179.3,191,178,192.3,176.4,192.3z"/> <path class="conf2" id="b4" d="M263.7,197.4h-3.2c-1.6,0-2.9-1.3-2.9-2.9v-3.2c0-1.6,1.3-2.9,2.9-2.9h3.2c1.6,0,2.9,1.3,2.9,2.9v3.2 C266.5,196.1,265.2,197.4,263.7,197.4z"/><path id="yellow-strip" d="M179.7,102.4c0,0,6.6,15.3-2.3,25c-8.9,9.7-24.5,9.7-29.7,15.6c-5.2,5.9-0.7,18.6,3.7,28.2 c4.5,9.7,2.2,23-10.4,28.2"/> <path class="conf8" id="yellow-strip" d="M252.2,156.1c0,0-16.9-3.5-28.8,2.4c-11.9,5.9-14.9,17.8-16.4,29c-1.5,11.1-4.3,28.8-31.5,33.4"/> <path class="conf0" id="a1" d="M277.5,254.8h-3.2c-1.6,0-2.9-1.3-2.9-2.9v-3.2c0-1.6,1.3-2.9,2.9-2.9h3.2c1.6,0,2.9,1.3,2.9,2.9v3.2 C280.4,253.5,279.1,254.8,277.5,254.8z"/> <path class="conf3" id="c4" d="M215.2,121.3L215.2,121.3c0.3,0.6,0.8,1,1.5,1.1l0,0c1.6,0.2,2.2,2.2,1.1,3.3l0,0c-0.5,0.4-0.7,1.1-0.6,1.7v0 c0.3,1.6-1.4,2.8-2.8,2l0,0c-0.6-0.3-1.2-0.3-1.8,0h0c-1.4,0.7-3.1-0.5-2.8-2v0c0.1-0.6-0.1-1.3-0.6-1.7l0,0 c-1.1-1.1-0.5-3.1,1.1-3.3l0,0c0.6-0.1,1.2-0.5,1.5-1.1v0C212.5,119.8,214.5,119.8,215.2,121.3z"/> <path class="conf3" id="b3" d="M224.5,191.7L224.5,191.7c0.3,0.6,0.8,1,1.5,1.1l0,0c1.6,0.2,2.2,2.2,1.1,3.3v0c-0.5,0.4-0.7,1.1-0.6,1.7l0,0 c0.3,1.6-1.4,2.8-2.8,2h0c-0.6-0.3-1.2-0.3-1.8,0l0,0c-1.4,0.7-3.1-0.5-2.8-2l0,0c0.1-0.6-0.1-1.3-0.6-1.7v0 c-1.1-1.1-0.5-3.1,1.1-3.3l0,0c0.6-0.1,1.2-0.5,1.5-1.1l0,0C221.7,190.2,223.8,190.2,224.5,191.7z"/> <path class="conf3" id="a2" d="M312.6,242.1L312.6,242.1c0.3,0.6,0.8,1,1.5,1.1l0,0c1.6,0.2,2.2,2.2,1.1,3.3l0,0c-0.5,0.4-0.7,1.1-0.6,1.7v0 c0.3,1.6-1.4,2.8-2.8,2l0,0c-0.6-0.3-1.2-0.3-1.8,0h0c-1.4,0.7-3.1-0.5-2.8-2v0c0.1-0.6-0.1-1.3-0.6-1.7l0,0 c-1.1-1.1-0.5-3.1,1.1-3.3l0,0c0.6-0.1,1.2-0.5,1.5-1.1v0C309.9,240.6,311.9,240.6,312.6,242.1z"/> <path class="conf8" id="yellow-strip" d="M290.7,215.4c0,0-14.4-3.4-22.6,2.7c-8.2,6.2-8.2,23.3-17.1,29.4c-8.9,6.2-19.8-2.7-32.2-4.1 c-12.3-1.4-19.2,5.5-20.5,10.9"/> </g> </svg>

</body>
</html>
<style>
button:disabled {
color: #fff !important;
}
</style>
<script>
function SubmitButton() {
$('button').attr('disabled', true);
$('button').text('Please wait..');
$('form').submit();
}
$(function() {
$('#agree').change(function() {
if($(this).is(":checked")) {
$('#next-terms').attr('disabled', false);
} else {
$('#next-terms').attr('disabled', true);
}
});
});
</script>
<style>
.btn-main {
color: #ffffff;
background-color: #8E24AA;
border-color:#8E24AA;
}
.btn-main:disabled {
color: #333;
border: none;
}
.btn-main:hover {
color: #ffffff;
background-color: #A72AC7;
border-color:#A72AC7;
}
font[color="green"] {
	color:#1DC028;
}
.setting-well b {
	font-weight:580;
}
</style>
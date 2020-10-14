<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: c.core.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

# TODAS LAS FUNCIONES:
#01. get_nucleo()
#02. get_tema()
#03. get_news()
#04. black_list()
#05. get_admin()
#06. getTitle()
#07. setProtect()
#08. exist_install()
#09. setLevel()
#10. redirect()
#11. getLink()
#12. send_email()
#13. getPag()
#14. parse_BBCode()
#15. parse_censurar()
#16. setSEO()
#17. get_content()
#18. clean_folder()
#19. delete_folder()
#20. delete_files()
#21. get_encrypt()
#22. get_decrypt()
#23. extraer()
#24. get_IP()
#25. get_value()
#26. plantilla_email()
#27. delete_sesions()
#28. tareas_globales()
#29. update_version()
#30. is_serialized()
#31. reseller_api()
#32. get_birthday()
#00. limpiar_variables()

class tsCore {
    var $settings;# VARIABLE DE CONFIGURACIONES
	var $table = tables;# TABLAS DB
    
	function __construct() {
	global $tsIcons;
	# CARGAMOS CONFIGURACIONES DEL NUCLEO
	$this->limpiar_variables();# Limpiar vars jquery
    $this->settings = $this->get_nucleo();
	$this->settings['url'] = EA_URL;# Direccion del dominio
	$this->settings['domain'] = $this->settings['url'];
	$this->settings['default'] = $this->settings['url'].'/temas/default';
	$this->settings['cod'] = $this->settings['cod_type'];
	$this->settings['tema'] = $this->get_tema();# El tema
	$this->settings['img']  = $this->settings['tema']['t_url'].'/img';
	$this->settings['css']  = $this->settings['tema']['t_url'].'/css';
	$this->settings['js'] = $this->settings['tema']['t_url'].'/js';
	$this->settings['date'] = time();# Tiempo actual
	$this->settings['ip'] = $this->get_IP();# IP del usuario
	$this->settings['news'] = $this->get_news();# Noticias de la web
	$this->settings['getLink'] = urldecode($this->getLink());# Enlace donde estamos
	$this->settings['install'] = $this->exist_install();# Alertas de instalacion
	# SI TIENE UN PLAN PENDIENTE (globo)
	if($_SESSION['pid'] > 0) $this->settings['p_plan'] = 1;
	}
	
	
	#01. NUCLEO DE LA WEB
    function get_nucleo() {
	$query = anaK('query', 'SELECT * FROM '.$this->table['00'], array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	
	// Extramos datos de los arrays()
	$activos = unserialize($data['activos']);
	// API servicios
	$data['api'] = unserialize($data['api_services']);
	// API reseller
	$data['cpanel'] = unserialize($data['cp']);
	// SMTP servicio email
	$data['smtp'] = unserialize($data['smtp']);
	$data['smtp']['password'] = $this->get_decrypt($data['smtp']['password']);
	
	// Creamos items|campos
	if($activos) {
	foreach($activos as $nombre => $valor) {
	$data[] = $data[$nombre] = $valor;// Ejemplo: $data['id'] = 0;
	//
	}
	//
	}
	//
	return $data;
    }
	
	
	#02. THEME WEB
    function get_tema() {
	$query = anaK('query', 'SELECT * FROM '.$this->table['01'].' WHERE t_id = \''.$this->setProtect($this->settings['tema_id']).'\' LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
    $data['t_url'] = $this->settings['url'].'/temas/'.$data['t_path'];
    return $data;
    }
	
	
	#03. NOTICIAS DE LA WEB
    function get_news() {
	$query = anaK('query', 'SELECT not_body FROM '.$this->table['02'].' WHERE not_active = \'1\' ORDER by RAND()', array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
	$row['not_body'] = $this->parse_BBCode($row['not_body'], 'news');
	$data[] = $row;
	}
	return $data;
    }
	
	
	#04. USER|IP|EMAIL|PROVEEDOR|BLOQUEADO|VALIDAR IP
	function black_list($id_obj = null, $type = null) {
	if(!$id_obj) die('0: This action cannot be executed. Error code: BL001/ID');
    elseif(!(int)$type) die('0: This action cannot be executed. Error code: BL002/TP');
	
	$data = anaK('num_rows', anaK('query', 'SELECT id FROM '.$this->table['03'].' WHERE b_type = \''.$this->setProtect((int)$type).'\' && b_value = \''.$this->setProtect($id_obj).'\' LIMIT 1', array(__FILE__, __LINE__)));
	$result = ($data > 0) ? true : false;
	return $result;
	}
	
	
	#05. PRIMER ADMIN + ACTIVO
	function get_admin() {
	$query = anaK('query', 'SELECT user_id, user_nick, user_name FROM '.$this->table['08'].' WHERE user_id >= \'1\' && user_rango = \'1\' && user_activo = \'1\' && user_baneado = \'0\' ORDER BY user_online DESC LIMIT 1', array(__FILE__, __LINE__));
	$data = anaK('fetch_assoc', $query);
	return $data;
	//
	}
	
	
	#06. TITULO PARA LAS SECCIONES
    function getTitle($title, $more = null) {
    $tsData = array(
    'mantenimiento' => 'Maintenance mode initiated.', 
	'principal' => 'Home page.', 
    'login' => 'Sign in.', 
	'registro' => 'Create your account.', 
    'ajustes' => 'Your account settings '.$more.'.', 
    'admin' => 'Administration area.', 
	'mensajes' => 'Message list.', 
	'notifications' => 'Notifications monitor.', 
	'panel' => 'Customer area.',
    );	
    $newTitle = $tsData[$title];
    return $newTitle;
    }
	
	
	#07. SET SECURE
    public function setProtect($var, $xss = false) {
	$var = anaK('real_escape_string', function_exists('magic_quotes_gpc') ? stripslashes($var) : $var);
    return $var;
    }
	
	
	#08. ALERTA INSTALADOR|UPDATE
	function exist_install() {
	$install = EA_ROOT.'/install/';
	$upgrade = EA_ROOT.'/upgrade/';
	if(is_dir($install)) return '<div id="msg-install" class="notification is-new">Please delete the folder <b>install</b></div>';		
	if(is_dir($upgrade)) return '<div id="msg-install" class="notification is-new">Please delete the folder <b>upgrade</b></div>';
	}
	
	
	#09. ERRORES SEGUN EL NIVEL DE LA PLANTILLA
    function setLevel($tsLevel, $msg = false) {
    global $tsUser;
	
	if($tsLevel == 0) return true;
    // SOLO VISITANTES
    elseif($tsLevel == 1) {
    if($tsUser->is_member == 0) return true;
    else {
    if($msg) $mensaje = 'This page can only be viewed by visiting users.';
    else $this->redirect('/');
	 }
    }
	// SOLO MIEMBROS
    elseif($tsLevel == 2) {
    if($tsUser->is_member == 1) return true;
    else {
    if($msg) $mensaje = 'You must login to access this page.';
    else $this->redirect('/login/?redirect='.$this->getLink());
     }
    }
	// SOLO MODERADORES
	elseif($tsLevel == 3) {
	if($tsUser->is_admod == 1 || $tsUser->is_admod == 2) return true;
	else {
	if($msg) $mensaje = 'Sorry but this section is for moderators only.';
	else $this->redirect('/login/?redirect='.$this->getLink());
	 }
	}
	// SOLO ADMINISTRADORES
    elseif($tsLevel == 4) {
	if($tsUser->is_admod == 1) return true;
    else {
    if($msg) $mensaje = 'Excuse me, but you\'re trying to do something that\'s not allowed.';
    else $this->redirect('/login/?redirect='.$this->getLink());
      }
    }
    return array('titulo' => 'Sorry you cannot perform this action.', 'mensaje' => $mensaje);
	}
	
	
	#10. IR A DONDE ESTABAMOS
    function redirect($tsDir) {
    $tsDir = urldecode($tsDir);
    header("Location: $tsDir");
    exit();
    }
	
	
	#11. LINK DONDE ESTAMOS
	function getLink() {
    $is_ssl = is_ssl() ? 'https://' : 'http://';
	$current_url_domain = $_SERVER['HTTP_HOST'];
	$current_url_path = $_SERVER['REQUEST_URI'];
	$current_url_querystring = $_SERVER['QUERY_STRING'];
	$current_url = $is_ssl.$current_url_domain.$current_url_path;
	$current_url = urlencode($current_url);
	return $current_url;
	}
	
    
	#12. ENVIAR EMAIL LOCAL|REMOTO
    function send_email($tsData) {
	// Activamos smtp externo.
	if($this->settings['smtp']['type'] == 1) {
	// Validamos datos de la cuenta email.
	if(!mb_ereg("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $this->settings['smtp']['email']) || (strlen($this->settings['smtp']['password']) < 4))
	return '0: The SMTP service is not configured correctly please contact the administrator..';
	
	// Clase PHPMailer
    include EA_EXTRA.'email/PHPMailer.php';
	include EA_EXTRA.'email/SMTP.php';
	$email = new PHPMailer();
	
	// CONECTAMOS, A GMAIL|YANDEX|ETC
	$email->Username = $this->settings['smtp']['email'];
	$email->Password = $this->settings['smtp']['password'];
	$email->SMTPDebug = $this->settings['smtp']['debug'];#MODO DE ERRORES
	$email->SMTPSecure = $this->settings['smtp']['secure'];#CONEXION [tls|ssl|none]
	$email->Host = $this->settings['smtp']['host'];#SERVIDOR
	$email->Port = $this->settings['smtp']['port'];#PUERTO
	$email->isSMTP();#SMTP? SI
	$email->SMTPAuth = $this->settings['smtp']['auth'];#AUTENTICAMOS
	// Preparamos el remitente usuario o servidor.
	$email->setFrom($email->Username, $this->settings['titulo']);#EMAIL|NOMBRE DEL SERVER
	// Reemplazamos simbolos para enviar a varios email a la vez..
	$all_email = preg_split('/[\s,:;|]+/', $tsData['to'], -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	// Enviamos a todos los email agregados..
	$count_email = count($all_email);
	if($count_email > 1) {
	for($i=0; $i < $count_email; $i++) {
	$email->addBCC($all_email[$i]);
	}
	// Solo un email? Lo enviamos a ese.
	} else $email->addAddress($all_email[0]);
	$email->AltBody  = 'To view the message, use an HTML-compatible email viewer';
	$email->Subject = $tsData['subject'];#TITULO DEL MENSAJE
	// Cuerpo del mensaje.
	$email->Body = $tsData['body'];
	$email->isHTML($this->settings['smtp']['html']);#ES HTML? SI|NO [1|0]
	$email->CharSet = $this->settings['smtp']['cod'];#CODIFICACION UTF-8
	// Listo? enviamos el mensaje.....
	if($email->send() == 1) return '1: The message has been sent successfully..';
	else return '0: Remote server connection error. <b>'.$email->ErrorInfo.'</b>';#ERROR?
	//
	} else {
	
	// VERIFICAMOS SI LA FUNCION MAIL ESTA ACTIVA.
    if(!function_exists('mail')) return '0: The mail function is not active on the server.';
	// Validamos si recibimos los datos del email
	elseif(!$tsData) return '0: Error when trying to receive the email data.';
	
	// La cabecera, valida para enviar html
	$tsData['headers']  = "From: ".$this->settings['titulo']." <".$tsData['from'].">\r\n";#Remitente usuario o servidor.
	$tsData['headers'] .= "Reply-To: ".$tsData['from']."\r\n";#Email por si le responden
	$tsData['headers'] .= "MIME-Version: 1.0\r\n";#Version del codigo
	$tsData['headers'] .= "Content-Type: text/html; charset=".$this->settings['cod']."\r\n";#Codificación.
	// Enviar mensaje a varios email...
	$tsData['emails'] = str_replace(array(' ',':',';','|'), array('',',',',',','), $tsData['to']);
	// Validamos si se envio el email.
    if(@mail($tsData['emails'], $tsData['subject'], $tsData['body'], $tsData['headers'])) 
	return '1: The message has been sent successfully..';
	
	else 
	return '0: Error occurred. Message could not be sent, try again in a few minutes..';
	//
	}
	//
	}
	     
    
	#13. CREAR PAGINADO
    function getPag($todas, $limite = 15) {
    // Tipo de entrada recibida.
	$method = empty($_GET['pagina']) ? $_POST['pagina'] : $_GET['pagina'];
	// Pagina actual que recibimos de la entrada.
	$pagina = empty($method) ? 1 : $method;
	// Recibimos todos los objetos y los dividimos por el limite de paginas.
	$paginas = empty($todas) ? 1 : ceil($todas / $limite);
	// Obtenemos el resultado real con el que vamos a equilibrar el paginado.
    $anterior = $pagina - 1;
    $pag['ant'] = ($pagina > 0) ? $anterior : 0;
    // Para el boton Anterior.
    $siguiente = $pagina + 1;
    $pag['sig'] = ($siguiente <= $paginas) ? $siguiente : 0;
    // Para el boton Siguiente. 
    $pag['limite'] = (($pagina - 1) * $limite).','.$limite;
	// Limite de paginas que vamos a enseñar en el resultado.
    $pag['todas'] = $todas;
	// Todos los resultados encontrados en numeros con esa palabra.
    $pag['actual'] = $pagina;
	// Todas las paginas disponibles.
    $pag['paginas'] = $paginas;
	// Generamos numeros aleatoreos con el limite de paginas, para (ALEATOREOS)...
	$pag['rand'] = mt_rand(1, $paginas);
    return $pag;
    }
	
	
	#14. REEMPLAZAR ICONOS|IMAGENES|URL|ETC.
    function parse_BBCode($bbcode, $type = 'normal') {
    // Clase BBCode
    include_once EA_EXTRA.'bbcode.php';
	$parser = new BBCode();
    switch($type) {
    // NORMAL
    case 'normal':
    $html = $parser->parseString($bbcode);
	// MENCIONES
    $html = $parser->setMenciones($html);
    break;
	// SIMPLE
	case 'simple':
    $html = $parser->simpleParse($bbcode);
	// EMOTICONES
	$html = $parser->parseSmiles($html);
	// MENCIONES
    $html = $parser->setMenciones($html);
	break;
	// NOTICIAS
	case 'news':
    $html = $parser->parseString($bbcode);
	// EMOTICONES
	$html = $parser->parseSmiles($html);
	break;
	// EMOTICONES
	case 'smiles':
	$html = $parser->parseSmiles($bbcode);
	// MENCIONES
    $html = $parser->setMenciones($html);
	break;
    }
    return nl2br($html);
    }
	
	
	#15. CENSURAR PALABRAS
	function parse_censurar($obj) {
	$tsData = unserialize($this->settings['web_censurar']);
	// No hay agregadas? No hacemos nada :3
	if($tsData) {
	foreach($tsData as $nombre => $valor) {
	$data[] = $data[$nombre] = $valor;
	// Si es de tipo 1 hacemos el cambio de palabras.
	if($data[$nombre]['p_type'] == 1) {
	$obj = str_ireplace($data[$nombre]['p_esta'], $data[$nombre]['p_otra'], $obj);
	//
	}
	//
	}
	//
	}
	return $obj;
	}
	
	
	#16. CONVERTIR CARACTERES LINK
    function setSEO($string, $max = false) {
    // ESPAÑOL
    $espanol = array('á','é','í','ó','ú','ñ');
    $ingles = array('á','é','í','ó','ú','ñ');
    // MINUS
    $string = str_replace($espanol, $ingles, $string);
    $string = trim($string);
	$string = trim(preg_replace('/[^ A-Za-z0-9_]/', '-', $string));
    $string = preg_replace('/[ \t\n\r]+/', '-', $string);
    $string = str_replace(' ', '-', $string);
    $string = preg_replace('/[ -]+/', '-', $string);
    //
    if($max) {
    $string = str_replace('-', '', $string);
    $string = strtolower($string);
    }
    //
    return $string;
    }
	
	
	#17. CARGAR DATOS DESDE URL
	function get_content($obj) {
	// Usamos curl o file?
	if(function_exists('curl_init')) {
	// User agent
	$useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; es-ES; rv:1.9) Gecko/2008052906 Firefox/3.0';
	// Abrir conexion  
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_URL, $obj);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$result = curl_exec($ch);
	curl_close($ch);
	} else {
	$result = @file_get_contents($obj);
	}
	return $result;
	}
	
	
	#18. LIMPIAR DIRECTORIO
    function clean_folder($dir) {
    // Algun directorio agregado?
    if(!$dir) die('0: This action cannot be executed. Error code: CO001/DL');
  
    $i = 0;
    $handle = opendir($dir);#abrimos el directorio
    while($file = readdir($handle)) {#leemos los archivos
  
    if(is_file($dir.$file)) {#identificamos los archivos
    // procedemos a eliminar por tipo
    if(@unlink($dir.$file)) {
    $i++;
    }
    //
    }
    //
    }
    // terminamos? cerramos
    closedir($handle);
    //  
    }
	
	
	#19. ELIMINAR DIRECTORIO|ARCHIVO, Ejemplo: (EA_ROOT.'/eldirectorio')
    function delete_folder($item, $type = null) {
    if(!$item) die('0: This action cannot be executed. Error code: CO002/DF');
	
	switch((int)$type) {
	case '':
	default:# ELIMINAR UNA CARPETA
	//<--
    if(!is_link($item)) {
    $item = realpath($item);
    }
    //
    $ok = true;
    if(is_link($item) || is_file($item)) 
    $ok = unlink($item);
    elseif(@is_dir($item)) {
   
    if(($handle = opendir($item)) === false) 
    die('0: Error opening the directory "'.basename($item).'" :(');
   
    while(($file = readdir($handle)) !== false) {
    if(($file==".." || $file==".")) continue;
   
    $new_item = $item."/".$file;
    if(!file_exists($new_item)) 
    die('0: Error in the directory "'.basename($item).'" no exist :(');
    if(@is_dir($new_item)) {
    $ok = $this->delete_folder($new_item);# repetimos el proceso
    } else {
    $ok = unlink($new_item);
    }
    //
    }
    //
    closedir($handle);
    $ok = @rmdir($item);
    }
	//-->
	break;
	case '1':# ELIMINAR UN ARCHIVO
	//<--
	if(is_file($item)) $ok = @unlink($item);
	//-->
	break;
	}
	//
    return $ok;
    }
	
	
	#20. ELIMINAR TODO EN DIRECTORIO, Ejemplo: (EA_ROOT.'/eldirectorio')
	function delete_files($root) {
	if(!$root) die('0: This action cannot be executed. Error code: CO003/DA');
	if(@is_dir($root)) {
	if($dir = opendir($root)) {
	while(($item = readdir($dir)) !== false) {
	if($item != '.' && $item != '..') {
	# NOMBRE DE ARCHIVOS|CARPETAS Y ELIMINAMOS
	$this->delete_folder($root.$item);
	}
	//
	}
	//
	closedir($dir);
	}
	//
	}
	//
	}
	
	
	#21. ENCRYPT CODE
	function get_encrypt($obj) {
	$key = hash('sha512', KEY_SEC);
	$ill = substr(hash('sha512', ILL_SEC), 0, 16);
	$out = openssl_encrypt($obj, METODO, $key, 0, $ill);
	$encrypt_pass = base64_encode($out);
	return $encrypt_pass;
	//	
	}
	
	
	#22. DECRYPT CODE
	function get_decrypt($obj) {
	$key = hash('sha512', KEY_SEC);
	$ill = substr(hash('sha512', ILL_SEC), 0, 16);
	$decrypt_pass = openssl_decrypt(base64_decode($obj), METODO, $key, 0, $ill);
	return $decrypt_pass;
	//	
	}
	
	
	#23. EXTRAER .ZIP Y MOVER A DIRECTORIO
	function extraer($obj, $dir, $action = null) {
	if(!$dir) return '0: This action cannot be executed. Error code: NO/DIR';
	elseif(!$obj) return '0: This action cannot be executed. Error code: NO/FILE';
	elseif(!file_exists($dir)) return '0: I\'m sorry, but the directory "'.$dir.'" no exist..';
	
	$zip = new ZipArchive;
	if($zip->open($obj) === true) {
	$zip->extractTo($dir);
	$zip->close();
	// Si la accion es 1 eliminamos el comprimido despues de extraer
	if($action == '1') $this->delete_folder($obj, 1);// 1 es archivo
	return true;
	} else {
	return false;
	}
	//
	}
	
	
	#24. IP DE USUARIO
    function get_IP() {
	// Metodos para obtener el IP
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
	// Leemos cada uno en orden
	foreach($tsIPKey as $key) {
	// Verificamos si existen 1 o mas IPS entrantes
	if(array_key_exists($key, $_SERVER) === true) {
	// Tomamos la primera en caso que sean mas de una.
	foreach(explode(',', $_SERVER[$key]) as $tsIP) {
	// Validamos, La nueva ip es valida?
	if(filter_var($tsIP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)!== false) {		
    // Quitamos espacios y elementos extraños y pasamos el IP.
    return trim($tsIP);
	
	} elseif(trim($tsIP) === '::1') {
	// Si la ip es local la pasamos para php7
	return trim($tsIP);
	}
	//
	}
	//
	}
	//
    }
	// Si no se obtuvo una ip valida detenemos todo
    return die('<h2>Error when trying to validate your IP.</h2>');
	}
	
	
	#25. RECORER ARRAY, UPDATE MySQLi
	function get_value($array, $pfx_table = '') {
	// Nombres de los campos.
	$nombre = array_keys($array);
	// Valores de las tablas.
	$valor = array_values($array);
	// Numericos y caracteres.
	foreach($valor as $i => $value) {
	$items[$i] = $pfx_table.$nombre[$i]." = '".$this->setProtect($value.'')."'";
	}
	$data = implode(', ',$items);
	return $data;
	}
	
	
	#26. PLANTILLA PARA EMAIL
	function plantilla_email($tsData = null) {
	if(!$tsData) exit('0: No data received, error code: PL001/ML');
	
	// Estilos y colores para el HTML
	$css = array('width:700px;table-layout:fixed;margin:0 auto;padding:10px;border:0;font-family:Segoe UI,Tahoma,sans-serif;border:1px solid #F5F5F5;',
	'font-size:0;background-color:#18B2E8;',
	'padding:70px 40px 40px 40px;color:#444444;font-size:14px;background:#FFFFFF;line-height:1.3em;border-top:1px solid #F5F5F5;',
	'display:block;',
	'padding:30px 30px 30px 30px;font-size:13px;font-weight:bold;line-height:18px;border-top:1px solid #F5F5F5;background-color:#FFFFFF;text-align:center;',
	);
	
	// HTML para el mensaje
	$html  = '<table cellpadding="0" cellspacing="0" style="'.$css[0].'">';
	$html .= '<tbody>';
	$html .= '<tr><td style="'.$css[1].'">';
	$html .= '<a href="'.$this->settings['url'].'" target="_blank">';
	$html .= '<img src="'.$this->settings['url'].'/default/logo-promo.jpg" style="width:100%;" border="0" alt="'.$this->settings['titulo'].'"/></a></td></tr>';
	$html .= '<tr><td style="'.$css[2].'">';
	$html .= $tsData;
	$html .= '<br /><span style="'.$css[3].'"><b>Best regards.</b><br />';
	$html .= '<i>The team at <b>'.$this->settings['titulo'].'</b> - '.$this->settings['description'].'</i></span></td></tr>';
	$html .= '<tr><td style="'.$css[4].'">© '.date('Y').' '.$this->settings['titulo'].' - All rights reserved.</td></tr>';
	$html .= '</tbody>';
	$html .= '</table>';
	return $html;
	}
	
	
	#27. ELIMINAR (mantenimiento)
	function delete_sesions() {
	// El modo mantenimiento esta activo?
	if($this->settings['web_on'] == 1) {
	// Vaciamos lo que esta dentro de cache.
	$this->delete_files(EA_ROOT.'/cache/');
	// Vaciamos lo que esta dentro de others.
	$this->clean_folder(EA_FILES.'/tmp/others/');
	// Limpiamos la tabla de las ips.
	anaK('query', 'TRUNCATE TABLE '.$this->table['04'], array(__FILE__, __LINE__));
	// Limpiamos la tabla de las sesiones.
	anaK('query', 'TRUNCATE TABLE '.$this->table['13'], array(__FILE__, __LINE__));
	// Activamos de nuevo la web si finalizo el mantenimiento (programado)
	if($this->settings['web_over'] > 0 && ($this->settings['date'] >= $this->settings['web_over'])) {
	// Actualizamos datos en la tabla de configuraciones....
	anaK('query', 'UPDATE '.$this->table['00'].' SET web_over = \'0\', web_on = \'0\' WHERE id = '.$this->setProtect((int)$this->settings['id']), array(__FILE__, __LINE__));
	//
	}
	//
	}
	//
	}
	
	
	#28. TAREAS GLOBALES & PROGRAMADAS
	function tareas_globales() {
	global $tsUser;
	// Tiempo programable.
	$time = array(
	'-3mins' => $this->settings['date'] - 180,# RESTAMOS 3 MINUTOS
	'+1dias' => $this->settings['date'] + (1*24*60*60),# SUMAMOS 1 DIA
	'-1dias' => $this->settings['date'] - (1*24*60*60),# RESTAMOS 1 DIA
	'-3dias' => $this->settings['date'] - (3*24*60*60),# RESTAMOS 3 DIAS
	'-30dias' => $this->settings['date'] - (30*24*60*60),#RESTAMOS 30 DIAS
	);
	
	// Agregar visita de usuario.
	$tsUser->get_visita(0, 1);
	// Limpiamos todo si esta en mantenimiento......
	$this->delete_sesions();
	
	// Contamos todos los usuarios online.
	$all = anaK('num_rows', anaK('query', 'SELECT id FROM '.$this->table['04'].' WHERE ip_type = \'1\' && ip_date >= '.$this->setProtect($time['-3mins']), array(__FILE__, __LINE__)));
	
	// Actualizamos la lista de usuarios.
    anaK('query', 'UPDATE '.$this->table['00'].' SET total_online = \''.$this->setProtect((int)$all).'\' WHERE id = '.$this->setProtect((int)$this->settings['id']), array(__FILE__, __LINE__));
	
	// Eliminamos las ips del dia anterior global.
    anaK('query', 'DELETE FROM '.$this->table['04'].' WHERE ip_type = \'1\' && ip_date < '.$this->setProtect($time['-1dias']), array(__FILE__, __LINE__));
	
	// Eliminamos las cuentas hosting, que tengan mas de 30 dias.
	anaK('query', 'DELETE FROM '.$this->table['16'].' WHERE cp_active = \'1\' && cp_over < '.$this->setProtect($this->settings['date']), array(__FILE__, __LINE__));
	
	// Eliminamos las notificaciones con mas de 30 dias.
	anaK('query', 'DELETE FROM '.$this->table['13'].' WHERE not_menubar = \'0\' && not_monitor = \'0\' && not_date < '.$this->setProtect($time['-30dias']), array(__FILE__, __LINE__));
		
	// Eliminamos peticiones de validacion con mas de 30 dias.
	anaK('query', 'DELETE FROM '.$this->table['07'].' WHERE hash_time < '.$this->setProtect($time['-30dias']), array(__FILE__, __LINE__));

	// Comparamos fechas para limpiar directorios......
	if($this->settings['date'] >= $this->settings['fecha_limpieza']) {
	// Vaciamos lo que esta dentro de cache.
	$this->delete_files(EA_ROOT.'/cache/');
	// Vaciamos lo que esta dentro de others.
	$this->clean_folder(EA_FILES.'/tmp/others/');
	// Eliminamos usuarios inactivos.
	$tsUser->delete_user_inactive();
	// Actualizamos la proxima fecha de limpieza, mañana a la misma hora.
	anaK('query', 'UPDATE '.$this->table['00'].' SET fecha_limpieza = \''.$this->setProtect($time['+1dias']).'\' WHERE id = '.$this->setProtect((int)$this->settings['id']), array(__FILE__, __LINE__));
	//
	}
	//	
	}
	
	#29. ACTUALIZAR VERSION DE LA PAGINA
	function update_version($obj = null) {
	global $tsUser;
	
	if(!$obj['activos']) return '0: This action cannot be executed. Error code: UP001/ACT';
	elseif(!$obj['version']) return '0: This action cannot be executed. Error code: UP002/NWV';
	elseif(!$obj['version_code']) return '0: This action cannot be executed. Error code: UP003/NVC';
	elseif($tsUser->is_admod != 1) return '0: Sorry but you do not have the necessary permissions to perform this action..';
	// Si todo esta bien actualizamos datos.
	if(anaK('query', 'UPDATE '.$this->table['00'].' SET activos = \''.$obj['activos'].'\', version = \''.$this->setProtect($obj['version']).'\', version_code = \''.$this->setProtect($obj['version_code']).'\' WHERE id = \''.$this->setProtect($this->settings['id']).'\' LIMIT 1', array(__FILE__, __LINE__))) {
	
	} else return '0: '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	
	
	#30. ES SERIALIZE?
	function is_serialized($data, $strict = true) {
	// Si no es una cadena, no se serializa..
	if(!is_string($data)) {
	return false;
	}
	$data = trim($data);
	if('N;' == $data) {
	return true;
	}
	if(strlen($data) < 4) {
	return false;
	}
	if(':' !== $data[1]) {
	return false;
	}
	if($strict) {
	$lastc = substr($data, -1);
	if(';' !== $lastc && '}' !== $lastc) {
	return false;
	}
	} else {
	$semicolon = strpos($data, ';');
	$brace = strpos($data, '}');
	// Either; or } debe existir.
	if(false === $semicolon && false === $brace) {
	return false;
	}
	// Pero tampoco deben estar en los primeros X caracteres.
	if(false !== $semicolon && $semicolon < 3) {
	return false;
	}
	if(false !== $brace && $brace < 4) {
	return false;
	}
	}
	$token = $data[0];
	switch($token) {
	case 's':
	if($strict) {
	if('"' !== substr($data, -2, 1)) {
	return false;
	}
	} elseif(false === strpos($data, '"')) {
	return false;
	}
	// or else caer a través
	case 'a':
	case 'O':
	return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
	case 'b':
	case 'i':
	case 'd':
	$end = $strict ? '$' : '';
	return (bool)preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
	}
	return false;
	}
	
	
	#31. RESELLER WHM API CLIENT
	function reseller_api($tsData = null) {
	global $tsMP;
	
	// Identificamos el prefix del reseller para usar el api adecuada..
	if(is_array($this->settings['cpanel']['domain_list'])) {
	foreach($this->settings['cpanel']['domain_list'] as $i => $data) {
	if(preg_match('/('.$data['prefix'].')_/', $tsData['cp_user']) || preg_match('/([a-z0-9-]+)\.('.$data['name'].')$/i', $tsData['cp_domain'])) 
	$id = $i;
	//
	}
	//
	}
	// Api con la que vamos a consultar..
	$cp_id = ($id > 0 ? $id : 1);// no hay prefix? usamos el 1 entonces.
	// Validamos los datos de la o las api..
	if(strlen($this->settings['cpanel']['account'][$cp_id]['cp_user']) < 250) 
	$error = 'Username is invalid or misspelled..';
	
	elseif(strlen($this->settings['cpanel']['account'][$cp_id]['cp_pass']) < 250) 
	$error = 'Password is invalid or misspelled..';
	
	elseif(!preg_match('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/', $this->settings['cpanel']['cp_panel'])) 
	$error = 'Write a valid web address to connect to the reseller server..';
	
	// Hacemos una ultima verificacion, si hay conexion con el servidor remoto..
	stream_context_set_default(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);// Para nuevo php con https :|
	$checking = @get_headers($this->settings['cpanel']['cp_panel'], 1);// se agrego el 1
	if(strpos($checking[0], '200 OK') == false) 
	$error = 'The connection to the main server could not be established, try again in a few minutes..';
	
	// Si se produce algun error conectando al reseller se lo indicamos al admin..
	if(strlen($error) > 2) {
	$tsAdmin = $this->get_admin();
	$tsMensaje = array(
	'msg_user' => $tsAdmin['user_nick'], 
	'msg_title' => 'Error connecting to WHM API.',
	'msg_body' => 'Hi, '.$tsAdmin['user_nick'].' the site is having problems with the WHM reseller API, the error [b]'.$error.'[/b]',
	'msg_ip' => $this->settings['ip'],
	'msg_date' => $this->settings['date'],
	);
	// Le enviamos el mensaje al admin que este online.
	$tsMP->new_mensaje($tsMensaje);
	
	} else {
	// Todo bien? agregamos la libreria del reseller...
	include EA_EXTRA.'reseller/autoload.php';
	$cP = new \AnakeWHM\AnakeWhm\Client;
	$api = $cP->create([
	'apiUsername' => $this->settings['cpanel']['account'][$cp_id]['cp_user'], 
	'apiPassword' => $this->settings['cpanel']['account'][$cp_id]['cp_pass'], 
	'apiUrl' => $this->settings['cpanel']['cp_panel'],
	]);
	//
	return $api;
	}
	//	
	}
	
	
	#32. MOSTRAR CUMPLEAÑEROS
	function get_birthday() {
	// Año actual 
	$year = date('Y', $this->settings['date']);
	// Realizamos la consulta, cuantos cumpleañeros hay.
	$query = anaK('query', 'SELECT u.user_id, u.user_nick, u.user_name, u.user_online, p.p_year FROM '.$this->table['08'].' AS u LEFT JOIN '.$this->table['09'].' AS p ON p.user_id = u.user_id WHERE u.user_activo = \'1\' && u.user_baneado = \'0\' && p.p_dia = \''.$this->setProtect(date('d', $this->settings['date'])).'\' && p.p_mes = \''.$this->setProtect(date('m', $this->settings['date'])).'\'', array(__FILE__, __LINE__));
	while($row = anaK('fetch_assoc', $query)) {
	$row['edad'] = ($year - $row['p_year']);// Edad que cumple.
	// Status de conexion del usuario.
	$online = ($this->settings['date'] - ($this->settings['user_activo'] * 60));
	$absent = ($this->settings['date'] - (($this->settings['user_activo'] * 60) * 2));
	if($row['user_online'] > $online) $row['status'] = 'online';
	elseif($row['user_online'] > $absent) $row['status'] = 'absent';
	else $row['status'] = 'offline';
	$data[] = $row;
	}
	// Si se recibieron usuarios los contamos..
	if($data) {
	$total = count($data);
	}
	//
	return array('data' => $data, 'total' => $total);
	}
	
	
	#00.+---------------- Funciones para proteger de ataques XSS y CSRF -----------------+
	function limpiar_variables() {
	global $tsPage;
	// ¿Qué función revierte las citas mágicas? Si Sybase está activado, indicamos que la BD tiene la función correcta de escape.
	$removeMagicQuoteFunction = @ini_get('magic_quotes_sybase') || strtolower(@ini_get('magic_quotes_sybase')) == 'on' ? 'unescapestring__recursive' : 'stripslashes__recursive';
	
	// Guarda algo de memoria.. (No los usamos de todos modos)
	unset($GLOBALS['HTTP_POST_VARS'], $GLOBALS['HTTP_POST_VARS']);
	unset($GLOBALS['HTTP_POST_FILES'], $GLOBALS['HTTP_POST_FILES']);
	
	// No deberían estar configuradas.. nunca
	if(isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS'])) die('<h2>Invalid variable request.</h2>');
	
	// No deberían estar configuradas las teclas numéricas.
	foreach(array_merge(array_keys($_POST), array_keys($_GET), array_keys($_FILES)) as $key) 
	if(is_numeric($key)) die('<h2>Requesting invalid numerical variables.</h2>');
	
	// Las claves numéricas en cookies no son problema. Sólo desarmamos esos.
	foreach($_COOKIE as $key => $value) 
	if(is_numeric($key)) unset($_COOKIE[$key]);
	
	// Obtener la cadena de consulta correcta. Puede estar en una variable de entorno.
	if(!isset($_SERVER['QUERY_STRING'])) $_SERVER['QUERY_STRING'] = getenv('QUERY_STRING');
	
	// Parece que pegar una URL después de la cadena de consulta es muy común, es malo, no lo hagas.
	if(strpos($_SERVER['QUERY_STRING'], 'http') === 0) {
	header('HTTP/1.1 400 Bad Request');
	exit();
	}
	
	// Si hay citas mágicas, hacemos el trabajo de limpiar...
	if(function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc() != 0) {
	$_ENV = $removeMagicQuoteFunction($_ENV);
	$_POST = $removeMagicQuoteFunction($_POST);
	$_COOKIE = $removeMagicQuoteFunction($_COOKIE);
	foreach($_FILES as $k => $dummy) 
	if(isset($_FILES[$k]['name'])) $_FILES[$k]['name'] = $removeMagicQuoteFunction($_FILES[$k]['name']);
	//
	}
	
	// Añadir entidades a GET. Esto es un poco como las barras en todo lo demás.
	$_GET = $this->htmlspecialchars__recursive($_GET);
    $_POST = $this->htmlspecialchars__recursive($_POST);
    $_COOKIE = $this->htmlspecialchars__recursive($_COOKIE);
	
	// No dependamos de la configuración de INI... ¿por qué incluso hay COOKIES alli?
	$_REQUEST = $_POST + $_GET;
	
	// Compruebe si la solicitud proviene de este sitio
    $IsMySite = strpos(preg_replace('/https?:\/\/|www\./', '', $_SERVER['HTTP_REFERER']), preg_replace('/https?:\/\/|www\./', '', $_SERVER['HTTP_HOST'])) === 0;
	
	if((!empty($_SERVER['HTTP_REFERER']) && (in_array($tsPage, array('admin', 'moderacion', 'ajustes')) || $_SERVER['QUERY_STRING'] == 'action=user-salir') && !$IsMySite) || $_SERVER['REQUEST_METHOD'] === 'POST' && !$IsMySite) { 
	die('<h2>Invalid application.</h2>');
	}
	//	
	}
	
	# AÑADE BARRAS A LA MATRIZ|VARIABLE. Utiliza dos guiones bajos para protegerse contra la sobrecarga.
	function escapestring__recursive($var) {
	if(!is_array($var)) return addslashes($var);
	// Reindexar la matriz con barras diagonales.
	$new_var = array();
	// ¡Añade barras a cada elemento, incluso los índices!
	foreach($var as $k => $v) 
	$new_var[addslashes($k)] = $this->escapestring__recursive($v);
	//
	return $new_var;
	}
	
	# AGREGA ENTIDADES HTML A MATRIZ|VARIABLE. Utiliza dos guiones bajos para protegerse contra la sobrecarga.
	function htmlspecialchars__recursive($var, $level = 0) {
	if(!is_array($var)) return htmlspecialchars($var, ENT_QUOTES);
	// Agrega los htmlspecialchars a cada elemento.
	foreach($var as $k => $v) 
	$var[$k] = $level > 25 ? NULL : $this->htmlspecialchars__recursive($v, $level + 1);
	//
	return $var;
	}
	
	# ESCAPADO DE UNA MATRIZ O VARIABLE. DOS GUIONES BAJOS POR RAZON NORMAL.
	function unescapestring__recursive($var) {
	if(!is_array($var)) return stripslashes($var);
	// Reindexar la matriz sin barras diagonales, esta vez.
	$new_var = array();
	// Tira las barras de cada elemento.
	foreach($var as $k => $v) 
	$new_var[stripslashes($k)] = $this->unescapestring__recursive($v);
	//
	return $new_var;
	}
	
	# ELIMINAR BARRAS RECURSIVAMENTE
	function stripslashes__recursive($var, $level = 0) {
	if(!is_array($var)) return stripslashes($var);
	// Reindexar la matriz sin barras diagonales, esta vez.
	$new_var = array();
	// Tira las barras de cada elemento.
	foreach($var as $k => $v) 
	$new_var[stripslashes($k)] = $level > 25 ? NULL : $this->stripslashes__recursive($v, $level + 1);
	//
	return $new_var;
	}
	#+-----------------------------------------------------------------------------------+  
}
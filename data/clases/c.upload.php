<?php if(!defined('EA_HEADER')) exit('<h2>Oops! page not found</h2>');
#+----------------------------------------------------------+
#| Pagina: c.upload.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

# TODAS LAS FUNCIONES:
#01. upload_remote()
#02. upload_local()
#03. get_code_name()
#04. upload_avatar()
#05. upload_theme()

class tsUpload {
	var $max_size = 4194304;// 4MB 
	var $max_tema = 10485760;// 10MB
    var $image_size = array('w' => 720, 'h' => 620);// Dimensiones para imagen.
	var $server = array('imgur' => 'https://api.imgur.com/3/image.json', 'otro' => '');// Server de subida.
    
	#01. SUBIR IMAGENES REMOTO
    function upload_remote($image) {
    global $tsCore, $tsUser;
	
    $imagen = $_FILES['img']['tmp_name'][0];// La imagen.
	$client_id = $tsCore->settings['api']['imgur']['id'];// Api del server.
    $timeout = 30;
    $handle = fopen($imagen, 'r');
    $data = fread($handle, filesize($imagen));
    $params = array('image' => base64_encode($data));
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->server[$tsCore->settings['upload_server']]);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID '.$client_id));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $result = curl_exec($ch);
    curl_close($ch);
	
	// Extraemos contenido del json.
    $data = json_decode($result, true);
    
    // No se recibio ningun dato?
    if(!$data['data']['link']) {
    return '0: An error occurred while trying to upload the file..';
    
    } else {
    // Obtenemos el url para eliminar si es imgur.
    if($tsCore->settings['upload_server'] == 'imgur') 
    $data['data']['deletehash'] = $tsCore->setProtect('http://imgur.com/delete/'.$data['data']['deletehash']);
    
    // Guardamos datos de la imagen subida.
    anaK('query', 'INSERT INTO '.$tsCore->table['05'].' (u_type, u_control, u_imagen, u_delete, u_date, u_ip) VALUES (\'1\', \''.$tsCore->setProtect($tsUser->uid).'\', \''.$tsCore->setProtect($data['data']['link']).'\', \''.$tsCore->setProtect($data['data']['deletehash']).'\', \''.$tsCore->setProtect($data['data']['datetime']).'\', \''.$tsCore->setProtect($tsCore->settings['ip']).'\')', array(__FILE__, __LINE__));
    }
    //
    return $data;
    }
	
	
	#02. SUBIR IMAGENES LOCAL
    function upload_local($image, $dir = 0, $more = 0) {#more 1 [via ajax/json]
    global $tsCore, $tsUser;
	
	$all_images = count($_FILES['img']['name']);#contamos las imagenes.
	
	// Recorremos las imagenes que se han subido.
	for($i = 0; $i < $all_images; $i++) {
		
	// Leemos el formato de la imagen subida.
    $type = explode('/', $_FILES['img']['type'][$i]);

	$folder = array(
	'0' => 'posts/',#0 posts
	'1' => 'user/',#1 user
	'2' => 'muro/',#2 muro
	'3' => 'imagenes/',#3 imagenes
	'4' => 'others/',#4 temporales
	);
	
    // Generamos un nombre para la imagen.
    $name = $this->get_code_name();
	
	// Leemos las dimensiones de la imagen.
	$size = getimagesize($_FILES['img']['tmp_name'][$i]);
	$size_w = $size[0];
    $size_h = $size[1];
	// Leemos el peso de la imagen.
	$peso = $_FILES['img']['size'][$i];
	
    // Validamos algunos campos.
    if($peso > $this->max_size) {
	return '0: File size must be less than 4 MB..';
	
	} elseif($type[1] != 'jpg' && $type[1] != 'jpeg' && $type[1] != 'gif' && $type[1] != 'png') {
	return '0: Sorry but the file format is not valid..';
    
	} elseif(is_uploaded_file(!$_FILES['img']['tmp_name'][$i])) {
	return '0: Excuse me, but this file has already been uploaded..';
    //
	} else {
    
	// Directorio donde ira el archivo.
    $root = EA_FILES.'tmp/'.$folder[$dir];
	if(!file_exists($root)) mkdir($root, 0777, true);// El directorio no existe? lo creamos.

	// Unimos el nombre creado + el formato.
    $archivo_final = 'einet_'.$name.'.'.$type[1];
	// Explodeamos para el id y el formato final.
	$v = explode('.', $archivo_final);
	
    // Validamos las dimensiones de la imagen y la reducimos.
    if($size_w > $this->image_size['w'] || $size_h > $this->image_size['h']) {
    // Obtenemos la escala.
    if($size_w > $size_h) {
    $_height = ($size_h * $this->image_size['w']) / $size_w;
    $_width = $this->image_size['w'];
    } else {
    $_width = ($size_w * $this->image_size['h']) / $size_h;
    $_height = $this->image_size['h'];
    }
	
    // El formato de la imagen para recrearla de nuevo.
    switch($_FILES['img']['type'][$i]) {
    case 'image/jpeg':
    case 'image/jpg':
    $model = imagecreatefromjpeg($_FILES['img']['tmp_name'][$i]);
    break;
    case 'image/gif':
    $model = imagecreatefromgif($_FILES['img']['tmp_name'][$i]);
    break;
    case 'image/png':
    $model = imagecreatefrompng($_FILES['img']['tmp_name'][$i]);
    break;
    }
	
    // Escalamos y recreamos la imagen.
    $new = imagecreatetruecolor($_width, $_height); 
    imagecopyresampled($new, $model, 0, 0, 0, 0, $_width, $_height, $size_w, $size_h);
    imagejpeg($new, $root.$archivo_final, 100);// Creamos la imagen nueva con las dimesiones exactas y la movemos.
    imagedestroy($new);// Eliminamos la imagen creada del TMP.
    imagedestroy($model);// Eliminamos la imagen modelo.
	
	} else {
	// Si todo salio bien la movemos al directorio.
    move_uploaded_file($_FILES['img']['tmp_name'][$i], $root.$archivo_final);
	}
	
	if($more == 1) {# pasar por json/ajax [1]
	// En caso que no se modifique la imagen original.
	$_width = empty($_width) ? $size_w : $_width;// NO RECIBE, $_width? EL ORIGINAL, $size_w
	$_height = empty($_height) ? $size_h : $_height;// NO RECIBE, $_height? EL ORIGINAL, $size_h
	
	// Para subir una sola imagen.
	$data = '1 |'.$tsCore->settings['url'].'/files/tmp/'.$folder[$dir].$archivo_final.'|'.$_width.'|'.$_height.'|'.$tsCore->settings['date'].'|'.$tsCore->settings['url'].'/delete_file/'.$v[0];
		
	} else {
	// Generamos datos que vamos a pasar y guardar de la imagen.
    $data['link'][$i] = $tsCore->setProtect($tsCore->settings['url'].'/files/tmp/'.$folder[$dir].$archivo_final);
    $data['deletehash'][$i] = $tsCore->setProtect($tsCore->settings['url'].'/delete_file/'.$v[0]);
    $data['datetime'][$i] = $tsCore->setProtect($tsCore->settings['date']);
	
	// Guardamos datos de la imagen.
    anaK('query', 'INSERT INTO '.$tsCore->table['05'].' (u_type, u_control, u_imagen, u_delete, u_date, u_ip) VALUES (\'1\', \''.$tsCore->setProtect($tsUser->uid).'\', \''.$data['link'][$i].'\', \''.$data['deletehash'][$i].'\', \''.$data['datetime'][$i].'\', \''.$tsCore->setProtect($tsCore->settings['ip']).'\')', array(__FILE__, __LINE__));
	//
	}
	//
	}
	//
	}#->for
	return $data;
    }
	
	
	#03. GENERADOR DE NOMBRE
	function get_code_name() {
	$chars = 'abCdfghiJkLmnOp';
    $name = '';
    $max = strlen($chars)-1;
    
	for($i = 0; $i < 15; $i++) {
    $name .= $chars[mt_rand(0,$max)];
    }
	//
    return $name;
    }
	
	
	#04. SUBIR IMAGEN RECORTADA
    function upload_avatar() {
	global $tsCore, $tsUser;
	
    $tsData = array(
	'imagen' => $_POST['img_url'],#url de la imagen.
	'nombre' => $tsUser->uid,#id del usuario.
	'width' => 120,#ancho para la imagen normal. 
	'height' => 120,#alto para la imagen normal.
	't_width' => 50,#ancho para la imagen mini.
	't_height' => 50,#alto para la imagen mini.
	'x1' => $tsCore->setProtect($_POST['thumb_x1']),#punto inicial ancho
	'y1' => $tsCore->setProtect($_POST['thumb_y1']),#punto inicial alto
	'x2' => $tsCore->setProtect($_POST['thumb_x2']),#punto final ancho
	'y2' => $tsCore->setProtect($_POST['thumb_y2']),#punto final alto
	'w' => $tsCore->setProtect($_POST['thumb_w']),#punto standar ancho
	'h' => $tsCore->setProtect($_POST['thumb_h']),#punto standar alto
	'root' => EA_FILES.'perfiles/',// Directorio de perfiles.
    );
	// Dimensiones limite.
	$min_w = 120;
	$min_h = 120;
	$max_w = 2000;
	$max_h = 2000;
	
	$data['imagen'] = getimagesize($tsData['imagen']);// Para datos de la imagen.
	$data['format'] = $data['imagen']['mime'];// Formato de la imagen.
	$data['borrar'] = end(explode('/', $tsData['imagen']));// La imagen a eliminar.
	
	// Validamos algunos campos.
    if(empty($data['imagen'][0])) 
	return '0: The image does not exist or is not a valid image..';
	
	elseif($data['imagen'][0] < $min_w || $data['imagen'][1] < $min_h) 
	return '0: Minimum image dimensions, 120x120 pixels..';
	
	elseif($data['imagen'][0] > $max_w || $data['imagen'][1] > $max_h) 
	return '0: Maximum dimensions for the image, 2000x2000 pixels.';
	
	// El formato de la imagen.
	switch($data['format']) {
	case 'image/pjpeg':
	case 'image/jpeg':
	case 'image/jpg':
	$img = imagecreatefromjpeg($tsData['imagen']);
	break;
	
	case 'image/gif':
	$img =  imagecreatefromgif($tsData['imagen']);
	break;
	
	case 'image/png':
	case 'image/x-png':
	$img =  imagecreatefrompng($tsData['imagen']);
	break;
	}
	$width = imagesx($img);#ancho
    $height = imagesy($img);#alto
	// Imagen 120px
    $avatar = imagecreatetruecolor($tsData['width'], $tsData['height']);// Creamos la imagen.
	imagecopyresampled($avatar, $img, 0, 0, $tsData['x1'], $tsData['y1'], $tsData['width'], $tsData['height'], $tsData['w'], $tsData['h']);
	
	// Imagen 50px
    $thumb = imagecreatetruecolor($tsData['t_width'], $tsData['t_height']);// Creamos la imagen.
    imagecopyresampled($thumb, $img, 0, 0, $tsData['x1'], $tsData['y1'], $tsData['t_width'], $tsData['t_height'], $tsData['w'], $tsData['h']);
	
	// Guardamos las 2 imagenes.
    imagejpeg($avatar, $tsData['root'].$tsData['nombre'].'_120.jpg', 90);
	imagejpeg($thumb, $tsData['root'].$tsData['nombre'].'_50.jpg', 90);
	// Actualizamos el campo en perfil que se agrego un avatar para el usuario.
	anaK('query', 'UPDATE '.$tsCore->table['09'].' SET p_imagen = \'1\' WHERE user_id = '.$tsCore->setProtect((int)$tsUser->uid), array(__FILE__, __LINE__));
	
	// Eliminamos ejemplos.
    imagedestroy($img);
    imagedestroy($avatar);
	imagedestroy($thumb);
	$tsCore->delete_folder(EA_FILES.'tmp/others/'.$data['borrar']);
	// Pasamos el url de la nueva imagen.
	return '1: '.$tsCore->settings['url'].'/files/perfiles/'.$tsUser->uid.'_120.jpg?onload='.$tsCore->settings['date'];
	}
	
	
	#05. SUBIR TEMA WEB
	function upload_theme() {
	global $tsCore, $tsUser;	
	
	// Datos del tema.
	$tsData = array(
	'tema' => $_FILES['pack']['tmp_name'],#El tema.
	'tipo' => explode('/', $_FILES['pack']['type']),#El formato.
	'peso' => $_FILES['pack']['size'],#El tamaño.
	);
	
	if($tsUser->is_admod != 1) {
	return '0: Sorry but you do not have the necessary permissions to perform this action..';
	
	} elseif(!$tsData['tema']) {
	return '0: This action cannot be executed. Error code: UT001/NF';
	
	} elseif($tsData['peso'] > $this->max_tema) {
	return '0: File size must be less than 10 MB...';
	
	} elseif($tsData['tipo'][1] != 'zip') {
	return '0: Sorry but the file format is not valid..';
    
	} elseif(is_uploaded_file(!$_FILES['pack']['tmp_name'])) {
	return '0: Excuse me, but this file has already been uploaded..';
	
	} else {
	// Directorio donde ira el archivo.
    $root = EA_FILES.'tmp/others/';
	if(!file_exists($root)) mkdir($root, 0777, true);// El directorio no existe? lo creamos.
	$name = str_replace(' ', '_', $_FILES['pack']['name']);// Removemos espacios.
	
	// Todo bien? lo movemos al directorio temporal.
    move_uploaded_file($_FILES['pack']['tmp_name'], $root.$name);
	// Lo extraemos, movemos los archivos a temas y eliminamos de temporales.
	$msg = $tsCore->extraer($root.$name, EA_ROOT.'/temas/', 1);#1 eliminar
	
	// Algun error? lo mostramos.
	if($msg !== true) return $msg;
	elseif($msg === true)
	$tema_nombre = explode('.', $name);// Leemos el archivo
	$tema['path'] = $tema_nombre[0];// Obtenemos nombre para el tema
	
	// Leemos el archivo install.php para datos del tema.
	$install = EA_ROOT.'/temas/'.$tema['path'].'/install.php';
	if(!file_exists($install)) return '0: An error occurred the install file is corrupt or does not exist, ¡check it!.';
	include $install;// cargamos datos
	
	if(strlen($tema['nombre']) < 2 || strlen($tema['url']) < 9) {
	// El install.php tiene errores? borramos la carpeta del tema.
	$tsCore->delete_folder(EA_ROOT.'/temas/'.$tema['path']);
	return '0: The install file contains errors, check it! and reload the theme..';
	
	} else {
	// El tema ya existe?
	$val = anaK('num_rows', anaK('query', 'SELECT t_id FROM '.$tsCore->table['01'].' WHERE t_path = \''.$tsCore->setProtect($tema['path']).'\' AND t_name = \''.$tsCore->setProtect($tema['nombre']).'\' LIMIT 1', array(__FILE__, __LINE__)));
	if($val > 0) return '0: Sorry, this topic has already been added to your website..';
	
	// Si no existe lo agregamos.
	if(anaK('query', 'INSERT INTO '.$tsCore->table['01'].' (t_name, t_url, t_path, t_copy) VALUES (\''.$tsCore->setProtect($tema['nombre']).'\', \''.$tsCore->setProtect($tema['url']).'\', \''.$tsCore->setProtect($tema['path']).'\', \''.$tsCore->setProtect($tema['copy']).'\')', array(__FILE__, __LINE__))) {
	
	return '1: Great theme has been added to your website..';
	//
	} else return '0: Error code. '.anaK('error', array(__FILE__, __LINE__));
	//
	}
	//
	}
	//
	}

}
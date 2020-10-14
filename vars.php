<?php
#+----------------------------------------------------------+
#| Pagina: vars.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#| Estableciendo una lista de variables importantes
#+----------------------------------------------------------+
 
  function is_ssl() {
  if(isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
  return true;
  //
  } elseif(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on') {
  return true;
  //
  }
  //
  return false;
  }
  
  // La entrada del url es http|https
  $is_ssl = is_ssl() ? 'https://' : 'http://';
  // Constante de seguridad
  if(defined('EA_HEADER')) return;
  // Tiempo de ejecucion
  set_time_limit(300);
  // Zona horaria pais
  date_default_timezone_set('america/los_angeles');
  // Creamos la sesión
  if(!isset($_SESSION)) session_start();
  // Lista de errores que vamos a demostrar
  error_reporting(E_CORE_ERROR ^ E_CORE_WARNING ^ E_COMPILE_ERROR ^ E_ERROR ^ E_WARNING ^ E_PARSE ^ E_USER_ERROR ^ E_USER_WARNING ^ E_RECOVERABLE_ERROR ^ E_DEPRECATED);
  // Mostramos errores
  ini_set('display_errors', true);

#+----------------------------------------------------------+
#| Definimos algunas constantes y directorios.				|
#+----------------------------------------------------------+
  
  define('EA_HEADER', true);
  
  define('EA_ROOT', realpath(dirname(__FILE__)));
  
  define('EA_URL', $is_ssl.$_SERVER['HTTP_HOST']);
  
  define('EA_CLASS', EA_ROOT.'/data/clases/');
  
  define('EA_EXTRA', EA_ROOT.'/data/extras/');
  
  define('EA_FILES', EA_ROOT.'/files/');
  
  set_include_path(get_include_path().PATH_SEPARATOR.realpath('./'));
  
#+----------------------------------------------------------+
#| Todos los archivos globales que vamos a usar.			|
#+----------------------------------------------------------+
  
  # Conexion con DB
  include dirname(__FILE__).'/inc/db_con.php';
  # Funciones
  include EA_EXTRA.'funciones.php';
  # Nucleo de la web
  include EA_CLASS.'c.core.php';
  # Datos de usuario
  include EA_CLASS.'c.user.php';
  # Monitor de usuario
  include EA_CLASS.'c.monitor.php';
  # Mensajes de usuario
  include EA_CLASS.'c.mensajes.php';
  # Smarty
  include EA_CLASS.'c.smarty.php';
  
#+----------------------------------------------------------+
#| Interactuamos con la base de datos y las funciones.		|
#+----------------------------------------------------------+   
  
  # Configuraciones del nucleo
  $tsCore = new tsCore();
  # Usuario
  $tsUser = new tsUser();
  # Monitor
  $tsMonitor = new tsMonitor();
  # Mensajes
  $tsMP = new tsMensajes();

  // El tema que vamos a utilizar
  $tsTema = $tsCore->settings['tema']['t_path'];
  // Si no se define colocamos el deafult
  if(empty($tsTema)) $tsTema = 'default';
  define('EA_TEMA', $tsTema);# Definimos con el EA_TEMA
  
  # Smarty llamar el script 
  $anAk = new tsSmarty();
  # Configuraciones del nucleo
  $anAk->assign('tsConfig', $tsCore->settings);
  # Datos del usuario
  $anAk->assign('tsUser', $tsUser);
  # Monitor de avisos
  $anAk->assign('tsAvisos', $tsMonitor->avisos);
  # Mensajes privados
  $anAk->assign('tsMPs', $tsMP->mensajes);
  
  // Tareas que hara la pagina.
  $tsCore->tareas_globales();
  
  // Tenemos alguna IP bloqueada? enviamos al usuario a otro lugar...
  if($tsCore->black_list($tsCore->settings['ip'], 1)) header('Location: http://www.google.com');
  
  # ACTIVAMOS EL MODO EN MANTENIMIENTO.
  if($tsCore->settings['web_on'] == 1 && $tsUser->is_admod != 1 && $_GET['w'] != 'login' && $_GET['action'] != 'login-user') {
  // Página solicitada
  $tsPage = 'mantenimiento';
  $anAk->assign("tsPage", $tsPage);
  $anAk->assign('tsTitle', $tsCore->settings['titulo'].' - '.$tsCore->getTitle($tsPage));
  $anAk->display('principal/mantenimiento.tpl');
  exit();
  
  # TENEMOS ALGUNA CUENTA DE USUARIO BANEADA?	 
  } elseif($tsUser->is_banned && $_GET['action'] != 'login-salir') {
  $banned_data = $tsUser->user_banned();# Datos del baneado
  if(!empty($banned_data)) {
  $anAk->assign('tsBanned', $banned_data);
  $anAk->display('principal/suspension.tpl');
  } else die('<h2>This account is disabled.</h2>');
  exit();
  //  
  }
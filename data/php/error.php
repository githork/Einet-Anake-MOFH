<?php
#+----------------------------------------------------------+
#| Pagina: error.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "aviso";// tsPage.tpl -> PLANTILLA A MOSTRAR.
 
   $tsLevel = 0;// NIVEL DE ACCESO A ESTA PAGINA

   $tsAjax = empty($_GET['ajax']) ? 0 : 1;// LA RESPUESTA AJAX?
 
   $tsContinue = true;// CONTINUAR EL SCRIPT
   
   $action = htmlspecialchars($_GET['action']);// TIPO DE ENTRADA
   
   $getTitle = array (
   '400' => 'HTTP 400 Incorrect application!!',
   '401' => 'HTTP 401 Unauthorized Access!!',
   '403' => 'HTTP 403 Access prohibited!!',
   '404' => 'HTTP 404 This page doesn\'t work!!',
   '500' => 'HTTP 500 Internal server error!!',
   );
   
   $tsTitle = $tsCore->settings['titulo'].' - '.$getTitle[$action];// TITULO DE LA PAGINA ACTUAL
 
   // VERIFICAMOS EL NIVEL DE ACCESO ANTES CONFIGURADO
   $tsLevelMsg = $tsCore->setLevel($tsLevel, true);
   if($tsLevelMsg != 1) {	
   $tsPage = 'aviso';
   $tsAjax = 0;
   $anAk->assign("tsAviso", $tsLevelMsg);
  
   $tsContinue = false;
   }

   if($tsContinue) {

#+----------------------------------------------------------+
#| Establecemos las variables y archivos					|
#+----------------------------------------------------------+
   
   switch($action) {
   case '400': // Error 400
   //<--
   $tsError = array(
   'codigo' => '400', 
   'titulo' => $getTitle['400'], 
   'mensaje' => 'An error occurred while trying to process your request, load limit consumed!!'
   );
   //-->
   break;
   case '401': // Error 401
   //<--
   $tsError = array(
   'codigo' => '401', 
   'titulo' => $getTitle['401'], 
   'mensaje' => 'Sorry, but you are trying to access a page that requires administrator permissions!!'
   );
   //-->
   break;
   case '403': // Error 403
   //<--
   $tsError = array(
   'codigo' => '403', 
   'titulo' => $getTitle['403'], 
   'mensaje' => 'Sorry, but you do not have permission to enter this site!!'
   );
   //-->
   break;
   case '404': // Error 404
   //<--
   $tsError = array(
   'codigo' => '404', 
   'titulo' => $getTitle['404'], 
   'mensaje' => 'Sorry but the page you are trying to access is not available or has been deleted!!'
   );
   //-->
   break;
   case '500': // Error 500
   //<--
   $tsError = array(
   'codigo' => '500', 
   'titulo' => $getTitle['500'], 
   'mensaje' => 'Something went wrong, the server is not responding to requests!!'
   );
   //-->
   break;
   }
   
   // Cargamos el mensaje del error
   $anAk->assign("tsAviso", array(
   'codigo' => $tsError['codigo'], 
   'titulo' => $tsError['titulo'], 
   'mensaje' => $tsError['mensaje'], 
   'but' => "Go to home page", 
   'link' => "{$tsCore->settings['url']}"));
   //
   }
   
#+----------------------------------------------------------+
#| Incluimos el script para cargar la plantilla				|
#+----------------------------------------------------------+
   	
   if(empty($tsAjax)) {
   // Asignamos título
   $anAk->assign("tsTitle", $tsTitle);
   // Cargamos el tpl indicado
   include EA_ROOT.'/plantilla.php';
   }
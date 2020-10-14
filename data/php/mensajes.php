<?php
#+----------------------------------------------------------+
#| Pagina: mensajes.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "mensajes";// tsPage.tpl -> PLANTILLA A MOSTRAR.
 
   $tsLevel = 2;// NIVEL DE ACCESO A ESTA PAGINA

   $tsAjax = empty($_GET['ajax']) ? 0 : 1;// LA RESPUESTA AJAX?
 
   $tsContinue = true;// CONTINUAR EL SCRIPT
   
   include '../../vars.php';// INCLUIR EL HEADER
   
   $search = htmlspecialchars($_GET['search']);

   $tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->getTitle($tsPage, $search);// TITULO DE LA PAGINA ACTUAL
 
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
   
   // Tipo de entrada.
   $action = htmlspecialchars($_GET['action']);
   
   // Para el paginado, para no mandarlo por get.
   if($action) $is_act = '?action='.$action;// si recibio una action la mostramos.
   if(empty($search)) $items = 'all_items'.$is_act;// si no recibio una palabra a buscar.
   else $items = $search.$is_act;// si se recibio una la pasamos para el paginado.
   
   $anAk->assign("tsAction", $action);
   $anAk->assign("tsAdicional", $items);
   
   switch($action) {
   case '':
   //<--
   $anAk->assign("tsMensajes", $tsMP->get_mensajes(2, $_POST['met']));
   //-->
   break;
   
   case 'enviados':
   //<--
   $anAk->assign("tsMensajes", $tsMP->get_mensajes(3));
   //-->
   break;
   
   case 'respondidos':
   //<--
   $anAk->assign("tsMensajes", $tsMP->get_mensajes(4));
   //-->
   break;
   
   case 'search':
   //<--
   $anAk->assign("tsSearch", $search);
   $anAk->assign("tsMensajes", $tsMP->get_mensajes(5));
   //-->
   break;
   
   case 'leer':
   //<--
   $tsData = $tsMP->read_mensaje();
   if($tsData['error']) {
   // No existe el mensaje? mostramos el error..
   $tsTitle = $tsCore->settings['titulo'].' - Sorry, a problem occurred..';
   $tsPage = 'aviso';
   $tsAjax = 0;
   $anAk->assign("tsAviso", array(
   'titulo' => 'Sorry, a problem occurred.', 
   'mensaje' => $tsData['error'], 
   'but' => 'Back to messages', 'link' => $tsCore->settings['url'].'/mensajes/'));
   //
   } else {
   $anAk->assign("tsMensajes", $tsData);
   }
   //-->
   break;
   
   case 'nuevo':
   //<--
   if($_GET['view'] == 1) {// Hay orden de ticket?
   // Si la hay tomamos el nickname del admin mas activo, indicamos que es para soporte.
   $tsAdmin = $tsCore->get_admin();
   $anAk->assign("getName", $tsAdmin['user_nick']);
   $anAk->assign("getTitle", "Ticket (support) - ".$tsUser->nick);
   
   // Si es un mensaje normal tomamos el nick que se ingrese por GET.
   } else $anAk->assign("getName", $tsCore->setProtect(htmlspecialchars($_GET['user'])));
   //-->
   break;
   }
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
<?php
#+----------------------------------------------------------+
#| Pagina: admin.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "admin";// tsPage.tpl -> PLANTILLA A MOSTRAR.
 
   $tsLevel = 4;// NIVEL DE ACCESO A ESTA PAGINA

   $tsAjax = empty($_GET['ajax']) ? 0 : 1;// LA RESPUESTA AJAX?
 
   $tsContinue = true;// CONTINUAR EL SCRIPT
 
   include '../../vars.php';// INCLUIR EL HEADER
   
   $tsAction = htmlspecialchars($_GET['action']);// TIPO DE ENTRADA

   $tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->getTitle($tsPage);// TITULO DE LA PAGINA ACTUAL
 
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
   
   // La clase que vamos a utilizar.
   include EA_CLASS.'c.admin.php';
   $tsAdmin = new tsAdmin();
   $anAk->assign("tsAction", $tsAction);
   
   switch($tsAction) {
   case '':
   default:
   // Todos los administradores.
   $anAk->assign("tsAdmins", $tsAdmin->get_admins());
   break;
   
   case 'creditos':
   // Versiones de script utilizados.
   $anAk->assign("tsVersion", $tsAdmin->get_versiones());
   break;
   
   case 'config':
   // Tipos de codificacion.
   $anAk->assign("tsCod", $tsCod);
   // Tipo de bienvenida al registrarse.
   $anAk->assign("tsBienvenida", $tsBienvenida);
   break;
   
   case 'temas':
   // Todos los temas de la pagina.
   $anAk->assign("tsTemas", $tsAdmin->get_temas());
   break;
   
   case 'noticias':
   // Las noticias publicadas por el admin.
   $anAk->assign("tsNoticias", $tsAdmin->get_noticias());
   break;
   
   case 'api':
   // Lista de errores para SMTP.
   $anAk->assign("tsDebug", $tsDebug);
   // Tipos de codificacion.
   $anAk->assign("tsCod", $tsCod);
   break;

   case 'stats':
   // Estadisticas globales.
   $anAk->assign("tsGlobal", $tsAdmin->estadisticas_globales());
   break;
   
   case 'blacklist':
   // Todas las solicitudes para blackList.
   $anAk->assign("tsBlackType", $tsBlackList);// Tipos de blackList.
   $anAk->assign("tsBlackList", $tsAdmin->get_blackList());
   break;
   case 'badwords':
   //<--
   $anAk->assign("tsBadWords", $tsAdmin->get_badwords());
   //-->
   break;
   case 'ipinfo':
   // Mostrar informacion del IP
   $anAk->assign("tsInfo", $tsAdmin->get_ipInfo());
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
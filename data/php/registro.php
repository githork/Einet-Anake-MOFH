<?php
#+----------------------------------------------------------+
#| Pagina: registro.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "registro";// tsPage.tpl -> PLANTILLA A MOSTRAR.
 
   $tsLevel = 0;// NIVEL DE ACCESO A ESTA PAGINA

   $tsAjax = empty($_GET['ajax']) ? 0 : 1;// LA RESPUESTA AJAX?
 
   $tsContinue = true;// CONTINUAR EL SCRIPT
   
   include '../../vars.php';// INCLUIR EL HEADER

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
   
   // Plan hosting.
   $tsPlan = htmlspecialchars((int)empty($_GET['pid']) ? $_SESSION['pid'] : $_GET['pid']);
   
   // El usuario ya esta logeado, redireccionamos.
   if($tsUser->is_member) {
   // Si seleccion algun plan y esta logueado, re-direccionamos....
   if(strlen($tsPlan) > 0) {
   header('Location: '.$tsCore->settings['url'].'/panel/?action=create&pid='.$tsPlan);
   } else {
   header('Location: '.$tsCore->settings['url']);   
   }
   //
   }
   
   // El año actual.
   $now_year = date('Y', $tsCore->settings['date']);
   // La edad maxima es 100 años menos la edad minima.
   $max_year = 100 - $tsCore->settings['allow_edad'];
   // El año actual menos la edad minima.
   $end_year = $now_year - $tsCore->settings['allow_edad'];	
   // Maximo de años para fecha permitidos.
   $anAk->assign("tsMax", $max_year);
   // Limite en edad maxima permitida.
   $anAk->assign("tsEndY", $end_year);
   // Lista de meses.
   $anAk->assign("tsMeses", $tsMeses);
   // Lista de paises.
   $anAk->assign("tsPaises", $tsPaises);
   // El plan hosting.
   $anAk->assign("getPlan", $tsPlan);
   
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
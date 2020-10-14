<?php
#+----------------------------------------------------------+
#| Pagina: ajustes.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "ajustes";// tsPage.tpl -> PLANTILLA A MOSTRAR.
 
   $tsLevel = 2;// NIVEL DE ACCESO A ESTA PAGINA

   $tsAjax = empty($_GET['ajax']) ? 0 : 1;// LA RESPUESTA AJAX?
 
   $tsContinue = true;// CONTINUAR EL SCRIPT
   
   include '../../vars.php';// INCLUIR EL HEADER
   
   $tsAction = htmlspecialchars($_GET['action']);// TIPO DE ENTRADA

   if($tsUser->is_member) $tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->getTitle($tsPage, $tsUser->nick);
   else $tsTitle = $tsCore->settings['titulo'];
 
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
   
   $anAk->assign("tsAction", $tsAction);
   // Lista de paises.
   $anAk->assign("tsPaises", $tsPaises);
   // Idiomas cPanel
   $anAk->assign("tsIdiomas", $tsIdiomas);
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
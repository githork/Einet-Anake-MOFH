<?php
#+----------------------------------------------------------+
#| Pagina: usuarios.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "usuarios";// tsPage.tpl -> PLANTILLA A MOSTRAR.
 
   $tsLevel = 2;// NIVEL DE ACCESO A ESTA PAGINA

   $tsAjax = empty($_GET['ajax']) ? 0 : 1;// LA RESPUESTA AJAX?
 
   $tsContinue = true;// CONTINUAR EL SCRIPT
   
   include '../../vars.php';// INCLUIR EL HEADER

   $tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['description'];// TITULO DE LA PAGINA ACTUAL
    
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
   
   // Datos de los usuarios.
   $tsData = $tsUser->get_users();
   // Lista de paises.
   $anAk->assign("tsPaises", $tsPaises);
   // Todos los usuarios.
   $anAk->assign("tsUsers", $tsData);
   // Filtros.
   $anAk->assign("tsFilter", $tsData['filtros']);
   // Rangos de usuarios.
   $anAk->assign("tsRangos", $tsUser->get_rango_user('', 2));
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
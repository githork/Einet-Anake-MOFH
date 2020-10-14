<?php
#+----------------------------------------------------------+
#| Pagina: principal.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "principal";// tsPage.tpl -> PLANTILLA A MOSTRAR.
 
   $tsLevel = 0;// NIVEL DE ACCESO A ESTA PAGINA

   $tsAjax = empty($_GET['ajax']) ? 0 : 1;// LA RESPUESTA AJAX?
 
   $tsContinue = true;// CONTINUAR EL SCRIPT
   
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
   
   // Equipo de administradores y moderadores - 2 admin|mods
   $anAk->assign("tsStaff", $tsUser->ult_users(2));
   // Estadisticas globales.
   $anAk->assign("tsStats", $tsAdmin->estadisticas_globales());
   // Cumpleañeros.
   $anAk->assign("tsBirthday", $tsCore->get_birthday());
   
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
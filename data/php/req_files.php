<?php
#+----------------------------------------------------------+
#| Pagina: req_files.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "";// tsPage.tpl -> PLANTILLA A MOSTRAR.

   $tsLevel = 0;// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

   $tsAjax = empty($_GET['ajax']) ? 0 : 1;// LA RESPUESTA AJAX?

   include '../../vars.php';// INCLUIR EL HEADER

   $tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->getTitle($tsPage);// TITULO DE LA PAGINA ACTUAL

#+----------------------------------------------------------+
#| Establecemos las variables y archivos					|
#+----------------------------------------------------------+

   $action = htmlspecialchars($_GET['action']);
   $action_type = explode('-', $action);
   $action_type = $action_type[0];

#+------------------------------+
#|  (INSTRUCCIONES DE CODIGO)	|
#+------------------------------+
 
   // Que necesitamos?
   $file = './req/req.'.$action_type.'.php';
   if(file_exists($file)) include($file);
   else die('0: The file you requested could not be found.');
   
#+----------------------------------------------------------+
#| Incluimos el script para cargar la plantilla				|
#+----------------------------------------------------------+
   	
   if(empty($tsAjax)) {
   // Asignamos título
   $anAk->assign("tsTitle", $tsTitle);
   // Cargamos el tpl indicado
   include EA_ROOT.'/plantilla.php';
   }
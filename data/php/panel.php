<?php
#+----------------------------------------------------------+
#| Pagina: panel.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "panel";// tsPage.tpl -> PLANTILLA A MOSTRAR.
 
   $tsLevel = 2;// NIVEL DE ACCESO A ESTA PAGINA

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
   include EA_CLASS.'c.panel.php';
   $tsPanel = new tsPanel();
   $anAk->assign("tsAction", $tsAction);
   // Cumpleañeros
   $anAk->assign("tsBirthday", $tsCore->get_birthday());
   
   switch($tsAction) {
   case '':
   default:
   //<-- todas la cuentas hosting.
   $anAk->assign("tsCuentas", $tsPanel->get_accounts());
   //-->
   break;
   case 're-direct':
   //<-- re-direccionar...
   $anAk->assign("tsGo", htmlspecialchars($_GET['view']));
   $anAk->assign("tsCuenta", $tsPanel->info_account());
   //-->
   break;
   case 'view':
   //<-- informacion de la cuenta hosting.
   $anAk->assign("tsCuenta", $tsPanel->info_account());
   //-->
   break;
   case 'create':
   //<--
   $anAk->assign("tsPlan", (empty($_GET['pid'])) ? 1 : $_GET['pid']);
   //-->
   break;
   case 'buscador':
   //<--
   $anAk->assign("tsCuentas", $tsPanel->search_account());
   $anAk->assign("tsSearch", htmlspecialchars($_GET['search']));
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
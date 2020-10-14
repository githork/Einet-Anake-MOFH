<?php
#+----------------------------------------------------------+
#| Pagina: upload.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

#+----------------------------------------------------------+
#| Definiendo variables por defecto							|
#+----------------------------------------------------------+

   $tsPage = "upload";// tsPage.tpl -> PLANTILLA A MOSTRAR.

   $tsLevel = 2;// NIVEL DE ACCESO A ESTA PAGINA

   $tsAjax = empty($_GET['ajax']) ? 0 : 1;// LA RESPUESTA AJAX?
	
   $tsContinue = true;// CONTINUAR EL SCRIPT

   include '../../vars.php';// INCLUIR EL HEADER

   $tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['description'];// TITULO DE LA PAGINA ACTUAL
	
   // VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
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
   include EA_CLASS.'c.upload.php';
   $tsUpload = new tsUpload();
	
   // Alguna imagen?
   if(!$_FILES['img']) {
   header("Content-type: text/javascript"); 
   echo '{"status":0,"msg":"This action cannot be executed. Error code UP001/NF"}';
	
   } else {
   // Se recibio la imagen? validamos campos de subida.
   $isIframe = ($_POST["iframe"]) ? true : false;
   $idarea = $_POST["idarea"];
   
   // Tipo de subida local, remota.
   switch($tsCore->settings['upload_type']) {
   case '0':// Local.
   //<--
   $data = $tsUpload->upload_local($_FILES['img']['tmp_name'], 0);#dir: 0 = posts, 1 = user, 2 = muro, 3 = imagenes, 4 = others
   //-->
   break;
   
   case '1':// remota.
   //<--
   $data = $tsUpload->upload_remote($_FILES['img']['tmp_name']);
   //-->
   break;
   }
   
   // Modelo de entrada para editores.
   $stat = explode(':', $data);#el status
   $html = '<font size=\"4\" color=\"#FF0000\">'.$stat[1].'</font>';
   $bbcode = '[size=14][color=#ff0000]'.$stat[1].'[/color][/size]';
   
   // Tipo iframe?
   if($isIframe) {
   // Algun error lo mostramos.
   if((int)$stat[0]) {
   echo '<html><body>OK<script>window.parent.$("#'.$idarea.'").insertAtCursor("'.$html.'","'.$bbcode.'").closeModal().updateUI();</script></body></html>';
   
   } else {
   // Todo bien, continuamos.
   switch($tsCore->settings['upload_type']) {
   case '0':// Local.
   //<--
   for($i = 0; $i < $data['all']; $i++) {#contamos todas las imagenes.
   $data['data']['link'] = $data['link'][$i];#url de la imagen.
   echo '<html><body>OK<script>window.parent.$("#'.$idarea.'").insertImage("'.$data['data']['link'].'","'.$data['data']['link'].'").closeModal().updateUI();</script></body></html>';
   }
   //-->
   break;
   case '1':// Remota.
   //<--
   echo '<html><body>OK<script>window.parent.$("#'.$idarea.'").insertImage("'.$data['data']['link'].'","'.$data['data']['link'].'").closeModal().updateUI();</script></body></html>'; 
   //--> 
   break;
   }
   //
   }
   //
   } else {
   header("Content-type: text/javascript");
   echo '{"status":1,"msg":"OK","image_link":"'.$data['data']['link'].'","thumb_link":"'.$data['data']['link'].'"}';   
   }
   //
   }
   //
   }
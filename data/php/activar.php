<?php
#+----------------------------------------------------------+
#| Pagina: activar.php
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
   
   include '../../vars.php';// INCLUIR EL HEADER

   $type = $_GET['type'];// TIPO DE ENTRADA DE DATOS
   
   $tsDescription = $type == 1 ? 'Reset password.' : 'Account activation.';
   
   $tsTitle = $tsCore->settings['titulo'].' - '.$tsDescription;// TITULO DE LA PAGINA ACTUAL
 
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
   
   // El user ya esta logueado? redireccionamos.
   if($tsUser->is_member) {
   header('Location: '.$tsCore->settings['url']);
   }
   // Recibimos datos.
   $tsData = array(
   'type' => intval($type),
   'email' => $tsCore->setProtect($_GET['email']),
   'code' => $tsCore->setProtect($_GET['hash']),
   'new_pass' => $tsCore->setProtect($_POST['new_pass']),
   );
   
   include EA_CLASS.'c.registro.php';
   $tsRegistro = new tsRegistro();
   $tsInfo = $tsRegistro->validation_code($tsData);
   $tsAjax = 0;
   $tsAction = explode(':', $tsInfo['msg']);#extraemos el mensaje
   
   switch($tsInfo['data']['hash_type']) {
   case '1':
   //<--
   if($tsAction[0] == 2) {
   $anAk->assign("tsAviso", array(
   'titulo' => 'Cool, <b>'.$tsInfo['data']['user_nick'].'</b> you are one step away from updating your password, write one that is valid!', 
   'mensaje' => '
   <form action="" method="POST" id="form-box">
   <section class="item">
   <label>New Password:</label>
   <input type="password" class="input" id="new_pass" name="new_pass" maxlength="40" placeholder="Enter new password" required="required">
   </section>
   <section class="item">
   <input type="submit" class="button is-success" value="Update password"/>
   </section>
   </form>'));
   }
   //-->
   break;
   default:
   //<--
   $anAk->assign("tsAviso", array(
   'titulo' => $tsAction[0] == 1 ? 'Perfect! Congratulations' : 'Sorry, problem has occurred.',
   'mensaje' => $tsAction[1], 
   'but' => $tsAction[0] == 1 ? 'Go to login' : 'Try again', 
   'link' => $tsAction[0] == 1 ? "{$tsCore->settings['url']}/login/" : "{$tsCore->settings['url']}/activar/{$tsData['type']}/{$tsData['email']}/{$tsData['code']}"));
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
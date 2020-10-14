<?php
#+----------------------------------------------------------+
#| Pagina: index.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#| Einet Anake - content management system more stable..
#| Copyright (c) 2008 Einet. All rights reserved
#+----------------------------------------------------------+
 
#+----------------------------------------------------------+
#| Validamos la pagina en la que nos encontramos			|
#+----------------------------------------------------------+
 
 // Funciones principales.
 include dirname(__FILE__).'/vars.php';
 
 // Nombre del archivo a cargar.
 $section = empty($_GET['w']) ? 'principal' : $_GET['w'];
 
 // Pagina en php desde nombre.
 include dirname(__FILE__).'/data/php/'.$section.'.php';
 
 // Cerramos MySQL
 mysqli_close($anaK);
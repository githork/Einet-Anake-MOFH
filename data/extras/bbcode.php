<?php
#+----------------------------------------------------------+
#| Pagina: c.bbcode.php
#| Codigo: Einet Anake v5.2.0.30
#| Autor: Einet
#+----------------------------------------------------------+

# TODAS LAS FUNCIONES:
#01. simpleParse()
#02. complexParse()
#03. setMenciones()
#04. parseSmiles()
#05. parseCodePHP()

class BBCode {
	
	public function parseString($text) {
	// Coloreado de codigo.
	$text = $this->parseCodePHP($text);
	// Simple BBCode.
	$text = $this->simpleParse($text);
	// Complejo BBCode.
	$text = $this->complexParse($text);
	//
	return $text;
	}
	
	
	#01. HACEMOS LA CONVERSION SIMPLE
	function simpleParse($text) {
	
	$a = array(
    "/\[i\](.*?)\[\/i\]/is",
    "/\[b\](.*?)\[\/b\]/is",
	"/\[p\](.*?)\[\/p\]/is",
    "/\[u\](.*?)\[\/u\]/is", 
    "/\[s\](.*?)\[\/s\]/is",
	"/\[strong\](.*?)\[\/strong\]/is",
    "/\[sub\](.*?)\[\/sub\]/is",
    "/\[sup\](.*?)\[\/sup\]/is",
    "/\[img\](.*?)\[\/img\]/is",
    "/\[img=(.*?)\]/is",
	"/\[url\](.*?)\[\/url\]/is",
    "/\[url=(.*?)\](.*?)\[\/url\]/is",
    "/\[quote\](.*?)\[\/quote\]/is",
	"/\[quote=(.*?)\](.*?)\[\/quote\]/is",
    "/\[spoiler\](.*?)\[\/spoiler\]/is",
    );
    
    $b = array(
    "<i>$1</i>",
    "<b>$1</b>",
	"<p>$1</p>",
    "<u>$1</u>",
    "<s>$1</s>",
	"<strong>$1</strong>",
    "<sub>$1</sub>",
    "<sup>$1</sup>",
    "<center><img id='img_str' src='$1'></center>",
    "<center><img id='img_str' src='$1'></center>",
	"<a href='$1' id='url_str' target='_blank'>$1</a>",
    "<a href='$1' id='url_str' target='_blank'>$2</a>",
	"<blockquote><div class='cita'><strong>Cita:</strong></div><div class='citacuerpo'><p>$1</p></div></blockquote>",
	"<blockquote><div class='cita'><strong>$1 dijo:</strong></div><div class='citacuerpo'><p>$2</p></div></blockquote>",
	"<div class='spoiler'><div class='title'><a href='#'><i class='sp-add'></i>Spoiler:</a></div><div class='body'>$1</div></div>",
    );
    
	$texto = preg_replace($a, $b, $text);
    return  $texto;
    }

    
	#02. HACEMOS UNA CONVERSION MAS COMPLETA
    function complexParse($text) {
    $a = array(
    "/\[i\](.*?)\[\/i\]/is",
    "/\[b\](.*?)\[\/b\]/is",
	"/\[p\](.*?)\[\/p\]/is",
    "/\[u\](.*?)\[\/u\]/is", 
    "/\[s\](.*?)\[\/s\]/is",
    "/\[sub\](.*?)\[\/sub\]/is",
    "/\[sup\](.*?)\[\/sup\]/is",
    "/\[color=(.*?)\](.*?)\[\/color\]/is",
    "/\[size=(.*?)\](.*?)\[\/size\]/is",
	"/\[h(.*?)\](.*?)\[\/h(.*?)\]/is",
    "/\[font=(.*?)\](.*?)\[\/font\]/is",
    "/\[quote\](.*?)\[\/quote\]/is",
	"/\[hr\]/is",
	"/\[ul\](.*?)\[\/ul\]/is",
	"/\[ol\](.*?)\[\/ol\]/is",
	"/\[li\](.*?)\[\/li\]/is",
	"/\[table\](.*?)\[\/table\]/is",
	"/\[tr\](.*?)\[\/tr\]/is",
	"/\[td\](.*?)\[\/td\]/is",
    "/\[align=(.*?)\](.*?)\[\/align]/is",
    "/\[img](.*?)\[\/img\]/is",
    "/\[img=(.*?)\]/is",
    "/\[url\](.*?)\[\/url\]/is",
    "/\[url=(.*?)\](.*?)\[\/url\]/is",
	"/\[email=(.*?)\](.*?)\[\/email\]/is",
    "/\[secure\](.*?)\[\/secure\]/is",// protegidos por captchas se recibe solo el id
    "/\[video\](.*?)\[\/video\]/is",
	"/\[swf=(.*?)\]/is",
	"/\[pdf=(.*?)\]/is",
	"/\[textarea\](.*?)\[\/textarea\]/is",
	"/\[notice\](.*?)\[\/notice\]/is",
	"/\[info\](.*?)\[\/info\]/is",
	"/\[warning\](.*?)\[\/warning\]/is",
	"/\[error\](.*?)\[\/error\]/is",
	"/\[success\](.*?)\[\/success\]/is",
	"/\[goear=(.*?)\]/is",
	"/\[onox=iframe\](.*?)\[\/onox\]/is",
    );
    $b = array(
    "<i>$1</i>",
    "<b>$1</b>",
	"<p>$1</p>",
    "<u>$1</u>",
    "<s>$1</s>",
    "<sub>$1</sub>",
    "<sup>$1</sup>",
    "<font color='$1'>$2</font>",
    "<font size='$1'>$2</font>",
	"<h$1>$2</h$3>",
    "<font face='$1'>$2</font>",
    "<blockquote><span>$1</span></blockquote>",
	"<hr/>",
    "<ul class='str_ul'>$1</ul>",
	"<ol class='str_ol'>$1</ol>",
    "<li>$1</li>",
	"<table class='wbb-table'>$1</table>",
    "<tr>$1</tr>",
	"<td>$1</td>",
	"<div style='text-align:$1'>$2</div>",
    "<center><img id='img_str' src='$1'></center>",
    "<center><img id='img_str' src='$1'></center>",
    "<a href='$1' id='url_str' target='_blank'>$1</a>",
    "<a href='$1' id='url_str' target='_blank'>$2</a>",
	"<a href='$1' id='url_str' target='_blank'>$2</a>",
    "<a href='#' id='url_str' class='str-secure' jail='$1' type='2'>Download file</a>",// protegidos por captchas solo el id
    "<center><iframe src='http://www.youtube.com/embed/$1' width='640' height='480' frameborder='0'></iframe></center><br/><b>Link:</b> <a href='http://www.youtube.com/watch?v=$1' id='url_str' target='_blank'>http://www.youtube.com/watch?v=$1</a>",
	"<center><embed src='$1' quality='high' width='640' height='480' type='application/x-shockwave-flash' allownetworking='internal' allowscriptaccess='never' autoplay='false' wmode='transparent'></center><br/><b>Link:</b> <a href='$1' id='url_str' target='_blank'>$1</a>",
	"<object data='$1' type='application/pdf' width='100%' height='700' internalinstanceid='3'><p><strong>Requires adobe reader to view the file <a href='https://get.adobe.com/es/reader/otherversions/' target='_blank'>Download adobe reader</a></strong></p></object><br/><b>Link:</b> <a href='$1' target='_blank' id='url_str' download='$1'>Download PDF file</a>",
	"<textarea class='str_textarea'>$1</textarea>",
	"<div class='bbcmsg notice'>$1</div>",
	"<div class='bbcmsg info'>$1</div>",
	"<div class='bbcmsg warning'>$1</div>",
	"<div class='bbcmsg error'>$1</div>",
	"<div class='bbcmsg success'>$1</div>",
	"<iframe src='http://www.goear.com/embed/sound/$1' width='580' height='115' scrolling='no' frameborder='0'></iframe>",
	"<iframe src='$1' target='_parent' width='100%' height='480' scrolling='auto' frameborder='0'></iframe>",
    );
    $texto = preg_replace($a, $b, $text);
    return  $texto;
    }
	
  
	#03. HACEMOS LA CONVERSION CON LAS MENCIONES @USUARIO
    public function setMenciones($text) {
    global $tsCore, $tsUser;
    // Hack
    $text = $text.' ';
    
    // Buscamos usuarios.
    preg_match_all('/\B@([a-zA-Z0-9_-]{4,16}+)\b/', $text, $users);
    
    $menciones = $users[1];
    // Cuales existen.
    foreach($menciones as $key => $user) {  
    if(strtolower($user) != strtolower($tsUser->nick)) {
    $data = $tsUser->user_data($user, 2);#2 Nick.
    if(!empty($data['user_id'])) {
    $find = '@'.$user.' ';
    $replace = '@<a href="'.$tsCore->settings['url'].'/perfil/'.$user.'" target="_blank" id="nick_str">'.$user.'</a> ';
    $text = str_replace($find, $replace, $text);
    }
    //
    }
    //
    }
	//
    return $text;
    }
	
	
	#04. CONVERSION DE EMOTICONES
    public function parseSmiles($text) {
	global $tsCore;  
    $a = array(
   ":|", "^_^", ":¹(", ":$", ":)", ":(", ":S", ":D", "(Y)", ":P", ":*", ":O", "<3", "(6)", "(3)", ":3", ":X", ">o", ";)", "8)", "o)", "<)", ":V", "o.O", ":love:", "xD", ":crak:", "*o*", "<--<", "-_-",
   
   // Adicionales.
   ":nb00:", ":nb01:", ":nb02:", ":nb03:", ":nb04:", ":nb05:", ":nb06:", ":nb07:", ":nb08:", ":nb09:", ":nb10:", 
   ":nb11:", ":nb12:", ":nb13:", ":nb14:", ":nb15:", ":nb16:", ":nb17:", ":nb18:", ":nb19:", ":nb20:", ":nb21:", 
   ":nb22:", ":nb23:", ":nb24:", ":nb25:", ":nb26:", ":nb27:", ":nb28:", ":nb29:", ":nb30:", ":nb31:", ":nb32:", 
   ":nb33:", ":nb34:", ":nb35:", ":nb36:", ":nb37:", ":nb38:", ":nb39:", ":nb40:", ":nb41:", ":nb42:", ":nb43:", 
   ":nb44:", ":nb45:", ":nb46:", ":nb47:", ":nb48:", ":nb49:", ":nb50:", ":nb51:", ":nb52:", ":nb53:", ":nb54:", 
   ":nb55:", ":nb56:", ":nb57:", ":nb58:", ":nb59:", ":nb60:", ":nb61:", ":nb62:", ":nb63:", ":nb64:", ":nb65:", 
   ":nb66:", ":nb67:", ":nb68:", ":nb69:", ":nb70:", ":nb71:", ":nb72:", ":nb73:", ":nb74:", ":nb75:", ":nb76:", 
   ":nb77:", ":nb78:", ":nb79:", ":nb80:", ":nb81:", ":nb82:", ":nb83:",
    ); 
    $b = array(
    "<img src='{replace.url}/emo_nimB.png' class='emo_017'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_014'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_036'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_001'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_002'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_044'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_033'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_005'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_057'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_012'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_022'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_060'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_049'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_011'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_045'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_041'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_043'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_026'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_004'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_015'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_010'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_025'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_035'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_040'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_053'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_009'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_046'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_006'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_050'>",
	"<img src='{replace.url}/emo_nimB.png' class='emo_020'>",
	
	// Adicionales.
	"<img src='{replace.url}/einet_nimB.png' class='nb-00'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-01'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-02'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-03'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-04'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-05'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-06'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-07'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-08'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-09'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-10'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-11'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-12'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-13'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-14'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-15'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-16'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-17'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-18'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-19'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-20'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-21'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-22'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-23'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-24'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-25'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-26'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-27'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-28'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-29'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-30'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-31'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-32'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-33'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-34'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-35'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-36'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-37'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-38'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-39'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-40'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-41'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-42'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-43'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-44'>",
	
	"<img src='{replace.url}/einet_nimB.png' class='nb-45'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-46'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-47'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-48'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-49'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-50'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-51'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-52'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-53'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-54'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-55'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-56'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-57'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-58'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-59'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-60'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-61'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-62'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-63'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-64'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-65'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-66'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-67'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-68'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-69'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-70'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-71'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-72'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-73'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-74'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-75'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-76'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-77'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-78'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-79'>",
	"<img src='{replace.url}/einet_nimB.png' class='nb-80'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-81'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-82'>", 
	"<img src='{replace.url}/einet_nimB.png' class='nb-83'>", 
	);
	$text = str_replace($a, $b, $text);
	$text = str_replace('{replace.url}', $tsCore->settings['img'], $text);
    return $text;
    }
	
	
	#05. REEMPLAZO Y COLOREADO DE CODIGO
	function parseCodePHP($text) {
	$code = 'code';
    
	// Obtenemos y contamos los [code](.*)[/code]
    preg_match_all("/(?i)\[{$code}\]([^\a]+?)\[\/{$code}\]/i", $text, $line);
    $all = count($line[1]);
	
	// Contamos y procesamos.
	for($i = 0; $i < $all; $i++) {
    $texto = trim($line[1][$i]);// pasamos linea por linea :D
	
	// El codigo no es php? agregamos estas etiquetas al inicio y al final.
	$open_tag = false;
	if(!preg_match("#^\s*<\?#si", $texto)) {
	$open_tag = true;
	$texto = "<?php \n".$texto;
	}
	
	// Igual que la anterior pero para cerrar.
	$end_tag = false;
	if(!preg_match("#\?>\s*$#si", $texto)) {
	$end_tag = true;
	$texto = $texto." \n?>";
	}
    
	// Coloreamos el codigo si es php.
    $texto = @highlight_string(html_entity_decode($texto), true);
	
	// Modificamos algunas etiquetas para mejorar el codigo
	$texto = preg_replace('#<code>\s*<span style="color: \#000000">\s*#i', '<code><i>código:</i> ', $texto);
	$texto = preg_replace('#</span>\s*</code>#', '</code>', $texto);
	$texto = preg_replace('#</span>(\r\n?|\n?)</code>#', '</span></code>', $texto);
	// Necesarias para el cambio de codigo.
	$texto = str_replace('\\\\', '&#092;', $texto);
	$texto = str_replace('\\\'', '&#39;', $texto);
	$texto = str_replace('\\"', '&#34;', $texto);
	$texto = str_replace('$', '&#36;', $texto);
	$texto = preg_replace('#&amp;\#([0-9]+);#si', '&#$1;', $texto);
	
	// Si el codigo no es en php agregamos las etiquetas php para colorear de la misma forma :D
	if($open_tag) {
	$texto = preg_replace('#<code><i>código</i> <span style="color: \#([A-Z0-9]{6})">&lt;\?php( |&nbsp;)(<br />?)#', '<code><i>código</i> <span style="color: #$1">', $texto);
	}
	//
	if($end_tag) {
	$texto = str_replace('?&gt;</span></code>', '</span></code>', $texto);
	$texto = str_replace('?&gt;</code>', '</code>', $texto);
	}
	
	// Modificamos algunas etiquetas para mejorar el codigo
	$texto = preg_replace('#<span style="color: \#([A-Z0-9]{6})"></span>#', '', $texto);
	$texto = str_replace('<code>', '<code>', $texto);
	$texto = str_replace('</code>', '</code>', $texto);
	$texto = preg_replace('# *$#', '', $texto);	
	$texto = preg_replace('/(\\n|\\r)/', '', $texto);
    
	// VOLVEMOS A REMPLAZAR LO QUE ESTA ENTRE LAS ETIQUETAS CODE
    $text = str_replace( "[{$code}]{$line[1][$i]}[/{$code}]", $texto, $text);
    //
	}
	//
	return $text;
    }
}
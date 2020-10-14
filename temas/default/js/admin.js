// JavaScript Document
$(document).ready(function() {
	// LO NUEVO QUE PASA EN LA WEB OFICIAL.
	if($('#app_list').length > 0) {
	global.ajax('get', 'update-support', '', function(data) {
	var d = (data.length == 0 ? '' : JSON.parse(data));
	$('#app_list').html('');
	if(d.length > 0) {
	for(var i = 0; i < d.length; i++) {
	var html = '<li onclick="window.open(\''+d[i].link+'\', \'_blank\');" title="More details about - '+d[i].title+'">';
	html += '<i class="einet icon-check-circle"></i>';
	html += '<span class="app-title">'+d[i].title+'</span>';
	html += '<span class="app-text">'+d[i].description+'</span>';
	html += '<span class="app-date" title="'+d[i].pubDate+'"><i class="einet icon-clock"></i> '+d[i].pubDate+'</span>';
	html += '</li>';
	$('#app_list').append(html);
	}
	//
	} else {
	$('#app_list').html('<span id="mod-nada" class="notification is-warning">No new news were found.</span>');
	}
	//
	});
	//
	}
	// HAY VERSIONES MAS NUEVAS?
	if($('#version_pp').length > 0) {
	global.ajax('get', 'update-version', '', function(data) {
	var d = (data.length == 0 ? '' : JSON.parse(data));
	for(var i = 0; i < d.length; i++) {
	var html = '<hr />';
	html += '<b>'+d[i].title+'</b>';
	html += '<font><a href="'+d[i].link+'" target="_blank" title="New version available, click to download..">'+d[i].description+'</a></font>';
	$('#version_pp').append(html);
	}
	//
	});
	//
	}
	// CONVERTIR DE SEGUNDOS A MINUTOS JS.
	$(document).on('keyup', '#form-admin #web-over', function() {
	var d = { 'over': $('#web-over'), 'time': $('#time-over')};
	if(!d.over.val().match(/^[0-9]+$/)) d.over.val('');
	if(d.over.val() > 0) var time = new Date((parseInt(d.time.attr('date')) + parseInt(d.over.val())) * 1000);
	else var time = new Date(Date.parse(new Date()));
	var fecha = time.toLocaleString('en-us', {weekday:'short', day:'numeric', month:'short', year:'numeric'});
	var t = {
	hour: time.getHours() > 12 ? time.getHours() - 12 : (time.getHours() < 10 ? '0'+time.getHours() : time.getHours()),
	mins: time.getMinutes() < 10 ? '0'+time.getMinutes() : time.getMinutes(),
	segs: time.getSeconds() < 10 ? '0'+time.getSeconds() : time.getSeconds(),
	jobs: time.getHours() >= 12 ? 'PM' : 'AM',
	};
	var getData = fecha+' '+t['hour']+':'+t['mins']+':'+t['segs']+' '+t['jobs'];
	$('#form-admin #time-over').val(getData).attr('title', getData);
	});
	// GUARDAR DATOS DE CONFIGURACION.
	$(document).on('click', '#form-admin input[type="submit"]', function(e) {
	var d = {
	step: $(this).attr('act'),
	titulo: $('#titulo'),
	url: $('#url'),
	email: $('#email-web'),
	cod: $('#cod'),
	min_posts: $('#min-posts'),
	max_posts: $('#max-posts'),
	online: $('#online'),
	limit_reg: $('#limit-reg'),
	allow_edad: $('#allow-edad'),
	};
	if(d.titulo.val().length < 4) {
	d.titulo.focus();
	global.error('Write a name for your website is important to identify your site.', 0);
	} else if(d.titulo.val().length > 50) {
	d.titulo.focus();
	global.error('Your site name cannot exceed 50 characters.', 0);
	} else if(!/^(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/.test(d.url.val())) {
	d.url.focus();
	global.error('Indicate the link of the directory where your website is located.', 0);
	} else if(!/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/.test(d.email.val())) {
	d.email.focus();
	global.error('The email is necessary to receive a reply and to indicate from where the messages are sent.', 0);
	} else if(!d.cod.val().length) {
	d.cod.focus();
	global.error('Select an encoding type for your website, enhance the characters in the text.', 0);
	} else if(d.max_posts.val() < 1 || d.max_posts.val() > 99) {
	d.max_posts.focus();
	global.error('Set a post limit for the website, between 1 minimum and 99 maximum in numbers.', 0);
	} else if(d.online.val() < 1 || d.online.val() > 99) {
	d.online.focus();
	global.error('Sets the time for online users. between 1 and 99 seconds in numbers.', 0);
	} else if(d.limit_reg.val() < 1 || d.limit_reg.val() > 9999999) {
	d.limit_reg.focus();
	global.error('Sets a limit on user registration between 1 and 9999999 in numbers.', 0);
	} else if(!d.allow_edad.val() || isNaN(d.allow_edad.val())) {
	d.allow_edad.focus();
	global.error('You must establish a numerical value in minimum required age.', 0);
	} else {
	var letsgo = $('#form-admin').serialize()+'&step='+encodeURIComponent(d.step);
	$('#form-admin').animate({opacity: 0.5});
	global.ajax('post', 'admin-save_config', letsgo, function(s) {
	switch(s.charAt(0)) {
	case '0':
	global.error(s.substring(3), 0);
	break;
	case '1':
	global.error(s.substring(3), 1);
	break;
	}
	$('#form-admin').animate({opacity: 1});
	});
	//
	}
	//
	e.preventDefault();
	});
	// TEMAS ADMINISTRADOR.
	$(document).on('click', '#tbl-tema #mini-tools> li', function() {
	var d = {tid: $(this).attr('tid'), type: $(this).attr('hi')};
	if(d.tid && d.type) {
	switch(d.type) {
	case '1':
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Activate theme');
	modal.body('<center>¿You really want to select this theme?</center>', 450);
	modal.buttons('Activate now', 'add.tools_temas(\''+d.tid+'\', \''+d.type+'\')', 'Cancel', 'close');
	break;
	case '2':
	global.ajax('post', 'admin-tema_edit', 'tid='+encodeURIComponent(parseInt(d.tid))+'&type='+encodeURIComponent(parseInt(d.type)), function(html) {
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Editing theme');
	modal.body(html, 450);
	modal.buttons('Save changes', 'add.tools_temas(\''+d.tid+'\', \''+d.type+'\')', 'Cancel', 'close');
	});
	break;
	case '3':
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Delete theme');
	modal.body('<center>¿You really want to delete this theme?</center>', 450);
	modal.buttons('Delete', 'add.tools_temas(\''+d.tid+'\', \''+d.type+'\')', 'Cancel', 'close');
	break;
	}
	//
	}
	//
	});
	// SUBIR TEMA WEB.
	$(document).on('change', '#subir-tema input[name="pack"]', function() {
	var tema = $(this);
	if(tema.val().length > 10) {
	var letsgo = new FormData($('#subir-tema')[0]);
	if(!tema.val().match(/\.(zip)/i)) {
	global.error('Sorry but the theme must be compressed in <b>.zip</b>.', 0);
	} else if(tema[0].files[0].size > 10485760) {// 10MB in Byte
	global.error('Excuse me, but the theme must not exceed the <b>10MB</b>.', 0);
	} else {
	global.multi_ajax('post', 'admin-upload_theme', letsgo, function(t) {
	switch(t.charAt(0)) {
	case '0':
	global.error(t.substring(3), 0);
	break;
	case '1':
	global.error(t.substring(3), 1);
	add.update_temas();
	break;
	}
	//
	});
	//
	}
	//
	}
	//
	});
	// NOTICIAS ADMINISTRAR.
	$(document).on('click', '#all-noticias #mini-tools > li', function() {
	var d = {nid: $(this).attr('nid'), type: $(this).attr('hi')};
	if(d.nid && d.type) {
	switch(d.type) {
	case '1':
	add.tools_noticias(d.nid, d.type);
	break;
	case '2':
	global.ajax('post', 'admin-noticia_edit', 'nid='+encodeURIComponent(parseInt(d.nid))+'&type='+encodeURIComponent(parseInt(d.type)), function(html) {
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Edit news');
	modal.body(html, 450);
	modal.buttons('Save changes', 'add.tools_noticias(\''+d.nid+'\', \''+d.type+'\')', 'Cancel', 'close');
	});
	break;
	case '3':
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Delete news');
	modal.body('<center>¿You really want to delete this news?</center>', 450);
	modal.buttons('Delete', 'add.tools_noticias(\''+d.nid+'\', \''+d.type+'\')', 'Cancel', 'close');
	break;
	}
	//
	}
	//
	});
	// AGREGAR NOTICIA.
	$(document).on('click', '#mod-noticias #add-item', function() {
	var add_new = $(this).attr('hi');
	if(add_new == 1) {
	var form = '';
	form += '<form method="post" name="new-noticia" id="new-noticia">';
	form += '<section class="item">';
	form += '<span class="left"><label class="switch"><input type="checkbox" id="not-active" name="not-active" checked="checked"><div class="sliper round"></div></label></span>';
	form += '<span class="right">¿You want to activate ● to deactivate the news when you add it?</span>';
	form += '</section>';
	form += '<section class="item">';
	form += '<label>Message:</label>';
	form += '<textarea class="input" id="not-msg" name="not-msg" maxlength="300" cols="30" rows="2"></textarea>';
	form += '<small>In the message field you are allowed to BBCode[]</small>';
	form += '</section></form>';
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Add news');
	modal.body(form, 450);
	modal.buttons('Add now', '$(\'#mod-noticias #add-item\').attr(\'hi\', \'2\').click();', 'Cancel', 'close'); 
	} else if(add_new == 2) {
	var letsgo = $('#new-noticia').serialize();
	$('#mod-noticias').animate({opacity: 0.5});
	$('#mod-noticias #add-item').attr('hi', '1');
	global.ajax('post', 'admin-noticia_agregar', letsgo, function(n) {
	switch(n.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', n.substring(3));
	$('#mod-noticias').animate({opacity: 1});
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', n.substring(3));
	add.update_noticias();
	$('#mod-noticias').animate({opacity: 1});
	break;
	}	
	});
	//
	}
	//
	});
	// GUARDAR DATOS DE PUBLICIDAD.
	$(document).on('click', '#form-pub input[type="submit"]', function(e) {
	var step = $(this).attr('act');
	var letsgo = $('#form-pub').serialize()+'&step='+encodeURIComponent(step);
	$('#form-pub').animate({opacity: 0.5});
	global.ajax('post', 'admin-save_publicidad', letsgo, function(p) {
	switch(p.charAt(0)) {
	case '0':
	global.error(p.substring(3), 0);
	break;
	case '1':
	global.error(p.substring(3), 1);
	break;
	}
	$('#form-pub').animate({opacity: 1});
	});
	//
	e.preventDefault();
	});
	// GUARDAR DATOS DE LAS API.
	$(document).on('click', '#form-api input[type="submit"]', function(e) {
	var step = $(this).attr('act');
	var letsgo = $('#form-api').serialize()+'&step='+encodeURIComponent(step);
	$('#form-api').animate({opacity: 0.5});
	global.ajax('post', 'admin-save_api', letsgo, function(a) {
	switch(a.charAt(0)) {
	case '0':
	global.error(a.substring(3), 0);
	break;
	case '1':
	global.error(a.substring(3), 1);
	break;
	}
	$('#form-api').animate({opacity: 1});
	});
	e.preventDefault();
	//
	});
	// GUARDAR DATOS DE CONFIGURACION 2.
	$(document).on('click', '#form-admin-2 input[type="submit"]', function(e) {
	var d = {
	step: $(this).attr('act'),
	live_time: $('#live-time'),
	live_hide: $('#live-hide'),
	}
	if(!d.live_time.val() || d.live_time.val() < 0.02 || isNaN(d.live_time.val())) {
	d.live_time.focus();
	global.error('You must set a numeric value in update live.', 0);
	} else if(!d.live_hide.val() || d.live_hide.val() < 0.02 || isNaN(d.live_hide.val())) {
	d.live_hide.focus();
	global.error('You must set a numerical value in hide live.', 0);
	} else {
	var letsgo = $('#form-admin-2').serialize()+'&step='+encodeURIComponent(d.step);
	$('#form-admin-2').animate({opacity: 0.5});
	global.ajax('post', 'admin-save_config', letsgo, function(s) {
	switch(s.charAt(0)) {
	case '0':
	global.error(s.substring(3), 0);
	break;
	case '1':
	global.error(s.substring(3), 1);
	break;
	}
	$('#form-admin-2').animate({opacity: 1});
	});
	//
	}
	//
	e.preventDefault();
	});
	// GUARDAR DATOS DE CONFIGURACION 3.
	$(document).on('click', '#form-admin-3 input[type="submit"]', function(e) {
	var d = {
	step: $(this).attr('act'),
	cp_panel: $('#cp-panel'),
	domain: $('#domain'),
	ftp: $('#ftp'),
	cpanel: $('#cpanel'),
	sql: $('#sql'),
	mail:$('#mail'),
	};
	if(d.cp_panel.val().length > 60) {
	d.cp_panel.focus();
	global.error('The url of the reseller server must not exceed 60 characters.', 0);
	} else if(d.domain.val().length > 60) {
	d.domain.focus();
	global.error('The domain url for customers must not exceed 60 characters.', 0);
	} else if(d.ftp.val().length > 60) {
	d.ftp.focus();
	global.error('The FTP url for clients should not exceed 60 characters.', 0);
	} else if(d.cpanel.val().length > 60) {
	d.cpanel.focus();
	global.error('The cPanel url for customers should not exceed 60 characters.', 0);
	} else if(d.sql.val().length > 60) {
	d.sql.focus();
	global.error('The MySQL url for clients should not exceed 60 characters.', 0);
	} else if(d.mail.val().length > 60) {
	d.mail.focus();
	global.error('The Web Mail url for clients should not exceed 60 characters.', 0);
	} else {
	var letsgo = $('#form-admin-3').serialize()+'&step='+encodeURIComponent(d.step);
	$('#form-admin-3').animate({opacity: 0.5});
	global.ajax('post', 'admin-save_config', letsgo, function(s) {
	switch(s.charAt(0)) {
	case '0':
	global.error(s.substring(3), 0);
	break;
	case '1':
	global.error(s.substring(3), 1);
	break;
	}
	$('#form-admin-3').animate({opacity: 1});
	});
	//
	}
	//
	e.preventDefault();
	});
	// BLACKLIST ADMINISTRAR.
	$(document).on('click', '#all-blacklist #mini-tools > li', function() {
	var d = {bid: $(this).attr('bid'), type: $(this).attr('hi')};
	if(d.bid && d.type) {
	switch(d.type) {
	case '1':
	global.ajax('post', 'admin-blackList_edit', 'bid='+encodeURIComponent(parseInt(d.bid))+'&type='+encodeURIComponent(parseInt(d.type)), function(html) {
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Edit blackList');
	modal.body(html, 450);
	modal.buttons('Save changes', 'add.tools_blacklist(\''+d.bid+'\', \''+d.type+'\')', 'Cancel', 'close');
	});
	break;
	case '2':
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Delete blackList');
	modal.body('<center>¿You really want to delete this blackList item?</center>', 450);
	modal.buttons('Delete', 'add.tools_blacklist(\''+d.bid+'\', \''+d.type+'\')', 'Cancel', 'close');
	break;
	}
	//
	}
	//
	});
	// AGREGAR BLACKLIST.
	$(document).on('click', '#mod-blacklist #add-item, #ipinfo #getip', function() {
	var id_form = $(this).attr('id');
	var add_new = $(this).attr('hi');
	var ip = $.trim($(this).text());
	if(add_new == 1) {
	var form = '';
	form += '<form method="post" name="new-blacklist" id="new-blacklist">';
	form += '<section class="item">';
	form += '<label>Type:</label>';
	form += '<div id="a-select" class="select"><select class="input" name="type">';
	form += '<option value="1">IP</option>';
	form += '<option value="2">Email address</option>';
	form += '<option value="3">Email provider</option>';
	form += '<option value="4">Name</option>';
	form += '</select></div>';
	form += '</section>';
	form += '<section class="item">';
	form += '<label>Address:</label>';
	form += '<input type="text" class="input" name="value" maxlength="50" value=""/>';
	form += '</section>';
	form += '<section class="item">';
	form += '<label>Reason:</label>';
	form += '<textarea class="input" id="razon" name="razon" cols="30" rows="2" maxlength="120"></textarea>';
	form += '</section>';
	form += '</form>';
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Add item to blackList');
	modal.body(form, 450);
	modal.buttons('Add now', '$(\'#mod-blacklist #add-item, #ipinfo #getip\').attr(\'hi\', \'2\').click();', 'Cancel', 'close');
	if(id_form == 'getip' && ip.match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/)) {
	$('#new-blacklist select option:first').attr('selected', 'selected');
	$('#new-blacklist input[name="value"]').val(ip);
	}
	//
	} else if(add_new == 2) {
	var letsgo = $('#new-blacklist').serialize();
	$('#loading').fadeIn(250);
	$('#mod-blacklist').animate({opacity: 0.5});
	$('#mod-blacklist #add-item, #ipinfo #getip').attr('hi', '1');
	global.ajax('post', 'admin-blackList_agregar', letsgo, function(b) {
	switch(b.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', b.substring(3));
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', b.substring(3));
	add.update_blacklist();
	break;
	}
	$('#loading').fadeOut(250);
	$('#mod-blacklist').animate({opacity: 1});
	});
	//
	}
	//
	});
	// HERRAMIENTAS PARA API RESELLER ADD|EDIT|DELETE.
	$(document).on('click', '#all-account #mini-tools li, #mod-reseller #rs_add', function() {
	var d = {cid: $(this).attr('cid'), type: $(this).attr('hi')}
	$('.gotop').click();
	if(d.cid && d.type) {
	switch(d.type) {
	case '1':
	global.ajax('post', 'admin-editar_reseller', 'cid='+encodeURIComponent(parseInt(d.cid))+'&type='+encodeURIComponent(parseInt(d.type)), function(html) {
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Edit Reseller Account');
	modal.body(html, 550);
	modal.buttons('Save changes', 'add.tools_reseller(\''+d.cid+'\', \''+d.type+'\')', 'Cancel', 'close');
	});
	break;
	case '2':
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Delete reseller account');
	modal.body('<center>¿You really want to delete this reseller account?</center>', 450);
	modal.buttons('Delete', 'add.tools_reseller(\''+d.cid+'\', \''+d.type+'\')', 'Cancel', 'close');
	break;
	}
	//
	} else {
	if($('#mod-reseller #rs_add').attr('hi') < 1) {
	$('#mod-reseller #rs_add').attr('hi', 1);
	var html = '';
	html += '<form method="post" name="new-reseller" id="new-reseller">';
	html += '<section class="item">';
	html += '<label>Username:</label>';
	html += '<input type="text" class="input" name="cp_user" maxlength="280" value=""/>';
	html += '<label>Password:</label>';
	html += '<input type="password" class="input" name="cp_pass" maxlength="280" value=""/>';
	html += '<label>Domain:</label>';
	html += '<input type="text" class="input" name="name" maxlength="50" value="" placeholder="example.com"/>';
	html += '<label>User prefix:</label>';
	html += '<input type="text" class="input" name="prefix" maxlength="6" value="" placeholder="expl"/>';
	html += '<label>Available:</label>';
	html += '<span class="left"><label class="switch"><input type="checkbox" name="on" checked="checked"><div class="sliper round"></div></label></span>';
	html += '<span class="right">enable ● disable availability for account creation under this api reseller.</span>';
	html += '</section></form>';
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Add reseller account');
	modal.body(html, 550);
	modal.buttons('Add now', '$(\'#mod-reseller #rs_add\').click();', 'Cancel', 'close');
	} else {
	$('#mod-reseller #rs_add').attr('hi', 0);
	var letsgo = $('#new-reseller').serialize();
	global.ajax('post', 'admin-tools_reseller', letsgo, function(c) {
	switch(c.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', c.substring(3));
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', c.substring(3));
	add.update_resellers();
	break;
	}
	//
	});
	//
	}
	//
	}
	//
	});
	// HERRAMIENTAS PARA NAMESERVER ADD|EDIT|DELETE.
	$(document).on('click', '#all-dns #mini-tools li, #mod-reseller #ns_add', function() {
	var d = {nid: $(this).attr('nid'), type: $(this).attr('hi')}
	$('.gotop').click();
	if(d.nid && d.type) {
	switch(d.type) {
	case '1':
	global.ajax('post', 'admin-editar_nameserver', 'nid='+encodeURIComponent(parseInt(d.nid))+'&type='+encodeURIComponent(parseInt(d.type)), function(html) {
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Edit nameserver');
	modal.body(html, 450);
	modal.buttons('Save changes', 'add.tools_nameserver(\''+d.nid+'\', \''+d.type+'\')', 'Cancel', 'close');
	});
	break;
	case '2':
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Delete nameserver');
	modal.body('<center>¿You really want to delete this nameserver?</center>', 450);
	modal.buttons('Delete', 'add.tools_nameserver(\''+d.nid+'\', \''+d.type+'\')', 'Cancel', 'close');
	break;
	}
	//
	} else {
	if($('#mod-reseller #ns_add').attr('hi') < 1) {
	$('#mod-reseller #ns_add').attr('hi', 1);
	var html = '';
	html += '<form method="post" name="new-nameserver" id="new-nameserver">';
	html += '<section class="item">';
	html += '<label>Name:</label>';
	html += '<input type="text" class="input" name="ns" maxlength="50" value="" placeholder="ns1.domain.com"/>';
	html += '</section></form>';
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Add nameserver');
	modal.body(html, 450);
	modal.buttons('Add now', '$(\'#mod-reseller #ns_add\').click();', 'Cancel', 'close');
	} else {
	$('#mod-reseller #ns_add').attr('hi', 0);
	var letsgo = $('#new-nameserver').serialize();
	global.ajax('post', 'admin-tools_nameserver', letsgo, function(n) {
	switch(n.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', n.substring(3));
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', n.substring(3));
	add.update_nameservers();
	break;
	}
	//
	});
	//
	}
	//
	}
	//
	});
	// HERRAMIENTAS PARA PLANES HOSTING ADD|EDIT|DELETE.
	$(document).on('click', '#all-plan #mini-tools li, #mod-reseller #ph_add', function() {
	var d = {pid: $(this).attr('pid'), type: $(this).attr('hi')}
	$('.gotop').click();
	if(d.pid && d.type) {
	switch(d.type) {
	case '1':
	global.ajax('post', 'admin-editar_plan', 'pid='+encodeURIComponent(parseInt(d.pid))+'&type='+encodeURIComponent(parseInt(d.type)), function(html) {
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Edit hosting plan');
	modal.body(html, 450);
	modal.buttons('Save changes', 'add.tools_planes(\''+d.pid+'\', \''+d.type+'\')', 'Cancel', 'close');
	});
	break;
	case '2':
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Delete plan hosting');
	modal.body('<center>¿You really want to Delete this hosting plan?</center>', 450);
	modal.buttons('Delete', 'add.tools_planes(\''+d.pid+'\', \''+d.type+'\')', 'Cancel', 'close');
	break;
	}
	//
	} else {
	if($('#mod-reseller #ph_add').attr('hi') < 1) {
	$('#mod-reseller #ph_add').attr('hi', 1);
	var html = '';
	html += '<form method="post" name="new-plan" id="new-plan">';
	html += '<section class="item">';
	html += '<label>Plan Name:</label>';
	html += '<input type="text" class="input" name="name_plan" maxlength="20" value="" placeholder="One Plan"/>';
	html += '</section></form>';
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Add hosting plan');
	modal.body(html, 450);
	modal.buttons('Add now', '$(\'#mod-reseller #ph_add\').click();', 'Cancel', 'close');
	} else {
	$('#mod-reseller #ph_add').attr('hi', 0);
	var letsgo = $('#new-plan').serialize();
	global.ajax('post', 'admin-tools_plan', letsgo, function(p) {
	switch(p.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', p.substring(3));
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', p.substring(3));
	add.update_planes();
	break;
	}
	//
	});
	//
	}
	//
	}
	//
	});
	
});
/* GLOBAL PARA ADMIN */
var add = {
	update_noticias: function() {
	$('#mod-noticias').animate({opacity: 0.5});
	global.ajax('post', 'admin-noticia_update', '', function(html) {
	$('#mod-noticias').html(html).animate({opacity: 1});
	});
	},
	update_temas: function() {
	$('#mod-temas').animate({opacity: 0.5});
	global.ajax('post', 'admin-tema_update', '', function(html) {
	$('#mod-temas').html(html).animate({opacity: 1});
	});
	},
	update_blacklist: function() {
	$('#all-blacklist').animate({opacity: 0.5});
	global.ajax('post', 'admin-blackList_update', '', function(html) {
	$('#all-blacklist').html(html).animate({opacity: 1});
	});
	},
	tools_noticias: function(id, type) {
	if(id && type) {
	if(type == '2') var letsgo = $('#edit-noticia').serialize();
	else var letsgo = 'nid='+encodeURIComponent(id)+'&type='+encodeURIComponent(type);
	global.ajax('post', 'admin-noticia_tools', letsgo, function(n) {
	switch(n.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', n.substring(3), false);
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', n.substring(3), false);
	add.update_noticias();
	break;
	}
	//
	});
	//
	}
	//
	},
	tools_temas: function(id, type) {
	if(id && type) {
	if(type == '2') var letsgo = $('#edit-tema').serialize();
	else var letsgo = 'tid='+encodeURIComponent(id)+'&type='+encodeURIComponent(type);
	global.ajax('post', 'admin-tema_tools', letsgo, function(t) {
	switch(t.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', t.substring(3), false);
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', t.substring(3), false);
	add.update_temas();
	if(type == '1') setTimeout('location.reload();', 5000);//5 segs
	break;
	}
	//
	});
	//
	}
	//
	},
	tools_blacklist: function(id, type) {
	if(id && type) {
	if(type == '1') var letsgo = $('#edit-blacklist').serialize();
	else var letsgo = 'bid='+encodeURIComponent(id)+'&type='+encodeURIComponent(type);
	global.ajax('post', 'admin-blackList_tools', letsgo, function(b) {
	switch(b.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', b.substring(3), false);
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', b.substring(3), false);
	add.update_blacklist();
	break;
	}
	//
	});
	//
	}
	//
	},
	tools_planes: function(id, type) {
	if(id && type) {
	var letsgo = (type == 1 ? $('#edit-plan').serialize() : 'pid='+encodeURIComponent(id)+'&type='+encodeURIComponent(type));
	global.ajax('post', 'admin-tools_plan', letsgo, function(p) {
	switch(p.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', p.substring(3), false);
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', p.substring(3), false);
	add.update_planes();
	break;
	}
	$('.gotop').click();
	//	
	});
	//	
	}
	//
	},
	update_planes: function() {
	global.ajax('post', 'admin-update_plan', 'type=3', function(html) {
	$('#all-plan').html(html);
	});
	//
	},
	tools_nameserver: function(id, type) {
	if(id && type) {
	var letsgo = (type == 1 ? $('#edit-nameserver').serialize() : 'nid='+encodeURIComponent(id)+'&type='+encodeURIComponent(type));
	global.ajax('post', 'admin-tools_nameserver', letsgo, function(n) {
	switch(n.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', n.substring(3), false);
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', n.substring(3), false);
	add.update_nameservers();
	break;
	}
	$('.gotop').click();
	//
	});
	//	
	}
	//	
	},
	update_nameservers: function() {
	global.ajax('post', 'admin-update_nameservers', 'type=3', function(html) {
	$('#all-dns').html(html);
	});
	//
	},
	tools_reseller: function(id, type) {
	if(id && type) {
	var letsgo = (type == 1 ? $('#edit-reseller').serialize() : 'cid='+encodeURIComponent(id)+'&type='+encodeURIComponent(type));
	global.ajax('post', 'admin-tools_reseller', letsgo, function(c) {
	switch(c.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', c.substring(3), false);
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', c.substring(3), false);
	add.update_resellers();
	break;
	}
	$('.gotop').click();
	//
	});
	//	
	}
	//	
	},
	update_resellers: function() {
	global.ajax('post', 'admin-update_resellers', 'type=3', function(html) {
	$('#all-account').html(html);
	});
	//
	}
	
};
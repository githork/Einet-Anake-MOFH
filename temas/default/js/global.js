// JavaScript Document
$(document).ready(function() {
	// SCREEN IN|OUT.
	$([window, document]).on('focus', function() {
	global_data.focus = true;
	}).on('blur', function() {
	global_data.focus = false;
	});
	// LOAD BBCODE.
	global.bbcode();
	// TIPSY.
	$('[tl]').tipsy();
	// MENU FIJO.
	var menu = $('#head-menu');
	var menu_offset = menu.offset();
	$(window).on('scroll', function() {
	if($(this).scrollTop() > menu_offset.top) {
	menu.addClass('menu-fijo');
	} else {
	menu.removeClass('menu-fijo');
	}
	// IR ARRIBA.
	if($(this).scrollTop() > 100) {
	$('.gotop').css({bottom:'0px'}).fadeIn();//top 50px
	} else {
	$('.gotop').css({bottom:'-100px'}).fadeOut();
	}
	// ELIMINAR ALERTAS CON SCROLL.
	if($('#gl-alert .new').length > 0) {
	$('#gl-alert .new').fadeOut(350, function(){$(this).remove();});
	}
	//
	});
	//
	$(document).on('click', '.gotop', function() {
	$('html, body').animate({scrollTop:'0'}, 600);
	return false;
	//
	});
	// ANTI-JOB.
	var searchWinHref = window.location.href;
	if(searchWinHref.indexOf("?i=") > -1) {
	window.location.href = window.location.href.split('?i=')[0];
	}
	// NOTICIAS.
	global.news.all = $('#top_news > li').length;
	global.news.slider();
	// INICIAR SESION.
	$(document).on('click', '#login input[type="submit"]', function(e) {
	var d = {
	'nick': $('#login input[name="username"]'),
	'pass': $('#login input[name="password"]'),
	'redir': $('#login input[name="redirect"]'),
	'error': $('#error-log'),
	'autolog': $('#login input[name="log"]').is(':checked') ? 'true' : 'false',
	};
	var letsgo = 'nick='+encodeURIComponent(d.nick.val())+'&pass='+encodeURIComponent(d.pass.val())+'&r='+encodeURIComponent(d.redir.val())+'&log='+encodeURIComponent(d.autolog);
	global.ajax('post', 'user-login_user', letsgo, function(l) {
	$('.login-box').animate({opacity: 0.5});
	switch(l.charAt(0)) {
	case '0':
	global.error(l.substring(3), 0);
	d.pass.focus();
	$('#login input[type="submit"]').removeAttr('disabled').removeClass('disabled');
	break;
	case '1':
	(l.substring(3) == '') ? location.reload() : location.href = l.substring(3);
	break;
	}
	$('.login-box').animate({opacity: 1});
	//
	});
	//
	e.preventDefault();
	//
	});
	// RE-ESTABLECER PASSWORD.
	$(document).on('click', '#remind_password', function(e) {
	if(e.type) {
	var email = $('#modal #rew_email').val();
	var form = '';
    form += '<label for="r_email" style="margin-left:0;">Email address:</label>';
    form += '<input type="text" tabindex="1" name="rew_email" id="rew_email" class="input" maxlength="40" title="Enter the email associated with your account" placeholder="example@email.com"/>';
	modal.show(true);
    modal.load_inicio();
    modal.load_fin();
    modal.title('Password recovery');
    modal.body(form, 450);
    modal.buttons('Continue', '$(\'#remind_password\').click()', 'Cancel', 'close');
	if(!/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/.exec(email)) {
	$('#modal #rew_email').focus();
	} else {
	global.ajax('post', 'user-recover_pass', 'rew_email='+encodeURIComponent(email)+'&type=1', function(r) {
	switch(r.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', r.substring(3), false);
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', r.substring(3), false);
	break;
	//
	}
	//
	});
	//
	}
	//
	}
	e.preventDefault();
	//
	});
	// ENVIAR ACTIVACION.
	$(document).on('click', '#resend_validation', function(e) {
	if(e.type) {
	var email = $('#modal #rew_email').val();
	var form = '';
    form += '<label for="r_email" style="margin-left:0;">Email address:</label>';
	form += '<input type="text" tabindex="1" name="rew_email" id="rew_email" class="input" maxlength="40" title="Enter the email associated with your account" placeholder="example@email.com"/>';
	modal.show(true);
    modal.load_inicio();
    modal.load_fin();
    modal.title('Send validation code');
    modal.body(form, 450);
    modal.buttons('Continue', '$(\'#resend_validation\').click()', 'Cancel', 'close');
	if(!/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/.exec(email)) {
	$('#modal #rew_email').focus();
	} else {
	global.ajax('post', 'user-validation', 'rew_email='+encodeURIComponent(email)+'&type=2', function(v) {
	switch(v.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', v.substring(3), false);
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', v.substring(3), false);
	break;
	//
	}
	//
	});
	//
	}
	//
	}
	e.preventDefault();
	//
	});
	// CERRAR NOTIFICACION BOX.
	$(document).on('click', '.beeper_x', function() {
	var id_obj = $(this).attr('bid');
	$('#beep_'+id_obj).fadeOut().remove();
	return false;
	});
	// ENVIAR MENSAJE, FOLLOW|UNFOLLOW.
	$(document).on('click', '.tools-user > li, #t1-user > span, .n-preview > #t2-user', function() {
	var d = {id: $(this).attr('id'), pid: $(this).attr('pid'), action: $(this).attr('act')}
	if(d.pid && d.action) {
	switch(d.action) {
	case '1':
	location.href = global_data.url+'/mensajes/nuevo/?user='+d.pid;
	break;
	case '2':
	var all = parseInt($('#u_seg strong').text())+parseInt(1);
	var letsgo = 'pid='+encodeURIComponent(d.pid)+'&type='+encodeURIComponent(d.action);
	global.ajax('post', 'user-follow_user', letsgo, function(u) {
	switch(u.charAt(0)) {
	case '0':
	global.error(u.substring(3), 0);
	break;
	case '1':
	if(d.id == 't1-follow') {
	$('span[pid='+d.pid+']').removeClass('is-success').addClass('is-danger').attr('title', 'Delete from friends.');
	$('span[pid='+d.pid+'] i').removeClass('icon-user-plus').addClass('icon-user-x');
	} else {
	$('.tools-user li[act=2]').attr('title', 'Delete from friends.').html('Delete from friends').addClass('here');
	$('#u_seg').attr('title', all+' followers');
	$('#u_seg strong').text(all);
	}
	break;
	case '2':
	if(d.id == 't1-follow') {
	$('span[pid='+d.pid+']').removeClass('is-danger').addClass('is-success').attr('title', 'Add my friends.');
	$('span[pid='+d.pid+'] i').removeClass('icon-user-x').addClass('icon-user-plus');
	} else {
	$('.tools-user li[act=2]').attr('title', 'Add my friends.').html('Add my friends').removeClass('here');
	$('#u_seg').attr('title', parseInt(all) - parseInt(2)+' followers');
	$('#u_seg strong').text(parseInt(all) - parseInt(2));
	}
	break;
	}
	//
	});
	//
	break;
	}
	//
	}
	//
	});
	// ENVIAR MENSAJE.
	$(document).on('click', '#message-form button[type="submit"]', function(e) {
	var d = {user: $('input[name="usuario"]'), title: $('input[name="titulo"]'), msg: $('textarea[name="mensaje"]')}
	if(d.user.val() == '' || d.user.val().length < 4) {
	global.error('Add the user you are sending the message to.', 0);
	d.user.focus();
	} else if(d.msg.val() == '' || d.msg.val().length < 4) {
	global.error('Remember to write a message before you send it.', 0);
	($('.wysibb-texarea').length == 1 && $('.wysibb-texarea').is(':visible') == false) ? $('.wysibb-body').focus() : d.msg.focus();
	} else {
	var letsgo = 'usuario='+encodeURIComponent(d.user.val())+'&titulo='+encodeURIComponent(d.title.val())+'&mensaje='+encodeURIComponent(d.msg.val());
	global.ajax('post', 'mensajes-enviar', letsgo, function(s) {
	switch(s.charAt(0)) {
	case '0':
	global.error(s.substring(3), 0);
	break;
	case '1':
	$('#message-form')[0].reset();
	$('.wysibb-body').empty();
	global.error(s.substring(3), 1);
	break;
	}
	//
	});
	//
	}
	e.preventDefault();
	});
	// ENVIAR RESPUESTA.
	$(document).on('click', '#respuesta-form button[type="submit"]', function(e) {
	($('.wysibb-texarea').length == 1) ? $('.wysibb-texarea').sync() : '';
	var d = {id: $('input[name="mp_id"]').val(), msg: $('textarea[name="mensaje"]').val().replace('\n', '')}
	if(d.msg == '' || d.msg.length < 2) {
	($('.wysibb-texarea').length == 1 && $('.wysibb-texarea').is(':visible') == false) ? $('.wysibb-body').focus() : $('textarea[name="mensaje"]').focus();
	} else {
	var letsgo = 'id='+encodeURIComponent(d.id)+'&mensaje='+encodeURIComponent(d.msg);
	global.ajax('post', 'mensajes-respuesta', letsgo, function(r) {
	switch(r.charAt(0)) {
	case '0':
	global.error(r.substring(3), 0);
	break;
	case '1':
	($('.wysibb-texarea').length == 1 && $('.wysibb-texarea').is(':visible') == false) ? $('.wysibb-body').empty().focus() : $('textarea[name="mensaje"]').val('').focus();
	$('#resp-prew').append($(r.substring(3)).fadeIn('slow')).animate({ scrollTop: $('#resp-prew')[0].scrollHeight}, 0);
	break;
	}
	//
	});
	//
	}
	e.preventDefault();
	});
	// MENSAJES PRIVADOS MARCAR|ELIMINAR.
	$(document).on('click', '#msg-lista #mini-tools> li', function() {
	var d = {id: parseInt($(this).attr('mid')), action: $(this).attr('hi'), stat: parseInt($(this).attr('rp')), event: $(this)}
	if(d.id && d.action) {
	var letsgo = 'id='+encodeURIComponent(d.id)+'&act='+encodeURIComponent(d.action)+'&stat='+encodeURIComponent(d.stat);
	switch(d.action) {
	case 'load':
	global.ajax('post', 'mensajes-tools', letsgo, function(m) {
	switch(m.charAt(0)) {
	case '0':
	global.error(m.substring(3), 0);
	break;
	case '1':
	if(d.stat == 1) {
	$(d.event).attr({'rp': '0', 'title': 'Mark as unread'}).html('<i class="einet icon-check-circle"></i><p>Not read</p>');
	$('article[mid='+d.id+']').removeClass('no-load');
	} else {
	$(d.event).attr({'rp': '1', 'title': 'Mark as read'}).html('<i class="einet icon-circle"></i><p>Read</p>');
	$('article[mid='+d.id+']').addClass('no-load');
	}
	break;
	}
	});
	break;
	case 'del':
	global.ajax('post', 'mensajes-tools', letsgo, function(m) {
	switch(m.charAt(0)) {
	case '0':
	global.error(m.substring(3), 0);
	break;
	case '1':
	$('article[mid='+d.id+']').animate({opacity: 0.5});
	break;
	}
	});
	break;
	}
	//
	}
	});
	// GUARDAR DATOS DEL USER.
	$(document).on('click', '#user input[type="submit"]', function(e) {
	var section = parseInt($(this).attr('act'));
	if(section > 0) {
	var letsgo = $('#user').serialize()+'&section='+encodeURIComponent(section);
	$('#user').animate({opacity: 0.5});
	global.ajax('post', 'user-cuenta_saved', letsgo, function(s) {
	switch(s.charAt(0)) {
	case '0':
	global.error(s.substring(3), 0);
	break;
	case '1':
	global.error(s.substring(3), 1);
	break;
	}
	$('#user').animate({opacity: 1});		
	});
	//
	}
	//
	e.preventDefault();
	});
	// CARGAR|GUARDAR AVATAR USER.
	$(document).on('change', '#avatar_file input[name="img[]"]', function() {
	if($(this).val().length) { global.upload_avatar(); }
	});
	// CONFIGURACION PARA NOTIFICACIONES.
	$(document).on('click', '#mon-tools li input[type="checkbox"]', function() {
	var d = {name: $(this).attr('name'), value: $(this).is(':checked') ? 'false' : 'true'}
	if(d.name.length > 1) {
	$('#mon-tools li').css('opacity', 0.4);
	global.ajax('post', 'user-guardar_filtros', 'name='+encodeURIComponent(d.name)+'&value='+encodeURIComponent(d.value), function(n) {
	switch(n.charAt(0)) {
	case '0':
	global.error(n.substring(3), 0);
	$('#mon-tools li').css('opacity', 1);
	break;
	case '1':
	$('#mon-tools li').css('opacity', 1);
	break;
	}
	//
	});
	//
	}
	//
	});
	// HERRAMIENTAS DE NOTIFICACIONES.
	$(document).on('click', '#ntf-right #mini-tools > li', function() {
	var d = {id: parseInt($(this).attr('nid')), action: $(this).attr('hi'), stat: parseInt($(this).attr('rp')), event: $(this)}
	if(d.id && d.action) {
	var letsgo = 'id='+encodeURIComponent(d.id)+'&act='+encodeURIComponent(d.action)+'&stat='+encodeURIComponent(d.stat);
	switch(d.action) {
	case 'load':
	global.ajax('post', 'user-notification_tools', letsgo, function(n) {
	switch(n.charAt(0)) {
	case '0':
	global.error(n.substring(3), 0);
	break;
	case '1':
	if(d.stat == 1) {
	$(d.event).attr({'rp': '0', 'title': 'Mark as read'}).html('<i class="einet icon-circle"></i><p>Read</p>');
	$('.items-all > li[nid='+d.id+']').addClass('no-load');
	} else {
	$(d.event).attr({'rp': '1', 'title': 'Mark as unread'}).html('<i class="einet icon-check-circle"></i><p>Not read</p>');
	$('.items-all > li[nid='+d.id+']').removeClass('no-load');
	}
	break;
	}
	//
	});
	break;
	case 'del':
	global.ajax('post', 'user-notification_tools', letsgo, function(n) {
	switch(n.charAt(0)) {
	case '0':
	global.error(n.substring(3), 0);
	break;
	case '1':
	$('.items-all > li[nid='+d.id+']').animate({opacity: 0.5});
	break;
	}
	//
	});
	break;
	}
	//
	}
	});
	// ACCIONES CON UN CLICK.
	$(document).on('click', function(e) {
	// Cerrar modal con esc.
	if(e.target.id != 'modal') {
	$(this).on('keydown', function(k) {
	key = (k == null) ? k.keyCode : k.which;
	if(key == 27) modal.close();
	});
	}
	// Cerrar monitors.
	if(e.target.id != 'msg_') {
	$('ul.menu_user> #msg').removeClass('here');
	$('ul.menu_user> li > #c-msg').hide();	
	}
	if(e.target.id != 'ntf_' && e.target.id != 'mark-r') {
	$('ul.menu_user> #ntf').removeClass('here');
	$('ul.menu_user> li > #c-ntf').hide();
	}
	//
	});
	// INICIAR LIVE.
	if(global_data.live[0] == 1 && global_data.is_member == 1) { live.init(); }
	// SI OBSERVAMOS LA WEB DESACTIVAMOS EL LIVE.
	if(global_data.focus == true) { live.hide(); }
	// SI NO ESTAMOS EN MENSAJES NOTIFICAMOS.
	if(global_data.mps > 0 && global_data.action != 'leer') { msg.balloon(global_data.mps); }
});

// AJAX GLOBAL.
var global = {
	cache: {},
	vars: Array(),
	ajax: function(method, action, params, response) {
	$('#loading').fadeIn(250);
	$.ajax({
	type: method,
	url: global_data.url+'/'+action+'.php',
	data: params,
	success: function(data) {
	response(data);
	$('#loading').fadeOut(250);
	}
	//
	});
	//
	},
	multi_ajax: function(method, action, params, response) {
	$('#loading').fadeIn(250);
	$.ajax({
	type: method,
	url: global_data.url+'/'+action+'.php',
	data: params,
	contentType: false,
	processData: false,
	success: function(data) {
	response(data);
	$('#loading').fadeOut(250);
	}
	//
	});
	//
	},
	news: {
	all: 0,
	count: 1,
	slider: function() {
	if(global.news.all > 1) {
	if(global.news.count < global.news.all) global.news.count++;
	else global.news.count = 1;
	$('#top_news > li').hide();
	$('#new_'+global.news.count).fadeIn();
	setTimeout('global.news.slider()', 7000);//7 segs
	}
	//
	}
	//
	},
	bbcode: function() {
	// Mensajes.
	if($('textarea[name="mensaje"]') && !$('.wysibb-texarea').length) {
	var wbbOpt = {buttons:'smilebox,img,link,bold,italic,underline,strike,sup,sub,code'};
	$('textarea[name="mensaje"]').html('').removeAttr('onblur onfocus class').css('height', '34').wysibb(wbbOpt);
	$('.wysibb').css('margin-bottom', '0');
	}
	//
	},
	points_image: function(img, selection) {
	var scaleX = $('#thumbnail').width() / selection.width;
	var scaleY = $('#thumbnail').height() / selection.height;
	var rx = 154 / selection.width;
	var ry = 115 / selection.height;
	$('input[name=thumb_x1]').val(selection.x1);
	$('input[name=thumb_y1]').val(selection.y1);
	$('input[name=thumb_x2]').val(selection.x2);
	$('input[name=thumb_y2]').val(selection.y2);
	$('input[name=thumb_w]').val(selection.width);
	$('input[name=thumb_h]').val(selection.height);
	},
	cut_image: function() {
	$.getScript(global_data.theme+'/js/jquery.imgareaselect.js', function() {
	$('#thumbnail').imgAreaSelect({
	show: true,
	x1: 0,
	y1: 0,
	x2 : 120,
	y2: 120,
	onSelectChange: global.points_image,
	minHeight: 120,
	minWidth: 120,
	outerOpacity: '0.3',
	handles: true,
	});
	//
	});
	$('.imgareaselect-outer, .imgareaselect-selection, .imgareaselect-border1, .imgareaselect-border2').css('z-index', '130');
	},
	upload_avatar: function() {
	var letsgo = new FormData($('#avatar_file')[0]);
	var d = $('#avatar_file input[name="img[]"]').val();
	if(!d.length) {
	} else if(!d.match(/\.(jpeg|jpg|gif|png)/i)) {
	global.error('The image must be in the format jpeg, jpg, gif, png', 0);
	} else {
	global.multi_ajax('post', 'user-imagen_original', letsgo, function(u) {
	switch(u.charAt(0)) {
	case '0':
	global.error(u.substring(3), 0);
	break;
	case '1':
	data = u.split('|');
	var i = {src: data[1], width: data[2]};
	global.step_avatar(i);
	$('input[name="img_url"]').val(i.src);
	break;
	}
	//
	});
	//
	}
	//
	},
	step_avatar: function(e) {
	if(e.width < 360) {
	var width = 300;
	} else var width = parseInt(e.width)+parseInt(40);
	modal.mask_close = false;
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Crop profile picture');
	modal.hide();
	modal.body('<center><img src="'+e.src+'" id="thumbnail" class="thumbnail-cortar"/></center>', width, '', -80);
	modal.buttons('Save image', 'global.save_avatar()', 'Cancel', 'modal.close();global.stop_avatar()');
	$('#modal #thumbnail').on('load', function() { global.cut_image(); });
	//
	},
	save_avatar: function() {
	modal.load_inicio();
	$('.imgareaselect-outer, .imgareaselect-selection, .imgareaselect-border1, .imgareaselect-border2').remove();
	$('.imgareaselect-border3').parent().remove();
	var letsgo = '';
	if($('.img-positions').length) {
	$('.img-positions input').each(function() { var val = $(this).val(); letsgo += '&'+$(this).attr('name')+'='+val; });
	}
	global.ajax('post', 'user-guardar_imagen', letsgo, function(s) {
	modal.load_fin();
	switch(s.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', s.substring(3), false);
	$('#avatar_file')[0].reset();
	break;
	case '1':
	$('#box_avatar').attr('src', s.substring(3));
	$('#avatar_file')[0].reset();
	modal.close();
	break;
	}
	//
	});
	//
	},
	stop_avatar: function() {
	$('.thumbnail-preview').attr('src', '').removeAttr('style');
	$('input[name=img_url]').val('');
	$('.imgareaselect-outer, .imgareaselect-selection, .imgareaselect-border1, .imgareaselect-border2').remove();
	$('.imgareaselect-border3').parent().remove();
	},
	error: function(text, type) {
	if(text && type >= 0) {
	var i = parseInt($('#gl-alert .new').length) + parseInt(1);
	switch(type) {
	case 1:
	d = {'class': 'success', 'icon': 'check-circle'};
	break;
	case 2:
	d = {'class': 'primary', 'icon': 'alert-octagon'};
	break;
	case 3:
	d = {'class': 'warning', 'icon': 'alert-octagon'};
	break;
	case 4:
	d = {'class': 'purple', 'icon': 'alert-octagon'};
	break;
	default:
	d = {'class': 'danger', 'icon': 'alert-octagon'};
	break
	}
	$('#gl-alert').append('<div id="new-'+i+'" class="new notification is-'+d.class+'"><i class="einet icon-'+d.icon+'"></i><span class="text">'+text+'</span><div class="clear"></div></div>');
	$('#new-'+i).animate({marginTop:'1px'},{duration: 350, easing:'easeOutExpo', queue:!1}).fadeOut(3000, function(){$(this).remove();});
	//
	}
	//
	}
};
// NOTIFICACIONES.
var live = {
	status: {'ntfs': 'ON', 'msgs' : 'ON', 'sound' : 'ON'},
	n_total: 0,
	m_total: 0,
	init: function() {
	// Notificaciones.
	live.status['ntfs'] = $.cookie('live_ntfs');
	if(live.status['ntfs'] == null) $.cookie('live_ntfs', 'ON', {expires: 90});
	// Mensajes.
	live.status['msgs'] = $.cookie('live_msgs');
	if(live.status['msgs'] == null) $.cookie('live_msgs', 'ON', {expires: 90});
	// Sonidos.
	live.status['sound'] = $.cookie('live_sound');
	if(live.status['sound'] == null) $.cookie('live_sound', 'ON', {expires: 90});
	// No hay notificaciones ni mensajes desactivamos el uodate.
	if(live.status['ntfs'] == 'OFF' && live.status['msgs'] == 'OFF') return true;
	// Update 30 segundos.
	else setTimeout(function() { live.update(); }, global_data.live[1]);
	//
	},
	print: function(e) {
	// El contenido.
	$('#js').html(e);
	// Total para todos.
	var n_total = parseInt($('#live-stream').attr('ntotal'));
	var m_total = parseInt($('#live-stream').attr('mtotal'));
	live.n_total = live.n_total + n_total;
	live.m_total = live.m_total + m_total;
	var total_ntfs = live.n_total + live.m_total;
	if(total_ntfs > 0) {
	var live_stream_html = $('#live-stream').html();
	// Las cargamos en pantalla.
	$('#AlertBox').html(live_stream_html);
	// Las mostramos en vivo.
	$('.NTBeeper_Full').fadeIn(1200);
	$('#AlertBox').slideToggle(1000);
	// Eventos con el mouse.
	this.mouse_events();
	// Estamos en la pagina?
	if(global_data.focus == true) {
	// Si estamos mirando las ocultamos.
	setTimeout(function() { live.hide(); }, global_data.live[2]);
	} else {
	// Cambiamos el titulo de la pagina si llega algo.
	$(document).attr('title','('+total_ntfs+') '+global_data.title+' - '+global_data.description);
	// Sonido para el tipo de notificacion.
	var sound_type = (live.m_total > 0) ? 'new-mensaje' : 'new-notificacion';
	if(live.status['sound'] == 'ON') {
	$('#speak').html('<audio autoplay="true" controls="true"><source src="'+global_data.theme+'/sound/'+sound_type+'.mp3" type="audio/mpeg"/><source src="'+global_data.theme+'/sound/'+sound_type+'.ogg" type="audio/ogg"/></audio>');
	}
	// globos de notificaciones.
	ntf.balloon(live.n_total);
	msg.balloon(live.m_total);
	}
	//
	}
	//
	},
	mouse_events: function() {
	$(document).on('mouseover', '.NTBeep', function() {
	$(this).addClass('NTBeep_Selected');
	$(this).parent().parent().addClass('NTBeep_Paused');
	}).on('mouseout', '.NTBeep', function() {
	$(this).removeClass('NTBeep_Selected');
	$(this).parent().parent().removeClass('NTBeep_Paused');
	live.hide();
	//
	});
	//
	},
	update: function() {
	$('#loading').fadeIn(250);
	$.ajax({
	type: 'post',
	url: global_data.url+'/live-stream.php',
	data: 'ntf='+live.status['ntfs']+'&msg='+live.status['msgs'],
	success: function(data) {
	live.print(data);
	$('#loading').fadeOut(350);
	},
	complete: function() {
	setTimeout(function() { live.update(); }, global_data.live[1]);
	$('#loading').fadeOut(350);
	}
	//
	});
	//
	},
	hide: function() {
	var div = $('.NTBeeper_Full');
	var total = div.length;
	setTimeout(function() {
	if(total > 0) {
	if($(div[0]).hasClass('NTBeep_Paused') == false) {
	$(div[0]).fadeOut().remove();
	live.hide();
	}
	//
	}
	//
	}, 1000);
	//
	},
	ch_status: function(e) {
	live.status[e] = (live.status[e] == 'ON') ? 'OFF' : 'ON';
	$.cookie('live_'+e, live.status[e], {expires: 90});
	}
}
// NOTFICACIONES FUNCIONES.
var ntf = {
	cache: {},
	vars: Array(),
	last: function() {
	msg.close();
	var c = parseInt($('ul.menu_user> #ntf .i').html());
	if($('ul.menu_user> li > #c-ntf').css('display') != 'none') {
	$('ul.menu_user> #ntf').removeClass('here');
	$('ul.menu_user> li > #c-ntf').hide();
	} else {
	if(($('ul.menu_user> li > #c-ntf').css('display') == 'none' && c > 0) || typeof ntf.cache.last == 'undefined') {  
	$('ul.menu_user> #ntf').addClass('here');
	$('ul.menu_user> li > #c-ntf').show();
	global.ajax('post', 'live-ajax', 'action=last', function(items) {
	ntf.cache.last = items;
	ntf.show();
	});
	} else ntf.show();
	//
	}
	//
	},
	balloon: function(data) {
	var c = parseInt($('ul.menu_user> #ntf .i').html());
	if(data != c && data > 0) {
	var total = (data != 1 ? ' notifications' : ' notification');
	if(!$('ul.menu_user> #ntf .i').length) $('ul.menu_user> #ntf .i').append('<span class="i">0</span>');
	$('ul.menu_user> #ntf .i').show().html((data >= 99 ? '+99' : data)).attr({'title':'('+data+')'+total});
	$('ul.menu_user> #ntf .i').animate({'marginTop': '-18px'}, 100, null, function() {
	$('ul.menu_user> #ntf .i').animate({'marginTop': '-10px'}, 100)});
	} else if(data == 0) $('ul.menu_user> #ntf .i').hide();
	//
	},
	show: function() {
	if(typeof ntf.cache.last != 'undefined') {
	$('ul.menu_user> #ntf').addClass('here');
	$('ul.menu_user> #ntf .i').hide();
	$('ul.menu_user> li > #c-ntf').show();
	$('ul.menu_user> #ntf .p-term').show().children('ul').html(ntf.cache.last);
	}
	//
	},
	mark: function(data) {
	if(data == 'ntf' || data == 'mon') {
	global.ajax('post', 'user-notification_tools', 'act='+encodeURIComponent(data), function(n) {
	switch(n.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', n.substring(3), false);
	break;
	case '1':
	if(data == 'ntf') {
	$('#ntf .p-term ul > li').removeClass('no-load');
	} else {
	$('#ntf-right #mini-tools > li[hi=load]').attr({'rp': '1', 'title': 'Mark as unread'}).html('<i class="einet icon-check-circle"></i><p>Not read</p>');
	$('ul > #ntf-item').removeClass('no-load');
	}
	break;
	}
	//	
	});
	//	
	}
	//
	},
	close: function() {
	$('ul.menu_user> #ntf').removeClass('here');
	$('ul.menu_user> li > #c-ntf').hide();
	}
}
// FUNCIONES PARA MENSAJES.
var msg = {
	cache: {},
	vars: Array(),
	last: function() {
	ntf.close();
	var c = parseInt($('ul.menu_user> #msg .i').html());
	if($('ul.menu_user> li > #c-msg').css('display') != 'none') {
	$('ul.menu_user> #msg').removeClass('here');
	$('ul.menu_user> li > #c-msg').hide();
	} else {
	if(($('ul.menu_user> li > #c-msg').css('display') == 'none' && c > 0) || typeof msg.cache.last == 'undefined') {
	$('ul.menu_user> #msg').addClass('here');
	$('ul.menu_user> li > #c-msg').show();
	global.ajax('post', 'mensajes-lista', '', function(items) {
	msg.cache.last = items;
	msg.show();
	});
	} else msg.show();
	//
	}
	//
	},
	balloon: function(data) {
	var c = parseInt($('ul.menu_user> #msg .i').html());
	if(data != c && data > 0) {
	var total = (data != 1 ? ' new messages' : ' new message');
	if(!$('ul.menu_user> #msg .i').length) $('ul.menu_user> #msg .i').append('<span class="i">0</span>');
	$('ul.menu_user> #msg .i').show().html((data >= 99 ? '+99' : data)).attr({'title':'('+data+')'+total});
	$('ul.menu_user> #msg .i').animate({'marginTop': '-18px'}, 100, null, function() {
	$('ul.menu_user> #msg .i').animate({'marginTop': '-10px'}, 100)});
	} else if(data == 0) $('ul.menu_user> #msg .i').hide();
	//
	},
	show: function() {
	if(typeof msg.cache.last != 'undefined') {
	$('ul.menu_user> #msg').addClass('here');
	$('ul.menu_user> #msg .i').hide();
	$('ul.menu_user> li > #c-msg').show();
	$('ul.menu_user> #msg .p-term').show().children('ul').html(msg.cache.last);
	}
	//
	},
	close: function() {
	$('ul.menu_user> #msg').removeClass('here');
	$('ul.menu_user> li > #c-msg').hide();
	}
}
// MODALBOX.
var modal = {
	is_show: false,
	mask_close: true,
	show: function() {
	if(this.is_show) return;
	else this.is_show = true;
	if($('#modal').html() == '') $('#modal').html('<div id="modal_content"><div id="modal_loading"></div><div id="modal_titulo"></div><div id="modal_body"></div><div id="modal_buttons"></div></div>').show();
	// Mover modalbox libre.
	$('#modal_content').draggable().css('cursor','move');
	$('#black-screen').show();
	$('#black-screen').click(function() { modal.close(); });
	if(this.mask_close) $('#modal').click(function(e) { if(e.target.id == 'modal') { modal.close(); } });
	else $('#modal').unbind('click');
	},
	title: function(title) {
	if(title) $('#modal_content #modal_titulo').html(title+' <a href="javascript:modal.close()" class="modal_close" title="Close/Esc">X</a>');
	else $('#modal_content #modal_titulo').css('display', 'none');	
	},
	body: function(body, width, height, top, more) {
	$('#modal #modal_content').width(width ? width:'').height(height ? height:'').css('top', top+'px');
	if(more === true) {
	$('#modal #modal_body').html('<div class="modal_cont">'+body+'</div>').css('padding', '0').addClass('clear');
	} else if(more == 'limit') {
	$('#modal #modal_body').html('<div class="modal_cont">'+body+'</div>').css({'height': '300px', 'overflow-y': 'auto'});
	} else if(more == 'ext') {
	$('#modal #modal_body').html('<div class="modal_cont">'+body+'</div>').css({'height': +height+'px', 'overflow-y': 'auto'});
	} else {
	$('#modal #modal_body').html('<div class="modal_cont">'+body+'</div>');
	}
	},
	buttons: function(btn_1, btn1_link, btn_2, btn2_link) {
	$('#modal #modal_buttons').html('');
	if(btn_1) {
	$('#modal #modal_buttons').append('<a onClick="'+(btn1_link == 'close' ? 'modal.close()': btn1_link)+'" class="accept button is-primary is-focused">'+btn_1+'</a>');
	}
	if(btn_2) {
	$('#modal #modal_buttons').append('<a onClick="'+(btn2_link == 'close' ? 'modal.close()': btn2_link)+'" class="cancel button is-danger is-focused">'+btn_2+'</a>');
	}
	if($('#modal #modal_buttons').html() == '') $('#modal #modal_buttons').remove();
	},
	close: function() {
	this.mask_close = true;
	this.close_button = false;
	this.is_show = false;
	$('#black-screen').hide();
	$('#modal #modal_content').fadeOut('fast', function(){ $(this).remove(); });
	$('#modal').hide();
	this.load_fin();
	},
	alert: function(title, body, reload) {
	this.load_fin();
	this.show();
	this.title(title);
	this.body(body);
	$('#modal #modal_body').css({'text-align' : 'center'});
	this.buttons('Accept', (reload ? 'location.reload();' : 'modal.close()'));
	},
	load_inicio: function() {
	$('#modal #modal_loading').fadeIn('fast');	
	},
	load_fin: function() {
	$('#modal #modal_loading').fadeOut('fast');
	},  
	load_imagen: function(e) {
	var data = new Image();
	data.src = e;
	width = data.width;
	height = data.height;
	if(data.width && data.height) { 
	if(data.width >= 800 || data.height >= 600) {
	width = height * 980 / width;
	height = width * 550 / height;
	}
	this.show(true);
	this.load_inicio();
	this.load_fin();
	this.title();
	this.buttons();
	this.body('<center><i class="einet icon-x" onclick="javascript:modal.close()" title="Close/Esc"></i><img src="'+e+'" width="'+width+'" height="'+height+'"><div class="modal_tools"><a href="'+e+'" target="_blank" download="'+e+'" title="Download image">Download</a></div></center>', width, height, -50, true);
	} else {
	this.show();
	this.load_inicio();
	this.load_fin();
	this.alert('Connection error!!', 'The selected image could not be loaded..', false);  
	}
	},
	screen: function(e) {
	if(e) this.mask_close = false;
	},
	hide: function() {
	$('#modal .modal_close').remove();
	}
}
// Facebook SDK: 7.0
window.fbAsyncInit = function() {
    FB.init({
	appId : $('meta[property="og:id"]').attr('content'),
	autoLogAppEvents : true,
	xfbml : true,
	version : 'v7.0',
    });
	};
// TIPSY Version: 1.0
(function(e) {
	e.fn.tipsy = function(t) {
	if(typeof(t) == "string" && ["show","hide"].indexOf(t) > -1) {
	switch(t) {
	case "show":
	$(this).trigger('tipsy.show');
	break;
	case "hide":
	$(this).trigger('tipsy.hide');
	break;
	}
	return this;
	}
	var n = e.extend({arrowWidth: 10, attr: 'data-tipsy', cls: null, duration: 150, offset: 7, position: 'top-center', trigger: 'hover', onShow: null, onHide: null}, t);
	return this.each(function(t, r) {
	var s = e(r),
	b = '.tipsy',
	o = e('<div class="tipsy"></div>'),
	p = ["top-left", "top-center", "top-right", "bottom-left", "bottom-center", "bottom-right", "left", "right"],
	f = {
	init: function() {
	var d = {};
	switch(n.trigger) {
	case 'hover':
	d = {mouseenter: f._show, mouseleave: f._hide}
	break;
	case 'focus':
	d = {focus: f._show, blur: f._hide}
	break;
	case 'click':
	d = {
	click: function(e) {
	if(!f._clSe) {
	f._clSe = true;
	f._show(e);
	} else {
	f._clSe = false;
	f._hide(e);
	}
	},
	}
	break;
	case 'manual':
	f._unbindOptions();
	d = {
	"tipsy.show": function(e) {
	f._clSe = true;
	f._show(e);
	},
	"tipsy.hide": function(e) {
	f._clSe = false;
	f._hide(e);
	}
	}
	break;
	}
	s.on(d);
	o.hide();
	},
	_show: function(e) {
	$(b).remove();
	f._clear();
	if(f.hasAttr(n.attr+'-disabled')) {
	return false;
	}
	f._createBox();
	if(n.trigger!='manual') {
	f._bindOptions();
	}
	},
	_hide: function(e) {
	f._fixTitle(true);
	o.stop(true, true).fadeOut(n.duration, function() {
	n.onHide != null && typeof n.onHide == "function" ? n.onHide(o, s) : null;
	f._clear();
	$(this).remove();
	});
	},
	_showIn: function() {
	o.stop(true, true).fadeIn(n.duration, function() {
	n.onShow != null && typeof n.onShow == "function" ? n.onShow(o, s) : null;
	});
	},
	_bindOptions: function() {
	e(window).bind("contextmenu", function() {
	f._hide();
	}).bind("blur", function() {
	f._hide();
	}).bind("resize", function() {
	f._hide();
	}).bind("scroll", function() {
	f._hide();
	});
	},
	_unbindOptions: function() {
	e(window).unbind("contextmenu", function() {
	f._hide();
	}).unbind("blur", function() {
	f._hide();
	}).unbind("resize", function() {
	f._hide();
	}).unbind("scroll", function() {
	f._hide();
	});
	},
	_clear: function() {
	o.attr("class", "tipsy").empty();
	f._lsWpI = [];
	f._lsWtI = [];
	},
	hasAttr: function(e) {
	e = s.attr(e); return typeof e !== typeof undefined && e !== false;
	},
	_fixTitle: function(a) {
	if(a) {
	if(f.hasAttr('data-title') && !f.hasAttr('title') && f._lsWtI[0] == true) {
	s.attr('title', f._lsWtI[1] || '').removeAttr('data-title');
	}
	} else {
	if(f.hasAttr('title') || !f.hasAttr('data-title')) {
	f._lsWtI = [true, s.attr('title')];
	s.attr('data-title', s.attr('title') || '').removeAttr('title');
	}
	}
	},
	_getTitle: function() {
	f._fixTitle();
	var title = s.attr('data-title');
	title = ''+title;
	return title;
	},
	_position: function(a) {
	var css = {top: 0, left: 0},
	position = (a ? a : (f.hasAttr(n.attr+'-position') ? s.attr(n.attr+'-position') : n.position)),
	arrow = position.split('-'),
	offset = (f.hasAttr(n.attr+'-offset') ? s.attr(n.attr+'-offset') : n.offset),
	style = {offsetTop: s.offset().top, offsetLeft: s.offset().left, width: s.outerWidth(), height: s.outerHeight()},
	tStyle = {width: o.outerWidth(), height: o.outerHeight()},
	wStyle = {
	width: $(window).outerWidth(),
	height: $(window).outerHeight(),
	scrollTop: $(window).scrollTop(),
	scrollLeft: $(window).scrollLeft(),
	};
	if($.inArray(position, p) == -1 || $.inArray(position, f._lsWpI) !== -1) { f._hide(); return css; } else { f._lsWpI.push(position); }
	switch(arrow[0]) {
	case 'bottom':
	css.top = style.offsetTop + style.height + offset;
	if(css.top >= wStyle.height + wStyle.scrollTop) {
	return f._position('top'+'-'+arrow[1]);
	}
	o.addClass('arrow-top');
	break;
	case 'top':
	css.top = style.offsetTop - tStyle.height - offset;
	if(css.top - wStyle.scrollTop <= 0) {
	return f._position('bottom'+'-'+arrow[1]);
	}
	o.addClass('arrow-bottom');
	break;
	case 'left':
	css.top = style.offsetTop + style.height / 2 - tStyle.height / 2;
	css.left =  style.offsetLeft - tStyle.width - offset;
	if(css.left <= 0) {
	return f._position('right');
	}
	o.addClass('arrow-side-right');
	return css;
	break;
	case 'right':
	css.top = style.offsetTop + style.height / 2 - tStyle.height / 2;
	css.left =  style.offsetLeft + style.width + offset;
	if(css.left + tStyle.width > wStyle.width) {
	return f._position('left');
	}
	o.addClass('arrow-side-left');
	return css;
	break;
	}
	switch(arrow[1]) {
	case 'left':
	css.left = style.offsetLeft + style.width / 2 - tStyle.width + n.arrowWidth;
	if(css.left <= 0) {
	return f._position(arrow[0]+'-'+'right');
	}
	o.addClass('arrow-right');
	break;
	case 'center':
	css.left = style.offsetLeft + style.width / 2 - tStyle.width / 2;
	if(css.left + tStyle.width > wStyle.width) {
	return f._position(arrow[0]+'-'+'left');
	}
	if(css.left <= 0) {
	return f._position(arrow[0]+'-'+'right');
	}
	o.addClass('arrow-center');
	break;
	case 'right':
	css.left = style.offsetLeft + style.width / 2 - n.arrowWidth;
	if(css.left + tStyle.width > wStyle.width) {
	return f._position(arrow[0]+'-'+'left');
	}
	o.addClass('arrow-left');
	break;
	}
	return css;
	},
	_createBox: function() {
	o.html(f._getTitle()).appendTo('body');
	if((n.cls != null && typeof(n.cls) == "string") || f.hasAttr(n.attr+'-cls')) {
	o.addClass((f.hasAttr(n.attr+'-cls') ? s.attr(n.attr+'-cls') : n.cls));
	}
	o.css(f._position());
	f._showIn();
	},
	_lsWtI: [],
	_lsWpI: []
	}
	f.init();
	return this;
	});
	}
})(jQuery);
// COOKIES.     
jQuery.cookie = function(name, value, options) {
	if(typeof value != 'undefined') {
	options = options || {};
	if(value === null) {
	value = '';
	options.expires = -1;
	}
	var expires = '';
	if(options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
	var date;
	if(typeof options.expires == 'number') {
	date = new Date();
	date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
	} else {
	date = options.expires;
	}
	expires = '; expires=' + date.toUTCString();
	}
	//
	var path = options.path ? '; path='+(options.path) : '';
	var domain = options.domain ? '; domain='+(options.domain) : '';
	var secure = options.secure ? '; secure' : '';
	document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	//
	} else {
	var cookieValue = null;
	if(document.cookie && document.cookie != '') {
	var cookies = document.cookie.split(';');
	for(var i = 0; i < cookies.length; i++) {
	var cookie = jQuery.trim(cookies[i]);
	if(cookie.substring(0, name.length + 1) == (name + '=')) {
	cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
	break;
	}
	//
	}
	//
	}
	return cookieValue;
	//
	}
};
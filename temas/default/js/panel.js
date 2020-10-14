// JavaScript Document
$(document).ready(function() {
	// SELECCIONAR DOMINIO|SUB-DOMINIO.
	$(document).on('click', '#add-account #b-ds', function() {
	var d = {'type': $('form').attr('get-ext')}
	$('#add-account')[0].reset();
	$('#add-account #domain').focus();
	switch(d.type) {
	case '1'://sub-dominio.
	$('form').attr('get-ext', '2');
	$('form #ext[name="ext_s"]').hide();
	$('form #ext[name="ext_d"]').show();
	$(this).attr('title', 'I better use sub-domain').html('<i class="einet icon-globe"></i> I have sub-domain');
	break;
	case '2'://dominio.
	$('form').attr('get-ext', '1');
	$('form #ext[name="ext_d"]').hide();
	$('form #ext[name="ext_s"]').show();
	$(this).attr('title', 'Use my domain').html('<i class="einet icon-globe"></i> I have a domain');
	break;
	}
	//
	});
	// VERIFICACION DOMINIO|SUB-DOMINIO.
	$(document).on('click', '#add-account #b-ch', function() {
	var ext = ($('form').attr('get-ext') == 1) ? 'ext_s' : 'ext_d';
	var d = {'domain': $('form #domain'), 'ext': $('form #ext[name='+ext+']')}
	if(d.domain.val().length < 4) {
	$('form input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
	global.error('The domain name must be at least 4 characters.', 0);
	d.domain.focus();
	} else if(d.ext.val().length < 1) {
	$('form input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
	global.error('You must add a valid extension for your domain.', 0);
	d.ext.focus();
	} else {
	$('#add-account').animate({opacity: 0.5});
	var letsgo = 'domain='+encodeURIComponent(d.domain.val())+'&ext='+encodeURIComponent(d.ext.val())+'&type='+encodeURIComponent(ext);
	global.ajax('post', 'panel-get_domain', letsgo, function(d) {
	switch(d.charAt(0)) {
	case '0':
	$('form input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
	global.error(d.substring(3), 0);
	break;
	case '1':
	$('form input[type="submit"]').removeAttr('disabled').removeClass('disabled');
	global.error(d.substring(3), 1);
	break;
	}
	$('#add-account').animate({opacity: 1});
	});
	}
	//
	});
	// CREAR HOSTING.
	$(document).on('click', '#add-account input[type="submit"]', function(e) {
	var ext = ($('form').attr('get-ext') == 1) ? 'ext_s' : 'ext_d';
	var d = {
	'domain': $('form #domain'),
	'ext': $('form #ext[name='+ext+']'),
	'captcha_active': $('form #s-captcha').attr('hi'),
	're_captcha': $('textarea[name="g-recaptcha-response"]'),
	'plan': $('form #plan'),
	}
	if(d.domain.val().length < 4) {
	global.error('The domain name must be at least 4 characters.', 0);
	d.domain.focus();
	} else if(d.ext.val().length < 1) {
	global.error('You must add a valid extension for your domain.', 0);
	d.ext.focus();
	} else if(d.captcha_active == 0 && !d.re_captcha.val()) {
	global.error('Complete the captcha, show us you\'re not a bot.', 0);
	} else if(!d.plan.val()) {
	global.error('You must select a hosting plan for your account.', 0);
	} else {
	$('#add-account').animate({opacity: 0.5});
	var letsgo = 'domain='+encodeURIComponent(d.domain.val())+'&ext='+encodeURIComponent(d.ext.val())+'&captcha='+encodeURIComponent(d.re_captcha.val())+'&type='+encodeURIComponent(ext)+'&plan='+encodeURIComponent(d.plan.val());
	global.ajax('post', 'panel-create_account', letsgo, function(n) {
	switch(n.charAt(0)) {
	case '0':
	$('form input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
	global.error(n.substring(3), 0);
	$('#add-account')[0].reset();
	($('#s-captcha').attr('hi') == 0) ? grecaptcha.reset() : '';
	break;
	case '1':
	$('form input[type="submit"]').removeAttr('disabled').removeClass('disabled');
	global.error(n.substring(3), 1);
	$('#add-account')[0].reset();
	($('#s-captcha').attr('hi') == 0) ? grecaptcha.reset() : '';
	$('.gotop').click();
	break;
	}
	//
	$('#add-account').animate({opacity: 1});
	});
	//
	}
	e.preventDefault();
	});
	// SI SE AGREGO BIEN LA CAPTCHA.
	$('#add-account .g-recaptcha').on('mouseenter', function() {
	if($('textarea[name="g-recaptcha-response"]').val().length > 1) {
	$('form input[type="submit"]').removeAttr('disabled').removeClass('disabled');
	}
	});
	// HOSTING TOOLS.
	$(document).on('click', '#account .i-right a', function() {
	var d = {'cp_id': parseInt($('#account').attr('cid')), 'cp_action': $(this).attr('hi')};
	switch(d.cp_action) {
	case '2':// Activar
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Re-activate hosting account');
	modal.body('<center>¿You really want to re-activate this account?</center>', 400);
	modal.buttons('Accept', 'add.hosting_tools(\''+d.cp_id+'\', \''+d.cp_action+'\')', 'Cancel', 'close');
	break;
	case '3':// Desactivar
	modal.show(true);
	modal.load_inicio();
	modal.load_fin();
	modal.title('Delete hosting account');
	modal.body('<center>¿You really want to delete this account?</center>', 400);
	modal.buttons('Accept', 'add.hosting_tools(\''+d.cp_id+'\', \''+d.cp_action+'\')', 'Cancel', 'close');
	break;	
	}
	//
	});
});
// GLOBAL PARA PANEL.
var add = {
	hosting_tools: function(id, action) {
	if(id > 0 && action > 0) {
	global.ajax('post', 'panel-tools_account', 'id='+encodeURIComponent(id)+'&type='+encodeURIComponent(action), function(h) {
	switch(h.charAt(0)) {
	case '0':
	modal.alert('A problem has occurred!!', h.substring(3), false);
	break;
	case '1':
	modal.alert('Perfect! Congratulations.', h.substring(3), false);
	setTimeout("location.reload();", 10000);
	break;
	}
	});
	//
	}
	//
	}
};
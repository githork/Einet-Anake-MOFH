// JavaScript Document
$(document).ready(function() {
	if(global_data.captcha[0] == '0' && global_data.captcha[1] == 'google') {
	$.getScript('https://www.google.com/recaptcha/api.js?hl=es');
	} else if(global_data.captcha[0] == '0' && global_data.captcha[1] == 'hcaptcha') {
	$.getScript('https://hcaptcha.com/1/api.js?hl=es');
	}
	var d = {cont: false, datos: new Array()}
	$(document).on('click, blur', '#register input[name="username"]', function() {
	var nick = $(this);
	if(nick.val().length < 5 || nick.val().length > 20) {
	nick.addClass('is-danger');
	global.error('Sorry, the nick must be between 5 and 20 characters.', 0);
	} else if(!/[^0-9]/.test(nick.val())) {
	nick.addClass('is-danger');
	global.error('The nick cannot contain only numeric characters.', 0);
	} else if(/[^a-zA-Z0-9_.]/.test(nick.val())) {
	nick.addClass('is-danger');
	global.error('The nick allows only letters, numbers, (_ ) and dots.', 0);
	} else {
	nick.addClass('is-danger');
	global.ajax('post', 'user-registro_nick', 'nick='+encodeURIComponent(nick.val()), function(r) {
	switch(r.charAt(0)) {
	case '0':
	nick.addClass('is-danger');
	global.error(r.substring(3), 0);
	//d.cont = false;
	break;
	case '1':
	nick.removeClass('is-danger');
	d.datos.nick = nick.val();
	//d.cont = true;
	break;
	}
	//
	});
	//
	}
	});
	$(document).on('click, blur', '#register input[name="nombre"]', function() {
	var nombre = $(this);
	if(nombre.val().length < 4) {
	nombre.addClass('is-danger');
	global.error('Remember to write a first and last name, it is necessary.', 0);
	} else if(nombre.val().length > 35) {
	nombre.addClass('is-danger');
	global.error('The name and surname must not exceed 35 characters.', 0);
	} else {
	nombre.removeClass('is-danger');
	d.datos.nombre = nombre.val();
	}
	});
	$(document).on('click, blur', '#register input[name="email"]', function() {
	var email = $(this);
	if(!/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/.exec(email.val())) {
	email.addClass('is-danger');
	global.error('Please write a valid email.', 0);
	} else if(email.val().length > 40) {
	email.addClass('is-danger');
	global.error('Email exceeds 40 characters.', 0);
	} else {
	email.addClass('is-danger');
	global.ajax('post', 'user-registro_email', 'email='+encodeURIComponent(email.val()), function(r) {
	switch(r.charAt(0)) {
	case '0':
	email.addClass('is-danger');
	global.error(r.substring(3), 0);
	break;
	case '1':
	email.removeClass('is-danger');
	d.datos.email = email.val();
	break;
	}
	//
	});
	//
	}
	});
	$(document).on('click, blur', '#register input[name="pass"]', function() {
	var pass = $(this);
	if($.inArray(pass.val().toLowerCase(), add.pass_block) != -1) {
	pass.addClass('is-danger');
	global.error('Sorry, you\'d better write down a password that\'s safe.', 0);
	} else if(pass.val().toLowerCase() == d.datos.nick.toLowerCase()) {
	pass.addClass('is-danger');
	global.error('Password must be different from username.', 0);
	} else if(pass.val().length < 5 || pass.val().length > 40) {
	pass.addClass('is-danger');
	global.error('The password must be between 5 and 40 characters.', 0);
	} else {
	pass.removeClass('is-danger');
	d.datos.pass = pass.val();
	}
	});
	$(document).on('click, blur', '#register input[name="re_pass"]', function() {
	var re_pass = $(this);
	if(re_pass.val().length < 5) {
	re_pass.addClass('is-danger');
	global.error('Please re-enter your password.', 0);
	} else if(d.datos.pass.toLowerCase() !== re_pass.val().toLowerCase()) {
	re_pass.addClass('is-danger');
	global.error('Check the 2 passwords must be identical.', 0);
	} else {
	re_pass.removeClass('is-danger');
	d.datos.re_pass = re_pass.val();
	}	
	});
	$(document).on('blur, change', '#register select[name="dia"], #register select[name="mes"], #register select[name="year"]', function() {
	var r = {'dia': $('#register select[name="dia"]'), 'mes': $('#register select[name="mes"]'), 'year': $('#register select[name="year"]')};
	if(!r.dia.val()) {
	$('#r-'+r.dia.attr('id')).addClass('is-danger');
	global.error('Remember to select the day of your birth.', 0);
	} else {
	$('#r-'+r.dia.attr('id')).removeClass('is-danger');
	d.datos.dia = r.dia.val();
	}
	if(!r.mes.val()) {
	$('#r-'+r.mes.attr('id')).addClass('is-danger');
	global.error('Remember to select the month of your birth.', 0);
	} else {
	$('#r-'+r.mes.attr('id')).removeClass('is-danger');
	d.datos.mes = r.mes.val();
	}
	if(!r.year.val()) {
	$('#r-'+r.year.attr('id')).addClass('is-danger');
	global.error('Remember to select your year of birth.', 0);
	} else {
	$('#r-'+r.year.attr('id')).removeClass('is-danger');
	d.datos.year = r.year.val();
	}
	});
	$(document).on('blur, change', '#register select[name="sexo"]', function() {
	var sexo = $(this);
	if(!sexo.val()) {
	$('#r-'+sexo.attr('id')).addClass('is-danger');
	global.error('Remember to select your gender.', 0);
	} else {
	$('#r-'+sexo.attr('id')).removeClass('is-danger');
	d.datos.sexo = sexo.val();
	}
	});
	$(document).on('blur, change', '#register select[name="pais"]', function() {
	var pais = $(this);
	if(!pais.val()) {
	$('#r-'+pais.attr('id')).addClass('is-danger');
	global.error('Remember to select your country.', 0);
	} else {
	$('#r-'+pais.attr('id')).removeClass('is-danger');
	d.datos.pais = pais.val();
	}	
	});
	$('#register #s-captcha[hi="0"] .g-recaptcha').on('focusin', function() {
	var captcha = $('textarea[name="g-recaptcha-response"]');
	if(!captcha.val()) {
	$('#register input[name="term"]').attr('checked', false);
	$('#register input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
	global.error('Prove to us that you\'re not a bot.', 0);
	} else {
	d.datos.re_captcha = captcha.val();
	$('#register input[type="submit"]').removeAttr('disabled').removeClass('disabled');
	}
	});
	$(document).on('click, change', '#register input[name="term"]', function() {
	var term = ($(this).is(':checked')) ? 1 : 0;
	if(parseInt(term) == 0) {
	$('#register input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
	global.error('Just one more step! Do you accept the terms and conditions?', 0);
	} else {
	d.datos.terminos = term;
	$('#register input[type="submit"]').removeAttr('disabled').removeClass('disabled');
	}	
	});
	$(document).on('click', '#register input[type="submit"]', function(e) {
	($('#register input[name="plan"]').val()) ? d.datos.plan = $('#register input[name="plan"]').val(): '';
	var get_captcha = ($('#register #s-captcha').attr('hi') == 0) ? !d.datos.re_captcha : ''; 
	var letsgo = '';
	var val = '';
	for(var i in d.datos) {
	letsgo += val+i+'='+encodeURIComponent(d.datos[i]);
	val = '&';
	}
	if(!d.datos.nick || !d.datos.nombre || !d.datos.email || !d.datos.pass || !d.datos.re_pass || !d.datos.dia || !d.datos.mes || !d.datos.year && !d.datos.sexo || !d.datos.pais || get_captcha || !d.datos.terminos) {
	global.error('Please check that all fields are in order.', 0);
	$('#register input[name="term"]').attr('checked', false);
	$('#register input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
	} else {
	$('.register-box').animate({opacity: 0.5});
	global.ajax('post', 'user-registro_nuevo', letsgo, function(s) {
	switch(s.charAt(0)) {
	case '0':
	global.error(s.substring(3), 0);
	($('#s-captcha').attr('hi') == 0) ? grecaptcha.reset() : '';
	break;
	case '1':
	$('#register input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
	global.error(s.substring(3), 1);
	$('#register')[0].reset();
	$('.gotop').click();
	($('#s-captcha').attr('hi') == 0) ? grecaptcha.reset() : '';
	break;
	}
	//
	$('.register-box').animate({opacity: 1});
	});
	//
	}
	//
	e.preventDefault();
	});
});
// GLOBAL PARA REGISTRO.
var add = {
	pass_block: ['11111', '22222', '33333', '44444', '55555', '12345', '54321', '123456', '654321', 'yo', 'admin', 'user', 'abc123', 'abcdef', '666666','696969','action','master','zzzzzz','xxxxxx', 'xxxxxxxx'],
};
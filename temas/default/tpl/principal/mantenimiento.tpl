{include file='principal/header.tpl'}
{if $tsConfig.web_over != '0'}
<script>
{literal}
var countDownDate = new Date(({/literal}{$tsConfig.web_over}{literal} * 1000)).getTime();
var x = setInterval(function() {
var now = new Date().getTime();

var distance = countDownDate - now;
var data = {
	'dias': Math.floor(distance / (1000 * 60 * 60 * 24)),
	'horas': Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
	'minutos': Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
	'segs': Math.floor((distance % (1000 * 60)) / 1000),
}
var html = '<li><b>Días</b><p>'+data.dias+'</p></li>';
	html += '<li><b>Horas</b><p>'+data.horas+'</p></li>';
	html += '<li><b>Minutos</b><p>'+data.minutos+'</p></li>';
	html += '<li><b>Segundos</b><p>'+data.segs+'</p></li>';
	$('#get-time ul').html(html);

	if(distance < 0) {
    clearInterval(x);
	$('#get-time ul').html('<b>Maintenance completed.</b>');
	setTimeout("location.href = window.location;", 15000);
	}
	}, 1000);
{/literal}
</script>
{/if}
<section class="content panel">
<div id="aviso-box">
<h1>{$tsConfig.titulo} in maintenance mode.</h1>
<div class="img"></div> 
<p>Our servers are currently under maintenance.</p>
<h2>{$tsConfig.web_mensaje}</h2>
{if $tsConfig.web_over != '0'}
<section id="get-time">
<strong>root@sh-3.5:~$ Completion of maintenance <i>_</i></strong>
<ul></ul>
<div class="clear"></div>
</section>
{/if}
</div>
</section>
<div class="clear"></div>
{include file='principal/footer.tpl'}
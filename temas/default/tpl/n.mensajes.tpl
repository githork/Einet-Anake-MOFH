{include file='principal/header.tpl'}
{literal}
<script>
$(document).ready(function() {
if(global_data.action == 'leer') {
$('#resp-prew').animate({ scrollTop: $('#resp-prew')[0].scrollHeight}, 0);
$(document).on('keyup', '#respuesta-form textarea[name="mensaje"], #respuesta-form .wysibb-body', function(e) {
if((e.keyCode ? e.keyCode : e.which) == 13) $('#respuesta-form button[type="submit"]').click();
//
});
//
}
//
});
</script>
{/literal}
<article id="module-left" class="column is-9">
{if $tsAction != 'leer' && $tsAction != 'nuevo'}
<span class="title-box"><span>Messages {if $tsAction == 'enviados'}sent{elseif $tsAction == 'respondidos'}answered{elseif $tsAction == 'avisos'}& avisos{else}received{/if}</span><i class="einet icon-message-square"></i></span>
<span class="cont-box">
<span class="msg-list">
<i class="einet {if $tsAction == 'avisos'}icon-alert-triangle{else}icon-mail{/if}"></i> <font>The entire list of {if $tsAction == 'avisos'}notices{else}messages{/if} are shown in order.</font>
{if $tsAction == ''}
<span class="prew-items" tl="1" title="Show {if $tsMensajes.page.met == ''}unread messages.{else}all messages.{/if}">
<strong>Show <i class="einet icon-chevron-right"></i></strong>
<a onclick="document.getElementById('met').submit();">{if $tsMensajes.page.met == ''}Unread{else}All{/if}</a>
<form action="{$tsConfig.url}/mensajes/" method="post" id="met">
<input type="hidden" name="met" value="{if $tsMensajes.page.met == ''}1{else}{/if}"/>
</form>
</span>
<div class="clear"></div>
{/if}
</span>
{/if}

{if $tsAction == '' || $tsAction == 'enviados' || $tsAction == 'respondidos' || $tsAction == 'search'}
{include file='modulos/c.mensajes_lista.tpl'}
{elseif $tsAction == 'leer'}
{include file='modulos/c.mensajes_leer.tpl'}
{elseif $tsAction == 'nuevo'}
<span class="title-box"><span>New message</span><i class="einet icon-message-square"></i></span>
<span id="msg-send" class="cont-box">
<form method="POST" id="message-form">
<input type="text" class="input" name="usuario" maxlength="20" placeholder="username" value="{$getName}">
<input type="text" class="input" name="titulo" maxlength="50" placeholder="subject" value="{$getTitle}">
<div id="send-form"><textarea name="mensaje" class="input" tabindex="1"></textarea></div>
<button type="submit" id="btn-msg" class="button is-success"><i class="einet icon-send"></i> Send message</button>
<div class="clear"></div>
</form>
</span>
{/if}
</article>
<aside id="module-right" class="column is-3">
{include file='modulos/c.mensajes_right.tpl'}
</aside>

<div class="clear"></div>
{include file='principal/footer.tpl'}
{include file='principal/header.tpl'}
<section class="content panel">
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i>
{if $tsAviso.codigo}
<h1>{$tsAviso.codigo}</h1>
{/if}
<p>{$tsAviso.titulo}</p>
<h2>{$tsAviso.mensaje}</h2>
{if $tsConfig.pub_active == 0 && $tsConfig.pub_728 !=''}
<div align="center" style="margin:3px auto;">{$tsConfig.pub_728}</div>
{/if}
{if $tsAviso.but}
<a href="{if $tsAviso.link}{$tsAviso.link}{else}{$tsConfig.url}{/if}" class="button is-primary" title="{$tsAviso.title}">{$tsAviso.but}</a>
{/if}
{if $tsAviso.return} 
<a href="{$tsConfig.url}" class="button is-success" title="Return to the main page">Return to the main page</a>
{/if}
</div>
</section>

<div class="clear"></div>
{include file='principal/footer.tpl'}
{include file='principal/header.tpl'}
<script src="{$tsConfig.js}/admin.js"></script>
<article id="module-left" class="column is-9">
{if $tsAction == ''}
{include file='admin/c.admin_welcome.tpl'}
{elseif $tsAction == 'creditos'}
{include file='admin/c.admin_creditos.tpl'}
{elseif $tsAction == 'config'}
{include file='admin/c.admin_config.tpl'}
{elseif $tsAction == 'temas'}
{include file='admin/c.admin_temas.tpl'}
{elseif $tsAction == 'noticias'}
{include file='admin/c.admin_noticias.tpl'}
{elseif $tsAction == 'pub'}
{include file='admin/c.admin_publicidad.tpl'}
{elseif $tsAction == 'api'}
{include file='admin/c.admin_api.tpl'}
{elseif $tsAction == 'others'}
{include file='admin/c.admin_others.tpl'}
{elseif $tsAction == 'reseller_config'}
{include file='admin/c.admin_reseller_config.tpl'}
{elseif $tsAction == 'reseller_others'}
{include file='admin/c.admin_reseller_others.tpl'}
{elseif $tsAction == 'stats'}
{include file='admin/c.admin_estadisticas.tpl'}
{elseif $tsAction == 'blacklist'}
{include file='admin/c.admin_blacklist.tpl'}
{elseif $tsAction == 'badwords'}
{include file='admin/c.admin_badwords.tpl'}
{elseif $tsAction == 'ipinfo'}
{include file='admin/c.admin_ipinfo.tpl'}
{/if}
</article>
<aside id="module-right" class="column is-3">
{include file='admin/c.admin_right.tpl'}
</aside>

<div class="clear"></div>
{include file='principal/footer.tpl'}
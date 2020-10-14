{include file='principal/header.tpl'}
<script src="{$tsConfig.js}/panel.js"></script>
<div id="module-left" class="column is-9 panel">
{if $tsAction == 'create'}
{include file='panel/c.panel_create.tpl'}
{elseif $tsAction == 're-direct'}
{include file='panel/c.panel_re-direct.tpl'}
{elseif $tsAction == 'view'}
{include file='panel/c.panel_view.tpl'}
{elseif $tsAction == 'buscador'}
{include file='panel/c.panel_cuentas.tpl'}
{else}
{include file='panel/c.panel_cuentas.tpl'}
{/if}
</div>

<div id="module-right" class="column is-3">
{include file='modulos/c.panel_right.tpl'}
</div>

<div class="clear"></div>
{include file='principal/footer.tpl'}
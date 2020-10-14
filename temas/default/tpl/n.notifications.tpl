{include file='principal/header.tpl'}
<article id="module-left" class="column is-9">
<span class="title-box"><span>Latest ({$tsNotifications.total}) notifications</span><i class="einet icon-globe"></i> 
<a href="javascript:ntf.mark('mon');" class="mark-r" tl="1" title="Mark notifications as read">Mark as read</a></span>
<span class="cont-box">
{if $tsNotifications.data}
<ul class="items-all">
{foreach from=$tsNotifications.data item=n}
<li id="ntf-item" {if $n.unread == '1'} class="no-load"{/if} nid="{$n.nid}">
<a href="{$tsConfig.url}/perfil/{$n.user}" target="_blank" class="avatar" tl="1" title="More details about - {$n.user}"><img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.url}/files/perfiles/{$n.avatar}" class="load"/></a>
<span id="ntf-right">
<i class="einet icon-more-vertical" tl="1" title="Options"></i>
<ul id="mini-tools">
{if $n.unread == '0'}
<li class="column is-6" title="Mark as unread" nid="{$n.nid}" hi="load" rp="1">
<i class="einet icon-check-circle"></i><p>Not read</p>
</li>
{else}
<li class="column is-6" title="Mark as read" nid="{$n.nid}" hi="load" rp="0">
<i class="einet icon-circle"></i><p>Read</p>
</li>
{/if}
<li class="column is-6" title="Delete notification" nid="{$n.nid}" hi="del">
<i class="einet icon-trash-2"></i><p>Delete</p>
</ul>
</span>
<div class="item-right">
<span class="n-title">
{if $n.total == '1'}<a href="{$tsConfig.url}/perfil/{$n.user}" target="_blank" tl="1" title="More details about - {$n.user}">@{$n.user}</a>{/if} <span class="n-date" tl="1" title="{$n.date|fecha}">{$n.date|hace}</span>
</span>
<span class="n-preview">
<span class="monac_icons ma_{$n.style}"></span> {$n.text} 
<a href="{$n.link}" {if $n.getdata}{$n.getdata}{/if} tl="1" title="{$n.ltext}">{$n.ltext}</a></span>
</div>
</li>
{/foreach}
</ul>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>Perfect! Congratulations.</p>
<h2>There are no notifications for you</h2>
</div>
<!-- nice -->
{/if}
</span>
</article>

<aside id="module-right" class="column is-3">
{include file='modulos/c.notifications_right.tpl'}
</aside>

<div class="clear"></div>
{include file='principal/footer.tpl'}
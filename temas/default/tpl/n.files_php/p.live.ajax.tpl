{if $tsNotifications.data}
{foreach from=$tsNotifications.data item=n}
<li{if $n.unread > 0} class="no-load"{/if}>
<a href="{if $n.link}{$n.link}{else}#{/if}">
{if $n.style == 'medal'}
<span class="monac_icons ma_{$n.style}"></span>
{else}	
<img src="{$tsConfig.url}/files/perfiles/{$n.avatar}?{$smarty.now}"/>		
{/if}
<div class="content">
<span id="icon" class="monac_icons ma_{$n.style}"></span>
<h5>{if $n.user}<b>{$n.user}</b>{/if} <p>{$n.text}</p> {if $n.ltext}<b>{$n.ltext}</b>{/if}</h5>
</div>
<div class="clear"></div>
</a>
</li>
{/foreach}
{else}
<div class="no-msg">No notifications for you</div>
{/if}
{if $tsMensajes.data}
{foreach from=$tsMensajes.data item=m}
<li {if $m.mp_read_to == 0 || $m.mp_read_mon_to == 0}class="no-load"{/if}>
<a href="{$tsConfig.url}/mensajes/leer/{$m.mp_id}" title="{$m.mp_subject}">
<img src="{$tsConfig.url}/files/perfiles/{$m.mp_from}_50.jpg"/>
<div class="content">
<span id="status" class="{$m.status}" title="User {$m.status}"></span>
<h5>{$m.user_nick|truncate:35:"..":true}</h5>
<p>{$m.mp_subject|truncate:35:"..":true}</p>
</div>
<strong class="date">{$m.mp_date|hace}</strong>
<div class="clear"></div>
</a>
</li>
{/foreach}
{else}
<div class="no-msg">No messages for you</div>
{/if}
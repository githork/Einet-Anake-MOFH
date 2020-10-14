{if $tsBirthday.data}
<span class="title-box"><span>Birthday guys ({$tsBirthday.total})</span><i class="einet icon-gift"></i></span>
<nav class="cont-box">
{foreach from=$tsBirthday.data item=b}
{if $tsUser->uid != $b.user_id}
<a href="{$tsConfig.url}/mensajes/nuevo/?user={$b.user_nick}" class="box-users" tl="1" title="¡Wish him well in his day!">
<figure class="image is-36x36">
<img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.url}/files/perfiles/{$b.user_id}_50.jpg" class="is-rounded load">
<i id="status" class="{$b.status}" title="User {$b.status}"></i>
</figure>
<span class="user-info">
<font>{if $b.user_name}{$b.user_name|truncate:32:"...":true}{else}{$b.user_nick|truncate:32:"...":true}{/if}</font>
<span class="detalles"><i class="einet icon-gift"></i> Age {$b.edad}</span>
</span>
<div class="clear"></div>
</a>
{/if}
{/foreach}
</nav>
{/if}
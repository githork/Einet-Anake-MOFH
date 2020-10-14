{if $tsMensajes}
<article id="resp-body" class="right">
<img src="{$tsConfig.url}/files/perfiles/{$tsUser->uid}_50.jpg" class="avatar"/>
<span class="resp-right">
<span class="resp-user">
<a href="{$tsConfig.url}/perfil/{$tsUser->nick}" class="user" title="More details about - {$tsUser->nick}">{$tsUser->nick}</a> 
{if $tsUser->is_admod}<a href="{$tsConfig.url}/admin/ipinfo/{$tsMensajes.mp_ip}" target="_blank" class="ip" title="More details about the IP">({$tsMensajes.mp_ip})</a>{/if}
</span>
<span class="resp-message">{$tsMensajes.mp_body}</span>
<span class="resp-date">{$tsMensajes.mp_date|fecha}</span>
</span>
<div class="clear"></div>
</article>
<hr/>
{/if}
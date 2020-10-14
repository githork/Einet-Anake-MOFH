<span class="title-box"><span>{$tsMensajes.mp_subject|truncate:37:"...":true}</span><i class="einet icon-message-square"></i></span>
<span id="msg-preview" class="cont-box">
<span class="msg-users">
<i class="einet icon-check-circle"></i> Conversation between <a href="{$tsConfig.url}/perfil/{$tsUser->nick}" tl="1" title="Your profile">You</a> <img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.url}/files/perfiles/{$tsUser->uid}_50.jpg" class="load"/> and <img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.url}/files/perfiles/{$tsMensajes.ext.uid}_50.jpg" class="load"/> <a href="{$tsConfig.url}/perfil/{$tsMensajes.ext.user}" tl="1" title="Profile of {$tsMensajes.ext.user}">{$tsMensajes.ext.user}</a>
</span>
{if $tsMensajes.resp}
<!-- nice -->
<section id="resp-prew">
{foreach from=$tsMensajes.resp item=m}
<article id="resp-body" {if $tsUser->uid == $m.mr_from}class="right"{/if}>
<img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.url}/files/perfiles/{$m.mr_from}_50.jpg" class="avatar load"/>
<span class="resp-right">
<span class="resp-user">
<a href="{$tsConfig.url}/perfil/{$m.user_nick}" class="user" tl="1" title="More details about - {$m.user_nick}">{$m.user_nick}</a> 
{if $tsUser->is_admod}<a href="{$tsConfig.url}/admin/ipinfo/{$m.mr_ip}" target="_blank" class="ip" tl="1" title="More details about the IP">({$m.mr_ip})</a>{/if}
</span>
<span class="resp-message">{$m.mr_body}</span>
<span class="resp-date">{$m.mr_date|fecha}</span>
</span>
<div class="clear"></div>
</article>
<hr/>
{/foreach}
</section>
<!-- nice -->
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>Eh!!... Something's not right.</p>
<h2>I can't load the messages for this conversation...</h2>
</div>
<!-- nice -->
{/if}
{if $tsUser->is_admod || ($tsMensajes.mp_del_to == 0 && $tsMensajes.mp_del_from == 0 && $tsMensajes.ext.can_read == 1)}
<section id="respuesta-form">
<input type="hidden" name="mp_id" value="{$tsMensajes.mp_id}"/>
<div id="send-form">
<textarea name="mensaje" class="input" tabindex="1" title="Write a reply..." placeholder="Write a reply..."></textarea>
</div>
<button type="submit" id="btn-msg" class="button is-success"><i class="einet icon-send"></i> Answer</button>
<div class="clear"></div>
</section>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>Eh!!... something's not right..</p>
<h2>The participant left the conversation or you are no longer allowed to respond...</h2>
</div>
<!-- nice -->
{/if}
</span>
{include file='principal/header.tpl'}
<div id="module-left" class="column is-9">
<span class="title-box"><span>Users</span> <i class="einet icon-users"></i></span>
<span class="cont-box">
{if $tsUsers.data}
<span id="result-users">
<i class="einet icon-chevron-right"></i> Showing results for users <b>{$tsUsers.page.actual}</b> of <b>{$tsUsers.page.todas}</b>
</span>
{if $tsUsers.page.paginas > 1}
<nav class="pagination is-centered" role="navigation" aria-label="pagination">
{if $tsUsers.page.ant > 0}
<a href="{$tsConfig.url}/usuarios/pagina/{$tsUsers.page.ant}/{if $tsFilter.online == '1'}&online=1{/if}{if $tsFilter.imagen == '1'}&imagen=1{/if}{if $tsFilter.sexo != ''}&sexo={$tsFilter.sexo}{/if}{if $tsFilter.pais != ''}&pais={$tsFilter.pais}{/if}{if $tsFilter.rango > 0}&rango={$tsFilter.rango}{/if}" class="pagination-previous">« Previous</a>
{else}
<span class="pagination-previous" disabled>« Previous</span>
{/if}
{if $tsUsers.page.sig <= $tsUsers.page.paginas && $tsUsers.page.sig > 0}
<a href="{$tsConfig.url}/usuarios/pagina/{$tsUsers.page.sig}/{if $tsFilter.online == '1'}&online=1{/if}{if $tsFilter.imagen == '1'}&imagen=1{/if}{if $tsFilter.sexo != ''}&sexo={$tsFilter.sexo}{/if}{if $tsFilter.pais != ''}&pais={$tsFilter.pais}{/if}{if $tsFilter.rango > 0}&rango={$tsFilter.rango}{/if}" class="pagination-next">Next »</a>
{else}
<span class="pagination-next" disabled>Next »</span>
{/if}
<ul class="pagination-list">
{section name=c start=1 loop=$tsUsers.page.paginas step=1 max=10}
{if $tsUsers.page.actual != $smarty.section.c.index}
<li>
<a href="{$tsConfig.url}/usuarios/pagina/{$smarty.section.c.index}/{if $tsFilter.online == '1'}&online=1{/if}{if $tsFilter.imagen == '1'}&imagen=1{/if}{if $tsFilter.sexo != ''}&sexo={$tsFilter.sexo}{/if}{if $tsFilter.pais != ''}&pais={$tsFilter.pais}{/if}{if $tsFilter.rango > 0}&rango={$tsFilter.rango}{/if}" class="pagination-link">{$smarty.section.c.index}</a>
</li>
{else}
<a href="#" class="pagination-link is-current">{$tsUsers.page.actual}</a></li>
{/if}
{/section}
</ul>
</nav>
{/if}
<section id="users-all">
{if $tsConfig.pub_active == 0 && $tsConfig.pub_468 !=''}
<div align="center" style="margin:3px auto;">{$tsConfig.pub_468}</div>
{/if}
{foreach from=$tsUsers.data item=u}
<div id="card-user">
<h4><i id="rank" class="m-{$u.r_imagen}" tl="1" title="Range - {$u.r_nombre}"></i>
<a href="{$tsConfig.url}/perfil/{$u.user_nick}" class="card-nick" style="color:#{$u.r_color};" tl="1" title="More details about - {if $u.user_name}{$u.user_name}{else}{$u.user_nick}{/if}">{if $u.user_name}{$u.user_name}{else}{$u.user_nick}{/if}</a>
</h4>
<section class="card-datos">
<div id="t1-user" class="card-tools">
<a href="{$tsConfig.url}/perfil/{$u.user_nick}" tl="1" title="More details about - {if $u.user_name}{$u.user_name}{else}{$u.user_nick}{/if}"><img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.url}/files/perfiles/{$u.user_id}_120.jpg" class="avatar load"/></a>
{if $tsUser->is_member == '1' && $tsUser->uid != $u.user_id}
{if $u.user_follow}
<span id="t1-follow" class="button is-danger" act="2" pid="{$u.user_id}" tl="1" title="Delete my friends"><i class="einet icon-user-x"></i></span>
{else}
<span id="t1-follow" class="button is-success" act="2" pid="{$u.user_id}" tl="1" title="Add my friends"><i class="einet icon-user-plus"></i></span>
{/if}
<span id="t1-mesage" class="button is-primary" act="1" pid="{$u.user_nick}" tl="1" title="Send private message"><i class="einet icon-mail"></i></span>
{/if}
</div>
<div class="card-info">
<ul>
<li>
<font tl="1" title="Gender - {$u.sexo}">Gender:<b>{$u.sexo}</b></font> 
<font tl="1" title="Country - {$u.pais}">Country:<b>{$u.pais}</b></font>
</li>
<li>Status:<b>{$u.status}</b><i id="status" class="{$u.status}" tl="1" title="User {$u.status}"></i></li>
<li>PIN: <a href="{$tsConfig.url}/perfil/{$u.user_pin}" tl="1" title="User pin - {$u.user_pin}">{$u.user_pin}</a></li>
{if $u.p_mensaje}
<li class="msg">
<font tl="1" title="Personal message: {$u.p_mensaje}"><i class="einet icon-message-circle"></i> {$u.p_mensaje}</font>
</li>
{/if}
</ul>
</div>
<div class="clear"></div>
</section>
</div>
{/foreach}
<div class="clear"></div>
<!-- nice -->
</section>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>Oops!!... Nothing here.</p>
<h2>No users were found with the selected filters..</h2>
</div>
<!-- nice -->
{/if}
</span>
</div>

<div id="module-right" class="column is-3">
{include file='modulos/c.usuarios_right.tpl'}
</div>

<div class="clear"></div>
{include file='principal/footer.tpl'}
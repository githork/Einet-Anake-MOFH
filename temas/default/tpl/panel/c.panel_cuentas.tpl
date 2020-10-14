<div id="panel-user">
<div id="panel-monitor">
<section id="p-etiq" class="notification is-primary">
<div class="p-items">
<i class="einet icon-hard-drive"></i>
<span class="p-resp" tl="1" title="Hosting accounts ({$tsCuentas.page.todas|number_format})"><p>{$tsCuentas.page.todas|number_format}</p><font>Hosting</font></span>
<div class="clear"></div>
</div>
<a href="{$tsConfig.url}/panel/?action=create" class="p-dets" tl="1" title="Create a hosting account"><span>Create hosting</span><i class="einet icon-plus-square"></i></a>
</section>
<section id="p-etiq" class="notification is-purple">
<div class="p-items">
<i class="einet icon-feather"></i>
<span class="p-resp" tl="1" title="Tickets created ({$tsCuentas.page.tickets|number_format})"><p>{$tsCuentas.page.tickets|number_format}</p><font>Tickets</font></span>
<div class="clear"></div>
</div>
<a href="{$tsConfig.url}/mensajes/nuevo/?view=1" class="p-dets" tl="1" title="Create a ticket"><span>Create ticket</span><i class="einet icon-plus-square"></i></a>
</section>
<section id="p-etiq" class="notification is-success">
<div class="p-items">
<i class="einet icon-pie-chart"></i>
<span class="p-resp" tl="1" title="Servers (0)"><p>0</p><font>Servers</font></span>
<div class="clear"></div>
</div>
<a href="#" target="_blank" class="p-dets" tl="1" title="View server status"><span>View status</span><i class="einet icon-external-link"></i></a>
</section>
<div class="clear"></div>
</div>
<span class="title-box"><span>Hosting accounts</span><i class="einet icon-server"></i></span>
<section id="all-accounts" class="cont-box">
{if $tsCuentas.data}
<!-- o -->
{if $tsCuentas.page.paginas >1}
<nav class="pagination is-centered" role="navigation" aria-label="pagination">
{if $tsCuentas.page.ant >0}
<a href="{$tsConfig.url}/panel/pagina/{$tsCuentas.page.ant}" class="pagination-previous">« Previous</a>
{else}
<span class="pagination-previous" disabled>« Previous</span>
{/if}
{if $tsCuentas.page.sig <= $tsCuentas.page.paginas && $tsCuentas.page.sig > 0}
<a href="{$tsConfig.url}/panel/pagina/{$tsCuentas.page.sig}" class="pagination-next">Next »</a>
{else}
<span class="pagination-next" disabled>Next »</span>
{/if}
<ul class="pagination-list">
{section name=c start=1 loop=$tsCuentas.page.paginas step=1 max=10}
{if $tsCuentas.page.actual != $smarty.section.c.index}
<li>
<a href="{$tsConfig.url}/panel/pagina/{$smarty.section.c.index}" class="pagination-link">{$smarty.section.c.index}</a>
</li>
{else}
<li>
<a href="#" class="pagination-link is-current">{$tsCuentas.page.actual}</a>
</li>
{/if}
{/section}
</ul>
</nav>
{/if}
{foreach from=$tsCuentas.data item=p}
<article class="panel-one" {if $p.cp_active == '1'}style="opacity:0.5;"{/if}>
<i id="stat" class="einet notification {if $p.cp_active == '0'}is-success icon-git-merge{else}is-danger icon-git-pull-request{/if}"></i>
<i id="icon" class="einet icon-package"></i>
<div class="p-right">
<a href="{$tsConfig.url}/panel/?action=view&id={$p.cp_id}" id="togo" class="button is-success"><i class="einet icon-hard-drive"></i> Manage</a>
<span class="p-title">{$p.cp_domain}</span>
<span class="p-date">
<font tl="1" title="Status - {if $p.cp_active == '0'}Active{else}Inactive{/if}">{if $p.cp_active == '0'}Active{else}Inactive{/if}</font> | <font tl="1" title="Created - {$p.cp_date|date_format:'d/m/Y - h:m:s a'}">Creation - {$p.cp_date|date_format:"d/m/Y"}</font></span>
</div>
</article>
{/foreach}
{if $tsCuentas.page.todas >= $tsUser->info.p_limite}
<span id="mod-nada" class="notification is-warning">
<b>{if $tsUser->info.user_name}{$tsUser->info.user_name}{else}{$tsUser->nick}{/if}</b>, you have reached the maximum limit on account creation per user.
</span>
{/if}
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>It's all good...</p>
<h2>You don't have any aggregate accounts yet, why don't you create one.</h2>
</div>
<!-- nice -->
{/if}
</section>
</div>
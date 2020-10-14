<section id="msg-lista">
{if $tsMensajes.data}
{if $tsMensajes.page.paginas >1}
<nav class="pagination is-centered" role="navigation" aria-label="pagination">
{if $tsMensajes.page.ant >0}
<a href="{$tsConfig.url}/mensajes/pagina/{$tsMensajes.page.ant}/{$tsAdicional}" class="pagination-previous">« Previous</a>
{else}
<span class="pagination-previous" disabled>« Previous</span>
{/if}
{if $tsMensajes.page.sig <= $tsMensajes.page.paginas && $tsMensajes.page.sig > 0}
<a href="{$tsConfig.url}/mensajes/pagina/{$tsMensajes.page.sig}/{$tsAdicional}" class="pagination-next">Next »</a>
{else}
<span class="pagination-next" disabled>Next »</span>
{/if}
<ul class="pagination-list">
{section name=c start=1 loop=$tsMensajes.page.paginas step=1 max=10}
{if $tsMensajes.page.actual != $smarty.section.c.index}
<li>
<a href="{$tsConfig.url}/mensajes/pagina/{$smarty.section.c.index}/{$tsAdicional}" class="pagination-link">{$smarty.section.c.index}</a>
</li>
{else}
<li>
<a href="#" class="pagination-link is-current">{$tsMensajes.page.actual}</a>
</li>
{/if}
{/section}
</ul>
</nav>
{/if}
{foreach from=$tsMensajes.data item=m}
<article id="msg-item" {if $m.mp_read_to == '0'}class="no-load"{/if} mid="{$m.mp_id}">
<img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.url}/files/perfiles/{$m.mp_from}_50.jpg" class="avatar load"/>
<span id="msg-right">
<i class="einet icon-more-vertical" tl="1" title="Options"></i>
<ul id="mini-tools">
{if $m.mp_read_to == '1'}
<li class="column is-6" title="Mark as unread" mid="{$m.mp_id}" hi="load" rp="0">
<i class="einet icon-check-circle"></i><p>Not read</p>
</li>
{else}
<li class="column is-6" title="Mark as read" mid="{$m.mp_id}" hi="load" rp="1">
<i class="einet icon-circle"></i><p>Read</p>
</li>
{/if}
<li class="column is-6" title="Delete message" mid="{$m.mp_id}" hi="del">
<i class="einet icon-trash-2"></i><p>Delete</p>
</li>
</ul>
</span>
<a class="item-right" href="{$tsConfig.url}/mensajes/leer/{$m.mp_id}">
<span class="m-title">{$m.user_nick}</span>
<span class="m-subject">{$m.mp_subject}</span>
<span class="m-preview">{if $m.mp_type == '1'}{/if}{$m.mp_preview|limit}</span>
<span class="m-date" tl="1" title="{$m.mp_date|fecha}">{$m.mp_date|hace}</span>
</a>
<div class="clear"></div>
</article>
<!-- nice -->
{/foreach}
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>Perfect! Congratulations.</p>
<h2>There is no message log for this category.</h2>
</div>
<!-- nice -->
{/if}
</section>
</span>
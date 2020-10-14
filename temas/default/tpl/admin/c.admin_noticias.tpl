<div id="panel-user">
<span class="title-box"><span>News</span><i class="einet icon-file-text"></i></span>
<span id="mod-noticias" class="cont-box">
{if $tsNoticias.data}
<!-- o -->
{if $tsNoticias.page.paginas >1}
<nav class="pagination is-centered" role="navigation" aria-label="pagination">
{if $tsNoticias.page.ant >0}
<a href="{$tsConfig.url}/admin/noticias/pagina/{$tsNoticias.page.ant}" class="pagination-previous">« Previous</a>
{else}
<span class="pagination-previous" disabled>« Previous</span>
{/if}
{if $tsNoticias.page.sig <= $tsNoticias.page.paginas && $tsNoticias.page.sig > 0}
<a href="{$tsConfig.url}/admin/noticias/pagina/{$tsNoticias.page.sig}" class="pagination-next">Next »</a>
{else}
<span class="pagination-next" disabled>Next »</span>
{/if}
<ul class="pagination-list">
{section name=c start=1 loop=$tsNoticias.page.paginas step=1 max=10}
{if $tsNoticias.page.actual != $smarty.section.c.index}
<li>
<a href="{$tsConfig.url}/admin/noticias/pagina/{$smarty.section.c.index}" class="pagination-link">{$smarty.section.c.index}</a>
</li>
{else}
<li>
<a href="#" class="pagination-link is-current">{$tsNoticias.page.actual}</a>
</li>
{/if}
{/section}
</ul>
</nav>
{/if}

<section id="all-noticias">
{foreach from=$tsNoticias.data item=n}
<article id="msg-item" nid="{$n.not_id}">
<img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.url}/files/perfiles/{$n.user_id}_50.jpg" class="avatar load"/>
<span id="msg-right">
<i class="einet icon-more-vertical" tl="1" title="Options"></i>
{if $tsUser->is_admod == '1'}
<ul id="mini-tools">
<li class="column is-6" nid="{$n.not_id}" hi="1" title="{if $n.not_active == '1'}Disable{else}Enable{/if} news">
<i class="einet icon-bookmark"></i><p>{if $n.not_active == '1'}Disable{else}Enable{/if}</p>
</li>
<li class="column is-6" nid="{$n.not_id}" hi="2" title="Edit news">
<i class="einet icon-edit"></i><p>Edit</p>
</li>
<li class="column is-12" nid="{$n.not_id}" hi="3" title="Delete news">
<i class="einet icon-trash-2"></i><p>Delete</p>
</li>
</ul>
{/if}
</span>
<div class="item-right">
<a href="{$tsConfig.url}/perfil/{$n.user_nick}" target="_blank" class="m-title" tl="1" title="More details about - {$n.user_nick}">{$n.user_nick}</a>
<span class="m-date" tl="1" title="{$n.not_date|fecha}">{$n.not_date|hace}</span>
<span class="tag {if $n.not_active == '1'}is-success{else}is-warning{/if}" tl="1" title="News - {if $n.not_active == '1'}active{else}inactive{/if}">{if $n.not_active == '1'}active{else}inactive{/if}</span>
<span class="m-preview">{$n.not_body}</span>
</div>
<div class="clear"></div>
</article>
<!-- nice -->
{/foreach}
<!-- nice -->
</section>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>It's all good..</p>
<h2>There's currently no news to show. ¡Come on, add one!</h2>
</div>
<!-- nice -->
{/if}

<span id="add-item" hi="1" tl="1" title="Add news.">+</span>
</span>
</div>
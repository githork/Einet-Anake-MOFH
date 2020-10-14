{if $tsAction == 'admin-blackList_edit'}
<form method="POST" name="edit-blacklist" id="edit-blacklist">
<input type="hidden" class="input" name="bid" value="{$tsBlackList.data.id}"/>
<input type="hidden" class="input" name="type" value="{$tsType}"/>
<section class="item">
<label>Type:</label>
<div id="a-select" class="select">
<select class="input" name="b-type">
{foreach from=$tsBlackType key=i item=nombre}
<option value="{$i}" {if $tsBlackList.data.b_type == $i}selected="selected"{/if}>{$nombre}</option>
{/foreach}
</select>
</div>
</section>
<section class="item">
<label>Direction:</label>
<input type="text" class="input" name="value" maxlength="50" value="{$tsBlackList.data.b_value}"/>
</section>
<section class="item">
<label>Reason:</label>
<textarea class="input" id="razon" name="razon" cols="30" rows="2" maxlength="120">{$tsBlackList.data.b_reason}</textarea>
</section>
</form>
{elseif $tsAction == 'admin-blackList_update'}
{if $tsBlackList.data}
<!-- o -->
{if $tsBlackList.page.paginas >1}
<nav class="pagination is-centered" role="navigation" aria-label="pagination">
{if $tsBlackList.page.ant >0}
<a href="{$tsConfig.url}/admin/blacklist/pagina/{$tsBlackList.page.ant}" class="pagination-previous">« Previous</a>
{else}
<span class="pagination-previous" disabled>« Previous</span>
{/if}
{if $tsBlackList.page.sig <= $tsBlackList.page.paginas && $tsBlackList.page.sig > 0}
<a href="{$tsConfig.url}/admin/blacklist/pagina/{$tsBlackList.page.sig}" class="pagination-next">Next »</a>
{else}
<span class="pagination-next" disabled>Next »</span>
{/if}
<ul class="pagination-list">
{section name=c start=1 loop=$tsBlackList.page.paginas step=1 max=10}
{if $tsBlackList.page.actual != $smarty.section.c.index}
<li>
<a href="{$tsConfig.url}/admin/blacklist/pagina/{$smarty.section.c.index}" class="pagination-link">{$smarty.section.c.index}</a>
</li>
{else}
<li>
<a href="#" class="pagination-link is-current">{$tsBlackList.page.actual}</a>
</li>
{/if}
{/section}
</ul>
</nav>
{/if}

<section id="all-blacklist">
<table id="panel-elements">
{foreach from=$tsBlackList.data item=b}
<tbody id="blk-item" bid="{$b.id}">
<tr>
<th><b>Type</b></th>
<td><font class="tag is-success" tl="1" title="Type - {$tsBlackType[$b.b_type]}"><i class="einet icon-check-square"></i> {$tsBlackType[$b.b_type]}</font>
<span id="blk-right">
<i class="einet icon-more-vertical" tl="1" title="Options"></i>
{if $tsUser->is_admod == '1'}
<ul id="mini-tools">
<li class="column is-6" bid="{$b.id}" hi="1" title="Edit element"><i class="einet icon-edit"></i><p>Edit</p></li>
<li class="column is-6" bid="{$b.id}" hi="2" title="Delete element"><i class="einet icon-trash-2"></i><p>Delete</p></li>
</ul>
{/if}
</span>
</td>
</tr>
<tr>
<th><b>Text</b></th>
<td><font class="text"><i class="einet icon-slack"></i> {if $b.b_type == '1'}<a href="{$tsConfig.url}/admin/ipinfo/{$b.b_value}" class="ip" target="_blank">{$b.b_value}</a>{else}{$b.b_value}{/if}</font></td>
</tr>
<tr>
<th><b>Reason</b></th>
<td><font tl="1" title="{$b.b_reason}"><i class="einet icon-message-circle"></i> {$b.b_reason}</font></td>
</tr>
<tr>
<th><b>Author</b></th>
<td><a href="{$tsConfig.url}/perfil/{$b.user_nick}" tl="1" title="More details about - {$b.user_nick}" target="_blank"><i class="einet icon-user-check"></i> {if $b.user_name}{$b.user_name}{else}{$b.user_nick}{/if}</a></td>
</tr>
<tr>
<th><b>Date</b></th>
<td><font class="date" tl="1" title="{$b.b_date|fecha}"><i class="einet icon-clock"></i> {$b.b_date|date}</font></td>
</tr>
</tbody>
{/foreach}
</table>
</section>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>It's all good..</p>
<h2>There are currently no items added to the blacklist.</h2>
</div>
<!-- nice -->
{/if}

<div class="clear"></div>
<span id="add-item" hi="1" tl="1" title="Add blocking.">+</span>
{/if}
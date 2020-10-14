<div id="panel-user">
<span class="title-box"><span>Censure words</span> <i class="einet icon-x-square"></i></span>
<span id="mod-badwords" class="cont-box">
{if $tsBadWords}







<section id="all-badwords">
<table id="panel-elements">
{foreach from=$tsBadWords item=b}
<tbody id="blk-item" bid="{$b.id}">
<tr>
<th><b>ID</b></th>
<td><font>{$b.p_id}</font>
<span id="blk-right">
<i class="einet icon-more-vertical" tl="1" title="Options"></i>
{if $tsUser->is_admod == '1'}
<ul id="mini-tools">
<li class="column is-6" bid="{$b.p_id}" hi="1" title="Edit element"><i class="einet icon-edit"></i><p>Edit</p></li>
<li class="column is-6" bid="{$b.p_id}" hi="2" title="Delete element"><i class="einet icon-trash-2"></i><p>Delete</p></li>
</ul>
{/if}
</span>
</td>
</tr>
<tr>
<th><b>Type</b></th>
<td><font>{$b.p_type}</font></td>
</tr>
<tr>
<th><b>Before</b></th>
<td><font>{$b.p_esta}</font></td>
</tr>
<tr>
<th><b>After</b></th>
<td>{if $b.p_type == 'Texto'}<font>{$b.p_otra}</font>{else}<img src="{$b.p_otra}"/>{/if}</td>
</tr>


<tr>
<th><b>Reason</b></th>
<td><font>{$b.p_razon}</font></td>
</tr>

<tr>
<th><b>Author</b></th>
<td><a href="{$tsCore.url}/perfil/{$b.p_autor.user_nick}" target="_blank"><i class="einet icon-user-check"></i> {$b.p_autor.user_nick}</a></td>
</tr>

<tr>
<th><b>Date</b></th>
<td><font class="date" tl="1" title="{$b.p_date|fecha}"><i class="einet icon-clock"></i> {$b.p_date|date}</font></td>
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
<h2>There are currently no words added to censorship.</h2>
</div>
<!-- nice -->
{/if}



<span id="add-item" hi="1" tl="1" title="Add a word.">+</span>
</span>
</div>
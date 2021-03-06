{if $tsAction == 'admin-tema_edit'}
<form method="POST" name="edit-tema" id="edit-tema">
<input type="hidden" class="input" name="tid" value="{$tsTema.t_id}"/>
<input type="hidden" class="input" name="type" value="{$tsType}"/>
<section class="item">
<label>Name:</label>
<input type="text" class="input" name="name" maxlength="15" value="{$tsTema.t_name}" onkeyup="$('input[name=link]').val('/temas/'+$('input[name=name]').val().toLowerCase())"/>
</section>
<section class="item">
<label>Directory: <small>(ej: /temas/nombre_carpeta)</small></label>
<input type="text" class="input" name="link" maxlength="18" value="{$tsTema.t_url}"/>
</section>
</form>
{elseif $tsAction == 'admin-tema_update'}
<table id="tbl-tema" class="tid-new">
<tbody>
<tr>
<td>
</td>
</tr>
<tr>
<td>
<form method="POST" enctype="multipart/form-data" id="subir-tema">
<input type="file" name="pack" class="file" title="Drag the subject over here | Click to upload one.">
<span class="tact">
<span class="p">Add a theme here.</span>
<span class="p2">Or select one from your computer.</span>
<i class="einet icon-upload-cloud"></i>
</span>
</form>
</td>
</tr>
</tbody>
</table>
{foreach from=$tsTemas item=tema}
<table id="tbl-tema" class="tid-{$tema.t_id}">
<tbody>
<tr>
<td>
<b>{$tema.t_name}</b> 
<span class="tools">
<i class="einet icon-more-vertical" tl="1" title="Options"></i>
{if $tsUser->is_admod == '1'}
<ul id="mini-tools">
{if $tsConfig.tema_id == $tema.t_id}
<li class="column is-6" title="Theme in use">
<i class="einet icon-check-square"></i><p>In use</p></li>
{else}
<li class="column is-6" tid="{$tema.t_id}" hi="1" title="Use theme">
<i class="einet icon-image"></i><p>Use</p></li>
{/if}
<li class="column is-6" tid="{$tema.t_id}" hi="2" title="Edit theme">
<i class="einet icon-edit"></i><p>Edit</p></li>
<li class="column is-12" tid="{$tema.t_id}" hi="3" title="Delete theme">
<i class="einet icon-trash-2"></i><p>Delete</p></li>
</ul>
{/if}
</span>
</td>
</tr>
<tr>
<td style="background: url('{$tema.t_cover}') center no-repeat;"></td>
</tr>
</tbody>
</table>
{/foreach}
<div class="clear"></div>
{/if}
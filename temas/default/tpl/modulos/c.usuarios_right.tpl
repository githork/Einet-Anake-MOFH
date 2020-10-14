<span class="title-box"><span>User filter </span><i class="einet icon-filter"></i></span>
<span class="cont-box">
<form method="GET" id="filter-users">
<label><input type="checkbox" name="online" value="1" {if $tsFilter.online == '1'}checked="true"{/if}/> Online</label>
<label><input type="checkbox" name="imagen" value="1" {if $tsFilter.imagen == '1'}checked="true"{/if}/> Image</label>
<label><input type="radio" name="sexo" value="h" {if $tsFilter.sexo == 'h'}checked="true"{/if}/> Male</label>
<label><input type="radio" name="sexo" value="m" {if $tsFilter.sexo == 'm'}checked="true"{/if}/> Female</label>
<label><input type="radio" name="sexo" value="" {if $tsFilter.sexo == ''}checked="true"{/if}/> Both</label>
<div class="select">
<select name="pais">
<option value="">All Countries..</option>
{foreach from=$tsPaises key=cod item=pais}
<option value="{$cod}" {if $tsFilter.pais == $cod}selected="true"{/if}>{$pais}</option>
{/foreach}
</select>
</div>
<div class="select">
<select name="rango">
<option value="">All Ranks...</option>
{foreach from=$tsRangos item=r}
<option value="{$r.id_rango}" {if $tsFilter.rango == $r.id_rango}selected="true"{/if}>{$r.r_nombre}</option>
{/foreach}
</select>
</div>
<input type="submit" id="filter" class="button is-success" value="Filter users"/>
</form>
</span>

{if $tsConfig.pub_active == 0 && $tsConfig.pub_300 !=''}
<span class="title-box"><span>Ads</span><i class="einet icon-dollar-sign"></i></span>
<span class="cont-box">
<center>{$tsConfig.pub_300}</center>
</span>
{/if}
{if $tsAction == 'admin-editar_plan'}
<form method="post" name="edit-plan" id="edit-plan">
<input type="hidden" class="input" name="pid" value="{$tsPlan.pid}"/>
<input type="hidden" class="input" name="type" value="{$tsPlan.type}"/>
<section class="item">
<label>Plan Name:</label>
<input type="text" class="input" name="name_plan" maxlength="20" value="{$tsConfig.cpanel.name_plan[$tsPlan.pid]}" placeholder="One Plan"/>
</section>
</form>
{elseif $tsAction == 'admin-update_plan'}
{if $tsPlanes.name_plan}
<label><i class="einet icon-hard-drive"></i> Hosting Plans:</label>
<table id="panel-elements">
{foreach from=$tsPlanes.name_plan item=p key=i}
<tbody id="plan-item" pid="{$i}">
<tr>
<th><b>Plan {$i}</b></th>
<td>
<font class="tag is-success" tl="1" title="Hosting plan - {$p}"><i class="einet icon-hard-drive"></i> {$p}</font>
<span id="blk-right">
<i class="einet icon-more-vertical" tl="1" title="Options"></i>
{if $tsUser->is_admod == '1'}
<ul id="mini-tools">
<li class="column is-6" pid="{$i}" hi="1" title="Edit plan"><i class="einet icon-edit"></i><p>Edit</p></li>
<li class="column is-6" pid="{$i}" hi="2" title="Delete plan"><i class="einet icon-trash-2"></i><p>Delete</p></li>
</ul>
{/if}
</span>
</td>
</tr>
</tbody>
{/foreach}
</table>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>It's all good..</p>
<h2>There are currently no hosting plans added to the list.</h2>
</div>
<!-- nice -->
{/if}

{elseif $tsAction == 'admin-editar_nameserver'}
<form method="post" name="edit-nameserver" id="edit-nameserver">
<input type="hidden" class="input" name="nid" value="{$tsServer.nid}"/>
<input type="hidden" class="input" name="type" value="{$tsServer.type}"/>
<section class="item">
<label>Name:</label>
<input type="text" class="input" name="ns" maxlength="50" value="{$tsConfig.cpanel.ns[$tsServer.nid]}" placeholder="ns1.domain.com"/>
</section>
</form>
{elseif $tsAction == 'admin-update_nameservers'}
<label><i class="einet icon-link-2"></i> Name servers:</label>
{if $tsServers.ns}
<table id="panel-elements">
{foreach from=$tsServers.ns item=ns key=i}
<tbody id="ns-item" nid="{$i}">
<tr>
<th><b>NS{$i}</b></th>
<td><font class="tag is-purple" tl="1" title="NameServer {$i}"><i class="einet icon-link-2"></i> {$ns}</font>
<span id="blk-right">
<i class="einet icon-more-vertical" tl="1" title="Options"></i>
{if $tsUser->is_admod == '1'}
<ul id="mini-tools">
<li class="column is-6" nid="{$i}" hi="1" title="Edit nameserver"><i class="einet icon-edit"></i><p>Edit</p></li>
<li class="column is-6" nid="{$i}" hi="2" title="Delete nameserver"><i class="einet icon-trash-2"></i><p>Delete</p></li>
</ul>
{/if}
</span>
</td>
</tr>
</tbody>
{/foreach}
</table>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>It's all good..</p>
<h2>There are currently no name servers added to the list.</h2>
</div>
<!-- nice -->
{/if}

{elseif $tsAction == 'admin-editar_reseller'}
<form method="post" name="edit-reseller" id="edit-reseller">
<input type="hidden" class="input" name="cid" value="{$tsCuenta.cid}"/>
<input type="hidden" class="input" name="type" value="{$tsCuenta.type}"/>
<section class="item">
<label>Username:</label>
<input type="text" class="input" name="cp_user" maxlength="280" value="{$tsConfig.cpanel.account.{$tsCuenta.cid}.cp_user}"/>
<label>Password:</label>
<input type="password" class="input" name="cp_pass" maxlength="280" value="{$tsConfig.cpanel.account.{$tsCuenta.cid}.cp_pass}"/>
<label>Domain:</label>
<input type="text" class="input" name="name" maxlength="50" value="{$tsConfig.cpanel.domain_list.{$tsCuenta.cid}.name}" placeholder="example.com"/>
<label>User prefix:</label>
<input type="text" class="input" name="prefix" maxlength="6" value="{$tsConfig.cpanel.domain_list.{$tsCuenta.cid}.prefix}" placeholder="expl"/>
<label>Available:</label>
<span class="left"><label class="switch">
<input type="checkbox" name="on" {if $tsConfig.cpanel.domain_list.{$tsCuenta.cid}.on == '0'}checked="checked"{/if}><div class="sliper round"></div></label></span>
<span class="right">enable ● disable availability for account creation under this api reseller.</span>
</section>
</form>
{elseif $tsAction == 'admin-update_resellers'}
<label><i class="einet icon-users"></i> Reseller accounts:</label>
{if $tsCuentas.account}
<table id="panel-elements">
{foreach from=$tsCuentas.account item=a key=i}
<tbody id="ac-item" cid="{$i}">
<tr>
<th><b>Username</b></th>
<td><font><i class="einet icon-user"></i> {$a.cp_user|truncate:70:"...":true}</font> 
<span id="blk-right">
<i class="einet icon-more-vertical" tl="1" title="Options"></i>
{if $tsUser->is_admod == '1'}
<ul id="mini-tools">
<li class="column is-6" cid="{$i}" hi="1" title="Edit account"><i class="einet icon-edit"></i><p>Edit</p></li>
<li class="column is-6" cid="{$i}" hi="2" title="Delete account"><i class="einet icon-trash-2"></i><p>Delete</p></li>
</ul>
{/if}
</span>
</td>
</tr>
<tr>
<th><b>Password</b></th>
<td><font><i class="einet icon-lock"></i> {$a.cp_pass|truncate:70:"...":true}</font></td>
</tr>
<tr>
<th><b>Domain</b></th>
<td><a href="http://{$tsConfig.cpanel.domain_list.$i.name}" target="_blank"><i class="einet icon-external-link"></i> {$tsConfig.cpanel.domain_list.$i.name}</a>
</td>
</tr>
</tbody>
{/foreach}
</table>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>It's all good..</p>
<h2>There are currently no reseller accounts added to the list.</h2>
</div>
<!-- nice -->
{/if}
<!-- finish -->
{/if}
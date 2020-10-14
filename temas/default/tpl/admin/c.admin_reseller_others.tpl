<div id="panel-user">
<span class="title-box"><span>Other configurations</span><i class="einet icon-database"></i></span>
<span id="mod-reseller" class="cont-box">
<section id="all-account">
<label><i class="einet icon-users"></i> Reseller accounts:</label>
{if $tsConfig.cpanel.account}
<table id="panel-elements">
{foreach from=$tsConfig.cpanel.account item=a key=i}
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
</section>
<hr />
<section id="all-dns">
<label><i class="einet icon-link-2"></i> Name servers:</label>
{if $tsConfig.cpanel.ns}
<table id="panel-elements">
{foreach from=$tsConfig.cpanel.ns item=ns key=i}
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
<h2>There are currently no nameservers added to the list.</h2>
</div>
<!-- nice -->
{/if}
</section>
<hr />
<section id="all-plan">
<label><i class="einet icon-hard-drive"></i> Hosting Plans:</label>
{if $tsConfig.cpanel.name_plan}
<table id="panel-elements">
{foreach from=$tsConfig.cpanel.name_plan item=p key=i}
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
</section>
<div class="clear"></div>
<span id="add-item">+
<ul id="reseller-menu">
<li id="rs_add" hi="0" title="Add reseller account">Reseller account</li>
<li id="ns_add" hi="0" title="Add nameserver">Name server</li>
<li id="ph_add" hi="0" title="Add hosting plan">Hosting plan</li>
</ul>
</span>
</span>
</div>
<div id="panel-user">
{if $tsCuenta.cp_id}
<section id="account" cid="{$tsCuenta.cp_id}">
<span class="title"><i class="einet icon-home"></i> Hosting <i class="einet icon-chevron-right"></i><span>{$tsCuenta.cp_domain}</span><i id="stat" class="einet notification {if $tsCuenta.cp_active == '0'}is-success icon-git-merge{else}is-danger icon-git-pull-request{/if}" tl="1" title="Account - {if $tsCuenta.cp_active == '0'}Active{else}Inactive{/if}"></i></span>
<article class="bloq column is-6">
<h2><i class="einet icon-user"></i> <span>Name | Username [cPanel • FTP • MySQL]</span></h2>
<p>{$tsCuenta.cp_user}</p>
<h2><i class="einet icon-globe"></i> Main Domain</h2>
<a href="http://{$tsCuenta.cp_domain}" target="_blank"><i class="einet icon-external-link"></i>{$tsCuenta.cp_domain}</a>
<h2><i class="einet icon-folder"></i> Root Directory</h2>
<p>/htdocs</p>
<h2><i class="einet icon-map-pin"></i> IP address</h2>
{if $tsUser->is_member == '1' && $tsUser->is_admod == '1'}
<a href="{$tsConfig.url}/admin/ipinfo/{$tsCuenta.cp_ip}" target="_blank"><i class="einet icon-external-link"></i>{$tsCuenta.cp_ip}</a>
{else}
<p>{$tsCuenta.cp_ip}</p>
{/if}
<h2><i class="einet icon-help-circle"></i> ID Client</h2>
<p>{$tsCuenta.cp_name}</p>
<h2><i class="einet icon-activity"></i> Account Status</h2>
<p>{if $tsCuenta.cp_active == '0'}Active{else}Inactive{/if}</p>
<h2><i class="einet icon-package"></i> Plan Hosting</h2>
<p>{$tsConfig.cpanel.name_plan[$tsCuenta.cp_plan]} / Free</p>
<h2><i class="einet icon-monitor"></i> cPanel</h2>
<a href="{$tsConfig.cpanel.cpanel}" target="_blank"><i class="einet icon-external-link"></i>{$tsConfig.cpanel.cpanel|replace:['http://', 'https://', 'http://www.', 'https://www.']:''}</a>
<h2><i class="einet icon-clock"></i> Creation</h2>
<p>{$tsCuenta.cp_date|date_format:"d/m/Y h:m:s A"}</p>
</article>
<article class="i-right bloq column is-6">
<a href="{$tsConfig.url}/panel/?action=re-direct&view=cpanel&id={$tsCuenta.cp_id}" target="_blank" class="button is-success" tl="1" title="Manage account from cPanel"><i class="einet icon-monitor"></i><span>cPanel</span></a>
<a href="{$tsConfig.url}/ajustes/seguridad/#pass_act" class="button is-success" tl="1" title="Change Password"><i class="einet icon-settings"></i><span>Password</span></a>
<a class="button is-danger" tl="1" {if $tsCuenta.cp_active == '0'}title="Delete hosting" hi="3"><i class="einet icon-x-square"></i><span>Delete</span>{else}disabled="disabled" title="Reactivate hosting" hi="2"><i class="einet icon-plus-square"></i><span>Reactivate</span>{/if}</a>
{if $tsCuenta.cp_active == '1'}
<hr />
<section class="status-ns notification is-warning">
<i id="alert" class="einet icon-alert-triangle"></i>
<article class="msg">
<p><b>{if $tsUser->info.user_name}{$tsUser->info.user_name}{else}{$tsUser->nick}{/if}</b>, we want to remind you that you have 30 days from the time of deactivation. After 30 days the account will be completely deleted and you will not be able to recover it.<br />If you want to <b>reactivate</b> your account you can do it before <b>{$tsCuenta.cp_over|date_format:"d/m/Y h:m:s A"}</b> if you think it was wrong.</p> 
</article>
<div class="clear"></div>
</section>
{/if}
<div class="clear"></div>
</section>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>Oops!!... Something's not right.</p>
<h2>Apparently this account doesn't exist, it was deleted or you don't own it. Contact the administration.</h2>
</div>
<!-- nice -->
{/if}
</div>
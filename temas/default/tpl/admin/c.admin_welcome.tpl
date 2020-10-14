<div id="panel-user">
<span class="title-box"><span>Administration Center</span><i class="einet icon-shopping-bag"></i></span>
<span class="cont-box">
<section class="alt-r1 notification is-primary">
<i class="einet icon-shopping-bag"></i>
<article class="rg">
<b>Hello young, {$tsUser->nick}!</b><br/>
<p>This is your <b>Administration Center.</b> Here you can modify the configuration of your web, modify users, resllers, publicity and many other things related to your web site.</p><br />

<p>If you have problems please check the <b>Support & Credits</b> page. If this information does not help you, <a href="https://x3host.ml" target="_blank">visit us to ask for help</a> about your problem.</p>
</article>
<div class="clear"></div>
</section>
<section class="alt-r2">
<h1><i class="einet icon-server"></i> Einet in progress:</h1>
<article id="new_app">
<ul id="app_list">
<i id="live-loading"></i>
</ul>
</article>
</section>
<section class="alt-r2">
<table id="tbl-admin">
<tbody>
<tr>
<td><b><i class="einet icon-sun"></i> Einet Anake</b></td>
<td><b><i class="einet icon-users"></i> Administrators</b></td>
<td><b><i class="einet icon-sunset"></i> Installations</b></td>
</tr>
<tr class="second">
<td id="version_pp">
<b>Installed version:</b>
<font>{$tsConfig.version}</font>
</td>
<td>
<ul class="app_list">
{foreach from=$tsAdmins item=a}
<li><a href="{$tsConfig.url}/perfil/{$a.user_nick}" uid="{$a.user_id}" tl="1" title="More details about - {$a.user_nick}" target="_blank">{$a.user_nick}</a></li>                             
{/foreach}
</ul> 
</td>
<td>
<b>Foundation:</b>
<h2 tl="1" title="{$tsConfig.web_create|date}">{$tsConfig.web_create|hace}</h2>
<b>Updated:</b>
<h2 tl="1" title="{$tsConfig.web_update|date}">{$tsConfig.web_update|hace}</h2>
</td>
</tr>
</tbody>
</table>
</section>
</span>
</div>
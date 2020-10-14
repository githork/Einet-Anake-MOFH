{include file='fragmentos/n.day_night.tpl'}
{include file='fragmentos/n.happy_birthday.tpl'}
{if $tsUser->is_admod == '1' && $tsUser->is_member == '1'}
<span class="title-box"><span>Search hosting</span><i class="einet icon-search"></i></span>
<nav class="cont-box">
<form action="{$tsConfig.url}/panel/buscador/" method="GET" id="search-host" name="search-host" class="panel">
<input type="text" id="search" name="search" value="{$tsSearch}" placeholder="username, id, name, domain!"/>
<input type="submit" id="button-host" value=""/><i class="einet icon-search"></i>
</form>
</nav>
{/if}
<span class="title-box"><span>List of tools</span><i class="einet icon-layout"></i></span>
<nav class="cont-box">
<a href="{$tsConfig.url}/panel/?action=create" class="mod-int" tl="1" title="Create hosting account">Create hosting<i class="einet icon-hard-drive"></i></a>
<a href="{$tsConfig.url}/panel/" class="mod-int" tl="1" title="All my accounts">Hosting accounts<i class="einet icon-server"></i></a>
<a href="{$tsConfig.url}/mensajes/nuevo/?view=1" class="mod-int" tl="1" title="Create support tickets">Create ticket <i class="einet icon-feather"></i></a>
</nav>
<span class="title-box"><span>Servers info</span><i class="einet icon-cpu"></i></span>
<nav class="cont-box o-right">
<h2><i class="einet icon-alert-circle"></i> Name servers:</h2>
<font>You created a hosting account with your own domain. Remember to add our <b>(NS)</b> to your domain's panel.</font>
{if $tsConfig.cpanel.ns}
{foreach from=$tsConfig.cpanel.ns item=server}
<span class="tag is-orange"><i class="einet icon-link-2"></i>{$server}</span>
{/foreach}
{/if}
<hr />
<h2><i class="einet icon-alert-circle"></i> SQL server:</h2>
<font>If your website uses a database, create and upload your <b>DB</b> from the cpanel and add the name of the server along with the other data to your connection file.</font>
{if $tsConfig.cpanel.sql}
<span class="tag is-warning"><i class="einet icon-database"></i>{$tsConfig.cpanel.sql}</span>
{/if}
<hr />
<h2><i class="einet icon-alert-circle"></i> FTP server:</h2>
<font>You use your own file manager to upload. Add the data associated with your account next to the FTP server name:</font>
{if $tsConfig.cpanel.ftp}
<a href="{$tsConfig.cpanel.ftp}" class="tag is-purple" target="_blank"><i class="einet icon-truck"></i> {$tsConfig.cpanel.ftp|replace:['ftp://', 'ftp://www.']:''}</a>
{/if}
<hr />
<h2><i class="einet icon-alert-circle"></i> WEB Mail:</h2>
<font>Hi <b>{$tsUser->nick}</b>, you have email created with your own domain name? <b>@yourdomain</b> now you can access it faster from:</font>
{if $tsConfig.cpanel.mail}
<a href="{$tsConfig.cpanel.mail}" class="tag is-success" target="_blank"><i class="einet icon-external-link"></i> {$tsConfig.cpanel.mail|replace:['http://', 'http://www.', '/roundcubemail/']:''}</a>
{/if}
</nav>
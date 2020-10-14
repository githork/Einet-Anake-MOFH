<div id="panel-user">
{if $tsGo == 'cpanel'}
<span class="title-box"><span>cPanel redirecting...</span><i class="einet icon-external-link"></i></span>
<section class="cont-box">
<form action="{$tsConfig.cpanel.cpanel}/login.php" method="post" id="form-cpanel">
<input type="hidden" class="input" name="uname" maxlength="20" value="{$tsCuenta.cp_user}">
<input type="hidden" class="input" name="passwd" maxlength="70" value="{$tsCuenta.cp_dpass}">
<input type="hidden" class="input" name="language" maxlength="30" value="{$tsCuenta.cp_idioma}">
<button class="button is-primary is-success"><span>Click here if you don't redirect it</span><i class="control is-loading"></i></button>
</form>
</section>
{elseif $tsGo == 'ftp-1'}
<span class="title-box"><span>net2FTP redirecting...</span><i class="einet icon-external-link"></i></span>
<section class="cont-box">
<form action="https://net2ftp.x3host.ml/?protocol=FTP&ftpserver={$tsConfig.cpanel.ftp|replace:['ftp://', 'ftps://', 'http://www.', 'https://www.']:''}&ftpserverport=21&username={$tsCuenta.cp_user}&password_encrypted={$tsCuenta.cp_password}&skin=shinra&viewmode=list&state=browse&state2=main" method="post" id="form-ftp-1">
<button class="button is-primary is-success"><span>Click here if you don't redirect it</span><i class="control is-loading"></i></button>
</form>
</section>
{/if}
</div>
{literal}
<script>
$(document).ready(function() { $('#panel-user').animate({opacity: 0.5}); $('#form-{/literal}{$tsGo}{literal}').submit(); });
</script>
{/literal}
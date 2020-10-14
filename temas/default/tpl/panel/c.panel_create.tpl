{literal}
<script>
if(global_data.captcha[0] == '0' && global_data.captcha[1] == 'google') {
$.getScript('https://www.google.com/recaptcha/api.js?hl=es');	
} else if(global_data.captcha[0] == '0' && global_data.captcha[1] == 'hcaptcha') {
$.getScript('https://hcaptcha.com/1/api.js?hl=es');	
}
$(document).ready(function() { $('#add-account #domain').focus(); });
</script>
{/literal}
<div id="panel-user">
<section class="status-ns notification is-light">
<i id="alert" class="einet icon-info"></i>
<article class="msg">
<ul>
<li>¿Do you have a domain of your own? Login to your domain panel and change the <b>NS</b> for ours.</li>
<li><b>{$tsConfig.titulo}</b> doesn't offer free domains with extensions <b>.com, .net, .org, etc</b>. If you want to buy one you can check its availability and cost from our online store.</li>
<li>The password for your <b>[cPanel • FTP • MySQL]</b> will be the same as the one with which you enter <b>{$tsConfig.titulo}</b></li>
</ul>
</article>
<div class="clear"></div>
</section>
<span class="title-box"><span>Create account</span><i class="einet icon-monitor"></i></span>
<form id="add-account" class="cont-box" get-ext="1">
<div class="notification is-primary">
<article id="new">
<input class="input" name="domain" id="domain" type="text" maxlength="50" placeholder="Write a name for your domain" value="">
<input class="input" name="ext_d" id="ext" type="text" maxlength="15" style="display:none;" placeholder=".com, .net, .org, .xyz, .etc" value="">
<select name="ext_s" id="ext">
{foreach from=$tsConfig.cpanel.domain_list item=d key=id}
{if $d.on == '0'}<option value="{$id}">{$d.name}</option>{/if}
{/foreach}
</select>
<span id="b-ds" class="button is-success" tl="1" title="Use my domain"><i class="einet icon-globe"></i> I have a domain</span>
<span id="b-ch" class="button is-danger"><i class="einet icon-check-circle"></i> Verify</span>
</article>
<span class="p-text">1. Write a name for your domain.</span>
</div>
{if $tsConfig.captcha_active != '1'}
<h1>2. Show us you're not a bot.</h1>
<div id="s-captcha" hi="{$tsConfig.captcha_active}">
<div class="g-recaptcha" data-sitekey="{$tsConfig.api.recaptcha.id}"></div>
</div>
{/if}
<h1>3. Select a hosting plan.</h1>
<div class="select">
<select name="plan" id="plan">
{foreach from=$tsConfig.cpanel.name_plan item=name key=id}
{if $tsUser->is_admod == '1'}
<option value="{$id}" {if $tsPlan == $id}selected="selected"{/if}>{$name}</option>
{else}
{if $id < '3'}<option value="{$id}" {if $tsPlan == $id}selected="selected"{/if}>{$name}</option>{/if}
{/if}
{/foreach}
</select>
</div>
<h1>4. ¿All set? Great now to create your account.</h1>
<section class="item">
<input type="submit" id="create-account" class="button is-success" value="Create account now »">
</section>
<div class="clear"></div>
</form>
</div>
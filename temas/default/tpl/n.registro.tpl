{include file='principal/header.tpl'}
<script src="{$tsConfig.js}/registro.js"></script>
<div id="module-left" class="register-box column is-9 panel">
<div class="registro-o">
<h3>Create an account in {$tsConfig.titulo}</h3>
{if $tsConfig.reg_active == '1'}
<div id="register-alert" class="notification is-new">Dear user, the service for creating accounts in {$tsConfig.titulo} is currently disabled, but this is temporary thanks to your understanding.</div>
{/if}
<form method="POST" id="register" name="register">
<input type="hidden" class="input" id="plan" name="plan" value="{$getPlan}">
<section id="social-login" class="item">
<b>Create your account with one of these services:</b>
{if $tsConfig.api.facebook.fb_active == '0'}
<a href="{$tsConfig.url}/social/1" class="button is-link" tl="1" title="Login with facebook"><i class="einet icon-facebook"></i> Facebook</a>
{/if}
{if $tsConfig.api.google.gl_active == '0'}
<a href="{$tsConfig.url}/social/2" class="button is-danger" tl="1" title="Login with google"><i class="einet icon-user"></i> Google</a>
{/if}
{if $tsConfig.api.twitter.tw_active == '0'}
<a href="{$tsConfig.url}/social/3" class="button is-info" tl="1" title="Login with twitter"><i class="einet icon-twitter"></i> Twitter</a>
{/if}
{if $tsConfig.api.github.gi_active == '0'}
<a href="{$tsConfig.url}/social/4" class="button is-black" tl="1" title="Login with github"><i class="einet icon-github"></i> GitHub</a>
{/if}
{if $tsConfig.api.windowslive.wl_active == '0'}
<a href="{$tsConfig.url}/social/5" class="button is-success" tl="1" title="Login with windows live"><i class="einet icon-mail"></i> Windows Live</a>
{/if}
<b><i class="einet icon-git-commit"></i></b>
</section>
<section class="item">
<label>Nick | Username:</label>
<input type="text" class="input" id="username" name="username" maxlength="20" placeholder="Nickname">
</section>
<section class="item">
<label>First name & Last name:</label>
<input type="text" class="input" id="nombre" name="nombre" maxlength="35" placeholder="First name & Last name">
</section>
<section class="item">
<label>Email address:</label>
<input type="text" class="input" id="email" name="email" maxlength="40" placeholder="Email@example.com">
</section>
<section class="item">
<label>Password:</label>
<input type="password" class="input" id="pass" name="pass" maxlength="40" placeholder="Password">
</section>
<section class="item">
<label>Repeat password:</label>
<input type="password" class="input" id="re_pass" name="re_pass" maxlength="40" placeholder="Repeat password">
</section>
<section class="item">
<label>Date of birth:</label>
<div id="r-dia" class="select">
<select id="dia" name="dia">
<option value="">Day</option>
{section name=dias start=1 loop=32}
<option value="{$smarty.section.dias.index}">{$smarty.section.dias.index}</option>
{/section}
</select>
</div>
<div id="r-mes" class="select">
<select id="mes" name="mes">
<option value="">Month</option>
{foreach from=$tsMeses key=i item=mes}
<option value="{$i}">{$mes}</option>
{/foreach}	
</select>
</div>
<div id="r-year" class="select">
<select id="year" name="year">
<option value="">Year</option>
{section name=year start=$tsEndY loop=$tsEndY step=-1 max=$tsMax}
<option value="{$smarty.section.year.index}">{$smarty.section.year.index}</option>
{/section}
</select>
</div>
</section>
<section class="item">
<label>Gender:</label>
<div id="r-sexo" class="select">
<select id="sexo" name="sexo">
<option value="">Select gender</option>
<option value="m">Female</option>
<option value="h">Male</option>
</select>
</div>
</section>
<section class="item">
<label>Country you live:</label>
<div id="r-pais" class="select">
<select id="pais" name="pais">
<option value="">Country</option>
{foreach from=$tsPaises key=cod item=pais}
<option value="{$cod}">{$pais}</option>
{/foreach}
</select>
</div>
</section>
{if $tsConfig.captcha_active != '1'}
<section class="item" id="s-captcha" hi="{$tsConfig.captcha_active}">
<label>Show us you're not a bot:</label>
<center style="padding:5px;">
<div class="g-recaptcha" data-sitekey="{$tsConfig.api.recaptcha.id}"></div>
</center>
<div class="help"><span><em></em></span></div>
</section>
{/if}
<section class="item">
<label>You accept the terms and conditions:</label>
<input type="checkbox" id="term" name="term">
<label style="display:inline-block;font-weight:normal;font-size:13px;">By registering I accept the terms and conditions of the site.</label>
</section>
<section class="item">
<input type="submit" class="button is-success disabled" id="btn-reg" value="Create my account" {if $tsConfig.reg_active == '1'}disabled="disabled"{else}disabled="disabled"{/if}>
<div class="clear"></div>
</section>
</form>
<div class="clear"></div>
</div>
</div>
<aside id="module-right" class="log-right column is-3 panel">
<div class="registro-o">
<img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.img}/einet_logo.png" id="logo-log" class="load"/>
<h3>¿You already have an account?</h3>
<a href="{$tsConfig.url}/login/" class="button is-danger">Logging in</a>
</div>
</aside>
<div class="clear"></div>
{include file='principal/footer.tpl'}
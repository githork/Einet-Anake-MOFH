{include file='principal/header.tpl'}
{literal}
<script> $(document).ready(function() { $('#login #username').focus(); }); </script>
{/literal}
<div id="module-left" class="login-box column is-9 panel">
<div class="login-o">
<h3>Login to {$tsConfig.titulo}</h3>
{if $tsConfig.log_active == '1'}
<div id="login-alert" class="notification is-new">Dear user, currently the login is temporarily disabled, because we are performing routine maintenance to open server. Please try to login in a few minutes.</div>
{/if}
<form method="POST" id="login">
<input type="hidden" id="redirect" name="redirect" value="{$tsDir}">
<section id="social-login" class="item">
<b>Sign in with one of these services:</b>
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
<label>Nick: | PIN: | Email address:</label>
<input type="text" class="input" id="username" name="username" maxlength="64" placeholder="PIN:A1B2C3D4">
</section>
<section class="item">
<label>Password:</label>
<input type="password" class="input" id="password" name="password" maxlength="64" placeholder="Password">
</section>
<section class="item">
<label class="switch"><input type="checkbox" id="log" name="log"><div class="sliper round"></div></label>
<label style="display:inline-block;font-weight:normal;">Keep the session started</label>
</section>
<section class="item">
<input type="submit" class="button is-primary" id="btn-login" value="Login" {if $tsConfig.log_active == '1'}disabled="disabled"{/if}>
</section>
</form>
<div class="clear"></div>
</div>
</div>
<aside id="module-right" class="log-right column is-3 panel">
<div class="login-o">
<img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.img}/einet_logo.png" id="logo-log" class="load"/>
<div class="img"></div>
<h3>¿You don't have an account yet.?</h3>
<a href="{$tsConfig.url}/registro/" class="button is-success">Register</a>
<h3>¿Problems accessing your account?</h3>
<ul class="log-rep">
<li><a href="#" id="remind_password">¿You cannot access your account?</a></li>
<li><a href="#" id="resend_validation">¿The validation email didn't arrive?</a></li>
</ul>
</div>
</aside>
<div class="clear"></div>
{include file='principal/footer.tpl'}
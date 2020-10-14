<div id="panel-user">
<span class="title-box"><span>API configuration</span><i class="einet icon-git-pull-request"></i></span>
<span class="cont-box">
<form method="POST" name="form-api" id="form-api">
<section class="item">
<label>API Facebook:</label>
<input type="text" class="input" id="fb-id" name="fb-id" maxlength="20" value="{$tsConfig.api.facebook.id}" placeholder="Application ID"/>
<input type="text" class="input" id="fb-sec" name="fb-sec" maxlength="40" value="{$tsConfig.api.facebook.secret}" placeholder="Secret application key"/>
<input type="text" class="input" id="fb-client" name="fb-client" maxlength="20" value="{$tsConfig.api.facebook.client}" placeholder="User ID where published"/>
</section>
<section class="item">
<label>Activate login:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="fb-active" name="fb-active" {if $tsConfig.api.facebook.fb_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function enables ● to disable facebook login. Perfect for creating your account in a few steps.</span>
</section>
<hr />
<section class="item">
<label>API Google:</label>
<input type="text" class="input" id="gl-id" name="gl-id" maxlength="80" value="{$tsConfig.api.google.id}" placeholder="Application ID"/>
<input type="text" class="input" id="gl-sec" name="gl-sec" maxlength="30" value="{$tsConfig.api.google.secret}" placeholder="Secret application key"/>
</section>
<section class="item">
<label>Activate login:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="gl-active" name="gl-active" {if $tsConfig.api.google.gl_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function enables ● to disable google login. Perfect for creating your account in a few steps.</span>
</section>
<hr />
<section class="item">
<label>API Twitter:</label>
<input type="text" class="input" id="tw-id" name="tw-id" maxlength="30" value="{$tsConfig.api.twitter.id}" placeholder="Application ID"/>
<input type="text" class="input" id="tw-sec" name="tw-sec" maxlength="55" value="{$tsConfig.api.twitter.secret}" placeholder="Secret application key"/>
</section>
<section class="item">
<label>Activate login:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="tw-active" name="tw-active" {if $tsConfig.api.twitter.tw_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function enables ● to disable twitter login. Perfect for creating your account in a few steps.</span>
</section>
<hr />
<section class="item">
<label>API GitHub:</label>
<input type="text" class="input" id="gi-id" name="gi-id" maxlength="25" value="{$tsConfig.api.github.id}" placeholder="Application ID"/>
<input type="text" class="input" id="gi-sec" name="gi-sec" maxlength="45" value="{$tsConfig.api.github.secret}" placeholder="Secret application key"/>
</section>
<section class="item">
<label>Activate login:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="gi-active" name="gi-active" {if $tsConfig.api.github.gi_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function activates ● to disable github login. Perfect for creating your account in a few steps.</span>
</section>
<hr />
<section class="item">
<label>API Windows Live:</label>
<input type="text" class="input" id="wl-id" name="wl-id" maxlength="42" value="{$tsConfig.api.windowslive.id}" placeholder="Application ID"/>
<input type="text" class="input" id="wl-sec" name="wl-sec" maxlength="45" value="{$tsConfig.api.windowslive.secret}" placeholder="Secret application key"/>
</section>
<section class="item">
<label>Activar login:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="wl-active" name="wl-active" {if $tsConfig.api.windowslive.wl_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function activates ● to disable logging in with windowslive. Perfect for creating your account in a few steps.</span>
</section>
<hr />
<section class="item">
<label>API Images:</label>
<input type="text" class="input" id="up-id" name="up-id" maxlength="45" value="{$tsConfig.api.imgur.id}" placeholder="Application ID"/>
<input type="text" class="input" id="up-sec" name="up-sec" maxlength="45" value="{$tsConfig.api.imgur.secret}" placeholder="Secret application key"/>
<input type="text" class="input" id="up-svr" name="up-svr" maxlength="20" value="{$tsConfig.upload_server}" placeholder="Server name"/>
</section>
<hr />
<section class="item">
<label>reCAPTCHA:<small> (service of <a href="https://www.google.com/recaptcha/" target="_blank">Google</a> or <a href="https://www.hcaptcha.com" target="_blank">hCaptcha</a>)</small></label>
<input type="text" class="input" id="rp-id" name="rp-id" maxlength="45" value="{$tsConfig.api.recaptcha.id}" placeholder="Application ID"/>
<input type="text" class="input" id="rp-sec" name="rp-sec" maxlength="45" value="{$tsConfig.api.recaptcha.secret}" placeholder="Secret application key"/>
<div id="a-select" class="select">
<select class="input" id="rp-name" name="rp-name">
<option value="">Select a service</option>
<option value="google" {if $tsConfig.api.recaptcha.name == 'google'}selected="selected"{/if}>Google</option>
<option value="hcaptcha" {if $tsConfig.api.recaptcha.name == 'hcaptcha'}selected="selected"{/if}>hCaptcha</option>
</select>
</div>
</section>
<hr />
<section class="item smtp">
<h2><i class="einet icon-share-2"></i> Email service:</h2>
<label>Username|Email:</label>
<input type="text" class="input" id="smtp-email" name="smtp-email" maxlength="50" value="{$tsConfig.smtp.email}" placeholder="Username|Email"/>
<label>Password:</label>
<input type="password" class="input" id="smtp-pass" name="smtp-pass" maxlength="40" value="{$tsConfig.smtp.password}" placeholder="Password"/>
<label>Server:</label>
<input type="text" class="input" id="smtp-host" name="smtp-host" maxlength="50" value="{$tsConfig.smtp.host}" placeholder="smtp.example.com"/>
<label>Port:</label>
<input type="text" class="input" id="smtp-port" name="smtp-port" maxlength="4" value="{$tsConfig.smtp.port}" placeholder="465"/>
<label>Protocol:</label>
<div id="a-select" class="select">
<select class="input" id="smtp-secure" name="smtp-secure">
<option value="tls" {if $tsConfig.smtp.secure == 'tls'}selected="selected"{/if}>TLS</option>
<option value="ssl" {if $tsConfig.smtp.secure == 'ssl'}selected="selected"{/if}>SSL</option>
<option value="none" {if $tsConfig.smtp.secure == 'none'}selected="selected"{/if}>None</option>
</select>
</div>
<label>Error list:</label>
<div id="a-select" class="select">
<select class="input" id="smtp-debug" name="smtp-debug">
{foreach from=$tsDebug key=cod item=name}
<option value="{$cod}" {if $tsConfig.smtp.debug == $cod}selected="selected"{/if}>{$name}</option>
{/foreach}
</select>
</div>
<label>Codification:</label>
<div id="a-select" class="select">
<select class="input" id="smtp-cod" name="smtp-cod">
<option value="">Select codification</option>
{foreach item=cod from=$tsCod}
<option value="{$cod}" {if $tsConfig.smtp.cod == $cod}selected="selected"{/if}>{$cod}</option>
{/foreach}
</select>
</div>
<label>Auth:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="smtp-auth" name="smtp-auth" {if $tsConfig.smtp.auth == '1'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function activates ● disables the authentication method on the SMTP servers in this case it is mandatory that it remains active.</span><br />
<label>HTML:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="smtp-html" name="smtp-html" {if $tsConfig.smtp.html == '1'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function activates ● deactivates the sending of HTML format in the emails, in the service for global sending of messages in the same.</span>
</section>
<hr />
<section class="item">
<label>Activate captcha:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="captcha" name="captcha" {if $tsConfig.captcha_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This feature enables ● to disable the captcha service for registration and other sections on the web that require anti-bot validation. [requires google api]</span>
</section>
<section class="item">
<label>Upload type:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="up-type" name="up-type" {if $tsConfig.upload_type == '1'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function activates ● deactivates the upload of files to an external image server, if you activate it you remember to fill in the requested fields.</span>
</section>
<section class="item">
<label>Email service:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="smtp-type" name="smtp-type" {if $tsConfig.smtp.type == '1'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function is to activate an external e-mail service, thus sending messages from the web via SMTP to any e-mail.</span>
</section>
<section class="item">
<input type="submit" class="button is-success" id="btn-config" value="Save settings" act="3">
</section>
</form>

<div class="clear"></div>
</span>
</div>
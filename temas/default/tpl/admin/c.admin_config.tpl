<div id="panel-user">
<span class="title-box"><span>Configuration</span><i class="einet icon-settings"></i></span>
<span class="cont-box">
<form method="POST" name="form-admin" id="form-admin">
<section class="item">
<label class="required">Site name:</label>
<input type="text" class="input" id="titulo" name="titulo" maxlength="50" value="{$tsConfig.titulo}" placeholder="Website title"/>
</section>
<section class="item">
<label>Site description:</label>
<input type="text" class="input" id="description" name="description" maxlength="60" value="{$tsConfig.description}" placeholder="Site description"/>
</section>
<section class="item">
<label class="required">Site address:</label>
<input type="text" class="input" id="url" name="url" value="{$tsConfig.url}" placeholder="http://www.yourdomain.com"/>
</section>
<section class="item">
<label>Site description words: <small>(keywords for internet search engines)</small></label>
<textarea class="input" id="meta-desc" name="meta-desc" cols="30" rows="2" maxlength="300">{$tsConfig.meta_desc}</textarea>
</section>
<section class="item">
<label>Site tags: <small>(key meta tags for internet search engines)</small></label>
<textarea class="input" id="meta-tags" name="meta-tags" cols="30" rows="2" maxlength="300">{$tsConfig.meta_tags}</textarea>
</section>
<section class="item">
<label class="required">Site email:</label>
<input type="text" class="input" id="email-web" name="email-web" maxlength="50" value="{$tsConfig.email_web}" placeholder="email@domain.com"/>
</section>
<section class="item">
<label class="required">Type codification:</label>
<div id="a-select" class="select">
<select class="input" id="cod" name="cod">
<option value="">Select codification</option>
{foreach item=cod from=$tsCod}
<option value="{$cod}" {if $tsConfig.cod_type == $cod}selected="selected"{/if}>{$cod}</option>
{/foreach}
</select>
</div>
</section>
<hr />
<section class="item">
<label>Maintenance mode:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="web-on" name="web-on" {if $tsConfig.web_on == '1'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function is to put the web in maintenance mode in case of update or replacement of the script only works in administrator mode.</span>
</section>
<section class="item">
<label>Maintenance message:</label>
<textarea class="input" id="web-msg" name="web-msg" cols="30" rows="2" maxlength="300">{$tsConfig.web_mensaje}</textarea>
</section>
<section class="item">
<label>Estimated time: <small>(Seconds 60 = 1 Min)</small></label>
<input type="text" class="input" id="web-over" name="web-over" maxlength="9" value="{$tsConfig.web_over}" placeholder="60"/>
<input type="text" class="input" readonly="readonly" id="time-over" date="{$tsConfig.date}" value=""/>
</section>
<hr />
<section class="item">
<label>Email service:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="service-email" name="service-email" {if $tsConfig.email_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function activates ● to deactivate the service for sending emails to registered users.</span>
</section>
<section class="item">
<label>Advertising:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="pub" name="pub" {if $tsConfig.pub_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function activates ● to deactivate advertising. Used in global mode it disables ads, with one click.</span>
</section>
<section class="item">
<label>Activate account:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="val-cuenta" name="val-cuenta" {if $tsConfig.val_cuenta == '1'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function activates ● to deactivate the validation of accounts created by users at the time of registration.</span>
</section>
<section class="item">
<label>Antiflood global:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="flood" name="flood" {if $tsConfig.antiFlood == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This feature enables ● to disable the timeout so that users can make new requests without forcing the script to consume all the bandwidth.</span>
</section>
<section class="item">
<label>Activate login:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="log" name="log" {if $tsConfig.log_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function enables ● to disable the login for users. Perfect for live maintenance.</span>
</section>
<section class="item">
<label>Activate registration:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="reg" name="reg" {if $tsConfig.reg_active == '0'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This function activates ● to deactivate the registration and creation of accounts on the website. Perfect for setting a maximum user limit.</span>
</section>
<hr />
<section class="item">
<label>Moderate to large view:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="vista-mod" name="vista-mod" {if $tsConfig.access_mod == '1'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This feature enables ● to disable the moderation team from viewing the various status of suspended or deleted posts and items.</span>
</section>
<hr />
<section class="item">
<label class="required">Online time</label>
<input type="text" class="input" id="online" name="online" maxlength="2" value="{$tsConfig.user_activo}"/>
</section>
<section class="item">
<label class="required">Limit of posts</label>
<input type="text" class="input" id="max-posts" name="max-posts" maxlength="2" value="{$tsConfig.max_posts}"/>
</section>
<section class="item">
<label class="required">Limit registration</label></td>
<input type="text" class="input" id="limit-reg" name="limit-reg" maxlength="7" value="{$tsConfig.reg_limit}"/></td>
</section>
<section class="item">
<label>Minimum age requirement:</label>
<input type="text" class="input" id="allow-edad" name="allow-edad" maxlength="2" value="{$tsConfig.allow_edad}" title="Minimum age requirement"/>
</section>
<section class="item">
<label>Welcome message:</label>
<div id="a-select" class="select">
<select class="input" id="welcome-type" name="welcome-type" onchange="if($('#welcome-type').val() != 0) $('textarea[name=welcome-msg]').slideDown(); else $('textarea[name=welcome-msg]').slideUp();">
{foreach from=$tsBienvenida key=cod item=nombre}
<option value="{$cod}" {if $tsConfig.bienvenida == $cod}selected="selected"{/if}>{$nombre}</option>
{/foreach}
</select>
</div>
<textarea class="input" id="welcome-msg" name="welcome-msg" cols="30" rows="2" maxlength="500" {if $tsConfig.bienvenida == '0'}style="display:none;"{/if}>{$tsConfig.bienvenida_msg}</textarea>
</section>
<section class="item">
<input type="submit" class="button is-success" id="btn-config" value="Save settings" act="1">
</section>
</form>

<div class="clear"></div>
</span>
</div>
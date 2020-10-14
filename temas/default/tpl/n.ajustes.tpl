{include file='principal/header.tpl'}
<article id="module-left" class="column is-9">
{if $tsAction == 'nickname'}
<span class="title-box"><span>Nick | Username</span><i class="einet icon-user"></i></span>
<span class="cont-box">
<form method="POST" name="user" id="user" class="ajustes-mod">
<span class="req"><label class="required"></label> Hello <b>{$tsUser->nick},</b> write a nickname to check if it is available, before making the change.</span>
<section class="item">
<label class="required">Nick | Username:</label>
<input type="text" class="input" id="nick" name="nick" maxlength="20" value="" placeholder="Nickname">
</section>
<section class="item">
<label class="required">Current password:</label>
<input type="password" class="input" id="pass_act" name="pass_act" maxlength="20" value="" placeholder="Current password">
</section>
<section class="item">
<input type="submit" class="button is-success" id="btn-config" value="Update information" act="4"/>
</section>
</form>
<div class="clear"></div>
</span>
{elseif $tsAction == 'seguridad'}
<span class="title-box"><span>Security</span><i class="einet icon-lock"></i></span>
<span class="cont-box">
<form method="POST" name="user" id="user" class="ajustes-mod">
<span class="req"><label class="required"></label> Remember that changing your access password will affect your <b>hosting accounts.</b> You must enter the same password to access <b>[CPANEL • FTP • MYSQL]</b></span>
<section class="item">
<label>Current password:</label>
<input type="password" class="input" id="pass_act" name="pass_act" maxlength="20" value="" placeholder="Current password">
</section>
<section class="item">
<label>New Password:</label>
<input type="password" class="input" id="pass" name="pass" maxlength="20" value="" placeholder="New Password">
</section>
<section class="item">
<label>New password repeat:</label>
<input type="password" class="input" id="re_pass" name="re_pass" maxlength="20" value="" placeholder="New password repeat">
</section>
<section class="item">
<input type="submit" class="button is-success" id="btn-config" value="Update information" act="3"/>
</section>
</form>
<div class="clear"></div>
</span>
{elseif $tsAction == 'perfil'}
<span class="title-box"><span>Profile</span><i class="einet icon-user-check"></i></span>
<span class="cont-box">
<form method="POST" name="user" id="user" class="ajustes-mod">
<section class="item">
<label>First name & Last name:</label>
<input type="text" class="input" id="nombre" name="nombre" maxlength="35" value="{$tsUser->info.user_name}" placeholder="First name & Last name">
</section>
<section class="item">
<label>Website:</label>
<input type="text" class="input" id="web" name="web" maxlength="35" value="{$tsUser->info.p_web}" placeholder="http://">
</section>
<section class="item">
<label>Personal message:</label>
<textarea class="input" id="mensaje" name="mensaje" maxlength="200" placeholder="Write a personal message">{$tsUser->info.p_mensaje}</textarea>
</section>
<section class="item">
<input type="submit" class="button is-success" id="btn-config" value="Update information" act="2"/>
</section>
</form>
<div class="clear"></div>
</span>
{else}
<span class="title-box"><span>Configuration</span><i class="einet icon-settings"></i></span>
<span class="cont-box">
<form method="POST" name="user" id="user" class="ajustes-mod">
<span class="req">Young, <b>{$tsUser->nick}</b> remember the highlighted fields <label class="required"></label> are required..</span>
<section class="item">
<label class="required">Email address:</label>
<input type="text" class="input" id="email" name="email" maxlength="40" value="{$tsUser->info.user_email}" placeholder="Email address">
</section>
<section class="item">
<label>Country you live:</label>
<div id="u-pais" class="select">
<select id="pais" name="pais">
<option value="">Country</option>
{foreach from=$tsPaises key=cod item=pais}
<option value="{$cod}" {if $tsUser->info.p_pais == $cod}selected="selected"{/if}>{$pais}</option>
{/foreach}
</select>
</div>
</section>
<section class="item">
<label class="required">Gender:</label>
<div id="u-sexo" class="select">
<select id="sexo" name="sexo">
<option value="m" {if $tsUser->info.p_sexo == '0'}selected="selected"{/if}>Female</option>
<option value="h" {if $tsUser->info.p_sexo == '1'}selected="selected"{/if}>Male</option>
</select>
</div>
</section>
<section class="item">
<label>Date of birth:</label>
<div id="u-dia" class="select">
<select id="dia" name="dia">
<option value="">Day</option>
{section name=dias start=1 loop=32}
<option value="{$smarty.section.dias.index}" {if $tsUser->info.p_dia == $smarty.section.dias.index}selected="selected"{/if}">{$smarty.section.dias.index}</option>
{/section}
</select>
</div>
<div id="u-mes" class="select">
<select id="mes" name="mes">
<option value="">Month</option>
{foreach from=$tsMeses key=i item=mes}
<option value="{$i}" {if $tsUser->info.p_mes == $i}selected="selected"{/if}>{$mes}</option>
{/foreach}	
</select>
</div>
<div id="u-year" class="select">
<select id="year" name="year">
<option value="">Year</option>
{section name=year start=$tsEndY loop=$tsEndY step=-1 max=$tsMax}
<option value="{$smarty.section.year.index}" {if $tsUser->info.p_year == $smarty.section.year.index}selected="selected"{/if}>{$smarty.section.year.index}</option>
{/section}
</select>
</div>
</section>
<section class="item">
<label class="required">Language cPanel:</label>
<div id="u-idioma" class="select">
<select id="idioma" name="idioma">
{foreach from=$tsIdiomas key=cod item=idioma}
<option value="{$cod}" {if $tsUser->info.p_idioma == $cod}selected="selected"{/if}>{$idioma}</option>
{/foreach}
</select>
</div>
</section>
<section class="item">
<input type="submit" class="button is-success" id="btn-config" value="Update information" act="1"/>
</section>
</form>
<div class="clear"></div>
</span>
{/if}


</article>
<aside id="module-right" class="column is-3">
{include file='modulos/c.ajustes_right.tpl'}
</aside>

<div class="clear"></div>
{include file='principal/footer.tpl'}
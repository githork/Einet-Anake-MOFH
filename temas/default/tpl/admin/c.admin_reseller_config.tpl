<div id="panel-user">
<span class="title-box"><span>WHM API configuration</span><i class="einet icon-git-pull-request"></i></span>
<span class="cont-box">
<form method="POST" name="form-admin-3" id="form-admin-3">
<section class="item">
<label>Server:</label>
<input type="text" class="input" id="cp-panel" name="cp-panel" maxlength="60" value="{$tsConfig.cpanel.cp_panel}" placeholder="https://example.domain.com/xml-api/"/>
</section>
<hr />
<section class="item">
<span class="left"><i class="einet icon-alert-circle" style="font-size:2.2em;margin:auto 30%;color:#18B2E8;"></i></span>
<span class="right">These settings are for client panel, domain address, ftp, cpanel, sql, mail and for help tips, main reseller data.</span>
</section>
<section class="item">
<label>Domain:</label>
<input type="text" class="input" id="domain" name="domain" maxlength="60" value="{$tsConfig.cpanel.domain}" placeholder="domain.com"/>
</section>
<section class="item">
<label>FTP:</label>
<input type="text" class="input" id="ftp" name="ftp" maxlength="60" value="{$tsConfig.cpanel.ftp}" placeholder="ftp://ftp.domain.com"/>
</section>
<section class="item">
<label>cPanel:</label>
<input type="text" class="input" id="cpanel" name="cpanel" maxlength="60" value="{$tsConfig.cpanel.cpanel}" placeholder="https://cpanel.domain.com"/>
</section>
<section class="item">
<label>MySQL:</label>
<input type="text" class="input" id="sql" name="sql" maxlength="60" value="{$tsConfig.cpanel.sql}" placeholder="sqlxxx.domain.com"/>
</section>
<section class="item">
<label>Web Mail:</label>
<input type="text" class="input" id="mail" name="mail" maxlength="60" value="{$tsConfig.cpanel.mail}" placeholder="https://mail.domain.com"/>
</section>
<section class="item">
<input type="submit" class="button is-success" id="btn-config" value="Save settings" act="5">
</section>
</form>
<div class="clear"></div>
</span>
</div>
<div id="panel-user">
<span class="title-box"><span>Other configurations</span><i class="einet icon-database"></i></span>
<span class="cont-box">
<form method="POST" name="form-admin-2" id="form-admin-2">
<section class="item">
<label>Activate notifications:</label>
<span class="left">
<label class="switch">
<input type="checkbox" id="live" name="live" {if $tsConfig.live_active == '1'}checked="checked"{/if}><div class="sliper round"></div>
</label>
</span>
<span class="right">This feature enables ● to disable live notifications when a message or reply arrives to the user, they are for [update time].</span>
</section>
<section class="item">
<label>Update & hide live: <small>(1 Min)</small></label>
<input type="text" class="input" id="live-time" name="live-time" maxlength="4" value="{math equation="({$tsConfig.live_time} / 60000)" format="%.2f"}" placeholder="update, {math equation="({$tsConfig.live_time} / 60000)" format="%.2f"} min"/>
<input type="text" class="input" id="live-hide" name="live-hide" maxlength="4" value="{math equation="({$tsConfig.live_hide} / 60000)" format="%.2f"}" placeholder="hide, {math equation="({$tsConfig.live_hide} / 60000)" format="%.2f"} min"/>
</section>
<section class="item">
<input type="submit" class="button is-success" id="btn-config" value="Save settings" act="4">
</section>
</form>

<div class="clear"></div>
</span>
</div>
<span class="title-box"><span>Filter activity</span><i class="einet icon-filter"></i></span>
<nav class="cont-box">
<ul id="mon-tools" class="mon-ul">
<p>Activate <i class="einet icon-status"></i> Deactivate all notifications you wish to receive and those you do not.</p>
<label><i class="einet icon-status"></i> Users I follow</label>
<li><label class="switch"><input type="checkbox" id="f1" name="f1" {if $tsUser->filtros.f1 == '0'}checked="checked"{/if}><div class="sliper round"></div></label> New <span class="monac_icons ma_follow"></span></li>
</ul>
</nav>
<span class="title-box"><span>Live Notifications</span><i class="einet icon-sunrise"></i></span>
<nav class="cont-box">
<ul id="mon-tools" class="mon-ul">
<li><label class="switch"><input type="checkbox" id="ntfs" name="ntfs" {if $tsUser->filtros.ntfs == '0'}checked="checked"{/if}><div class="sliper round"></div></label> Notifications</li>
<li><label class="switch"><input type="checkbox" id="msgs" name="msgs" {if $tsUser->filtros.msgs == '0'}checked="checked"{/if}><div class="sliper round"></div></label> Messages</li>
<li><label class="switch"><input type="checkbox" id="sound" name="sound" {if $tsUser->filtros.sound == '0'}checked="checked"{/if}><div class="sliper round"></div></label> Playing sounds</li>
</ul>
</nav>
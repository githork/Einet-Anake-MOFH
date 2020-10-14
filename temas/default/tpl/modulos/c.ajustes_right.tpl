<span class="title-box"><span>Profile image</span><i class="einet icon-image"></i></span>
<span class="cont-box">
<form method="POST" enctype="multipart/form-data" id="avatar_file">
<input type="file" name="img[]" class="file_img" title="Dragging an image | Click to select a."/>
<img src="{$tsConfig.img}/einet_default.jpg" data-src="{$tsConfig.url}/files/perfiles/{$tsUser->uid}_120.jpg" id="box_avatar" class="load"/>
<span class="tactil"><i class="einet icon-crop"></i> | <i class="einet icon-camera"></i></span>
</form>
<div class="img-positions">
<input type="hidden" name="thumb_x1" value="0"/>
<input type="hidden" name="thumb_y1" value="0"/>
<input type="hidden" name="thumb_x2" value="120"/>
<input type="hidden" name="thumb_y2" value="120"/>
<input type="hidden" name="thumb_w" value="120"/>
<input type="hidden" name="thumb_h" value="120"/>
<input type="hidden" name="img_url" value=""/>
</div>

<div class="clear"></div>
</span>

{if $tsConfig.pub_active == 0 && $tsConfig.pub_160 !=''}
<span class="title-box"><span>Ads</span><i class="einet icon-dollar-sign"></i></span>
<span class="cont-box">
<center>{$tsConfig.pub_160}</center>
</span>
{/if}

<span class="title-box"><span>Settings menu</span><i class="einet icon-layout"></i></span>
<span class="cont-box">
<a href="{$tsConfig.url}/ajustes/" class="mod-int">Configuration <i class="einet icon-settings"></i></a>
<a href="{$tsConfig.url}/ajustes/perfil" class="mod-int">Profile <i class="einet icon-user-check"></i></a>
<a href="{$tsConfig.url}/ajustes/seguridad" class="mod-int">Security <i class="einet icon-lock"></i></a>
<a href="{$tsConfig.url}/ajustes/nickname" class="mod-int">Nick | Username<i class="einet icon-user"></i></a>
</span>
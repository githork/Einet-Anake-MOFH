{if $tsConfig.day_night.data && $tsUser->is_member == '1'}
<span class="title-box"><span></span> <i class="einet {if $tsConfig.day_night.data.icono}{$tsConfig.day_night.data.icono}{else}icon-sunrise{/if}"></i></span>
<nav id="day-night" class="cont-box">
<span class="d-title">{$tsConfig.day_night.data.title}</span>
<span class="d-text" style="border-left: 2.5px solid {$tsConfig.day_night.data.color};">{$tsConfig.day_night.data.mensaje}</span>
<img src="{$tsConfig.day_night.data.imagen}" class="d-image"/>
<div class="clear"></div>
</nav>
{/if}
<div id="live-stream" ntotal="{$tsStream.total}" mtotal="{$tsMensajes.page.total}">
{if $tsStream.data}
{foreach from=$tsStream.data item=noti key=id}
<div class="NTBeeper_Full" id="beep_{$id}">
<div class="Beeps">
<div class="NTBeep">
<a href="{$noti.link}" class="NTBeep_NonIntentional">
<span class="NTBeep_Icon monac_icons ma_{$noti.style}"></span>
<span class="beeper_x" bid="{$id}">X</span>
<div class="NTBeep_Title">
<span class="blueName">{if $noti.total == 1}{$noti.user}{/if}</span> <b>{$noti.text}</b> {$noti.ltext}
</div>
</a>
</div>
</div>
</div>
{/foreach}
{/if}

{if $tsMensajes.data}
{foreach from=$tsMensajes.data item=mp key=id}
<div class="NTBeeper_Full" id="beep_m{$id}">
<div class="Beeps">
<div class="NTBeep">
<a href="{$tsConfig.url}/mensajes/leer/{$mp.mp_id}" class="NTBeep_NonIntentional">
<img src="{$tsConfig.url}/files/perfiles/{$mp.mp_from}_50.jpg" class="NTBeep_Icon"/>
<span class="beeper_x" bid="m{$id}">X</span>
<div class="NTBeep_Title">
<b><i class="einet icon-mail"></i> new message</b><br />                    
<span class="blueName">{$mp.user_nick}</span> {$mp.mp_preview|limit|truncate:35:"...":true}
</div>
</a>
</div>
</div>
</div>
{/foreach}
{/if}
</div>
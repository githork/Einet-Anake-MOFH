<span class="title-box"><span>Message menu</span><i class="einet icon-layout"></i></span>
<nav class="cont-box">
<ul id="message-tools"> 
<li><a href="{$tsConfig.url}/mensajes/nuevo/" {if $tsAction == 'nuevo'}class="here"{/if}>Write message <i class="einet icon-edit-2"></i></a></li>
<li><a href="{$tsConfig.url}/mensajes/" {if $tsAction == ''}class="here"{/if}>Messages received <i class="einet icon-skip-back"></i></a></li>
<li><a href="{$tsConfig.url}/mensajes/enviados/" {if $tsAction == 'enviados'}class="here"{/if}>Messages sent <i class="einet icon-skip-forward"></i></a></li>
<li><a href="{$tsConfig.url}/mensajes/respondidos/" {if $tsAction == 'respondidos'}class="here"{/if}>Messages replied <i class="einet icon-share"></i></a></li>
</ul>
</nav>
{if $tsAction == 'leer'}
<span class="title-box"><span>Message Actions</span><i class="einet icon-settings"></i></span>
<span class="cont-box">
</span>
{/if}

{if $tsConfig.pub_active == 0 && $tsConfig.pub_160 !=''}
<span class="title-box"><span>Ads</span><i class="einet icon-dollar-sign"></i></span>
<span class="cont-box">
<center>{$tsConfig.pub_160}</center>
</span>
{/if}
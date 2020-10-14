<div id="panel-user">
<span class="title-box"><span>Información de la IP </span><i class="einet icon-map-pin"></i></span>
<span class="cont-box">
{if $tsInfo.data}
<table id="panel-elements">
<tbody id="ipinfo">
<tr>
<th><b>Dirección IP</b></th>
<td><font id="getip" class="tag is-success" hi="1" tl="1" title="¿Quieres bloquear esta IP? - {$tsInfo.data.ip}"><i class="einet icon-globe"></i> {$tsInfo.data.ip}</font></td>
</tr>
<tr>
<th><b>Ciudad - Estado</b></th>
<td><font><i class="einet icon-corner-up-right"></i> {$tsInfo.data.city} - {$tsInfo.data.region}</font></td>
</tr>
<tr>
<th><b>País</b></th>
<td><font class="text">{if $tsInfo.data.country}<img class="c-flag" src="//ipapi.co/static/images/flags/24/{$tsInfo.data.country|lower}.png" tl="1" title="{$tsInfo.data.country_name}">{/if} {$tsInfo.data.country} / {$tsInfo.data.country_name}</font></td>
</tr>
<tr>
<th><b>Latitud y Longitud</b></th>
<td><font tl="1" title="Latitud: {$tsInfo.data.latitude}, Longitud: {$tsInfo.data.longitude}"><i class="einet icon-map-pin"></i> {$tsInfo.data.latitude}, {$tsInfo.data.longitude}</font></td>
</tr>
<tr>
<th><b>Zona Horaria</b></th>
<td><font class="text"><i class="einet icon-clock"></i> {$tsInfo.data.timezone}</font></td>
</tr>
<tr>
<th><b>Coordenadas UTM</b></th>
<td><font><i class="einet icon-map"></i> {$tsInfo.data.utc_offset}</font></td>
</tr>
<tr>
<th><b>Código Postal</b></th>
<td><font><i class="einet icon-slack"></i> {$tsInfo.data.postal}</font></td>
</tr>
<tr>
<th><b>Código de llamada</b></th>
<td><font><i class="einet icon-tablet"></i> {$tsInfo.data.country_calling_code}</font></td>
</tr>
<tr>
<th><b>Código Continente</b></th>
<td><font><i class="einet icon-compass"></i> {$tsInfo.data.continent_code}</font></td>
</tr>
<tr>
<th><b>Moneda</b></th>
<td><font class="text"><i class="einet icon-dollar-sign"></i> {$tsInfo.data.currency}</font></td>
</tr>
<tr>
<th><b>Idioma</b></th>
<td><font><i class="einet icon-mic"></i> {$tsInfo.data.languages}</font></td>
</tr>
<tr>
<th><b>ASN</b></th>
<td><font><i class="einet icon-sliders"></i> {$tsInfo.data.asn}</font></td>
</tr>
<tr>
<th><b>Unión Europea</b></th>
<td><font><i class="einet icon-pocket"></i> {if $tsInfo.data.in_eu == true}SI{else}NO{/if}</font></td>
</tr>
<tr>
<th><b>Servicio de Internet</b></th>
<td><font class="text"><i class="einet icon-monitor"></i> {$tsInfo.data.org}</font></td>
</tr>
</tbody>
</table>
{else}
<!-- nice -->
<div id="aviso-box">
<i class="einet icon-alert-octagon"></i> 
<p>Oops!!... Ocurrió un problema</p>
<h2>{$tsInfo.error}</h2>
</div>
<!-- nice -->
{/if}


<div class="clear"></div>
</span>
</div>

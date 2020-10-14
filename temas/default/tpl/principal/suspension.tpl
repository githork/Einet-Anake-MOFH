{include file='principal/header.tpl'}
<style type="text/css">
#aviso-box {
	background:var(--purple);
	color:var(--white);
}
#aviso-box p {
	font-weight:bold;
	font-size:1.6em;
}
#aviso-box font {
	font-style:italic;
}
#aviso-box h4, #aviso-box h6 {
	color:var(--white);
}
</style>
<section class="content panel">
<div id="aviso-box">
<i class="einet icon-user-x"></i>
<p>The account is disabled.</p>
<h4>Cause: <font>{$tsBanned.s_extra}</font></h4>

<h4>Suspension date: <font>{$tsBanned.s_date|date_format:"%d/%m/%Y a las %I:%M:%S %p"}</font></h4>

<h4>Date of rehabilitation:
<font>
{if $tsBanned.s_termina == 0}
Indefinitely
{elseif $tsBanned.s_termina == 1}
Permanently
{else}
{$tsBanned.s_termina|date_format:"%d/%m/%Y a las %I:%M:%S %p"}
{/if}
</font>
</h4>

<h6>Server date: <font>{$smarty.now|date_format:"%d/%m/%Y  %I:%M:%S %p"}</font></h6>

</div>
</section>
<div class="clear"></div>
{include file='principal/footer.tpl'}
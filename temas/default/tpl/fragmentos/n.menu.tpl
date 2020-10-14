<section id="head-menu">
<nav id="menu" class="container">
<span class="menu-o">
<a href="{$tsConfig.url}" id="logo" alt="{$tsConfig.titulo}" title="{$tsConfig.titulo}"><i id="icon-onox"></i></a>
</span>

<span class="menu-o">
<div class="menu-movil"><i class="einet icon-grid" style="display:none;"></i>
<ul class="menu_ul">
<li class="sel{if $tsPage == 'principal' || $tsPage == 'panel'} here{/if}">
<a href="{$tsConfig.url}{if $tsUser->is_member == '1'}/panel/{/if}" {if $tsUser->is_member == '1'}tl="1" title="Customer area"{/if}>
<i class="einet icon-home">{if $tsUser->is_member == '1' && $tsConfig.p_plan == '1'}<span class="i" tl="1" title="You have 1 task left">1</span>{/if}</i> Home</a>
</li>

<li class="sel{if $tsPage == 'usuarios'} here{/if}">
<a href="{$tsConfig.url}/usuarios/"><i class="einet icon-users"></i> Users</a>	
</li>
{if $tsUser->is_member == '1' && $tsUser->is_admod == '1' || $tsUser->is_GM == '1'}
<li class="mod_menu">
<i class="einet icon-menu"></i>
<ul class="menu_others">
<li title="Administration"><a href="{$tsConfig.url}/admin/"><i class="einet icon-shield"></i> Administration</a></li>
</ul>
</li>
{/if}
</ul>
<div class="clear"></div>
</div>
</span>

<span class="menu-o">
{if $tsUser->is_member == '1'}
<ul class="menu_user">
<li id="ntf" title="Notifications" class="sel{if $tsPage == 'notifications'} here{/if}">
<i class="einet icon-globe" id="ntf_" onclick="ntf.last(); return false"><span class="i">0</span></i>
<div class="p-notif" id="c-ntf">
<span class="p-data">
<strong class="alt-p">Notifications</strong>
<a href="javascript:ntf.mark('ntf');" id="mark-r" class="alt-r">Mark as read</a>
<div class="clear"></div>
</span>

<span class="p-term">
<ul></ul>
</span>

<span class="p-data p-flu"><a href="{$tsConfig.url}/notifications/" class="p-more">View all</a></span>
</div>
</li>

<li id="msg" title="Messages" class="sel{if $tsPage == 'mensajes'} here{/if}">
<i class="einet icon-mail" id="msg_" onclick="msg.last(); return false"><span class="i">0</span></i>
<div class="p-notif" id="c-msg">
<span class="p-data">
<strong class="alt-p">Messages</strong> 
<a href="{$tsConfig.url}/mensajes/nuevo/" class="alt-r">Write new</a>
<div class="clear"></div>
</span>

<span class="p-term">
<ul></ul>
</span>

<span class="p-data p-flu"><a href="{$tsConfig.url}/mensajes/" class="p-more">View all</a></span>
</div>
</li>

<li id="user-loged">
<figure class="image is-45x45">
<img class="is-rounded" src="{$tsConfig.url}/files/perfiles/{$tsUser->uid}_50.jpg"/>
</figure>

<ul id="u-menu">
<li class="column is-4" title="Go to my profile">
<a href="{$tsConfig.url}/perfil/{$tsUser->nick}"><i class="einet icon-user"></i><p>Profile</p></a>
</li>

<li class="column is-4" title="My account settings">
<a href="{$tsConfig.url}/ajustes/"><i class="einet icon-settings"></i><p>Account</p></a>
</li>

<li class="column is-4" title="Close session">
<a href="{$tsConfig.url}/salir/"><i class="einet icon-log-out"></i><p>Logout</p></a>
</li>
</ul>
</li>
</ul>
{else}
<span class="p-log"><i class="einet icon-user"></i>
<ul class="p-menu-user">
<li class="column is-6">
<a href="{$tsConfig.url}/login/?redirect={$tsConfig.getLink}"><i class="einet icon-log-in"></i><p>Login</p></a>
</li>
<li class="column is-6">
<a href="{$tsConfig.url}/registro/"><i class="einet icon-user-plus"></i><p>Create account!</p></a>
</li>
</ul>
</span>
{/if}
</span>
</nav>
</section>

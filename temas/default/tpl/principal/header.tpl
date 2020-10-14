<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="{$tsConfig.cod}">
<link rel="shortcut icon" href="{$tsConfig.tema.t_url}/img/st.ico"/>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0"/>
<meta name="Title" content="{$tsTitle}">
<meta name="Description" content="{$tsConfig.meta_desc}"/>
<meta name="Keywords" content="{$tsConfig.meta_tags}"/>
<meta name="Robots" content="all"/> 
<meta name="Author" content="Einet"/> 
<meta http-equiv="content-language" content="en"/>
<meta http-equiv="expires" content="0"/>
<meta http-equiv="Window-target" content="_top"/>
<meta property="og:id" content="{$tsConfig.api.facebook.id}"/>
<meta property="og:caption" content="{$tsConfig.domain}"/>
<link rel="stylesheet" href="{$tsConfig.css}/bulma.css"/>
<link rel="stylesheet" href="{$tsConfig.css}/global.css"/>
{if $tsPage != 'aviso' && $tsPage != ''}
<link rel="stylesheet" href="{$tsConfig.css}/{$tsPage}.css"/>
{/if}
<script src="{$tsConfig.js}/jquery.min.js"></script>
<script src="{$tsConfig.js}/jquery-ui.min.lazy.O.o.js"></script>
<script src="{$tsConfig.js}/global.js"></script>
<script src="{$tsConfig.js}/wysibb.js"></script>
<script>
// {literal}
var global_data = {
// {/literal}
url:'{$tsConfig.url}',
img:'{$tsConfig.img}',
theme:'{$tsConfig.tema.t_url}',
theme_name:'{$tsConfig.tema.t_path}',
mps:'{$tsMPs}',
live: new Array('{$tsConfig.live_active}', '{$tsConfig.live_time}', '{$tsConfig.live_hide}'),
action:'{$tsAction}',
focus: true,
is_member:'{$tsUser->is_member}',
title:'{$tsConfig.titulo}',
description:'{$tsConfig.description}',
pub: '{$tsConfig.pub_active}',
captcha: new Array('{$tsConfig.captcha_active}', '{$tsConfig.api.recaptcha.name}'),
// {literal}
};
// {/literal}
</script>
<title>{$tsTitle}</title>
</head>
<body>
{if $tsUser->is_admod == '1'}{$tsConfig.install}{/if}
<section id="gl-alert"></section>


<div id="black-screen"></div>
<div id="modal"></div>
<div class="gotop" title="Ir arriba"><span class="avp">&#94;</span></div>

<div id="loading" style="display:none;"><div class="spinnner"></div></div>

<div id="speak"></div>
<div id="js" style="display:none;"></div>
<div class="NTBeeper" id="AlertBox"></div>
<header id="header"{if $tsUser->p_cover.url && $tsUser->is_member == '1'} style="background:#18B2E8 url({$tsUser->p_cover.url}) fixed {$tsUser->p_cover.position};"{/if}>
{include file='fragmentos/n.menu.tpl'}
</header>

{include file='fragmentos/n.head_noticias.tpl'}

<!-- container -->
<div id="container" class="container">
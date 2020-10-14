CURLANG = {
	bold: "Bold",
	italic: "Italic",
	underline: "Underline",
	strike: "Strike",
	link: "Insert url",
	img: "Insert image",
	link_secure: "Protected Url",
	sup: "Super Index",
	sub: "Sub Index",
	hr: "Separator",
	justify: 'Justify',
	justifyleft: "Align left",
	justifycenter: "Center",
	justifyright: "Align right",
	table: "Insert table",
	bullist: "Bullet list",
	numlist: "Numbered list",
	spoiler: "Spoiler",
	messages: "Messages",
	msg_notice: "News",
	msg_info: "Information",
	msg_warning: "Warning",
	msg_error: "Error",
	msg_success: "Success",
	quote: "Quote",
	code: "Code",
	spoiler: "Spoiler",
	fontcolor: "Text color",
	fontsize: "Text size",
	fontfamily: "Source",
	fs_verysmall: "Very small",
	fs_small: "Small",
	fs_normal: "Normal",
	fs_big: "Big",
	fs_verybig: "Very big",
	fs_verybig2: "Gigant",
	smilebox: "Smilebox",
	swf: "Insert SWF file",
	pdf: "Insert PDF file",
	iframe: "Insert url",
	video: "Insert YouTube Video",
	goear: "Insert Goear Song",
	fullscreen: "Maximize",
	removeFormat: "Remove format",
	
	modal_link_title: "Insert url",
	modal_link_text: "Text for url: Example: Google",
	modal_link_url: "Add url: Example: http://google.com",
	modal_email_text: "Text link",
	modal_email_url: "Email",
	modal_link_tab1: "Insert from URL",
	
	modal_img_title: "Insert image",
	modal_img_tab1: "Add image from url",
	modal_img_tab2: "Add PC image",
	modal_imgsrc_text: "Insert image url",
	modal_img_btn: "Select an image",
	add_attach: "File Attachment",
	
	modal_swf_text: "SWF file URL",
	modal_pdf_text: "PDF file URL",
	modal_iframe_text: "Example: http://google.com",
	modal_video_text: "Example: http://www.youtube.com/watch?v=4A1b2",
	modal_goear_text: "Song URL",
	
	close: "Close / ESC key",
	save: "Save",
	cancel: "Cancel",
	remove: "Delete",
	
	validation_err: "The information entered is not valid",
	error_onupload: "An error occurred while uploading the files",
	
	fileupload_text1: "Drag the image here",
	fileupload_text2: "Or select a picture",
	loading: "Please wait..",
	auto: "Auto",
	views: "Visits",
	downloads: "Downloads",
	//integrados
	icons_limit: 143,//emoticones
};

$(function() {
	$('input[type=submit], button[type=submit], .wysibb-body, #btn-msg').mouseover(function() {
	if($('.wysibb-texarea').length == 1) {
	$('.wysibb-texarea').sync();
	} else {
	return false;
	}
  });
});

var toFullScreen = function() {
	if(!$('.wysibb').attr('fullscreen')) {
		var maxHeight = $(window).height() - $('.wysibb-toolbar').height();
		$('body').css('overflow', 'hidden');
		$('.wysibb').addClass('fullscreen').attr('fullscreen', true);
		$('.wysibb-body, .wysibb-texarea').css({'max-height': maxHeight, 'height': maxHeight});
	}else {
		$('body, .wysibb').removeAttr('style');
		$('.wysibb').removeAttr('fullscreen').removeClass('fullscreen');
		$('.wysibb-body, .wysibb-texarea').css({'max-height': 500, 'height': ''});
	}
};

wbbdebug = false;

(function($) {
	'use strict';
	$.wysibb = function(txtArea,settings) {
		$(txtArea).data("wbb",this);
		this.txtArea=txtArea;
		this.$txtArea=$(txtArea);
		var id = this.$txtArea.attr("id") || this.setUID(this.txtArea);
		this.options = {
			bbmode:				false,
			onlyBBmode:			false,
			themeName:			global_data.theme_name,
			themePrefix:		global_data.url+'/temas/',
			bodyClass:			'',
			lang:				'es',
			tabInsert:			true,
			toolbar:			true,
			//img upload config 
			imgupload:			true,
			img_uploadurl:		global_data.url+'/upload/',
			img_maxwidth:		800,
			img_maxheight:		640,
			hotkeys:			true,
			showHotkeys:		true,
			autoresize:			true,
			resize_maxheight:	900,
			loadPageStyles:		true,
			traceTextarea:		true,
			smileConversion:	true,

			//END img upload config 
			buttons: "smileBox,|,bold,italic,underline,strike,sub,sup,|,img,video,link,linksec,goear,|,fontcolor,fontsize,fontfamily,|,bullist,numlist,|,spoiler,messages,table,|,justifyleft,justifycenter,justifyright,justify,|,hr,quote,code,swf,pdf,|,removeFormat,fullscreen",
			allButtons: {
				bold : {
					title: CURLANG.bold,
					buttonHTML: '<span class="fonticon ve-tlb-bold1">\uE901</span>',
					excmd: 'bold',
					hotkey: 'ctrl+b',
					transform : {
						'<b>{SELTEXT}</b>':"[b]{SELTEXT}[/b]",
						'<strong>{SELTEXT}</strong>':"[b]{SELTEXT}[/b]"
					}
				},
				italic : {
					title: CURLANG.italic,
					buttonHTML: '<span class="fonticon ve-tlb-italic1">\uE900</span>',
					excmd: 'italic',
					hotkey: 'ctrl+i',
					transform : {
						'<i>{SELTEXT}</i>':"[i]{SELTEXT}[/i]",
						'<em>{SELTEXT}</em>':"[i]{SELTEXT}[/i]"
					}
				},
				underline : {
					title: CURLANG.underline,
					buttonHTML: '<span class="fonticon ve-tlb-underline1">\uE8ff</span>',
					excmd: 'underline',
					hotkey: 'ctrl+u',
					transform : {
						'<u>{SELTEXT}</u>':"[u]{SELTEXT}[/u]"
					}
				},
				strike : {
					title: CURLANG.strike,
					buttonHTML: '<span class="fonticon fi-stroke1 ve-tlb-strike1">\uE98a</span>',
					excmd: 'strikeThrough',
					hotkey: 'shift+t',
					transform : {
						'<strike>{SELTEXT}</strike>':"[s]{SELTEXT}[/s]",
						'<s>{SELTEXT}</s>':"[s]{SELTEXT}[/s]"
					}
				},
				sup : {
					title: CURLANG.sup,
					buttonHTML: '<span class="fonticon ve-tlb-sup1">\uE13b</span>',
					hotkey: 'ctrl+p',
					excmd: 'superscript',
					transform : {
						'<sup>{SELTEXT}</sup>':"[sup]{SELTEXT}[/sup]"
					}
				},
				sub : {
					title: CURLANG.sub,
					buttonHTML: '<span class="fonticon ve-tlb-sub1">\uE12b</span>',
					hotkey: 'ctrl+s',
					excmd: 'subscript',
					transform : {
						'<sub>{SELTEXT}</sub>':"[sub]{SELTEXT}[/sub]"
					}
				},
				hr : {
					title: CURLANG.hr,
					buttonHTML: '<span class="fonticon ve-tlb-hr1">\uE03b</span>',
					transform : {
						'<hr>':"[hr]"
					}
				},
				link : {
					title: CURLANG.link,
					buttonHTML: '<span class="fonticon ve-tlb-link1">\uE884</span>',
					hotkey: 'ctrl+k',
					modal: {
						title: CURLANG.modal_link_title,
						width: "450px",
						tabs: [
							{
								input: [
									{param: "SELTEXT",title:CURLANG.modal_link_text, type: "div"},
									{param: "URL",title:CURLANG.modal_link_url,validation: '^http(s)?://'}
								]
							}
						]
					},
					transform : {
						'<a href="{URL}">{SELTEXT}</a>':"[url={URL}]{SELTEXT}[/url]",
						'<a href="{URL}">{URL}</a>':"[url]{URL}[/url]"
					}
				},
				//Enlace protegido integrada por mi
				linksec : {
					title: CURLANG.link_secure,
					buttonHTML: '<span class="fonticon ve-tlb-linksec1">\uE8e4</span>',
					hotkey: 'shift+s',
					transform : {
					'<a class="str-secure">¡url secure:{SELTEXT}!</a>':'[secure]{SELTEXT}[/secure]'
					}
				},
				img : {
					title: CURLANG.img,
					buttonHTML: '<span class="fonticon ve-tlb-img1">\uE99a</span>',
					modal: {
						title: CURLANG.modal_img_title,
						width: "450px",
						tabs: [
							{
								title: CURLANG.modal_img_tab1,
								input: [
									{param: "SRC",title:CURLANG.modal_imgsrc_text,validation: '^http(s)?://.*?\.(jpg|png|gif|jpeg)$'}
								]
							},
							{
								title: CURLANG.modal_img_tab2,
								html: '<div id="imguploader"> <form id="fupform" class="upload" action="{img_uploadurl}" method="post" enctype="multipart/form-data" target="fupload"><input type="hidden" name="iframe" value="1"/><input type="hidden" name="idarea" value="'+id+'" /><div class="fileupload"><input id="fileupl" class="file" type="file" name="img[]" multiple="multiple"/><button id="nicebtn" class="wbb-button button is-danger is-focused">'+CURLANG.modal_img_btn+'</button> </div> </form> </div><iframe id="fupload" name="fupload" src="about:blank" frameborder="0" style="width:0px;height:0px;display:none"></iframe></div>'
							}
						],
						onLoad: this.imgLoadModal
					},
					transform : {
						'<img src="{SRC}" style="max-width: 100%;">':"[img={SRC}]",
						'<img src="{SRC}" style="max-width: 100%;" />':"[img]{SRC}[/img]"
					}
				},
				bullist : {
					title: CURLANG.bullist,
					hotkey: 'ctrl+l',
					buttonHTML: '<span class="fonticon ve-tlb-list1">\uE886</span>',
					excmd: 'insertUnorderedList',
					transform : {
						'<ul>{SELTEXT}</ul>':"\n[ul]\n{SELTEXT}[/ul]\n",
						'<li>{SELTEXT}</li>':"[li]{SELTEXT}[/li]\n"
					}
				},
				numlist : {
					title: CURLANG.numlist,
					hotkey: 'ctrl+m',
					buttonHTML: '<span class="fonticon ve-tlb-numlist1">\uE95a</span>',
					excmd: 'insertOrderedList',
					transform : {
						'<ol>{SELTEXT}</ol>':"\n[ol]\n{SELTEXT}[/ol]\n",
						'<li>{SELTEXT}</li>':"[li]{SELTEXT}[/li]\n"
					}
				},
				messages: {
					type: 'select',
					title: CURLANG.messages,
					options: "notice,info,warning,error,success"
				},
				notice: {
					title: CURLANG.msg_notice,
					buttonText: "notice",
					exvalue: "1",
					transform: {
						'<div class="bbcmsg notice">{SELTEXT}</div>':'[notice]{SELTEXT}[/notice]'
					}
				},
				info: {
					title: CURLANG.msg_info,
					buttonText: "info",
					exvalue: "2",
					transform: {
						'<div class="bbcmsg info">{SELTEXT}</div>':'[info]{SELTEXT}[/info]'
					}
				},
				warning: {
					title: CURLANG.msg_warning,
					exvalue: "3",
					transform: {
						'<div class="bbcmsg warning">{SELTEXT}</div>':'[warning]{SELTEXT}[/warning]'
					}
				},
				error: {
					title: CURLANG.msg_error,
					exvalue: "4",
					transform: {
						'<div class="bbcmsg error">{SELTEXT}</div>':'[error]{SELTEXT}[/error]'
					}
				},
				success: {
					title: CURLANG.msg_success,
					exvalue: "5",
					transform: {
						'<div class="bbcmsg success">{SELTEXT}</div>':'[success]{SELTEXT}[/success]'
					}
				},
				spoiler : {
					title: CURLANG.spoiler,
					buttonHTML: '<span class="fonticon ve-tlb-spoiler1">\uE04b</span>',
					transform : {
						'<div class="spoiler"><div class="title" contenteditable="false"><a href="#"><i class="sp-add"></i>Spoiler:</a></div><div class="body">{SELTEXT}</div></div>':"[spoiler]{SELTEXT}[/spoiler]",
					}
				},
				quote : {
					title: CURLANG.quote,
					buttonHTML: '<span class="fonticon ve-tlb-quote1">\uE96a</span>',
					hotkey: 'ctrl+q',
					transform : {
						'<blockquote><div class="cita" contenteditable="false"><strong>Cita:</strong></div><div class="citacuerpo">{SELTEXT}</div></blockquote>':"[quote]{SELTEXT}[/quote]",
						'<blockquote><div class="cita" contenteditable="false"><strong>{AUTOR}</strong> dijo:</div><div class="citacuerpo">{SELTEXT}</div></blockquote>':"[quote={AUTOR}]{SELTEXT}[/quote]"
					}
				},
				code : {
					title: CURLANG.code,
					buttonHTML: '<span class="fonticon ve-tlb-code1">\uE915</span>',
					onlyClearText: true,
					transform : {
						'<code><i contenteditable="false">código:</i> {SELTEXT}</code>':"[code]{SELTEXT}[/code]"
					}
				},
				fontcolor: {
					type: "colorpicker",
					title: CURLANG.fontcolor,
					excmd: "foreColor",
					valueBBname: "color",
					subInsert: true,
					colors: "#000000,#444444,#666666,#999999,#b6b6b6,#cccccc,#d8d8d8,#efefef,#f4f4f4,#ffffff,-, \
							 #ff0000,#980000,#ff7700,#ffff00,#00ff00,#00ffff,#1e84cc,#0000ff,#9900ff,#ff00ff,-, \
							 #f4cccc,#dbb0a7,#fce5cd,#fff2cc,#d9ead3,#d0e0e3,#c9daf8,#cfe2f3,#d9d2e9,#ead1dc, \
							 #ea9999,#dd7e6b,#f9cb9c,#ffe599,#b6d7a8,#a2c4c9,#a4c2f4,#9fc5e8,#b4a7d6,#d5a6bd, \
							 #e06666,#cc4125,#f6b26b,#ffd966,#93c47d,#76a5af,#6d9eeb,#6fa8dc,#8e7cc3,#c27ba0, \
							 #cc0000,#a61c00,#e69138,#f1c232,#6aa84f,#45818e,#3c78d8,#3d85c6,#674ea7,#a64d79, \
							 #900000,#85200C,#B45F06,#BF9000,#38761D,#134F5C,#1155Cc,#0B5394,#351C75,#741B47, \
							 #660000,#5B0F00,#783F04,#7F6000,#274E13,#0C343D,#1C4587,#073763,#20124D,#4C1130",
					transform: {
						'<font color="{COLOR}">{SELTEXT}</font>':'[color={COLOR}]{SELTEXT}[/color]'
					}
				},
				table: {
					type: "table",
					title: CURLANG.table,
					cols: 10,
					rows: 10,
					cellwidth: 20,
					transform: {
						'<td>{SELTEXT}</td>': '[td]{SELTEXT}[/td]',
						'<tr>{SELTEXT}</tr>': '[tr]{SELTEXT}[/tr]',
						'<table class="wbb-table">{SELTEXT}</table>': '[table]{SELTEXT}[/table]'
					},
					skipRules: true
				},
				fontsize: {
					type: 'select',
					title: CURLANG.fontsize,
					options: "fs_verysmall,fs_small,fs_normal,fs_big,fs_verybig,fs_verybig2"
				},
				fontfamily: {
					type: 'select',
					title: CURLANG.fontfamily,
					excmd: 'fontName',
					valueBBname: "font",
					options: [
						{title: "Arial",exvalue: "Arial"},
						{title: "Comic Sans MS",exvalue: "Comic Sans MS"},
						{title: "Courier New",exvalue: "Courier New"},
						{title: "Georgia",exvalue: "Georgia"},
						{title: "Lucida Sans Unicode",exvalue: "Lucida Sans Unicode"},
						{title: "Tahoma",exvalue: "Tahoma"},
						{title: "Times New Roman",exvalue: "Times New Roman"},
						{title: "Trebuchet MS",exvalue: "Trebuchet MS"},
						{title: "Verdana",exvalue: "Verdana"}
					],
					transform: {
						'<font face="{FONT}">{SELTEXT}</font>':'[font={FONT}]{SELTEXT}[/font]'
					}
				},
				smilebox: {
					type: 'smilebox',
					title: CURLANG.smilebox,
					buttonHTML: '<span class="fonticon ve-tlb-smilebox1">\uE93a</span>'
				},
				justify: {
					title: CURLANG.justify,
					buttonHTML: '<span class="fonticon ve-tlb-justify1">\uE821</span>',
					groupkey: 'align',
					transform: {
						'<div style="text-align:justify">{SELTEXT}</div>': '[align=justify]{SELTEXT}[/align]'
					}
				},
				justifyleft: {
					title: CURLANG.justifyleft,
					buttonHTML: '<span class="fonticon ve-tlb-textleft1">\uE822</span>',
					groupkey: 'align',
					transform: {
						'<div style="text-align:left">{SELTEXT}</div>': '[align=left]{SELTEXT}[/align]'
					}
				},
				justifyright: {
					title: CURLANG.justifyright,
					buttonHTML: '<span class="fonticon ve-tlb-textright1">\uE823</span>',
					groupkey: 'align',
					transform: {
						'<div style="text-align:right">{SELTEXT}</div>': '[align=right]{SELTEXT}[/align]'
					}
				},
				justifycenter: {
					title: CURLANG.justifycenter,
					buttonHTML: '<span class="fonticon ve-tlb-textcenter1">\uE81f</span>',
					groupkey: 'align',
					transform: {
						'<div style="text-align:center">{SELTEXT}</div>': '[align=center]{SELTEXT}[/align]'
					}
				},
				swf : {
					title: CURLANG.swf,
					hotkey: 'ctrl+h',
					buttonHTML: '<span class="fonticon ve-tlb-swf1">\uE08b</span>',
					modal: {
						title: CURLANG.swf,
						width: "450px",
						tabs: [
							{
								input: [
									{param: "URL",title:CURLANG.modal_swf_text,validation: '^http(s)?://.*?\.(swf)$'}
								]
							}
						]
					},
					transform : {
						'<embed src="{URL}" width="640" height="480" type="application/x-shockwave-flash">':"[swf={URL}]"
					}
				},
				// PDF
				pdf : {
					title: CURLANG.pdf,
					hotkey: 'ctrl+f',
					buttonHTML: '<span class="fonticon ve-tlb-pdf1">\uE09b</span>',
					modal: {
						title: CURLANG.pdf,
						width: "450px",
						tabs: [
							{
								input: [
									{param: "URL",title:CURLANG.modal_pdf_text,validation: '^http(s)?://.*?\.(pdf)$'}
								]
							}
						]
					},
					transform : {
						'<object data="{URL}" type="application/pdf" width="100%" height="450"><p><strong>Requiere adobe reader para visualizar el archivo <a href="https://get.adobe.com/es/reader/otherversions/" target="_blank">Descargar adobe reader</a></strong></p></object>':"[pdf={URL}]"
					}
				},
				iframe : {
					title: CURLANG.iframe,
					hotkey: 'ctrl+z',
					buttonHTML: '<span class="fonticon ve-tlb-ifr1">\uE841</span>',
					modal: {
						title: CURLANG.iframe,
						width: "450px",
						tabs: [
							{
								input: [
									{param: "SRC",title:CURLANG.modal_iframe_text,validation: '^(http[s]?:\\/\\/(www\\.)?){1}([0-9A-Za-z-\\.@:%_\+~#=]+)+((\\.[a-zA-Z]{2,3})+)(/(.)*)?(\\?(.)*)?'}
								]
							}
						]
					},
					transform: {
						'<center><iframe src="{SRC}" target="_parent" width="100%" height="480" scrolling="auto" frameborder="0"></iframe></center>':'[onox=iframe]{SRC}[/onox]'
					}
				},
				video: {
					title: CURLANG.video,
					hotkey: 'ctrl+y',
					buttonHTML: '<span class="fonticon ve-tlb-video1">\uE92a</span>',
					modal: {
						title: CURLANG.video,
						width: "450px",
						tabs: [
							{
								title: CURLANG.video,
								input: [
									{param: "SRC",title:CURLANG.modal_video_text}
								]
							}
						],
						onSubmit: function(cmd,opt,queryState) {
							var url = this.$modal.find('input[name="SRC"]').val();
							if (url) {
								url = url.replace(/^\s+/,"").replace(/\s+$/,"");
							}
							var a;
							if (url.indexOf("youtu.be")!=-1) {
								a = url.match(/^http[s]*:\/\/youtu\.be\/([a-z0-9_-]+)/i);
							}else{
								a = url.match(/^http[s]*:\/\/www\.youtube\.com\/watch\?.*?v=([a-z0-9_-]+)/i);
							}
							if (a && a.length==2) {
								var code = a[1];
								this.insertAtCursor(this.getCodeByCommand(cmd,{src:code}));
							}
							this.closeModal();
							this.updateUI();
							return false;
						}
					},
					transform: {
						'<center><iframe src="http://www.youtube.com/embed/{SRC}" width="640" height="480" frameborder="0"></iframe></center><br/><b>Enlace:</b> <a href="http://www.youtube.com/watch?v={SRC}" id="url_str" target="_blank" allowfullscreen>http://www.youtube.com/watch?v={SRC}</a>':'[video]{SRC}[/video]'
					}
				},
				goear: {
					title: CURLANG.goear,
					buttonHTML: '<span class="fonticon ve-tlb-mp31">\uE97a</span>',
					modal: {
						title: CURLANG.goear,
						width: "450px",
						tabs: [
							{
								title: CURLANG.video,
								input: [
									{param: "SRC", title: CURLANG.modal_goear_text}
								]
							},
						],
						onSubmit: function(cmd,opt,queryState) {
							var url = this.$modal.find('input[name="SRC"]').val();
							if (url) {
								url = url.replace(/^\s+/,"").replace(/\s+$/,"");
							}
							var a = url.match(/^http[s]*:\/\/www\.goear\.com\/listen\/([a-z0-9_-]+)/i);
							
							if (a && a.length==2) {
								var code = a[1];
								this.insertAtCursor(this.getCodeByCommand(cmd,{src:code}));
							}
							this.closeModal();
							this.updateUI();
							return false;
						}
					},
					transform: {
						'<iframe src="http://www.goear.com/embed/sound/{SRC}" width="580" height="115" scrolling="no" frameborder="0"></iframe>':'[goear={SRC}]'
					}
				},
				//select options
				fs_verysmall: {
					title: CURLANG.fs_verysmall,
					buttonText: "fs1",
					excmd: 'fontSize',
					exvalue: "1",
					transform: {
						'<span style="font-size: 11px;">{SELTEXT}</span>':'[size=11]{SELTEXT}[/size]',
						'<font size="1">{SELTEXT}</font>':'[size=11]{SELTEXT}[/size]',
						'<h6>{SELTEXT}</h6>':'[h6]{SELTEXT}[/h6]'
					}
				},
				fs_small: {
					title: CURLANG.fs_small,
					buttonText: "fs2",
					excmd: 'fontSize',
					exvalue: "2",
					transform: {
						'<span style="font-size: 15px;">{SELTEXT}</span>':'[size=15]{SELTEXT}[/size]',
						'<font size="2">{SELTEXT}</font>':'[size=15]{SELTEXT}[/size]',
						'<h5>{SELTEXT}</h5>':'[h5]{SELTEXT}[/h5]'
					}
				},
				fs_normal: {
					title: CURLANG.fs_normal,
					buttonText: "fs3",
					excmd: 'fontSize',
					exvalue: "3",
					transform: {
						'<span style="font-size: 17px;">{SELTEXT}</span>':'[size=17]{SELTEXT}[/size]',
						'<font size="3">{SELTEXT}</font>':'[size=17]{SELTEXT}[/size]',
						'<span style="font-size: {SIZE}px;">{SELTEXT}</span>':'[size={SIZE}]{SELTEXT}[/size]',
						'<h4>{SELTEXT}</h4>':'[h4]{SELTEXT}[/h4]'
					}
				},
				fs_big: {
					title: CURLANG.fs_big,
					buttonText: "fs4",
					excmd: 'fontSize',
					exvalue: "4",
					transform: {
						'<span style="font-size: 20px;">{SELTEXT}</span>':'[size=20]{SELTEXT}[/size]',
						'<font size="4">{SELTEXT}</font>':'[size=20]{SELTEXT}[/size]',
						'<h3>{SELTEXT}</h3>':'[h3]{SELTEXT}[/h3]'
					}
				},
				fs_verybig: {
					title: CURLANG.fs_verybig,
					buttonText: "fs5",
					excmd: 'fontSize',
					exvalue: "5",
					transform: {
						'<span style="font-size: 26px;">{SELTEXT}</span>':'[size=26]{SELTEXT}[/size]',
						'<font size="5">{SELTEXT}</font>':'[size=26]{SELTEXT}[/size]',
						'<h2>{SELTEXT}</h2>':'[h2]{SELTEXT}[/h2]'
					}
				},
				fs_verybig2: {
					title: CURLANG.fs_verybig2,
					buttonText: "fs6",
					excmd: 'fontSize',
					exvalue: "6",
					transform: {
						'<span style="font-size: 36px;">{SELTEXT}</span>':'[size=36]{SELTEXT}[/size]',
						'<font size="6">{SELTEXT}</font>':'[size=36]{SELTEXT}[/size]',
						'<h1>{SELTEXT}</h1>':'[h1]{SELTEXT}[/h1]'
					}
				},
				fullscreen: {
					title: CURLANG.fullscreen,
					buttonHTML: '<span class="fonticon ve-tlb-fullscreen1">\uE908</span>',
					cmd: toFullScreen
				},
				removeformat: {
					title: CURLANG.removeFormat,
					buttonHTML: '<span class="fonticon ve-tlb-removeformat1">\uE01b</span>',
					excmd: "removeFormat"
				}
			},
			systr: {
				'<br/>':"\n",
				'<span class="wbbtab">{SELTEXT}</span>': '   {SELTEXT}'
			},
			customRules: {
				td: [["[td]{SELTEXT}[/td]",{seltext: {rgx:false,attr:false,sel:false}}]],
				tr: [["[tr]{SELTEXT}[/tr]",{seltext: {rgx:false,attr:false,sel:false}}]],
				table: [["[table]{SELTEXT}[/table]",{seltext: {rgx:false,attr:false,sel:false}}]]
			},
			smileList: [
{title: ":|", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_017">', bbcode:":|"},
{title: "^_^", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_014">', bbcode:"^_^"},
{title: ":'(", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_036">', bbcode:":¹("},
{title: ":$", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_001">', bbcode:":$"},
{title: ":)", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_002">', bbcode:":)"},
{title: ":(", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_044">', bbcode:":("},
{title: ":S", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_033">', bbcode:":S"},
{title: ":D", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_005">', bbcode:":D"},
{title: "(Y)", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_057">', bbcode:"(Y)"},
{title: ":P", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_012">', bbcode:":P"},
{title: ":*", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_022">', bbcode:":*"},
{title: ":O", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_060">', bbcode:":O"},
{title: "<3", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_049">', bbcode:"<3"},
{title: "(6)", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_011">', bbcode:"(6)"},
{title: "(3)", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_045">', bbcode:"(3)"},
{title: ":3", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_041">', bbcode:":3"},
{title: ":X", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_043">', bbcode:":X"},
{title: ">o", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_026">', bbcode:">o"},
{title: ";)", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_004">', bbcode:";)"},
{title: "8)", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_015">', bbcode:"8)"},
{title: "o)", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_010">', bbcode:"o)"},
{title: "<)", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_025">', bbcode:"<)"},
{title: ":V", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_035">', bbcode:":V"},
{title: "o.O", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_040">', bbcode:"o.O"},
{title: ":love:", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_053">', bbcode:":love:"},
{title: "xD", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_009">', bbcode:"xD"},
{title: ":crak:", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_046">', bbcode:":crak:"},
{title: "*o*", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_006">', bbcode:"*o*"},
{title: "<--<", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_050">', bbcode:"<--<"},
{title: "-_-", img: '<img src="{themePrefix}{themeName}/img/emo_nimB.png" class="emo_020">', bbcode:"-_-"},
// ADICIONALES
{title: ":nb00:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-00">', bbcode:":nb00:"},
{title: ":nb01:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-01">', bbcode:":nb01:"},
{title: ":nb02:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-02">', bbcode:":nb02:"},
{title: ":nb03:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-03">', bbcode:":nb03:"},
{title: ":nb04:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-04">', bbcode:":nb04:"},
{title: ":nb05:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-05">', bbcode:":nb05:"},
{title: ":nb06:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-06">', bbcode:":nb06:"},
{title: ":nb07:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-07">', bbcode:":nb07:"},
{title: ":nb08:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-08">', bbcode:":nb08:"},
{title: ":nb09:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-09">', bbcode:":nb09:"},
{title: ":nb10:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-10">', bbcode:":nb10:"},
{title: ":nb11:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-11">', bbcode:":nb11:"},
{title: ":nb12:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-12">', bbcode:":nb12:"},
{title: ":nb13:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-13">', bbcode:":nb13:"},
{title: ":nb14:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-14">', bbcode:":nb14:"},
{title: ":nb15:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-15">', bbcode:":nb15:"},
{title: ":nb16:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-16">', bbcode:":nb16:"},
{title: ":nb17:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-17">', bbcode:":nb17:"},
{title: ":nb18:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-18">', bbcode:":nb18:"},
{title: ":nb19:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-19">', bbcode:":nb19:"},
{title: ":nb20:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-20">', bbcode:":nb20:"},
{title: ":nb21:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-21">', bbcode:":nb21:"},
{title: ":nb22:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-22">', bbcode:":nb22:"},
{title: ":nb23:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-23">', bbcode:":nb23:"},
{title: ":nb24:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-24">', bbcode:":nb24:"},
{title: ":nb25:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-25">', bbcode:":nb25:"},
{title: ":nb26:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-26">', bbcode:":nb26:"},
{title: ":nb27:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-27">', bbcode:":nb27:"},
{title: ":nb28:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-28">', bbcode:":nb28:"},
{title: ":nb29:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-29">', bbcode:":nb29:"},
{title: ":nb30:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-30">', bbcode:":nb30:"},
{title: ":nb31:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-31">', bbcode:":nb31:"},
{title: ":nb32:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-32">', bbcode:":nb32:"},
{title: ":nb33:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-33">', bbcode:":nb33:"},
{title: ":nb34:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-34">', bbcode:":nb34:"},
{title: ":nb35:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-35">', bbcode:":nb35:"},
{title: ":nb36:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-36">', bbcode:":nb36:"},
{title: ":nb37:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-37">', bbcode:":nb37:"},
{title: ":nb38:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-38">', bbcode:":nb38:"},
{title: ":nb39:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-39">', bbcode:":nb39:"},
{title: ":nb40:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-40">', bbcode:":nb40:"},
{title: ":nb41:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-41">', bbcode:":nb41:"},
{title: ":nb42:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-42">', bbcode:":nb42:"},
{title: ":nb43:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-43">', bbcode:":nb43:"},
{title: ":nb44:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-44">', bbcode:":nb44:"},
{title: ":nb45:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-45">', bbcode:":nb45:"},
{title: ":nb46:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-46">', bbcode:":nb46:"},
{title: ":nb47:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-47">', bbcode:":nb47:"},
{title: ":nb48:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-48">', bbcode:":nb48:"},
{title: ":nb49:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-49">', bbcode:":nb49:"},
{title: ":nb50:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-50">', bbcode:":nb50:"},
{title: ":nb51:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-51">', bbcode:":nb51:"},
{title: ":nb52:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-52">', bbcode:":nb52:"},
{title: ":nb53:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-53">', bbcode:":nb53:"},
{title: ":nb54:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-54">', bbcode:":nb54:"},
{title: ":nb55:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-55">', bbcode:":nb55:"},
{title: ":nb56:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-56">', bbcode:":nb56:"},
{title: ":nb57:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-57">', bbcode:":nb57:"},
{title: ":nb58:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-58">', bbcode:":nb58:"},
{title: ":nb59:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-59">', bbcode:":nb59:"},
{title: ":nb60:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-60">', bbcode:":nb60:"},
{title: ":nb61:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-61">', bbcode:":nb61:"},
{title: ":nb62:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-62">', bbcode:":nb62:"},
{title: ":nb63:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-63">', bbcode:":nb63:"},
{title: ":nb64:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-64">', bbcode:":nb64:"},
{title: ":nb65:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-65">', bbcode:":nb65:"},
{title: ":nb66:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-66">', bbcode:":nb66:"},
{title: ":nb67:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-67">', bbcode:":nb67:"},
{title: ":nb68:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-68">', bbcode:":nb68:"},
{title: ":nb69:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-69">', bbcode:":nb69:"},
{title: ":nb70:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-70">', bbcode:":nb70:"},
{title: ":nb71:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-71">', bbcode:":nb71:"},
{title: ":nb72:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-72">', bbcode:":nb72:"},
{title: ":nb73:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-73">', bbcode:":nb73:"},
{title: ":nb74:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-74">', bbcode:":nb74:"},
{title: ":nb75:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-75">', bbcode:":nb75:"},
{title: ":nb76:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-76">', bbcode:":nb76:"},
{title: ":nb77:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-77">', bbcode:":nb77:"},
{title: ":nb78:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-78">', bbcode:":nb78:"},
{title: ":nb79:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-79">', bbcode:":nb79:"},
{title: ":nb80:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-80">', bbcode:":nb80:"},
{title: ":nb81:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-81">', bbcode:":nb81:"},
{title: ":nb82:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-82">', bbcode:":nb82:"},
{title: ":nb83:", img: '<img src="{themePrefix}{themeName}/img/einet_nimB.png" class="nb-83">', bbcode:":nb83:"},
			],
			attrWrap: ['src','color','href'] //use becouse FF and IE change values for this attr, modify [attr] to _[attr]
		}
		
		//FIX for Opera. Wait while iframe loaded
		this.inited=this.options.onlyBBmode;
		
		//init css prefix, if not set
		if (!this.options.themePrefix) {
			$('link').each($.proxy(function(idx, el) {
				var sriptMatch = $(el).get(0).href.match(/(.*\/)(.*)\/wbbtheme\.css.*$/);
				if (sriptMatch !== null) {
					this.options.themeName = sriptMatch[2];
					this.options.themePrefix = sriptMatch[1];
				}
			},this));
		}
		
		//check for preset
		if (typeof(WBBPRESET)!="undefined") {
			if (WBBPRESET.allButtons) {
				//clear transform
				$.each(WBBPRESET.allButtons,$.proxy(function(k,v) {
					if (v.transform && this.options.allButtons[k]) {
						delete this.options.allButtons[k].transform;
					}
				},this));
			}
			$.extend(true,this.options,WBBPRESET);
		} 
		
		if (settings && settings.allButtons) {
			$.each(settings.allButtons,$.proxy(function(k,v) {
				if (v.transform && this.options.allButtons[k]) {
					delete this.options.allButtons[k].transform;
				}
			},this));
		}
		$.extend(true,this.options,settings);
		this.init();
	}
	
	$.wysibb.prototype = {
		lastid : 1,
		init:	function() {
			$.log("Init",this);
			//check for mobile
			this.isMobile = function(a) {(/android|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|meego.+mobile|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a))}(navigator.userAgent||navigator.vendor||window.opera);
			
			//use bbmode on mobile devices
			//this.isMobile = true; //TEMP
			if (this.options.onlyBBmode===true) {this.options.bbmode=true;}
			//create array of controls, for queryState
			this.controllers = [];
			
			//convert button string to array
			this.options.buttons = this.options.buttons.toLowerCase();
			this.options.buttons = this.options.buttons.split(",");
			
			//init system transforms
			this.options.allButtons["_systr"] = {};
			this.options.allButtons["_systr"]["transform"]= this.options.systr;
			
			this.smileFind();
			this.initTransforms();
			this.build();
			this.initModal();
			if (this.options.hotkeys===true && !this.isMobile) {
				this.initHotkeys();
			}
			
			//sort smiles
			if (this.options.smileList && this.options.smileList.length>0) {
				this.options.smileList.sort(function(a,b) {
					return (b.bbcode.length-a.bbcode.length);
				})
			}
			
			this.$txtArea.parents("form").bind("submit",$.proxy(function() {
				this.sync();
				return true;
			},this)); 
			
			
			//phpbb2
			this.$txtArea.parents("form").find("input[id*='preview'],input[id*='submit'],input[class*='preview'],input[class*='submit'],input[name*='preview'],input[name*='submit']").bind("mousedown",$.proxy(function() {
				this.sync();
				setTimeout($.proxy(function() {
					if (this.options.bbmode===false) {
						this.$txtArea.removeAttr("wbbsync").val("");
					}
				},this),1000);
			},this));
			//end phpbb2
			
			if (this.options.initCallback) {
				this.options.initCallback.call(this);
			}
			
			$.log(this);
			
		},
		initTransforms: function() {
			$.log("Create rules for transform HTML=>BB");
			var o = this.options;
			//need to check for active buttons
			if (!o.rules) {o.rules={};}
			if (!o.groups) {o.groups={};} //use for groupkey, For example: justifyleft,justifyright,justifycenter. It is must replace each other.
			var  btnlist = o.buttons.slice();
			
			//add system transform
			btnlist.push("_systr");
			for (var bidx=0; bidx<btnlist.length; bidx++) {
				var ob = o.allButtons[btnlist[bidx]];
				if (!ob ) {continue;}
				ob.en=true;
				
				//check for simplebbcode
				if (ob.simplebbcode && $.isArray(ob.simplebbcode) && ob.simplebbcode.length==2) {
					ob.bbcode = ob.html = ob.simplebbcode[0]+"{SELTEXT}"+ob.simplebbcode[1];
					if (ob.transform) delete ob.transform;
					if (ob.modal)  delete ob.modal;
				}
				
				//add transforms to option list
				if (ob.type=="select" && typeof(ob.options)=="string") {
					var olist = ob.options.split(",");
					$.each(olist,function(i,op) {
						if ($.inArray(op,btnlist)==-1) {
							btnlist.push(op);
						}
					});
				}
				if (ob.transform && ob.skipRules!==true) {
					var obtr = $.extend({},ob.transform);
					
					for (var bhtml in obtr) {
						var orightml = bhtml;
						var bbcode = obtr[bhtml];
						
						//create root selector for isContain bbmode
						if (!ob.bbSelector) {ob.bbSelector=[];}
						if ($.inArray(bbcode,ob.bbSelector)==-1) {
							ob.bbSelector.push(bbcode);
						}
						if (this.options.onlyBBmode===false) {
						
							//wrap attributes 
							bhtml = this.wrapAttrs(bhtml);
							

							var $bel = $(document.createElement('DIV')).append($(this.elFromString(bhtml,document)));
							var rootSelector = this.filterByNode($bel.children());
							
							
							//check if current rootSelector is exist, create unique selector for each transform (1.2.2)
							if (rootSelector=="div" || typeof(o.rules[rootSelector])!="undefined") {
								//create unique selector
								$.log("create unique selector: "+rootSelector);
								this.setUID($bel.children());
								rootSelector = this.filterByNode($bel.children());
								$.log("New rootSelector: "+rootSelector);
								//replace transform with unique selector
								var nhtml2 = $bel.html();
								nhtml2 = this.unwrapAttrs(nhtml2);
								var obhtml = this.unwrapAttrs(bhtml);
								
								
								ob.transform[nhtml2]=bbcode;
								delete ob.transform[obhtml];
								
								bhtml=nhtml2;
								orightml = nhtml2;
							}
							
							//create root selector for isContain
							if (!ob.excmd) {
								if (!ob.rootSelector) {ob.rootSelector=[];}
								ob.rootSelector.push(rootSelector);
							}
							
							//check for rules on this rootSeletor
							if (typeof(o.rules[rootSelector])=="undefined") {
								o.rules[rootSelector]=[];
							}
							var crules={};
							
							if (bhtml.match(/\{\S+?\}/)) {
								$bel.find('*').each($.proxy(function(idx,el) {
									//check attributes
									
									var attributes = this.getAttributeList(el);
									$.each(attributes,$.proxy(function(i, item) {
										var attr = $(el).attr(item);
										if (item.substr(0,1)=='_') {
											item = item.substr(1);
										}
										
										var r = attr.match(/\{\S+?\}/g);
										if (r) {
											for (var a=0; a<r.length; a++) {
												var rname = r[a].substr(1,r[a].length-2);
													rname = rname.replace(this.getValidationRGX(rname),"");
												var p = this.relFilterByNode(el,rootSelector);
												var regRepl = (attr!=r[a]) ? this.getRegexpReplace(attr,r[a]):false;
												crules[rname.toLowerCase()]={sel:(p) ? $.trim(p):false,attr:item,rgx:regRepl}
											}
										}
									},this));
									
									//check for text
									var  sl=[];
									if (!$(el).is("iframe")) {
										$(el).contents().filter(function() {return this.nodeType===3}).each($.proxy(function(i,rel) {
											var txt = rel.textContent || rel.data;
											if (typeof(txt)=="undefined") {return true;}
											var r = txt.match(/\{\S+?\}/g)
											if (r) {
												for (var a=0; a<r.length; a++) {	
													var rname = r[a].substr(1,r[a].length-2);
														rname = rname.replace(this.getValidationRGX(rname),"");
													var p = this.relFilterByNode(el,rootSelector);
													var regRepl = (txt!=r[a]) ? this.getRegexpReplace(txt,r[a]):false;
													var sel = (p) ? $.trim(p):false;
													if($.inArray(sel,sl)>-1 || $(rel).parent().contents().length > 1) {
														//has dublicate and not one children, need wrap
														var nel = $("<span>").html("{"+rname+"}");
														this.setUID(nel,"wbb");
														var start = (txt.indexOf(rname)+rname.length)+1;
														var after_txt = txt.substr(start,txt.length-start);
														//create wrap element
														rel.data = txt.substr(0,txt.indexOf(rname)-1);
														$(rel).after(this.elFromString(after_txt,document)).after(nel);
														
														sel=((sel) ? sel+" ":"")+this.filterByNode(nel);
														regRepl=false;
													}
													crules[rname.toLowerCase()]={sel:sel,attr:false,rgx:regRepl}
													sl[sl.length]=sel;
												}
											}
										},this));
									}
									sl=null;
									
									
								},this));
								
								var nbhtml = $bel.html();
								//UnWrap attributes 
								nbhtml = this.unwrapAttrs(nbhtml);
								if (orightml!=nbhtml) {
									//if we modify html, replace it
									delete ob.transform[orightml];
									ob.transform[nbhtml]=bbcode;
									bhtml=nbhtml;
								}
								
							}
							o.rules[rootSelector].push([bbcode,crules]);
							
							//check for onlyClearText
							if (ob.onlyClearText===true) {
								if (!this.cleartext) {this.cleartext={};}
								this.cleartext[rootSelector]=btnlist[bidx];
							}
							
							//check for groupkey
							if (ob.groupkey) {
								if (!o.groups[ob.groupkey]) {o.groups[ob.groupkey]=[]}
								o.groups[ob.groupkey].push(rootSelector);
							}
						}
					}
					
					//sort rootSelector
					if (ob.rootSelector) {
						this.sortArray(ob.rootSelector,-1);
					}
						
					var htmll = $.map(ob.transform,function(bb,html) {return html}).sort(function(a,b) {
							return ((b[0] || "").length-(a[0] || "").length)
					});
					ob.bbcode = ob.transform[htmll[0]];
					ob.html = htmll[0];
				}
			};
			
			this.options.btnlist=btnlist; //use for transforms, becouse select elements not present in buttons
			
			//add custom rules, for table,tr,td and other
			$.extend(o.rules,this.options.customRules);
		
			//smile rules
			o.srules={};
			if (this.options.smileList) {
				$.each(o.smileList,$.proxy(function(i,sm) {
					var $sm = $(this.strf(sm.img,o));
					var f = this.filterByNode($sm);
					o.srules[f]=[sm.bbcode,sm.img];
				},this));
			}
			
			//sort transforms by bbcode length desc
			for (var rootsel in o.rules) {
				this.options.rules[rootsel].sort(function(a,b) {
					return (b[0].length-a[0].length)
				});
			}
			
			//create rootsel list
			this.rsellist = [];
			for (var rootsel in this.options.rules) {
				this.rsellist.push(rootsel);
			}
			this.sortArray(this.rsellist,-1);
		},
		
		//BUILD
		build: function() {
			$.log("Build editor");
			
			//this.$editor = $('<div class="wysibb">');
			this.$editor = $('<div>').addClass("wysibb");
			
			if (this.isMobile) {
				this.$editor.addClass("wysibb-mobile");
			}
			
			//set direction if defined
			if (this.options.direction) {this.$editor.css("direction",this.options.direction)}
			
			this.$editor.insertAfter(this.txtArea).append(this.txtArea);
			
			this.startHeight = this.$txtArea.outerHeight();
			this.$txtArea.addClass("wysibb-texarea");
			this.buildToolbar();
			//Build iframe if needed
			this.$txtArea.wrap('<div class="wysibb-text">');
			
			if (this.options.onlyBBmode===false) {
				var width = this.$txtArea.outerWidth();
				var height = this.options.minheight || this.$txtArea.outerHeight();
				var maxheight = this.options.resize_maxheight;
				var mheight = (this.options.autoresize===true) ? this.options.resize_maxheight:height;
				this.$body = $(this.strf('<div class="wysibb-text-editor" style="width:{width}px;max-height:{maxheight}px;min-height:{height}px;"></div>',{width:width,maxheight:mheight,height:height})).insertAfter(this.$txtArea);
				this.body = this.$body[0];
				this.$txtArea.hide();
				
				if (height>32) {
					this.$toolbar.css("max-height",height);
				}
				
				$.log("WysiBB loaded");
				
				this.$body.addClass("wysibb-body").addClass(this.options.bodyClass);
				
				//set direction if defined
				if (this.options.direction) {this.$body.css("direction",this.options.direction)}
				
				
				if ('contentEditable' in this.body) {
					this.body.contentEditable=true;
					try{
						document.execCommand('StyleWithCSS', false, false);
						//document.designMode = "on";
						this.$body.append("<span></span>");
					}catch(e) {}
				}else{
					//use onlybbmode
					this.options.onlyBBmode=this.options.bbmode=true;
				}
				
				//check for exist content in textarea
				if (this.txtArea.value.length>0) {
					this.txtAreaInitContent();
				}
				
				
				//clear html on paste from external editors
				this.$body.bind('keydown', $.proxy(function(e) {
					if ((e.which == 86 && (e.ctrlKey==true || e.metaKey==true)) || (e.which == 45 && (e.shiftKey==true || e.metaKey==true))) {
						if (!this.$pasteBlock) {
							this.saveRange();
							this.$pasteBlock = $(this.elFromString('<div style="opacity:0;" contenteditable="true"><br></div>'));
							
							this.$pasteBlock.appendTo(this.body);
								setTimeout($.proxy(function() {
									this.clearPaste(this.$pasteBlock);
									var rdata = '<span>'+this.$pasteBlock.html()+'</span>';
									this.$body.attr("contentEditable","true");
									this.$pasteBlock.blur().remove();
									this.body.focus();

									if (this.cleartext) {
										$.log("Check if paste to clearText Block");
										if (this.isInClearTextBlock()) {
											rdata = this.toBB(rdata).replace(/\n/g,"<br/>").replace(/\s{3}/g,'<span class="wbbtab"></span>');
										}
									}
									rdata = rdata.replace(/\t/g,'<span class="wbbtab"></span>');
									this.selectRange(this.lastRange);
									this.insertAtCursor(rdata,false);
									this.lastRange=false;
									this.$pasteBlock=false;
								}
								,this), 1);
							this.selectNode(this.$pasteBlock[0]);
						}
						return true;
					}
				},this));
				
				//insert BR on press enter
				this.$body.bind('keydown',$.proxy(function(e) {
					if (e.which == 13) {
						var isLi = this.isContain(this.getSelectNode(),'li');
						if (!isLi) {
							if (e.preventDefault) {e.preventDefault();}
							this.checkForLastBR(this.getSelectNode());
							this.insertAtCursor('<br/>',false);
						}
					}
				},this));
				
				//tabInsert
				if (this.options.tabInsert===true) {
					this.$body.bind('keydown', $.proxy(this.pressTab,this));
				}
				
				//add event listeners
				this.$body.bind('mouseup keyup',$.proxy(this.updateUI,this));
				this.$body.bind('mousedown',$.proxy(function(e) {this.clearLastRange();this.checkForLastBR(e.target)},this));

				//trace Textarea
				if (this.options.traceTextarea===true) {
					$(document).bind("mousedown",$.proxy(this.traceTextareaEvent,this));
					this.$txtArea.val("");
				}

				//attach hotkeys
				if (this.options.hotkeys===true) {
					this.$body.bind('keydown',$.proxy(this.presskey,this));
				}
				
				//smileConversion
				if (this.options.smileConversion===true) {
					this.$body.bind('keyup',$.proxy(this.smileConversion,this));
				}

				this.inited=true;

				//create resize lines
				if (this.options.autoresize===true) {
					this.$bresize = $(this.elFromString('<div class="bottom-resize-line"></div>')).appendTo(this.$editor)
						.wdrag({
							scope:this,
							axisY: true,
							height: height
						});
				}
				
				this.imgListeners();
			}
			
			//add event listeners to textarea 
			this.$txtArea.bind('mouseup keyup',$.proxy(function() {
				clearTimeout(this.uitimer);
				this.uitimer = setTimeout($.proxy(this.updateUI,this),100);
			},this));
			
			//attach hotkeys
			if (this.options.hotkeys===true) {
				$(document).bind('keydown',$.proxy(this.presskey,this));
			}
		},
		buildToolbar: function() {
			if (this.options.toolbar === false) {return false;}
			this.$toolbar = $('<div>').addClass("wysibb-toolbar").prependTo(this.$editor);
			
			var $btnContainer;
			$.each(this.options.buttons,$.proxy(function(i,bn) {
				var opt = this.options.allButtons[bn];
				if (i==0 || bn=="|" || bn=="-") {
					if (bn=="-") {
						this.$toolbar.append("<div>");
					}
					$btnContainer = $('<div class="wysibb-toolbar-container">').appendTo(this.$toolbar);
				}
				if (opt) {
					if (opt.type=="colorpicker") {
						this.buildColorpicker($btnContainer,bn,opt);
					}else if (opt.type=="table") {
						this.buildTablepicker($btnContainer,bn,opt);
					}else if (opt.type=="select") {
						this.buildSelect($btnContainer,bn,opt);
					}else if (opt.type=="smilebox") {
						this.buildSmilebox($btnContainer,bn,opt);
					}else{
						this.buildButton($btnContainer,bn,opt);
					}
				}
			},this));
			
			//fix for hide tooltip on quick mouse over
			this.$toolbar.find(".btn-tooltip").hover(function () {$(this).parent().css("overflow","hidden")},function() {$(this).parent().css("overflow","visible")});
			
			//build bbcode switch button
			var $bbsw = $(document.createElement('div')).addClass("wysibb-toolbar-container modeSwitch").html('<div class="wysibb-toolbar-btn mswitch" unselectable="on"><span class="btn-inner modesw" unselectable="on">[/]</span><span class="btn-tooltip">View code</span></div>').appendTo(this.$toolbar);
			if (this.options.bbmode==true) {$bbsw.children(".wysibb-toolbar-btn").addClass("on");}
			if (this.options.onlyBBmode===false) {
				$bbsw.children(".wysibb-toolbar-btn").click($.proxy(function(e) {
					$(e.currentTarget).toggleClass("on");
					this.modeSwitch();
				},this));
			}
		},
		buildButton: function(container,bn,opt) {
			if (typeof(container)!="object") {
				container = this.$toolbar;
			}
			var btnHTML = (opt.buttonHTML) ? $(this.strf(opt.buttonHTML,this.options)).addClass("btn-inner") : this.strf('<span class="btn-inner btn-text">{text}</span>',{text:opt.buttonText.replace(/</g,"&lt;")});
			var hotkey = (this.options.hotkeys===true && this.options.showHotkeys===true && opt.hotkey) ? (' <span class="tthotkey">['+opt.hotkey+']</span>'):""
			var $btn = $('<div class="wysibb-toolbar-btn wbb-'+bn+'">').appendTo(container).append(btnHTML).append(this.strf('<span class="btn-tooltip">{title}{hotkey}</span>',{title:opt.title,hotkey:hotkey}));
			
			//attach events
			this.controllers.push($btn);
			$btn.bind('queryState',$.proxy(function(e) {
				(this.queryState(bn)) ? $(e.currentTarget).addClass("on"):$(e.currentTarget).removeClass("on");
			},this));
			$btn.mousedown($.proxy(function(e) {
				e.preventDefault();
				this.execCommand(bn,opt.exvalue || false);
				$(e.currentTarget).trigger('queryState');
			},this));
		},
		buildColorpicker: function(container,bn,opt) {
			var $btn = $('<div class="wysibb-toolbar-btn wbb-dropdown wbb-cp">').appendTo(container).append('<div class="ve-tlb-colorpick"><span class="fonticon">\uE02b</span><span class="cp-line"></span></div><ins class="fonticon ar">\uE842</ins>').append(this.strf('<span class="btn-tooltip">{title}<ins/></span>',{title:opt.title}));
			var $cpline = $btn.find(".cp-line");
			
			var $dropblock = $('<div class="wbb-list">').appendTo($btn); 
			$dropblock.append('<div class="nc">'+CURLANG.auto+'</div>');
			var colorlist = (opt.colors) ? opt.colors.split(","):[]; 
			for (var j=0; j<colorlist.length; j++) {
				colorlist[j] = $.trim(colorlist[j]);
				if (colorlist[j]=="-") { 
					//insert padding
					$dropblock.append('<span class="pl"></span>');
				}else{ 
					$dropblock.append(this.strf('<div class="sc" style="background:{color}" title="{color}"></div>',{color:colorlist[j]}));
				}
			}
			var basecolor = $(document.body).css("color");
			//attach events
			this.controllers.push($btn);
			$btn.bind('queryState',$.proxy(function(e) {
				//queryState
				$cpline.css("background-color",basecolor);
				var r = this.queryState(bn,true);
				if (r) {
					$cpline.css("background-color",(this.options.bbmode) ? r.color:r);
					$btn.find(".ve-tlb-colorpick span.fonticon").css("color",(this.options.bbmode) ? r.color:r);
				}
			},this));
			$btn.mousedown($.proxy(function(e) {
				e.preventDefault();
				this.dropdownclick(".wbb-cp",".wbb-list",e);
			},this));
			$btn.find(".sc").mousedown($.proxy(function(e) {
				e.preventDefault();
				this.selectLastRange();
				var c = $(e.currentTarget).attr("title");
				this.execCommand(bn,c);
				$btn.trigger('queryState');
			},this));
			$btn.find(".nc").mousedown($.proxy(function(e) {
				e.preventDefault();
				this.selectLastRange();
				this.execCommand(bn,basecolor);
				$btn.trigger('queryState');
			},this));
			$btn.mousedown(function(e) { 
				if (e.preventDefault) e.preventDefault();
			});
		},
		buildTablepicker: function(container,bn,opt) {
			var $btn = $('<div class="wysibb-toolbar-btn wbb-dropdown wbb-tbl">').appendTo(container).append('<span class="btn-inner fonticon ve-tlb-table1">\uE94a</span><ins class="fonticon ar">\uE842</ins>').append(this.strf('<span class="btn-tooltip">{title}<ins/></span>',{title:opt.title}));
			
			var $listblock = $('<div class="wbb-list">').appendTo($btn);
			var $dropblock = $('<div>').css({"position":"relative","box-sizing":"border-box"}).appendTo($listblock);
			var rows = opt.rows || 10;
			var cols = opt.cols || 10;
			var allcount = rows*cols;
			$dropblock.css("height",(rows*opt.cellwidth+2)+"px");
			for (var j=1; j<=cols; j++) {
				for (var h=1; h<=rows; h++) {
					var html = '<div class="tbl-sel" style="width:'+(j*100/cols)+'%;height:'+(h*100/rows)+'%;z-index:'+(--allcount)+'" title="'+h+','+j+'"></div>';
					$dropblock.append(html);
				}
			}
			$btn.find(".tbl-sel").mousedown($.proxy(function(e) {
				e.preventDefault();
				var t = $(e.currentTarget).attr("title");
				var rc = t.split(",");
				var code = (this.options.bbmode) ? '[table]':'<table class="wbb-table">';
				for (var i=1; i<=rc[0]; i++) {
					code += (this.options.bbmode) ? '[tr]\n':'<tr>';
					for (var j=1; j<=rc[1]; j++) {
						code += (this.options.bbmode) ? '[td][/td]\n':'<td><br></td>';
					}
					code += (this.options.bbmode) ? '[/tr]\n':'</tr>';
				}
				code += (this.options.bbmode) ? '[/table]':'</table>';
				this.insertAtCursor(code);
			},this));
			//this.debug("END Attach event on: tbl-sel");
			$btn.mousedown($.proxy(function(e) {
				e.preventDefault();
				this.dropdownclick(".wbb-tbl",".wbb-list",e);
			},this));
			
		},
		buildSelect: function(container,bn,opt) {
			var $btn = $('<div class="wysibb-toolbar-btn wbb-select wbb-'+bn+'">').appendTo(container).append(this.strf('<span class="val">{title}</span><ins class="fonticon sar">\uE00b</ins>',opt)).append(this.strf('<span class="btn-tooltip">{title}<ins/></span>',{title:opt.title}));  
			var $sblock = $('<div class="wbb-list">').appendTo($btn);
			var $sval = $btn.find("span.val");
			
			var olist = ($.isArray(opt.options)) ? opt.options:opt.options.split(",");
			var $selectbox = (this.isMobile) ? $("<select>").addClass("wbb-selectbox"):"";
			for (var i=0; i<olist.length; i++) {
				var oname = olist[i];
				if (typeof(oname)=="string") {
					var option = this.options.allButtons[oname];
					if (option) {
						if (option.html) {
							$('<span>').addClass("option").attr("oid",oname).attr("cmdvalue",option.exvalue).appendTo($sblock).append(this.strf(option.html,{seltext:option.title}));
						}else{
							$sblock.append(this.strf('<span class="option" oid="'+oname+'" cmdvalue="'+option.exvalue+'">{title}</span>',option));
						}
						
						//SelectBox for mobile devices
						if (this.isMobile) {
							$selectbox.append($('<option>').attr("oid",oname).attr("cmdvalue",option.exvalue).append(option.title));
						}
					}
				}else{
					//build option list from array
					var params = {
						seltext: oname.title
					}
					params[opt.valueBBname]=oname.exvalue;
					$('<span>').addClass("option").attr("oid",bn).attr("cmdvalue",oname.exvalue).appendTo($sblock).append(this.strf(opt.html,params));
					
					if (this.isMobile) {$selectbox.append($('<option>').attr("oid",bn).attr("cmdvalue",oname.exvalue).append(oname.exvalue))}
				}
			}
			if (this.isMobile) {
				$selectbox.appendTo(container);
				this.controllers.push($selectbox);
				
				$selectbox.bind('queryState',$.proxy(function(e) {
					//queryState
					$selectbox.find("option").each($.proxy(function(i,el){
						var $el = $(el);
						var r = this.queryState($el.attr("oid"),true);
						var cmdvalue = $el.attr("cmdvalue");
						if ((cmdvalue && r==$el.attr("cmdvalue")) || (!cmdvalue && r)) {
							$el.prop("selected",true);
							return false;
						}
					},this));
				},this));
				
				$selectbox.change($.proxy(function(e) {
					e.preventDefault();
					var $o =  $(e.currentTarget).find(":selected");
					var oid = $o.attr("oid");
					var cmdvalue = $o.attr("cmdvalue");
					var opt = this.options.allButtons[oid];
					this.execCommand(oid,opt.exvalue || cmdvalue || false);
					$(e.currentTarget).trigger('queryState');
				},this));
				
			}
			this.controllers.push($btn);
			$btn.bind('queryState',$.proxy(function(e) {
				//queryState
				$sval.text(opt.title);
				$btn.find(".option.selected").removeClass("selected");
				$btn.find(".option").each($.proxy(function(i,el){
					var $el = $(el);
					var r = this.queryState($el.attr("oid"),true);
					var cmdvalue = $el.attr("cmdvalue");
					if ((cmdvalue && r==$el.attr("cmdvalue")) || (!cmdvalue && r)) {
						$sval.text($el.text());
						$el.addClass("selected");
						return false;
					}
				},this));
			},this));
			$btn.mousedown($.proxy(function(e) {
				e.preventDefault();
				this.dropdownclick(".wbb-select",".wbb-list",e);
			},this));
			$btn.find(".option").mousedown($.proxy(function(e) {
				e.preventDefault();
				var oid = $(e.currentTarget).attr("oid");
				var cmdvalue = $(e.currentTarget).attr("cmdvalue");
				var opt = this.options.allButtons[oid];
				this.execCommand(oid,opt.exvalue || cmdvalue || false);
				$(e.currentTarget).trigger('queryState');
			},this));
		},
		buildSmilebox: function(container,bn,opt) {
			if (this.options.smileList && this.options.smileList.length>0) {
				var $btnHTML = $(this.strf(opt.buttonHTML,opt)).addClass("btn-inner");
				var $btn = $('<div class="wysibb-toolbar-btn wbb-smilebox wbb-'+bn+'">').appendTo(container).append($btnHTML).append(this.strf('<span class="btn-tooltip">{title}<ins/></span>',{title:opt.title}));  
				var $sblock = $('<div class="wbb-list">').appendTo($btn);
				if ($.isArray(this.options.smileList)) {
					$.each(this.options.smileList,$.proxy(function(i,sm){
					if(i <= CURLANG.icons_limit) {//limite de iconos
					$('<span>').addClass("smile").appendTo($sblock).append($(this.strf(sm.img,this.options)).attr("title",sm.title));
					}
					//
					},this));
				}
				$btn.mousedown($.proxy(function(e) {
					e.preventDefault();
					this.dropdownclick(".wbb-smilebox",".wbb-list",e);
				},this));
				$btn.find('.smile').mousedown($.proxy(function(e) {
					e.preventDefault();
					//this.selectLastRange();
					this.insertAtCursor((this.options.bbmode) ? this.toBB($(e.currentTarget).html()):$($(e.currentTarget).html()));
				},this))
			}
		},
		updateUI: function(e) {
			if (!e || ((e.which>=8 && e.which<=46) || e.which>90 || e.type=="mouseup")) {
				$.each(this.controllers,$.proxy(function(i,$btn) {
					$btn.trigger('queryState');
				},this));
			}
			
			//check for onlyClearText
			this.disNonActiveButtons();
			
		},
		initModal: function() {
			this.$modal=$("#wbbmodal");
			if(this.$modal.length == 0) {
				$.log("Init modal");
				this.$modal = $('<div>').attr("id","wbbmodal").prependTo(document.body)
					.html('<div class="wbbm"><div class="wbbm-title"><span class="wbbm-title-text"></span><span class="wbbclose" title="'+CURLANG.close+'">X</span></div><div class="wbbm-content"></div><div class="wbbm-bottom"><button id="wbbm-submit" class="wbb-button button is-info is-focused">'+CURLANG.save+'</button><button id="wbbm-cancel" class="wbb-cancel-button button is-danger is-focused">'+CURLANG.cancel+'</button><button id="wbbm-remove" class="wbb-remove-button button is-danger is-focused">'+CURLANG.remove+'</button></div></div>').hide();
				
				this.$modal.find('#wbbm-cancel,.wbbclose').click($.proxy(this.closeModal,this));
				this.$modal.bind('click',$.proxy(function(e) {
					if ($(e.target).parents(".wbbm").length == 0) {
						this.closeModal();
					}
				},this));
				
				$(document).bind("keydown",$.proxy(this.escModal,this)); //ESC key close modal
			}
		},
		initHotkeys: function() {
			$.log("initHotkeys");
			this.hotkeys=[];
			var klist = "0123456789       abcdefghijklmnopqrstuvwxyz";
			$.each(this.options.allButtons,$.proxy(function(cmd,opt) {
				if (opt.hotkey) {
					var keys = opt.hotkey.split("+");
 					if (keys && keys.length>=2) {
						var metasum=0;
						var key = keys.pop();
						$.each(keys,function(i,k) {
							switch($.trim(k.toLowerCase())) {
								case "ctrl": {metasum+=1;break;}
								case "shift": {metasum+=4;break;}
								case "alt": {metasum+=7;break;}
							}
						})
						if (metasum>0) {
							if (!this.hotkeys["m"+metasum]) {this.hotkeys["m"+metasum]=[];}
							this.hotkeys["m"+metasum]["k"+(klist.indexOf(key)+48)]=cmd;
						}
					}
				}
			},this))
		},
		presskey: function(e) {
			if (e.ctrlKey==true || e.shiftKey==true || e.altKey==true) {
				var  metasum = ((e.ctrlKey==true) ? 1:0)+((e.shiftKey==true) ? 4:0)+((e.altKey==true) ? 7:0);
				if (this.hotkeys["m"+metasum] && this.hotkeys["m"+metasum]["k"+e.which]) {
					this.execCommand(this.hotkeys["m"+metasum]["k"+e.which],false);
					e.preventDefault();
					return false;
				}
			}
		},
		
		//CODCOMMAND FUNCTIONS
		execCommand: function(command,value) {
			$.log("execCommand: "+command);
			var opt = this.options.allButtons[command];
			if (opt.en!==true) {return false;}
			var queryState = this.queryState(command,value);
			
			//check for onlyClearText
			var skipcmd = this.isInClearTextBlock();
			if (skipcmd && skipcmd!=command) {return;}
			
			
			if (opt.excmd) {
				//use NativeCommand
				if (this.options.bbmode) {
					$.log("Native command in bbmode: "+command);
					if (queryState && opt.subInsert!=true) {
						//remove bbcode
						this.wbbRemoveCallback(command,value);
					}else{
						//insert bbcode
						var v = {};
						if (opt.valueBBname && value) {
							v[opt.valueBBname]=value;
						}
						this.insertAtCursor(this.getBBCodeByCommand(command,v));
					}
				}else{
					this.execNativeCommand(opt.excmd,value || false);
				}
			}else if (!opt.cmd) {
				//wbbCommand
				this.wbbExecCommand.call(this,command,value,queryState);
			}else{
				//user custom command
				opt.cmd.call(this,command,value,queryState);
			}
			this.updateUI();
		},
		queryState: function(command,withvalue) {
			var opt = this.options.allButtons[command];
			if (opt.en!==true) {return false;}
			if (this.options.bbmode) {
				//bbmode
				if (opt.bbSelector) {
					for (var i=0; i<opt.bbSelector.length; i++) {
						var b = this.isBBContain(opt.bbSelector[i]);
						if (b) {
							return this.getParams(b,opt.bbSelector[i],b[1]);
						}
					}
				}
				return false;
			}else{
				if (opt.excmd) {
					//native command
					if (withvalue) {
						try {
							//Firefox fix
							var v = (document.queryCommandValue(opt.excmd)+"").replace(/\'/g,"");
							if (opt.excmd=="foreColor") {
								v = this.rgbToHex(v);
							}
							//return (v==value);
							return v;
						}catch(e) {return false;}
					}else{
						try { //Firefox fix, exception while get queryState for UnorderedList

							if ((opt.excmd=="bold" || opt.excmd=="italic" || opt.excmd=="underline" || opt.excmd=="strikeThrough") && $(this.getSelectNode()).is("img")) { //Fix, when img selected
								return false;
							}else if(opt.excmd=="underline" && $(this.getSelectNode()).closest("a").length > 0) { //fix, when link select
								return false;
							}else {
								return document.queryCommandState(opt.excmd);
							}
						}catch(e) {return false;}
					}
				}else{
					//custom command
					if ($.isArray(opt.rootSelector)) {
						for (var i=0; i<opt.rootSelector.length; i++) {
							var n = this.isContain(this.getSelectNode(),opt.rootSelector[i]);
							if (n) {
								return this.getParams(n,opt.rootSelector[i]);
							}
						}
					}
					return false;
				}
			}
		},
		wbbExecCommand: function(command,value,queryState) { //default command for custom bbcodes
			$.log("wbbExecCommand");
			var opt = this.options.allButtons[command];
			if (opt) {
				if (opt.modal) {
					if ($.isFunction(opt.modal)) {
						//custom modal function
						opt.modal.call(this,command,opt.modal,queryState);
					}else{
						this.showModal.call(this,command,opt.modal,queryState);
					}
				}else{
					if (queryState && opt.subInsert!=true) {
						//remove formatting
						this.wbbRemoveCallback(command);
					}else{
						//insert format
						if (opt.groupkey) {
							var groupsel = this.options.groups[opt.groupkey];
							if (groupsel) {
								var snode = this.getSelectNode();
								$.each(groupsel,$.proxy(function(i,sel) {
									var is = this.isContain(snode,sel);
									if (is) {
										var $sp = $('<span>').html(is.innerHTML)
										var id = this.setUID($sp);
										$(is).replaceWith($sp);
										this.selectNode(this.$editor.find("#"+id)[0]);
										return false;
									}
								},this));
							}
						}
						this.wbbInsertCallback(command,value)
					}
				}
			}
		},
		wbbInsertCallback: function(command,paramobj) {
			if (typeof(paramobj)!="object") {paramobj={}};
			$.log("wbbInsertCallback: "+command);
			var data = this.getCodeByCommand(command,paramobj);
			this.insertAtCursor(data);
			
			if (this.seltextID && data.indexOf(this.seltextID)!=-1) {
				var snode = this.$body.find("#"+this.seltextID)[0];
				this.selectNode(snode);
				$(snode).removeAttr("id");
				this.seltextID=false;
			}
		},
		wbbRemoveCallback: function(command,clear) {
			$.log("wbbRemoveCallback: "+command);
			var opt = this.options.allButtons[command];
			if (this.options.bbmode) {
				//bbmode
				//REMOVE BBCODE
				var pos = this.getCursorPosBB();
				var stextnum=0;
				$.each(opt.bbSelector,$.proxy(function(i,bbcode) {
					var stext = bbcode.match(/\{[\s\S]+?\}/g);
					$.each(stext,function(n,s) {
						if (s.toLowerCase()=="{seltext}") {stextnum=n;return false}
					});
					var a = this.isBBContain(bbcode);
					if (a) {
						this.txtArea.value = this.txtArea.value.substr(0,a[1])+this.txtArea.value.substr(a[1],this.txtArea.value.length-a[1]).replace(a[0][0],(clear===true) ? '':a[0][stextnum+1]);
						this.setCursorPosBB(a[1]);
						return false;
					}
				},this));
			}else{
				var node = this.getSelectNode();
				$.each(opt.rootSelector,$.proxy(function(i,s) {
					var root = this.isContain(node,s);
					if (!root) {return true;}
					var $root = $(root);
					var cs = this.options.rules[s][0][1];
					if ($root.is("span[wbb]") || !$root.is("span,font")) { //remove only blocks
						if (clear===true || (!cs || !cs["seltext"])) {
							this.setCursorByEl($root);
							$root.remove();
						}else{
							if (cs && cs["seltext"] && cs["seltext"]["sel"]) {
								var htmldata = $root.find(cs["seltext"]["sel"]).html();
								if (opt.onlyClearText===true) {
									htmldata = this.getHTML(htmldata,true,true);
									htmldata = htmldata.replace(/\&#123;/g,"{").replace(/\&#125;/g,"}");
								}
								$root.replaceWith(htmldata);
							}else{
								var htmldata = $root.html();
								if (opt.onlyClearText===true) {
									htmldata = this.getHTML(htmldata,true);
									htmldata = htmldata.replace(/\&lt;/g,"<").replace(/\&gt;/g,">").replace(/\&#123;/g,"{").replace(/\&#125;/g,"}");
								}
								$root.replaceWith(htmldata);
							}
						}
						return false;
					}else{
						//span,font - extract select content from this span,font
						var rng = this.getRange();
						var shtml = this.getSelectText();
						var rnode = this.getSelectNode();
						if (shtml=="") {
							shtml="<br>";
						}else{
							shtml = this.clearFromSubInsert(shtml,command);
						}
						var ins = this.elFromString(shtml);
						
						var before_rng = (window.getSelection) ? rng.cloneRange():this.body.createTextRange();
						var after_rng = (window.getSelection) ? rng.cloneRange():this.body.createTextRange();

						if (window.getSelection) {
							this.insertAtCursor('<span id="wbbdivide"></span>');
							var div = $root.find('span#wbbdivide').get(0);
							before_rng.setStart(root.firstChild,0);
							before_rng.setEndBefore(div);
							after_rng.setStartAfter(div);
							after_rng.setEndAfter(root.lastChild);
						}else{
							before_rng.moveToElementText(root);
							after_rng.moveToElementText(root);
							before_rng.setEndPoint('EndToStart',rng);
							after_rng.setEndPoint('StartToEnd',rng);
						}
						var bf = this.getSelectText(false,before_rng);
						var af = this.getSelectText(false,after_rng);
						if (af!="") {
							var $af = $root.clone().html(af);
							$root.after($af);
						}
						if (clear!==true) $root.after(ins); //insert select html
						if (window.getSelection) {
							$root.html(bf);
							if (clear!==true) this.selectNode(ins);
						}else{
							$root.replaceWith(bf);
						}
						return false;
					}
				},this));
			}
		},
		execNativeCommand: function(cmd,param) { 
			this.body.focus(); //set focus to frame body
			if (cmd=="insertHTML" && !window.getSelection) { //IE does't support insertHTML
				var r = (this.lastRange) ? this.lastRange:document.selection.createRange(); //IE 7,8 range lost fix
				r.pasteHTML(param);
				var txt = $('<div>').html(param).text(); //for ie selection inside block
				var brsp = txt.indexOf("<br>");
				if (brsp>-1) {
					r.moveStart('character',(-1)*(txt.length-brsp));
					r.select();
				}
				this.lastRange=false;
			}else if (cmd=="insertHTML") { //fix webkit bug with insertHTML
				var sel = this.getSelection();
				var e = this.elFromString(param);
				var rng = (this.lastRange) ? this.lastRange:this.getRange();
				rng.deleteContents();
				rng.insertNode(e);
				rng.collapse(false);
				sel.removeAllRanges();
				sel.addRange(rng);
			}else{
				if (typeof param == "undefined") {param=false;}
				if (this.lastRange) {
					$.log("Last range select");
					this.selectLastRange()
				}
				document.execCommand(cmd, false, param);
			}
			
		},
		getCodeByCommand: function(command,paramobj) {
			return (this.options.bbmode) ? this.getBBCodeByCommand(command,paramobj):this.getHTMLByCommand(command,paramobj);
		},
		getBBCodeByCommand: function(command,params) {
			if (!this.options.allButtons[command]) {return "";}
			if (typeof(params)=="undefined") {params={};}
			params = this.keysToLower(params);
			if (!params["seltext"]) {
				//get selected text
				params["seltext"] = this.getSelectText(true);
			}
			
			var bbcode = this.options.allButtons[command].bbcode;
			bbcode = bbcode.replace(/\{(.*?)(\[.*?\])*\}/g,function(str,p,vrgx) {
				if (vrgx) {
					var vrgxp;
					if (vrgx) {
						vrgxp = new RegExp(vrgx+"+","i");
					}
					if (typeof(params[p.toLowerCase()])!="undefined" && params[p.toLowerCase()].toString().match(vrgxp)===null) {
						//not valid value
						return "";
					}
				}
				return (typeof(params[p.toLowerCase()])=="undefined") ? "":params[p.toLowerCase()];
			});
			
			//insert first with max params
			var rbbcode=null,maxpcount=0;
			if (this.options.allButtons[command].transform) {
				var tr=[];
				$.each(this.options.allButtons[command].transform,function(html,bb) {
					tr.push(bb);
				});
				tr=this.sortArray(tr,-1);
				$.each(tr,function(i,v) {
					var valid=true,pcount=0,pname={};;
					v = v.replace(/\{(.*?)(\[.*?\])*\}/g,function(str,p,vrgx) {
						var vrgxp;
						p = p.toLowerCase();
						if (vrgx) {
							vrgxp = new RegExp(vrgx+"+","i");
						}
						if (typeof(params[p.toLowerCase()])=="undefined" || (vrgx && params[p.toLowerCase()].toString().match(vrgxp)===null)) {valid=false;};
						if (typeof(params[p])!="undefined" && !pname[p]) {pname[p]=1;pcount++;}
						return (typeof(params[p.toLowerCase()])=="undefined") ? "":params[p.toLowerCase()];
					});
					if (valid && (pcount>maxpcount)) {rbbcode = v;maxpcount=pcount;}
				});
			}
			return rbbcode || bbcode;
		},
		getHTMLByCommand: function(command,params) {
			if (!this.options.allButtons[command]) {return "";}
			params = this.keysToLower(params);
			if (typeof(params)=="undefined") {params={};}
			if (!params["seltext"]) {
				//get selected text
				params["seltext"] = this.getSelectText(false);
				if (params["seltext"]=="") {params["seltext"]="<br>";}
				else{
					//clear selection from current command tags
					params["seltext"] = this.clearFromSubInsert(params["seltext"],command);
					
					//toBB if params onlyClearText=true
					if (this.options.allButtons[command].onlyClearText===true) {
						params["seltext"] = this.toBB(params["seltext"]).replace(/\</g,"&lt;").replace(/\n/g,"<br/>").replace(/\s{3}/g,'<span class="wbbtab"></span>'); 
					}
					
				}
			}
			
			var postsel="";
			this.seltextID = "wbbid_"+(++this.lastid);
			if (command!="link" && command!="img") {
				params["seltext"] = '<span id="'+this.seltextID+'">'+params["seltext"]+'</span>'; //use for select seltext
			}else{
				postsel = '<span id="'+this.seltextID+'"><br></span>'
			}
			var html = this.options.allButtons[command].html;
			html = html.replace(/\{(.*?)(\[.*?\])*\}/g,function(str,p,vrgx) {
				if (vrgx) {
					var vrgxp = new RegExp(vrgx+"+","i");
					if (typeof(params[p.toLowerCase()])!="undefined" && params[p.toLowerCase()].toString().match(vrgxp)===null) {
						//not valid value
						return "";
					}
				}
				return (typeof(params[p.toLowerCase()])=="undefined") ? "":params[p.toLowerCase()];
			});
			
			//insert first with max params
			var rhtml=null,maxpcount=0;
			if (this.options.allButtons[command].transform) {
				var tr=[];
				$.each(this.options.allButtons[command].transform,function(html,bb) {
					tr.push(html);
				});
				tr=this.sortArray(tr,-1);
				$.each(tr,function(i,v) {
					var valid=true, pcount=0,pname={};
					v = v.replace(/\{(.*?)(\[.*?\])*\}/g,function(str,p,vrgx) {
						var vrgxp;
						p = p.toLowerCase();
						if (vrgx) {
							vrgxp = new RegExp(vrgx+"+","i");
						}
						if (typeof(params[p])=="undefined" || (vrgx && params[p].toString().match(vrgxp)===null)) {valid=false;};
						if (typeof(params[p])!="undefined" && !pname[p]) {pname[p]=1;pcount++;}
						return (typeof(params[p])=="undefined") ? "":params[p];
					});
					if (valid && (pcount>maxpcount)) {rhtml = v;maxpcount=pcount;}
				});
			}
			return (rhtml || html)+postsel;
		},
		
		//SELECTION FUNCTIONS
		getSelection: function() {
			if (window.getSelection) {
				return window.getSelection();
			}else if (document.selection) {
				return (this.options.bbmode) ? document.selection.createRange():document.selection.createRange();
			}
		},
		getSelectText: function(fromTxtArea,range) {
			if (fromTxtArea) {
				//return select text from textarea
				this.txtArea.focus();
				if('selectionStart' in this.txtArea) {
					var l = this.txtArea.selectionEnd - this.txtArea.selectionStart;
					return this.txtArea.value.substr(this.txtArea.selectionStart, l);
				}else{
					//IE
					var r = document.selection.createRange();
					return r.text;
				}
			}else{
				//return select html from body
				this.body.focus();
				if (!range)  {range=this.getRange()};
				if (window.getSelection) {
					//w3c
					if (range) {
						return $('<div>').append(range.cloneContents()).html();
					}
				}else{
					//ie
					return range.htmlText;
				}
			}
			return "";
		},
		getRange: function() {
			if (window.getSelection) {
				var sel = this.getSelection();
				if (sel.getRangeAt && sel.rangeCount>0) {
					return sel.getRangeAt(0);
				}else if (sel.anchorNode) {
					var range = (this.options.bbmode) ? document.createRange() : document.createRange();
					range.setStart (sel.anchorNode, sel.anchorOffset);
					range.setEnd (sel.focusNode, sel.focusOffset);
					return range;
				}
			}else{
				return (this.options.bbmode===true) ? document.selection.createRange():document.selection.createRange();
			}
		},
		insertAtCursor: function(code,forceBBMode) {
			if (typeof(code)!="string") {code = $("<div>").append(code).html();}
			if ((this.options.bbmode && typeof(forceBBMode)=="undefined") || forceBBMode===true) {
				var clbb = code.replace(/.*(\[\/\S+?\])$/,"$1");
				var p = this.getCursorPosBB()+((code.indexOf(clbb)!=-1 && code.match(/\[.*\]/)) ? code.indexOf(clbb):code.length);
				if (document.selection) {
					//IE
					this.txtArea.focus();
					this.getSelection().text=code;
				}else if (this.txtArea.selectionStart || this.txtArea.selectionStart == '0') {
					this.txtArea.value = this.txtArea.value.substring(0, this.txtArea.selectionStart) + code + this.txtArea.value.substring(this.txtArea.selectionEnd, this.txtArea.value.length);
				}
				if (p<0) {p=0;}
				this.setCursorPosBB(p);
			}else{
				this.execNativeCommand("insertHTML",code);
				var node = this.getSelectNode();
				if (!$(node).closest("table,tr,td")) {
					this.splitPrevNext(node);
				}
			}
		},
		getSelectNode: function(rng) {
			this.body.focus();
			if (!rng) {rng=this.getRange();}
			if (!rng) {return this.$body;}
			var sn = (window.getSelection) ? rng.commonAncestorContainer:rng.parentElement();
			if ($(sn).is(".imgWrap")) {sn = $(sn).children("img")[0];}
			return sn;
		},
		getCursorPosBB: function() {	
			var pos=0;
			if ('selectionStart' in this.txtArea) {
				pos = this.txtArea.selectionStart;
			}else{
				this.txtArea.focus();
				var r = this.getRange();
				var rt = document.body.createTextRange();
				rt.moveToElementText(this.txtArea);
				rt.setEndPoint('EndToStart',r);
				pos = rt.text.length;
			}
			return pos;
		},
		setCursorPosBB: function(pos) {
			if (this.options.bbmode) {
				if (window.getSelection) {
					this.txtArea.selectionStart=pos;
					this.txtArea.selectionEnd=pos;
				}else{
					var range = this.txtArea.createTextRange();
					range.collapse(true);
					range.move('character', pos);
					range.select();
				}
			}
		},
		selectNode: function(node,rng) {
			if (!rng) {rng = this.getRange();}
			if (!rng) {return;}
			if (window.getSelection) {
				var sel = this.getSelection();
				rng.selectNodeContents(node)
				sel.removeAllRanges();
				sel.addRange(rng);
			}else{
				rng.moveToElementText(node);
				rng.select();
			}
		},
		selectRange: function(rng) {
			if (rng) {
				if (!window.getSelection) {
					rng.select();
				}else{
					var sel = this.getSelection();
					sel.removeAllRanges();
					sel.addRange(rng);
				}
			}
		},
		cloneRange: function(rng) {
			if (rng) {
				if (!window.getSelection) {
					return rng.duplicate();
				}else{
					return rng.cloneRange();
				}
			}
		},
		getRangeClone: function() {
			return this.cloneRange(this.getRange());
		},
		saveRange: function() {
			this.setBodyFocus();
			this.lastRange=this.getRangeClone();
		},
		selectLastRange: function() {
			if (this.lastRange) {
				this.body.focus();
				this.selectRange(this.lastRange);
				this.lastRange=false;
			}
		},
		setBodyFocus: function() {
			$.log("Set focus to WysiBB editor");
			if (this.options.bbmode) {
				if (!this.$txtArea.is(":focus")) {
					this.$txtArea.focus();
				}
			}else{
				if (!this.$body.is(":focus")) {
					this.$body.focus();
				}
			}
		},
		clearLastRange: function() {
			this.lastRange=false;
		},
		 
		//TRANSFORM FUNCTIONS
		filterByNode: function(node) {
			var $n = $(node);
			var tagName = $n.get(0).tagName.toLowerCase();
			var filter=tagName;
			var attributes = this.getAttributeList($n.get(0));
			$.each(attributes, $.proxy(function(i, item) {
				var v = $n.attr(item);
				if (item.substr(0,1)=="_") {item=item.substr(1,item.length)}
				if (v && !v.match(/\{.*?\}/)) {
					//$.log("I1: "+item);
					if (item=="style") {
						var v = $n.attr(item);
						var va = v.split(";");
						$.each(va,function(i,f) {
							if (f && f.length>0) {
								filter+='['+item+'*="'+$.trim(f)+'"]';
							}
						});
					}else{
						filter+='['+item+'="'+v+'"]';
					}
				}else if (v && item=="style") {
					var vf = v.substr(0,v.indexOf("{"));
					if (vf && vf!="") {
						var v = v.substr(0,v.indexOf("{"));
						var va = v.split(";");
						$.each(va,function(i,f) {
							filter+='['+item+'*="'+f+'"]';
						});
					}
				}else{ //1.2.2
					filter+='['+item+']';
				}
			},this));
			
			//index
			var idx = $n.parent().children(filter).index($n);
			if (idx>0) {
				filter+=":eq("+$n.index()+")";
			}
			return filter;
		},
		relFilterByNode: function(node,stop) {
			var p="";
			$.each(this.options.attrWrap,function(i,a) {
				stop = stop.replace('['+a,'[_'+a);
			});
			while (node && node.tagName!="BODY" && !$(node).is(stop)) {
				p=this.filterByNode(node)+" "+p;
				if (node) {node = node.parentNode;}
			}
			return p;
		},
		getRegexpReplace: function(str,validname) {
			str = str.replace(/(\(|\)|\[|\]|\.|\*|\?|\:|\\)/g,"\\$1") 
				.replace(/\s+/g,"\\s+")
				.replace(validname.replace(/(\(|\)|\[|\]|\.|\*|\?|\:|\\)/g,"\\$1"),"(.+)")
				.replace(/\{\S+?\}/g,".*");
			return (str);
		},
		getBBCode: function() {
			if (!this.options.rules) {return this.$txtArea.val();}
			if (this.options.bbmode) {return this.$txtArea.val();}
			this.clearEmpty();
			this.removeLastBodyBR();
			return this.toBB(this.$body.html());
		},
		toBB: function(data) {
			if (!data) {return "";};
			var $e = (typeof(data)=="string") ? $('<span>').html(data):$(data);
			//remove last BR
			$e.find("div,blockquote,p").each(function() {
				if (this.nodeType!=3 && this.lastChild && this.lastChild.tagName=="BR") {
					$(this.lastChild).remove();
				}
			})
			if ($e.is("div,blockquote,p") && $e[0].nodeType!=3 && $e[0].lastChild && $e[0].lastChild.tagName=="BR") {
				$($e[0].lastChild).remove();
			}
			//END remove last BR
			
			//Remove BR
			$e.find("ul > br, table > br, tr > br").remove();
			//IE
			
			var outbb="";
			
			//transform smiles
			$.each(this.options.srules,$.proxy(function(s,bb) {
				$e.find(s).replaceWith(bb[0]);
			},this));
			
			$e.contents().each($.proxy(function(i,el) {
				var $el = $(el);
				if (el.nodeType===3) {
					outbb+=el.data.replace(/\n+/,"").replace(/\t/g,"   ");
				}else{
					//process html tag
					var rpl,processed=false;

					for (var j=0; j<this.rsellist.length; j++) {
						var rootsel = this.rsellist[j];
						if ($el && $el.is(rootsel)) {
							//it is root sel
							var rlist = this.options.rules[rootsel];
							for (var i=0; i<rlist.length; i++) {
								var bbcode = rlist[i][0];
								var crules = rlist[i][1];
								var skip=false,keepElement=false,keepAttr=false;
								if (!$el.is("br")) {
									bbcode = bbcode.replace(/\n/g,"<br>");
								}
								bbcode = bbcode.replace(/\{(.*?)(\[.*?\])*\}/g,$.proxy(function(str,s,vrgx) {
									var c = crules[s.toLowerCase()];
									if (typeof(c)=="undefined") {$.log("Param: {"+s+"} not found in HTML representation.");skip=true;}
									var $cel = (c.sel) ? $(el).find(c.sel):$(el);
									if (c.attr && !$cel.attr(c.attr)) {skip=true;return s;} //skip if needed attribute not present, maybe other bbcode
									var cont = (c.attr) ? $cel.attr(c.attr):$cel.html();
									if (typeof(cont)=="undefined" || cont==null) {skip=true;return s;}
									var regexp = c.rgx;
									
									//style fix 
									if (regexp && c.attr=="style" && regexp.substr(regexp.length-1,1)!=";") {
										regexp+=";";
									}
									if (c.attr=="style" && cont && cont.substr(cont.length-1,1)!=";") {cont+=";"}
									//prepare regexp
									var rgx = (regexp) ? new RegExp(regexp,""):false;
									if (rgx) {
										if (cont.match(rgx)) {
											var m = cont.match(rgx);
											if (m && m.length==2) {
												cont=m[1];
											}
										}else{
											cont="";
										}
									}
									
									//if it is style attr, then keep tag alive, remove this style
									if (c.attr && skip===false) {
										if (c.attr=="style") {
											keepElement=true;
											var nstyle="";
											var r = c.rgx.replace(/^\.\*\?/,"").replace(/\.\*$/,"").replace(/;$/,"");
											$($cel.attr("style").split(";")).each(function(idx,style) {
												if (style && style!="") {
													if (!style.match(r)) {
														nstyle+=style+";";
													}
												}
											});
											if (nstyle=="") {
												$cel.removeAttr("style");
											}else{
												$cel.attr("style",nstyle);
											}
										}else if (c.rgx===false){	
											keepElement=true;
											keepAttr=true;
											$cel.removeAttr(c.attr);
										}
									}
									if ($el.is('table,tr,td,font')) {keepElement=true;}
									
									return cont || "";
								},this));
								if (skip) {continue;}
								if ($el.is("img,br,hr")) {
									//replace element
									outbb+=bbcode;
									$el=null;
									break;
								}else{
									if (keepElement && !$el.attr("notkeep")) {
										if ($el.is("table,tr,td")) {
											bbcode = this.fixTableTransform(bbcode);
											outbb+=this.toBB($('<span>').html(bbcode));
											$el=null;
										}else{
											$el.empty().html('<span>'+bbcode+'</span>');
										}
										
									}else{
										if ($el.is("iframe")) {
											outbb+=bbcode;
										}else{
											$el.empty().html(bbcode);
											outbb+=this.toBB($el);
											$el=null;
											
										}
										break;
									}
								}
							}
						}
					}
					if (!$el || $el.is("iframe,img")) {return true;}
					outbb+=this.toBB($el);
				}
			},this));
			
			outbb.replace(/<br>/g,"");
			return outbb;
		},
		getHTML: function(bbdata,init,skiplt) {
			if (!this.options.bbmode && !init) {return this.$body.html()}
			
			if (!skiplt) {bbdata = bbdata.replace(/</g,"&lt;").replace(/\{/g,"&#123;").replace(/\}/g,"&#125;");}
			bbdata = bbdata.replace(/\[code\]([\s\S]*?)\[\/code\]/g,function(s) {
				s = s.substr("[code]".length,s.length-"[code]".length-"[/code]".length).replace(/\[/g,"&#91;").replace(/\]/g,"&#93;");
				return "[code]"+s+"[/code]";
			});
			
			
			$.each(this.options.btnlist,$.proxy(function(i,b){
				if (b!="|" && b!="-") {
					var find=true;
					if (!this.options.allButtons[b] || !this.options.allButtons[b].transform) {
						return true;
					}

					$.each(this.options.allButtons[b].transform,$.proxy(function(html,bb) {
						html = html.replace(/\n/g,""); //IE 7,8 FIX
						var a=[];
						bb = bb.replace(/(\(|\)|\[|\]|\.|\*|\?|\:|\\|\\)/g,"\\$1");
							//.replace(/\s/g,"\\s");
						bb = bb.replace(/\{(.*?)(\\\[.*?\\\])*\}/gi,$.proxy(function(str,s,vrgx) {
							a.push(s);
							if (vrgx) {
								//has validation regexp
								vrgx = vrgx.replace(/\\/g,"");
								return "("+vrgx+"*?)";
							}
							return "([\\s\\S]*?)";
						},this));
						var n=0,am;
						while ((am = (new RegExp(bb,"mgi")).exec(bbdata)) != null) {
							if (am) {
								var r={};
								$.each(a,$.proxy(function(i,k) {
									r[k]=am[i+1];
								},this));
								var nhtml = html;
								nhtml = nhtml.replace(/\{(.*?)(\[.*?\])\}/g,"{$1}");
								nhtml = this.strf(nhtml,r);
								bbdata = bbdata.replace(am[0],nhtml);
							}
						}
					},this));
				}
			},this));
			
			//transform system codes
			$.each(this.options.systr,function(html,bb) {
				bb = bb.replace(/(\(|\)|\[|\]|\.|\*|\?|\:|\\|\\)/g,"\\$1")
					.replace(" ","\\s");
				bbdata = bbdata.replace(new RegExp(bb,"g"),html);
			});
			
			
			var $wrap = $(this.elFromString("<div>"+bbdata+"</div>"));
			//transform smiles
			this.getHTMLSmiles($wrap);
			return $wrap.html();
		},
		getHTMLSmiles: function(rel) {
			$(rel).contents().filter(function() {return this.nodeType==3}).each($.proxy(this.smileRPL,this));
		},
		smileRPL: function(i,el) {
			var ndata = el.data;
			$.each(this.options.smileList,$.proxy(function(i,row) {
				var fidx = ndata.indexOf(row.bbcode);
				if (fidx!=-1) {
					var afternode_txt = ndata.substring(fidx+row.bbcode.length,ndata.length);
					var afternode = document.createTextNode(afternode_txt);
					el.data = ndata = el.data.substr(0,fidx);
					$(el).after(afternode).after(this.strf(row.img,this.options));
					this.getHTMLSmiles(el.parentNode);
					return false;
				}
			this.getHTMLSmiles(el);
			},this));	
		},
		//UTILS
		setUID: function(el,attr) {
			var id = "wbbid_"+(++this.lastid);
			if (el) {
				$(el).attr(attr || "id",id);
			}
			return id;
		},
		keysToLower: function(o) {
			$.each(o,function(k,v) {
				if (k!=k.toLowerCase()) {
					delete o[k];
					o[k.toLowerCase()]=v;
				}
			});
			return o;
		},
		strf: function(str,data) {
			data = this.keysToLower($.extend({},data));
			return str.replace(/\{([\w\.]*)\}/g, function (str, key) {key = key.toLowerCase();var keys = key.split("."), value = data[keys.shift().toLowerCase()];$.each(keys, function () { value = value[this]; }); return (value === null || value === undefined) ? "" : value;});
		},
		elFromString: function(str) {
			if (str.indexOf("<")!=-1 && str.indexOf(">")!=-1) {
				//create tag
				var wr = document.createElement("SPAN");
				$(wr).html(str);
				this.setUID(wr,"wbb");
				return ($(wr).contents().length > 1) ? wr:wr.firstChild;
			}else{
				//create text node
				return document.createTextNode(str);
			}
		},
		isContain: function(node,sel) {
			while (node && !$(node).hasClass("wysibb")) {
				if ($(node).is(sel)) {return node};
				if (node) {node = node.parentNode;}
				else{return null;}
			}
		},
		isBBContain: function(bbcode) {
			var pos=this.getCursorPosBB();
			var b = this.prepareRGX(bbcode);
			var bbrgx = new RegExp(b,"g");
			var a;
			var lastindex=0;
			while ((a=bbrgx.exec(this.txtArea.value))!=null) {
				var p = this.txtArea.value.indexOf(a[0],lastindex);
				if (pos>p && pos<(p+a[0].length)) {
					return [a,p];
				}
				lastindex=p+1;
			}
		},
		prepareRGX: function(r) {
			return r.replace(/(\[|\]|\)|\(|\.|\*|\?|\:|\||\\)/g,"\\$1").replace(/\{.*?\}/g,"([\\s\\S]*?)");
		},
		checkForLastBR: function(node) {
			if (!node) {$node = this.body;} 
			if (node.nodeType==3) {node=node.parentNode;}
			var $node = $(node);
			if ($node.is("span[id*='wbbid']")) {$node = $node.parent();}
			if (this.options.bbmode===false && $node.is('div,blockquote,code') && $node.contents().length > 0) {
				var l = $node[0].lastChild;
				if (!l || (l && l.tagName!="BR")) {$node.append("<br/>");}
			}
			if(this.$body.contents().length > 0 && this.body.lastChild.tagName!="BR") {
				this.$body.append('<br/>');
			}
		},
		getAttributeList: function(el) {
			var a=[];
			$.each(el.attributes,function(i,attr) {
				if (attr.specified) {
					a.push(attr.name);
				}
			});
			return a;
		},
		clearFromSubInsert: function(html,cmd) {
			if (this.options.allButtons[cmd] && this.options.allButtons[cmd].rootSelector) {
				var $wr = $('<div>').html(html);
				$.each(this.options.allButtons[cmd].rootSelector,$.proxy(function(i,s) {
					var seltext=false;
					if (typeof(this.options.rules[s][0][1]["seltext"])!="undefined") {
						seltext = this.options.rules[s][0][1]["seltext"]["sel"];
					}
					var res=true;
					$wr.find("*").each(function() { //work with find("*") and "is", becouse in ie7-8 find is case sensitive
						if ($(this).is(s)) {
							if (seltext && seltext["sel"]) {
								$(this).replaceWith($(this).find(seltext["sel"].toLowerCase()).html());
							}else{
								$(this).replaceWith($(this).html());
							}
							res=false;
						}
					});
					return res;
				},this));
				return $wr.html();
			}
			return html;
		},
		splitPrevNext: function(node) {
			if (node.nodeType==3) {node = node.parentNode};
			var f = this.filterByNode(node).replace(/\:eq.*$/g,"");
			if ($(node.nextSibling).is(f)) {
				$(node).append($(node.nextSibling).html());
				$(node.nextSibling).remove();
			}
			if ($(node.previousSibling).is(f)) {
				$(node).prepend($(node.previousSibling).html());
				$(node.previousSibling).remove();
			}
		},
		modeSwitch: function() {
			if (this.options.bbmode) {
				//to HTML
				this.$body.html(this.getHTML(this.$txtArea.val())).css("min-height",this.$txtArea.height());
				this.$txtArea.hide().removeAttr("wbbsync").val("");
				this.$body.show().focus();
			}else{
				//to bbcode
				this.$txtArea.val(this.getBBCode()).css("min-height",this.$body.height());
				this.$body.hide();
				this.$txtArea.show().focus();
			}
			this.options.bbmode=!this.options.bbmode;
		},
		clearEmpty: function () {
			this.$body.children().filter(emptyFilter).remove();
			function emptyFilter() {
				if (!$(this).is("span,font,a,b,i,u,s")) {
					//clear empty only for span,font
					return false;
				}
				if (!$(this).hasClass("wbbtab") && $.trim($(this).html()).length==0) {
					return true;
				}else if($(this).children().length > 0) {
					$(this).children().filter(emptyFilter).remove();
					if ($(this).html().length==0 && this.tagName!="BODY") {
						return true;
					}
				}
			}
		},
		dropdownclick: function(bsel,tsel,e) {
			var $btn = $(e.currentTarget).closest(bsel);
			if ($btn.hasClass("dis")) {return;}
			if ($btn.attr("wbbshow")) {
				//hide dropdown
				$btn.removeAttr("wbbshow");
				$(document).unbind("mousedown",this.dropdownhandler);
				if (document) {
					$(document).unbind("mousedown",this.dropdownhandler);
				}
				this.lastRange=false;
				
			}else{
				this.saveRange();
				this.$editor.find("*[wbbshow]").each(function(i,el) {
					$(el).removeClass("on").find($(el).attr("wbbshow")).hide().end().removeAttr("wbbshow");
				})
				$btn.attr("wbbshow",tsel);
				$(document.body).bind("mousedown",$.proxy(function(evt) {this.dropdownhandler($btn,bsel,tsel,evt)},this));
				if (this.$body) {
					this.$body.bind("mousedown",$.proxy(function(evt) {this.dropdownhandler($btn,bsel,tsel,evt)},this));
				}
			}
			$btn.find(tsel).toggle();
			$btn.toggleClass("on");
		},
		dropdownhandler: function($btn,bsel,tsel,e) {
			if ($(e.target).parents(bsel).length == 0) {
				$btn.removeClass("on").find(tsel).hide();
				$(document).unbind('mousedown',this.dropdownhandler);
				if (this.$body) {
					this.$body.unbind('mousedown',this.dropdownhandler);
				}
			}
		},
		rgbToHex: function(rgb) {
			if (rgb.substr(0, 1)=='#') {return rgb;}
			if (rgb.indexOf("rgb")==-1) {
				//IE
				var color=parseInt(rgb);
				color = ((color & 0x0000ff) << 16) | (color & 0x00ff00) | ((color & 0xff0000) >>> 16);
				return '#'+color.toString(16);
			}
			var digits = /(.*?)rgb\((\d+),\s*(\d+),\s*(\d+)\)/.exec(rgb);
			return "#"+this.dec2hex(parseInt(digits[2]))+this.dec2hex(parseInt(digits[3]))+this.dec2hex(parseInt(digits[4])); 
		},
		dec2hex: function(d) {
			if(d>15) {
				return d.toString(16);
			}else{
				return "0"+d.toString(16);
			}
		},
		sync: function() {
			if (this.options.bbmode) {
				this.$body.html(this.getHTML(this.txtArea.value,true));
			}else{
				this.$txtArea.attr("wbbsync",1).val(this.getBBCode());
			}
		},
		clearPaste: function(el) {
			var $block = $(el);
			//NEW 
			$.each(this.options.rules,$.proxy(function(s,ar) {
				var $sf = $block.find(s).attr("wbbkeep",1);
				if ($sf.length > 0) {
					var s2 = ar[0][1];
					$.each(s2,function(i,v) {
						if (v.sel) {
							$sf.find(v.sel).attr("wbbkeep",1);
						}
					});
				}
			},this));
			$block.find("*[wbbkeep!='1']").each($.proxy(function(i,el) {
				var $this = $(el);
				if ($this.is('div,p') && ($this.children().length == 0 || el.lastChild.tagName!="BR")) {
					$this.after("<br/>");
				}
			},this));
			$block.find("*[wbbkeep]").removeAttr("wbbkeep").removeAttr("style");
			$.log($block.html());
			$block.html(this.getHTML(this.toBB($block),true));
			$.log($block.html());
		},
		sortArray: function(ar,asc) {
			ar.sort(function(a,b) {
				return (a.length-b.length)*(asc || 1);
			});
			return ar;
		},
		smileFind: function() {
			if (this.options.smilefind) {
				var $smlist = $(this.options.smilefind).find('img[alt]');
				if ($smlist.length > 0) {
					this.options.smileList=[];
					$smlist.each($.proxy(function(i,el) {
						var $el=$(el);
						this.options.smileList.push({title:$el.attr("title"),bbcode:$el.attr("alt"),img:$el.removeAttr("alt").removeAttr("title")[0].outerHTML});
					},this));
				}
			}
		},
		destroy: function() {
			this.$editor.replaceWith(this.$txtArea);
			this.$txtArea.removeClass("wysibb-texarea").show();
			this.$modal.remove();
			this.$txtArea.data("wbb",null);
		},
		pressTab: function(e) {
			if (e && e.which == 9) {
				//insert tab
				if (e.preventDefault) {e.preventDefault();}
				if (this.options.bbmode) {
					this.insertAtCursor('   ',false);
				}else{
					this.insertAtCursor('<span class="wbbtab"><br></span>',false);
				}
			}
		},
		removeLastBodyBR: function() {
			if (this.body.lastChild && this.body.lastChild.nodeType!=3 && this.body.lastChild.tagName=="BR") {
				this.body.removeChild(this.body.lastChild);
				this.removeLastBodyBR();
			}
		},
		traceTextareaEvent: function(e) {
			if ($(e.target).closest("div.wysibb").length == 0) {
				if ($(document.activeElement).is("div.wysibb-body")) {
					this.saveRange();
				}
				setTimeout($.proxy(function() {
					var data = this.$txtArea.val();
					if (this.options.bbmode===false && data!="" && $(e.target).closest("div.wysibb").length == 0 && !this.$txtArea.attr("wbbsync")) {
						this.selectLastRange();
						this.insertAtCursor(this.getHTML(data,true));
						this.$txtArea.val("");
					}
					if ($(document.activeElement).is("div.wysibb-body")) {
						this.lastRange=false;
					}
				},this),100);
			}
		},
		txtAreaInitContent: function() {
			this.$body.html(this.getHTML(this.txtArea.value,true));
		},
		getValidationRGX: function(s) {
			if (s.match(/\[\S+\]/)) {
				return s.replace(/.*(\\*\[\S+\]).*/,"$1");
			}
			return "";
		},
		smileConversion: function() {
			if (this.options.smileList && this.options.smileList.length>0) {
				var snode = this.getSelectNode();
				if (snode.nodeType==3) {
					var ndata = snode.data;
					if (ndata.length>=2 && !this.isInClearTextBlock(snode) && $(snode).parents("a").length == 0) {
						$.each(this.options.srules,$.proxy(function(i,sar) {
							var smbb = sar[0];
							var fidx = ndata.indexOf(smbb);
							if (fidx!=-1) {
								var afternode_txt = ndata.substring(fidx+smbb.length,ndata.length);
								var afternode = document.createTextNode(afternode_txt);
								var afternode_cursor = document.createElement("SPAN");
								snode.data = snode.data.substr(0,fidx);
								$(snode).after(afternode).after(afternode_cursor).after(this.strf(sar[1],this.options));
								this.selectNode(afternode_cursor);
								return false;
							}
						},this));
					}
				}
			}
		},
		isInClearTextBlock: function() {
			if (this.cleartext) {
				var find=false;
				$.each(this.cleartext,$.proxy(function(sel,command) {
					if (this.queryState(command)) {
						find=command;
						return false;
					}
				},this))
				return find;
			}
			return false;
		},
		wrapAttrs: function(html) {
			$.each(this.options.attrWrap,function(i,a) {
				html = html.replace(a+'="','_'+a+'="');
			});
			return html;
		},
		unwrapAttrs: function(html) {
			$.each(this.options.attrWrap,function(i,a) {
				html = html.replace('_'+a+'="',a+'="');
			});
			return html;
		},
		disNonActiveButtons: function() {
			if (this.isInClearTextBlock()) {
				this.$toolbar.find(".wysibb-toolbar-btn:not(.on,.mswitch)").addClass("dis");
			}else{
				this.$toolbar.find(".wysibb-toolbar-btn.dis").removeClass("dis");
			}
		},
		setCursorByEl: function(el) {
			var sl = document.createTextNode("<br>");
			$(el).after(sl);
			this.selectNode(sl);
		},
		
		//img listeners
		imgListeners: function() {
			$(document).on("mousedown",$.proxy(this.imgEventHandler,this));
		},
		imgEventHandler: function(e) {
			var $e = $(e.target);
			if (this.hasWrapedImage && ($e.closest(".wbb-img,#wbbmodal").length == 0 || $e.hasClass("wbb-cancel-button"))) {
				this.$body.find(".imgWrap ").each(function() {
					$.log("Removed imgWrap block");
					$(this).replaceWith($(this).find("img"));
				})
				this.hasWrapedImage = false;
				this.updateUI();
			}
			
			if ($e.is("img") && $e.closest(".wysibb-body").length > 0) {
				$e.wrap("<span class='imgWrap'></span>");
				this.hasWrapedImage = $e;
				this.$body.focus();
				this.selectNode($e.parent()[0]);
			}
		},
		
		//MODAL WINDOW
		showModal: function(cmd,opt,queryState) {
			$.log("showModal: "+cmd);
			this.saveRange();
			var $cont = this.$modal.find(".wbbm-content").html("");
			var $wbbm = this.$modal.find(".wbbm").removeClass("hastabs");
			this.$modal.find("span.wbbm-title-text").html(opt.title);
			if (opt.tabs && opt.tabs.length>1) {
				//has tabs, create
				$wbbm.addClass("hastabs");
				var $ul = $('<div class="wbbm-tablist">').appendTo($cont).append("<ul>").children("ul");
				$.each(opt.tabs,$.proxy(function(i,row) {
					if (i==0) {row['on']="on"}
					$ul.append(this.strf('<li class="{on}" onClick="$(this).parent().find(\'.on\').removeClass(\'on\');$(this).addClass(\'on\');$(this).parents(\'.wbbm-content\').find(\'.tab-cont\').hide();$(this).parents(\'.wbbm-content\').find(\'.tab'+i+'\').show()">{title}</li>',row));
					
				},this))
			}
			if (opt.width) {
				$wbbm.css("width",opt.width);
			}
			var $cnt = $('<div class="wbbm-cont">').appendTo($cont);
			if (queryState) {
				$wbbm.find('#wbbm-remove').show();
			}else{
				$wbbm.find('#wbbm-remove').hide();
			}
			$.each(opt.tabs,$.proxy(function(i,r) {
				var $c = $('<div>').addClass("tab-cont tab"+i).attr("tid",i).appendTo($cnt);
				if (i>0) {$c.hide();} 
				if (r.html) {
					$c.html(this.strf(r.html,this.options));
				}else{
					$.each(r.input,$.proxy(function(j,inp) {
						inp["value"]=queryState[inp.param.toLowerCase()];
						if (inp.param.toLowerCase()=="seltext" && (!inp["value"] || inp["value"]=="")) {
							inp["value"] = this.getSelectText(this.options.bbmode);
						}
						if (inp["value"] && inp["value"].indexOf("<span id='wbbid")==0 && $(inp["value"]).is("span[id*='wbbid']")) {
							inp["value"] = $(inp["value"]).html();
						}
						if (inp.type && inp.type=="div") {
							//div input, support wysiwyg input
							$c.append(this.strf('<div class="wbbm-inp-row"><label>{title}</label><div class="inp-text div-modal-text" contenteditable="true" name="{param}">{value}</div></div>',inp));
						}else{
							//default input
							$c.append(this.strf('<div class="wbbm-inp-row"><label>{title}</label><input class="inp-text modal-text" type="text" name="{param}" value="{value}"/></div>',inp));
						}
						
						
					},this));
				}
			},this));
			
			if ($.isFunction(opt.onLoad)) {
				opt.onLoad.call(this,cmd,opt,queryState);
			}
			
			$wbbm.find('#wbbm-submit').click($.proxy(function() {
				
				if ($.isFunction(opt.onSubmit)) { //custom submit function, if return false, then don't process our function
					var r = opt.onSubmit.call(this,cmd,opt,queryState);
					if (r===false) {return;}
				}
				var params={};
				var valid=true;
				this.$modal.find(".wbbm-inperr").remove();
				this.$modal.find(".wbbm-brdred").removeClass("wbbm-brdred");
				$.each(this.$modal.find(".tab-cont:visible .inp-text"),$.proxy(function(i,el) {
					var tid = $(el).parents(".tab-cont").attr("tid");
					var pname = $(el).attr("name").toLowerCase();
					var pval="";
					if ($(el).is("input,textrea,select")) {
						pval = $(el).val();
					}else{
						pval = $(el).html();
					}
					var validation = opt.tabs[tid]["input"][i]["validation"];
					if (typeof(validation)!="undefined") {
						if (!pval.match(new RegExp(validation,"i"))) {
							valid=false;
							$(el).after('<span class="wbbm-inperr">'+CURLANG.validation_err+'</span>').addClass("wbbm-brdred");
						}
					}
					params[pname]=pval;
				},this));
				if (valid) {
					$.log("Last range: "+this.lastRange);
					this.selectLastRange();
					//insert callback
					if (queryState) {
						this.wbbRemoveCallback(cmd,true);
					}
					this.wbbInsertCallback(cmd,params);
					//END insert callback
					
					this.closeModal();
					this.updateUI();
				}
			},this));
			$wbbm.find('#wbbm-remove').click($.proxy(function() {
				this.selectLastRange();
				this.wbbRemoveCallback(cmd); //remove callback
				this.closeModal();
				this.updateUI();
			},this));
			
			$(document.body).css("overflow","hidden"); //lock the screen, remove scroll on body
			if ($("body").height() > $(window).height()) { //if body has scroll, add padding-right 18px
				$(document.body).css("padding-right","18px");
			}
			this.$modal.show(); 
			if (this.isMobile) {
				$wbbm.css("margin-top","10px");
			}else{
				$wbbm.css("margin-top",($(window).height()-$wbbm.outerHeight())/3+"px");
			}
			setTimeout($.proxy(function() {this.$modal.find(".inp-text:visible")[0].focus()},this),10);
		},
		escModal: function(e) {
			if (e.which==27) {this.closeModal();}
		},
		closeModal: function() {
			$(document.body).css("overflow","auto").css("padding-right","0").unbind("keyup",this.escModal); //ESC key close modal;
			this.$modal.find('#wbbm-submit,#wbbm-remove').unbind('click');
			this.$modal.hide();
			this.lastRange=false;
			return this;
		},
		getParams: function(src,s,offset) {
			var params={};
			if (this.options.bbmode) {
				//bbmode
				var stext = s.match(/\{[\s\S]+?\}/g);
				s = this.prepareRGX(s);
				var rgx = new RegExp(s,"g");
				var val = this.txtArea.value;
				if (offset>0) {
					val = val.substr(offset,val.length-offset);
				}
				var a = rgx.exec(val);
				if (a) {
					$.each(stext,function(i,n) {
						params[n.replace(/\{|\}/g,"").replace(/"/g,"'").toLowerCase()] = a[i+1];
					});
				}
			}else{
				var rules = this.options.rules[s][0][1];
				$.each(rules,$.proxy(function(k,v) {
					var value="";
					var $v = (v.sel!==false) ? value=$(src).find(v.sel):$(src);
					if (v.attr!==false) {
						value=$v.attr(v.attr);
					}else{
						value=$v.html();
					}
					if (value) {
						if (v.rgx!==false) {
							var m = value.match(new RegExp(v.rgx));
							if (m && m.length==2) {
								value = m[1];
							}
						}
						params[k]=value.replace(/"/g,"'");
					}
				},this))
			}
			return params;
		},
		
		 
		//imgUploader
		imgLoadModal: function() {
			$.log("imgLoadModal");
			if (this.options.imgupload===true) {
				this.$modal.find("#imguploader").dragfileupload({
					url: this.strf(this.options.img_uploadurl,this.options),
					extraParams: {
						maxwidth: this.options.img_maxwidth,
						maxheight: this.options.img_maxheight
					},
					themePrefix: this.options.themePrefix,
					themeName: this.options.themeName,
					success: $.proxy(function(data) {
						this.$txtArea.insertImage(data.image_link,data.thumb_link);
						
						this.closeModal();
						this.updateUI();
					},this)
				});
				
				this.$modal.find("#fileupl").bind("change",function() {
					$("#fupform").submit();
				});
				this.$modal.find("#fupform").bind("submit",$.proxy(function(e) {
					$(e.target).parents("#imguploader").hide().after('<div class="loader"><i class="wbloadbding"></i><br/><span>'+CURLANG.loading+'</span></div>').parent().css("text-align","center");
				},this))
				
			}else{
				this.$modal.find(".hastabs").removeClass("hastabs");
				this.$modal.find("#imguploader").parents(".tab-cont").remove();
				this.$modal.find(".wbbm-tablist").remove();
			}
		},
		imgSubmitModal: function() {
			$.log("imgSubmitModal");
		},
		//DEBUG
		printObjectInIE: function(obj) {
			try{
			$.log(JSON.stringify(obj));
			}catch(e) {}
		},
		checkFilter: function(node,filter) {
			$.log("node: "+$(node).get(0).outerHTML+" filter: "+filter+" res: "+$(node).is(filter.toLowerCase()));
		},
		debug: function(msg) {
			if (this.options.debug===true) {
				var time = (new Date()).getTime();
				if (typeof(console)!="undefined") {
					console.log((time-this.startTime)+" ms: "+msg);
				}else{
					$("#exlog").append('<p>'+(time-this.startTime)+" ms: "+msg+'</p>');  
				}
				this.startTime=time;
			}
		},
		
		//Browser fixes
		isChrome: function() {
			return (window.chrome) ? true:false;
		},
		fixTableTransform: function(html) {
			if (!html) {return "";}
			if ($.inArray("table",this.options.buttons)==-1) {
				return html.replace(/\<(\/*?(table|tr|td|tbody))[^>]*\>/ig,"");
			}else{
				return html.replace(/\<(\/*?(table|tr|td))[^>]*\>/ig,"[$1]".toLowerCase()).replace(/\<\/*tbody[^>]*\>/ig,"");
			}
		}
	}
	
	$.log = function(msg) {
		if (typeof(wbbdebug)!="undefined" && wbbdebug===true) {
			if (typeof(console)!="undefined") {
				console.log(msg);
			}else{
				$("#exlog").append('<p>'+msg+'</p>');  
			}
		}
	}
	$.fn.wysibb = function(settings) {
		return this.each(function() {
			var data = $(this).data("wbb");
			if (!data) {
				new $.wysibb(this, settings);
			}
		});
	}
	$.fn.wdrag = function(opt) {
		if (!opt.scope) {opt.scope=this;}
		var start={x:0,y:0, height: 0};
		var drag;
		opt.scope.drag_mousedown = function(e) {
			e.preventDefault();
			start = {
				x: e.pageX,
				y: e.pageY,
				height: opt.height,
				sheight: opt.scope.$body.height()
			}
			drag=true;
			$(document).bind("mousemove",$.proxy(opt.scope.drag_mousemove,this));
			$(this).addClass("drag");
		};
		opt.scope.drag_mouseup = function(e) {
			if (drag===true) {
				e.preventDefault();
				$(document).unbind("mousemove",opt.scope.drag_mousemove);
				$(this).removeClass("drag");
				drag=false;
			}
		};
		opt.scope.drag_mousemove = function(e) {
			e.preventDefault();
			var axisX=0,axisY=0;
			if (opt.axisX) {
				axisX = e.pageX-start.x;
			}
			if (opt.axisY) {
				axisY = e.pageY-start.y;
			}
			if (axisY!=0) {
				var nheight = start.sheight+axisY;
				if (nheight>start.height && nheight<=opt.scope.options.resize_maxheight) {
					if (opt.scope.options.bbmode==true) {
						opt.scope.$txtArea.css((opt.scope.options.autoresize===true) ? "min-height":"height",nheight+"px");
					}else{
						opt.scope.$body.css((opt.scope.options.autoresize===true) ? "min-height":"height",nheight+"px");
					}
				}
			}
		};

		
		$(this).bind("mousedown",opt.scope.drag_mousedown);
		$(document).bind("mouseup",$.proxy(opt.scope.drag_mouseup,this));
	},
	
	//API
	$.fn.getDoc = function() {
		return this.data('wbb').doc;
	}
	$.fn.getSelectText = function(fromTextArea) {
		return this.data('wbb').getSelectText(fromTextArea);
	}
	$.fn.bbcode = function(data) {
		if (typeof(data)!="undefined") {
			if (this.data('wbb').options.bbmode) {
				this.data('wbb').$txtArea.val(data);
			}else{
				this.data('wbb').$body.html(this.data("wbb").getHTML(data));
			}
			return this;
		}else{
			return this.data('wbb').getBBCode();
		}
	}
	$.fn.htmlcode = function(data) {
		if (!this.data('wbb').options.onlyBBMode && this.data('wbb').inited===true) {
			if (typeof(data)!="undefined") {
				this.data('wbb').$body.html(data);
				return this;
			}else{
				return this.data('wbb').getHTML(this.data('wbb').$txtArea.val());
			}
		}
	}
	$.fn.getBBCode = function() {
		return this.data('wbb').getBBCode();
	}
	$.fn.getHTML = function() {
		var wbb = this.data('wbb');
		return wbb.getHTML(wbb.$txtArea.val());
	}
	$.fn.getHTMLByCommand = function(command,params) {
		return this.data("wbb").getHTMLByCommand(command,params);
	}
	$.fn.getBBCodeByCommand = function(command,params) {
		return this.data("wbb").getBBCodeByCommand(command,params);
	}
	$.fn.insertAtCursor = function(data,forceBBMode) {
		this.data("wbb").insertAtCursor(data,forceBBMode);
		return this.data("wbb");
	}
	$.fn.execCommand = function(command,value) {
		this.data("wbb").execCommand(command,value);
		return this.data("wbb");
	}
	$.fn.insertImage = function(imgurl,thumburl) {
		var editor = this.data("wbb");
		var code = editor.getCodeByCommand('img',{src:imgurl});
		this.insertAtCursor(code);
		return editor;
	}
	$.fn.sync = function() {
		this.data("wbb").sync();
		return this.data("wbb");
	}
	$.fn.destroy = function() {
		this.data("wbb").destroy();
	}
	
	
	$.fn.queryState = function(command) {
		return this.data("wbb").queryState(command);
	}
})(jQuery);

//Drag&Drop file uploader
(function($) {
	'use strict';
	
	$.fn.dragfileupload = function(options) {		
		return this.each(function() { 
			var upl = new FileUpload(this, options);
			upl.init();
		});
	}; 
	
	function FileUpload(e, options) {
		this.$block=$(e);
		
		this.opt = $.extend({
			url: false,
			success: false,
			extraParams: false,
			fileParam: 'img',
			validation: '\.(jpg|png|gif|jpeg)$',
			
			t1: CURLANG.fileupload_text1,
			t2: CURLANG.fileupload_text2
		},options);
	}
	
	FileUpload.prototype = {
		init: function() {
			if (window.FormData != null) {
				this.$block.addClass("drag");
				this.$block.prepend('<div class="p2">'+this.opt.t2+'</div>');
				this.$block.prepend('<div class="p">'+this.opt.t1+'</div>');
				
				this.$block.bind('dragover', function() {$(this).addClass('dragover');return false;});
				this.$block.bind('dragleave', function() {$(this).removeClass('dragover');return false;});
				
				//upload progress
				var uploadProgress = $.proxy(function(e) { 
					var p = parseInt(e.loaded/e.total*100, 10);
					this.$loader.children("span").text(CURLANG.loading+': '+ p+'%');
					
				}, this);
				var xhr = jQuery.ajaxSettings.xhr(); 
				if (xhr.upload) {
					xhr.upload.addEventListener('progress', uploadProgress, false);
				}
				this.$block[0].ondrop = $.proxy(function(e) {
					e.preventDefault();
					this.$block.removeClass('dragover');
					var ufile = e.dataTransfer.files[0];
					if (this.opt.validation && !ufile.name.match(new RegExp(this.opt.validation))) {
						this.error(CURLANG.validation_err);
						return false;
					}
					var fData = new FormData();
					fData.append(this.opt.fileParam, ufile);
					
					if (this.opt.extraParams) { //check for extraParams to upload
						$.each(this.opt.extraParams,function(k,v) {
							fData.append(k, v);
						});
					}
					
					this.$loader = $('<div class="loader"><i class="wbloadbding"></i><br/><span>'+CURLANG.loading+'</span></div>');
					this.$block.html(this.$loader);
					
					$.ajax({
						type: 'POST',
						url: this.opt.url,
						data: fData,
						processData: false,
						contentType: false,
						xhr: function() {return xhr},
						dataType: 'json',
						success: $.proxy(function(data) {
							if (data && data.status==1) {
								this.opt.success(data); 
							}else{
								this.error(data.msg || CURLANG.error_onupload);
							}
						},this),
						error: $.proxy(function (xhr, txt, thr) {this.error(CURLANG.error_onupload)},this)
					});
				},this);
				
			}
		},
		error: function(msg) {
			this.$block.find(".upl-error").remove().end().append('<span class="upl-error">'+msg+'</span>').addClass("wbbm-brdred");
		}
	}
})(jQuery);
/*-----------------jQuery Tags Input Plugin 1.3.3 http://xoxco.com/clickable/jquery-tags-input----------------*/
(function($) {
var delimiter = new Array();
	var tags_callbacks = new Array();
	$.fn.doAutosize = function(o){
	    var minWidth = $(this).data('minwidth'),
	        maxWidth = $(this).data('maxwidth'),
	        val = '',
	        input = $(this),
	        testSubject = $('#'+$(this).data('tester_id'));
	
	    if (val === (val = input.val())) {return;}
	
	    // Enter new content into testSubject
	    var escaped = val.replace(/&/g, '&amp;').replace(/\s/g,' ').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	    testSubject.html(escaped);
	    // Calculate new width + whether to change
	    var testerWidth = testSubject.width(),
	        newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth,
	        currentWidth = input.width(),
	        isValidWidthChange = (newWidth < currentWidth && newWidth >= minWidth) || (newWidth > minWidth && newWidth < maxWidth);
	
	    // Animate width
	    if (isValidWidthChange) {
	        input.width(newWidth);
	    }
  };
  $.fn.resetAutosize = function(options){
    var minWidth =  $(this).data('minwidth') || options.minInputWidth || $(this).width(),
        maxWidth = $(this).data('maxwidth') || options.maxInputWidth || ($(this).closest('.tagsinput').width() - options.inputPadding),
        val = '',
        input = $(this),
        testSubject = $('<tester/>').css({
            position: 'absolute',
            top: -9999,
            left: -9999,
            width: 'auto',
            fontSize: input.css('fontSize'),
            fontFamily: input.css('fontFamily'),
            fontWeight: input.css('fontWeight'),
            letterSpacing: input.css('letterSpacing'),
            whiteSpace: 'nowrap'
        }),
        testerId = $(this).attr('id')+'_autosize_tester';
    if(! $('#'+testerId).length > 0){
      testSubject.attr('id', testerId);
      testSubject.appendTo('body');
    }

    input.data('minwidth', minWidth);
    input.data('maxwidth', maxWidth);
    input.data('tester_id', testerId);
    input.css('width', minWidth);
  };
  
	$.fn.addTag = function(value,options) {
			options = jQuery.extend({focus:false,callback:true},options);
			this.each(function() { 
				var id = $(this).attr('id');

				var tagslist = $(this).val().split(delimiter[id]);
				if (tagslist[0] == '') { 
					tagslist = new Array();
				}

				value = jQuery.trim(value);
		
				if (options.unique) {
					var skipTag = $(this).tagExist(value);
					if(skipTag == true) {
					    //Marks fake input as not_valid to let styling it
    				    $('#'+id+'_tag').addClass('not_valid');
    				}
				} else {
					var skipTag = false; 
				}
				
				if (value !='' && skipTag != true) { 
                    $('<span>').addClass('tag').append(
                        $('<span>').text(value).append('&nbsp;&nbsp;'),
                        $('<a>', {
                            href  : '#',
                            title : 'Eliminar etiqueta',
                            text  : 'x'
                        }).click(function () {
                            return $('#' + id).removeTag(escape(value));
                        })
                    ).insertBefore('#' + id + '_addTag');

					tagslist.push(value);
				
					$('#'+id+'_tag').val('');
					if (options.focus) {
						$('#'+id+'_tag').focus();
					} else {		
						$('#'+id+'_tag').blur();
					}
					
					$.fn.tagsInput.updateTagsField(this,tagslist);
					
					if (options.callback && tags_callbacks[id] && tags_callbacks[id]['onAddTag']) {
						var f = tags_callbacks[id]['onAddTag'];
						f.call(this, value);
					}
					if(tags_callbacks[id] && tags_callbacks[id]['onChange'])
					{
						var i = tagslist.length;
						var f = tags_callbacks[id]['onChange'];
						f.call(this, $(this), tagslist[i-1]);
					}					
				}
		
			});		
			
			return false;
		};
		
	$.fn.removeTag = function(value) { 
			value = unescape(value);
			this.each(function() { 
				var id = $(this).attr('id');
	
				var old = $(this).val().split(delimiter[id]);
					
				$('#'+id+'_tagsinput .tag').remove();
				str = '';
				for (i=0; i< old.length; i++) { 
					if (old[i]!=value) { 
						str = str + delimiter[id] +old[i];
					}
				}
				
				$.fn.tagsInput.importTags(this,str);

				if (tags_callbacks[id] && tags_callbacks[id]['onRemoveTag']) {
					var f = tags_callbacks[id]['onRemoveTag'];
					f.call(this, value);
				}
			});
					
			return false;
		};
	
	$.fn.tagExist = function(val) {
		var id = $(this).attr('id');
		var tagslist = $(this).val().split(delimiter[id]);
		return (jQuery.inArray(val, tagslist) >= 0); //true when tag exists, false when not
	};
	
	// clear all existing tags and import new ones from a string
	$.fn.importTags = function(str) {
                id = $(this).attr('id');
		$('#'+id+'_tagsinput .tag').remove();
		$.fn.tagsInput.importTags(this,str);
	}
		
	$.fn.tagsInput = function(options) { 
    var settings = jQuery.extend({
      interactive:true,
      defaultText:'',
      minChars:0,
      width:'300px',
      height:'100px',
      autocomplete: {selectFirst: false },
      'hide':true,
      'delimiter':',',
      'unique':true,
      removeWithBackspace:true,
      placeholderColor:'#666666',
      autosize: true,
      comfortZone: 20,
      inputPadding: 6*2
    },options);

		this.each(function() { 
			if (settings.hide) { 
				$(this).hide();				
			}
			var id = $(this).attr('id');
			if (!id || delimiter[$(this).attr('id')]) {
				id = $(this).attr('id', 'tags' + new Date().getTime()).attr('id');
			}
			
			var data = jQuery.extend({
				pid:id,
				real_input: '#'+id,
				holder: '#'+id+'_tagsinput',
				input_wrapper: '#'+id+'_addTag',
				fake_input: '#'+id+'_tag'
			},settings);
	
			delimiter[id] = data.delimiter;
			
			if (settings.onAddTag || settings.onRemoveTag || settings.onChange) {
				tags_callbacks[id] = new Array();
				tags_callbacks[id]['onAddTag'] = settings.onAddTag;
				tags_callbacks[id]['onRemoveTag'] = settings.onRemoveTag;
				tags_callbacks[id]['onChange'] = settings.onChange;
			}
	
			var markup = '<div id="'+id+'_tagsinput" class="tagsinput"><div id="'+id+'_addTag">';
			
			if (settings.interactive) {
				markup = markup + '<input id="'+id+'_tag" value="" data-default="'+settings.defaultText+'" />';
			}
			
			markup = markup + '</div><div class="tags_clear"></div></div>';
			
			$(markup).insertAfter(this);

			$(data.holder).css('width',settings.width);
			$(data.holder).css('min-height',settings.height);
			$(data.holder).css('height','100%');
	
			if ($(data.real_input).val()!='') { 
				$.fn.tagsInput.importTags($(data.real_input),$(data.real_input).val());
			}		
			if (settings.interactive) { 
				$(data.fake_input).val($(data.fake_input).attr('data-default'));
				$(data.fake_input).css('color',settings.placeholderColor);
		        $(data.fake_input).resetAutosize(settings);
		
				$(data.holder).bind('click',data,function(event) {
					$(event.data.fake_input).focus();
				});
			
				$(data.fake_input).bind('focus',data,function(event) {
					if ($(event.data.fake_input).val()==$(event.data.fake_input).attr('data-default')) { 
						$(event.data.fake_input).val('');
					}
					$(event.data.fake_input).css('color','#000000');		
				});
						
				if (settings.autocomplete_url != undefined) {
					autocomplete_options = {source: settings.autocomplete_url};
					for (attrname in settings.autocomplete) { 
						autocomplete_options[attrname] = settings.autocomplete[attrname]; 
					}
				
					if (jQuery.Autocompleter !== undefined) {
						$(data.fake_input).autocomplete(settings.autocomplete_url, settings.autocomplete);
						$(data.fake_input).bind('result',data,function(event,data,formatted) {
							if (data) {
								$('#'+id).addTag(data[0] + "",{focus:true,unique:(settings.unique)});
							}
					  	});
					} else if (jQuery.ui.autocomplete !== undefined) {
						$(data.fake_input).autocomplete(autocomplete_options);
						$(data.fake_input).bind('autocompleteselect',data,function(event,ui) {
							$(event.data.real_input).addTag(ui.item.value,{focus:true,unique:(settings.unique)});
							return false;
						});
					}
				
					
				} else {
						// if a user tabs out of the field, create a new tag, this is only available if autocomplete is not used.
						$(data.fake_input).bind('blur',data,function(event) { 
							var d = $(this).attr('data-default');
							if ($(event.data.fake_input).val()!='' && $(event.data.fake_input).val()!=d) { 
								if( (event.data.minChars <= $(event.data.fake_input).val().length) && (!event.data.maxChars || (event.data.maxChars >= $(event.data.fake_input).val().length)) )
									$(event.data.real_input).addTag($(event.data.fake_input).val(),{focus:true,unique:(settings.unique)});
							} else {
								$(event.data.fake_input).val($(event.data.fake_input).attr('data-default'));
								$(event.data.fake_input).css('color',settings.placeholderColor);
							}
							return false;
						});
				
				}
				// if user types a comma, create a new tag
				$(data.fake_input).bind('keypress',data,function(event) {
					if (event.which==event.data.delimiter.charCodeAt(0) || event.which==13 ) {
					    event.preventDefault();
						if( (event.data.minChars <= $(event.data.fake_input).val().length) && (!event.data.maxChars || (event.data.maxChars >= $(event.data.fake_input).val().length)) )
							$(event.data.real_input).addTag($(event.data.fake_input).val(),{focus:true,unique:(settings.unique)});
					  	$(event.data.fake_input).resetAutosize(settings);
						return false;
					} else if (event.data.autosize) {
			            $(event.data.fake_input).doAutosize(settings);
            
          			}
				});
				//Delete last tag on backspace
				data.removeWithBackspace && $(data.fake_input).bind('keydown', function(event)
				{
					if(event.keyCode == 8 && $(this).val() == '')
					{
						 event.preventDefault();
						 var last_tag = $(this).closest('.tagsinput').find('.tag:last').text();
						 var id = $(this).attr('id').replace(/_tag$/, '');
						 last_tag = last_tag.replace(/[\s]+x$/, '');
						 $('#' + id).removeTag(escape(last_tag));
						 $(this).trigger('focus');
					}
				});
				$(data.fake_input).blur();
				
				//Removes the not_valid class when user changes the value of the fake input
				if(data.unique) {
				    $(data.fake_input).keydown(function(event){
				        if(event.keyCode == 8 || String.fromCharCode(event.which).match(/\w+|[áéíóúÁÉÍÓÚñÑ,/]+/)) {
				            $(this).removeClass('not_valid');
				        }
				    });
				}
			} // if settings.interactive
		});
			
		return this;
	
	};
	
	$.fn.tagsInput.updateTagsField = function(obj,tagslist) { 
		var id = $(obj).attr('id');
		$(obj).val(tagslist.join(delimiter[id]));
	};
	
	$.fn.tagsInput.importTags = function(obj,val) {			
		$(obj).val('');
		var id = $(obj).attr('id');
		var tags = val.split(delimiter[id]);
		for (i=0; i<tags.length; i++) { 
			$(obj).addTag(tags[i],{focus:false,callback:false});
		}
		if(tags_callbacks[id] && tags_callbacks[id]['onChange'])
		{
			var f = tags_callbacks[id]['onChange'];
			f.call(obj, obj, tags[i]);
		}
	};

})(jQuery);
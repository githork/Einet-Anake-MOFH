!function(e){function t(){return e("<div/>")}var o=Math.abs,i=Math.max,n=Math.min,s=Math.round;e.imgAreaSelect=function(d,r){function a(e){return e+ye.left-be.left}function c(e){return e+ye.top-be.top}function l(e){return e-ye.left+be.left}function u(e){return e-ye.top+be.top}function h(e){return e.pageX-be.left}function f(e){return e.pageY-be.top}function m(e){var t=e||Q,o=e||X;return{x1:s(ve.x1*t),y1:s(ve.y1*o),x2:s(ve.x2*t),y2:s(ve.y2*o),width:s(ve.x2*t)-s(ve.x1*t),height:s(ve.y2*o)-s(ve.y1*o)}}function p(e,t,o,i,n){var d=n||Q,r=n||X;ve={x1:s(e/d||0),y1:s(t/r||0),x2:s(o/d||0),y2:s(i/r||0)},ve.width=ve.x2-ve.x1,ve.height=ve.y2-ve.y1}function y(){E&&le.width()&&(ye={left:s(le.offset().left),top:s(le.offset().top)},D=le.innerWidth(),R=le.innerHeight(),ye.top+=le.outerHeight()-R>>1,ye.left+=le.outerWidth()-D>>1,F=s(r.minWidth/Q)||0,G=s(r.minHeight/X)||0,J=s(n(r.maxWidth/Q||1<<24,D)),U=s(n(r.maxHeight/X||1<<24,R)),"1.3.2"!=e().jquery||"fixed"!=xe||we.getBoundingClientRect||(ye.top+=i(document.body.scrollTop,we.scrollTop),ye.left+=i(document.body.scrollLeft,we.scrollLeft)),be=/absolute|relative/.test($.css("position"))?{left:s($.offset().left)-$.scrollLeft(),top:s($.offset().top)-$.scrollTop()}:"fixed"==xe?{left:e(document).scrollLeft(),top:e(document).scrollTop()}:{left:0,top:0},L=a(0),j=c(0),(ve.x2>D||ve.y2>R)&&k())}function b(t){if(Z){switch(ue.css({left:a(ve.x1),top:c(ve.y1)}).add(he).width(re=ve.width).height(ae=ve.height),he.add(fe).add(pe).css({left:0,top:0}),fe.width(i(re-fe.outerWidth()+fe.innerWidth(),0)).height(i(ae-fe.outerHeight()+fe.innerHeight(),0)),e(me[0]).css({left:L,top:j,width:ve.x1,height:R}),e(me[1]).css({left:L+ve.x1,top:j,width:re,height:ve.y1}),e(me[2]).css({left:L+ve.x2,top:j,width:D-ve.x2,height:R}),e(me[3]).css({left:L+ve.x1,top:j+ve.y2,width:re,height:R-ve.y2}),re-=pe.outerWidth(),ae-=pe.outerHeight(),pe.length){case 8:e(pe[4]).css({left:re>>1}),e(pe[5]).css({left:re,top:ae>>1}),e(pe[6]).css({left:re>>1,top:ae}),e(pe[7]).css({top:ae>>1});case 4:pe.slice(1,3).css({left:re}),pe.slice(2,4).css({top:ae})}t!==!1&&(e.imgAreaSelect.onKeyPress!=ze&&e(document).unbind(e.imgAreaSelect.keyPress,e.imgAreaSelect.onKeyPress),r.keys&&e(document)[e.imgAreaSelect.keyPress](e.imgAreaSelect.onKeyPress=ze)),ke&&fe.outerWidth()-fe.innerWidth()==2&&(fe.css("margin",0),setTimeout(function(){fe.css("margin","auto")},0))}}function g(e){y(),b(e),_=a(ve.x1),ee=c(ve.y1),te=a(ve.x2),oe=c(ve.y2)}function x(e,t){r.fadeSpeed?e.fadeOut(r.fadeSpeed,t):e.hide()}function v(e){var t=l(h(e))-ve.x1,o=u(f(e))-ve.y1;ce||(y(),ce=!0,ue.one("mouseout",function(){ce=!1})),Y="",r.resizable&&(o<=r.resizeMargin?Y="n":o>=ve.height-r.resizeMargin&&(Y="s"),t<=r.resizeMargin?Y+="w":t>=ve.width-r.resizeMargin&&(Y+="e")),ue.css("cursor",Y?Y+"-resize":r.movable?"move":""),T&&T.toggle()}function w(t){e("body").css("cursor",""),(r.autoHide||ve.width*ve.height==0)&&x(ue.add(me),function(){e(this).hide()}),e(document).unbind("mousemove",C),ue.mousemove(v),r.onSelectEnd(d,m())}function S(t){return 1!=t.which?!1:(y(),Y?(e("body").css("cursor",Y+"-resize"),_=a(ve[/w/.test(Y)?"x2":"x1"]),ee=c(ve[/n/.test(Y)?"y2":"y1"]),e(document).mousemove(C).one("mouseup",w),ue.unbind("mousemove",v)):r.movable?(q=L+ve.x1-h(t),B=j+ve.y1-f(t),ue.unbind("mousemove",v),e(document).mousemove(W).one("mouseup",function(){r.onSelectEnd(d,m()),e(document).unbind("mousemove",W),ue.mousemove(v)})):le.mousedown(t),!1)}function z(e){V&&(e?(te=i(L,n(L+D,_+o(oe-ee)*V*(te>_||-1))),oe=s(i(j,n(j+R,ee+o(te-_)/V*(oe>ee||-1)))),te=s(te)):(oe=i(j,n(j+R,ee+o(te-_)/V*(oe>ee||-1))),te=s(i(L,n(L+D,_+o(oe-ee)*V*(te>_||-1)))),oe=s(oe)))}function k(){_=n(_,L+D),ee=n(ee,j+R),o(te-_)<F&&(te=_-F*(_>te||-1),L>te?_=L+F:te>L+D&&(_=L+D-F)),o(oe-ee)<G&&(oe=ee-G*(ee>oe||-1),j>oe?ee=j+G:oe>j+R&&(ee=j+R-G)),te=i(L,n(te,L+D)),oe=i(j,n(oe,j+R)),z(o(te-_)<o(oe-ee)*V),o(te-_)>J&&(te=_-J*(_>te||-1),z()),o(oe-ee)>U&&(oe=ee-U*(ee>oe||-1),z(!0)),ve={x1:l(n(_,te)),x2:l(i(_,te)),y1:u(n(ee,oe)),y2:u(i(ee,oe)),width:o(te-_),height:o(oe-ee)},b(),r.onSelectChange(d,m())}function C(e){return te=/w|e|^$/.test(Y)||V?h(e):a(ve.x2),oe=/n|s|^$/.test(Y)||V?f(e):c(ve.y2),k(),!1}function A(t,o){te=(_=t)+ve.width,oe=(ee=o)+ve.height,e.extend(ve,{x1:l(_),y1:u(ee),x2:l(te),y2:u(oe)}),b(),r.onSelectChange(d,m())}function W(e){return _=i(L,n(q+h(e),L+D-ve.width)),ee=i(j,n(B+f(e),j+R-ve.height)),A(_,ee),e.preventDefault(),!1}function I(){e(document).unbind("mousemove",I),y(),te=_,oe=ee,k(),Y="",me.is(":visible")||ue.add(me).hide().fadeIn(r.fadeSpeed||0),Z=!0,e(document).unbind("mouseup",K).mousemove(C).one("mouseup",w),ue.unbind("mousemove",v),r.onSelectStart(d,m())}function K(){e(document).unbind("mousemove",I).unbind("mouseup",K),x(ue.add(me)),p(l(_),u(ee),l(_),u(ee)),this instanceof e.imgAreaSelect||(r.onSelectChange(d,m()),r.onSelectEnd(d,m()))}function P(t){return 1!=t.which||me.is(":animated")?!1:(y(),q=_=h(t),B=ee=f(t),e(document).mousemove(I).mouseup(K),!1)}function N(){g(!1)}function H(){E=!0,O(r=e.extend({classPrefix:"imgareaselect",movable:!0,parent:"body",resizable:!0,resizeMargin:10,onInit:function(){},onSelectStart:function(){},onSelectChange:function(){},onSelectEnd:function(){}},r)),ue.add(me).css({visibility:""}),r.show&&(Z=!0,y(),b(),ue.add(me).hide().fadeIn(r.fadeSpeed||0)),setTimeout(function(){r.onInit(d,m())},0)}function M(e,t){for(var o in t)void 0!==r[o]&&e.css(t[o],r[o])}function O(o){if(o.parent&&($=e(o.parent)).append(ue.add(me)),e.extend(r,o),y(),null!=o.handles){for(pe.remove(),pe=e([]),se=o.handles?"corners"==o.handles?4:8:0;se--;)pe=pe.add(t());pe.addClass(r.classPrefix+"-handle").css({position:"absolute",fontSize:0,zIndex:ge+1||1}),!parseInt(pe.css("width"))>=0&&pe.width(5).height(5),(de=r.borderWidth)&&pe.css({borderWidth:de,borderStyle:"solid"}),M(pe,{borderColor1:"border-color",borderColor2:"background-color",borderOpacity:"opacity"})}for(Q=r.imageWidth/D||1,X=r.imageHeight/R||1,null!=o.x1&&(p(o.x1,o.y1,o.x2,o.y2),o.show=!o.hide),o.keys&&(r.keys=e.extend({shift:1,ctrl:"resize"},o.keys)),me.addClass(r.classPrefix+"-outer"),he.addClass(r.classPrefix+"-selection"),se=0;se++<4;)e(fe[se-1]).addClass(r.classPrefix+"-border"+se);M(he,{selectionColor:"background-color",selectionOpacity:"opacity"}),M(fe,{borderOpacity:"opacity",borderWidth:"border-width"}),M(me,{outerColor:"background-color",outerOpacity:"opacity"}),(de=r.borderColor1)&&e(fe[0]).css({borderStyle:"solid",borderColor:de}),(de=r.borderColor2)&&e(fe[1]).css({borderStyle:"dashed",borderColor:de}),ue.append(he.add(fe).add(T)).append(pe),ke&&((de=(me.css("filter")||"").match(/opacity=(\d+)/))&&me.css("opacity",de[1]/100),(de=(fe.css("filter")||"").match(/opacity=(\d+)/))&&fe.css("opacity",de[1]/100)),o.hide?x(ue.add(me)):o.show&&E&&(Z=!0,ue.add(me).fadeIn(r.fadeSpeed||0),g()),V=(ne=(r.aspectRatio||"").split(/:/))[0]/ne[1],le.add(me).unbind("mousedown",P),r.disable||r.enable===!1?(ue.unbind("mousemove",v).unbind("mousedown",S),e(window).unbind("resize",N)):((r.enable||r.disable===!1)&&((r.resizable||r.movable)&&ue.mousemove(v).mousedown(S),e(window).resize(N)),r.persistent||le.add(me).mousedown(P)),r.enable=r.disable=void 0}var E,T,L,j,D,R,$,q,B,Q,X,Y,F,G,J,U,V,Z,_,ee,te,oe,ie,ne,se,de,re,ae,ce,le=e(d),ue=t(),he=t(),fe=t().add(t()).add(t()).add(t()),me=t().add(t()).add(t()).add(t()),pe=e([]),ye={left:0,top:0},be={left:0,top:0},ge=0,xe="absolute",ve={x1:0,y1:0,x2:0,y2:0,width:0,height:0},we=document.documentElement,Se=navigator.userAgent,ze=function(e){var t,o,s=r.keys,d=e.keyCode;if(t=isNaN(s.alt)||!e.altKey&&!e.originalEvent.altKey?!isNaN(s.ctrl)&&e.ctrlKey?s.ctrl:!isNaN(s.shift)&&e.shiftKey?s.shift:isNaN(s.arrows)?10:s.arrows:s.alt,"resize"==s.arrows||"resize"==s.shift&&e.shiftKey||"resize"==s.ctrl&&e.ctrlKey||"resize"==s.alt&&(e.altKey||e.originalEvent.altKey)){switch(d){case 37:t=-t;case 39:o=i(_,te),_=n(_,te),te=i(o+t,_),z();break;case 38:t=-t;case 40:o=i(ee,oe),ee=n(ee,oe),oe=i(o+t,ee),z(!0);break;default:return}k()}else switch(_=n(_,te),ee=n(ee,oe),d){case 37:A(i(_-t,L),ee);break;case 38:A(_,i(ee-t,j));break;case 39:A(_+n(t,D-l(te)),ee);break;case 40:A(_,ee+n(t,R-u(oe)));break;default:return}return!1};this.remove=function(){O({disable:!0}),ue.add(me).remove()},this.getOptions=function(){return r},this.setOptions=O,this.getSelection=m,this.setSelection=p,this.cancelSelection=K,this.update=g;var ke=(/msie ([\w.]+)/i.exec(Se)||[])[1],Ce=/opera/i.test(Se),Ae=/webkit/i.test(Se)&&!/chrome/i.test(Se);for(ie=le;ie.length;)ge=i(ge,isNaN(ie.css("z-index"))?ge:ie.css("z-index")),"fixed"==ie.css("position")&&(xe="fixed"),ie=ie.parent(":not(body)");ge=r.zIndex||ge,ke&&le.attr("unselectable","on"),e.imgAreaSelect.keyPress=ke||Ae?"keydown":"keypress",Ce&&(T=t().css({width:"100%",height:"100%",position:"absolute",zIndex:ge+2||2})),ue.add(me).css({visibility:"hidden",position:xe,overflow:"hidden",zIndex:ge||"0"}),ue.css({zIndex:ge+2||2}),he.add(fe).css({position:"absolute",fontSize:0}),d.complete||"complete"==d.readyState||!le.is("img")?H():le.one("load",H),!E&&ke&&ke>=7&&(d.src=d.src)},e.fn.imgAreaSelect=function(t){return t=t||{},this.each(function(){e(this).data("imgAreaSelect")?t.remove?(e(this).data("imgAreaSelect").remove(),e(this).removeData("imgAreaSelect")):e(this).data("imgAreaSelect").setOptions(t):t.remove||(void 0===t.enable&&void 0===t.disable&&(t.enable=!0),e(this).data("imgAreaSelect",new e.imgAreaSelect(this,t)))}),t.instance?e(this).data("imgAreaSelect"):this}}(jQuery);
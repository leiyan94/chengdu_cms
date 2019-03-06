/*! modernizr 3.3.1 (Custom Build) | MIT *
 * https://modernizr.com/download/?-cssanimations-video-setclasses !*/
!function(e,n,t){function o(e,n){return typeof e===n}function r(){var e,n,t,r,s,i,a;for(var l in w)if(w.hasOwnProperty(l)){if(e=[],n=w[l],n.name&&(e.push(n.name.toLowerCase()),n.options&&n.options.aliases&&n.options.aliases.length))for(t=0;t<n.options.aliases.length;t++)e.push(n.options.aliases[t].toLowerCase());for(r=o(n.fn,"function")?n.fn():n.fn,s=0;s<e.length;s++)i=e[s],a=i.split("."),1===a.length?Modernizr[a[0]]=r:(!Modernizr[a[0]]||Modernizr[a[0]]instanceof Boolean||(Modernizr[a[0]]=new Boolean(Modernizr[a[0]])),Modernizr[a[0]][a[1]]=r),g.push((r?"":"no-")+a.join("-"))}}function s(e){var n=_.className,t=Modernizr._config.classPrefix||"";if(b&&(n=n.baseVal),Modernizr._config.enableJSClass){var o=new RegExp("(^|\\s)"+t+"no-js(\\s|$)");n=n.replace(o,"$1"+t+"js$2")}Modernizr._config.enableClasses&&(n+=" "+t+e.join(" "+t),b?_.className.baseVal=n:_.className=n)}function i(){return"function"!=typeof n.createElement?n.createElement(arguments[0]):b?n.createElementNS.call(n,"http://www.w3.org/2000/svg",arguments[0]):n.createElement.apply(n,arguments)}function a(e,n){return!!~(""+e).indexOf(n)}function l(e){return e.replace(/([a-z])-([a-z])/g,function(e,n,t){return n+t.toUpperCase()}).replace(/^-/,"")}function f(e,n){return function(){return e.apply(n,arguments)}}function c(e,n,t){var r;for(var s in e)if(e[s]in n)return t===!1?e[s]:(r=n[e[s]],o(r,"function")?f(r,t||n):r);return!1}function u(e){return e.replace(/([A-Z])/g,function(e,n){return"-"+n.toLowerCase()}).replace(/^ms-/,"-ms-")}function p(){var e=n.body;return e||(e=i(b?"svg":"body"),e.fake=!0),e}function d(e,t,o,r){var s,a,l,f,c="modernizr",u=i("div"),d=p();if(parseInt(o,10))for(;o--;)l=i("div"),l.id=r?r[o]:c+(o+1),u.appendChild(l);return s=i("style"),s.type="text/css",s.id="s"+c,(d.fake?d:u).appendChild(s),d.appendChild(u),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(n.createTextNode(e)),u.id=c,d.fake&&(d.style.background="",d.style.overflow="hidden",f=_.style.overflow,_.style.overflow="hidden",_.appendChild(d)),a=t(u,e),d.fake?(d.parentNode.removeChild(d),_.style.overflow=f,_.offsetHeight):u.parentNode.removeChild(u),!!a}function m(n,o){var r=n.length;if("CSS"in e&&"supports"in e.CSS){for(;r--;)if(e.CSS.supports(u(n[r]),o))return!0;return!1}if("CSSSupportsRule"in e){for(var s=[];r--;)s.push("("+u(n[r])+":"+o+")");return s=s.join(" or "),d("@supports ("+s+") { #modernizr { position: absolute; } }",function(e){return"absolute"==getComputedStyle(e,null).position})}return t}function v(e,n,r,s){function f(){u&&(delete E.style,delete E.modElem)}if(s=o(s,"undefined")?!1:s,!o(r,"undefined")){var c=m(e,r);if(!o(c,"undefined"))return c}for(var u,p,d,v,y,h=["modernizr","tspan","samp"];!E.style&&h.length;)u=!0,E.modElem=i(h.shift()),E.style=E.modElem.style;for(d=e.length,p=0;d>p;p++)if(v=e[p],y=E.style[v],a(v,"-")&&(v=l(v)),E.style[v]!==t){if(s||o(r,"undefined"))return f(),"pfx"==n?v:!0;try{E.style[v]=r}catch(g){}if(E.style[v]!=y)return f(),"pfx"==n?v:!0}return f(),!1}function y(e,n,t,r,s){var i=e.charAt(0).toUpperCase()+e.slice(1),a=(e+" "+S.join(i+" ")+i).split(" ");return o(n,"string")||o(n,"undefined")?v(a,n,r,s):(a=(e+" "+x.join(i+" ")+i).split(" "),c(a,n,t))}function h(e,n,o){return y(e,t,t,n,o)}var g=[],w=[],C={_version:"3.3.1",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,n){var t=this;setTimeout(function(){n(t[e])},0)},addTest:function(e,n,t){w.push({name:e,fn:n,options:t})},addAsyncTest:function(e){w.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=C,Modernizr=new Modernizr;var _=n.documentElement,b="svg"===_.nodeName.toLowerCase();Modernizr.addTest("video",function(){var e=i("video"),n=!1;try{(n=!!e.canPlayType)&&(n=new Boolean(n),n.ogg=e.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),n.h264=e.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),n.webm=e.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,""),n.vp9=e.canPlayType('video/webm; codecs="vp9"').replace(/^no$/,""),n.hls=e.canPlayType('application/x-mpegURL; codecs="avc1.42E01E"').replace(/^no$/,""))}catch(t){}return n});var P="Moz O ms Webkit",S=C._config.usePrefixes?P.split(" "):[];C._cssomPrefixes=S;var x=C._config.usePrefixes?P.toLowerCase().split(" "):[];C._domPrefixes=x;var T={elem:i("modernizr")};Modernizr._q.push(function(){delete T.elem});var E={style:T.elem.style};Modernizr._q.unshift(function(){delete E.style}),C.testAllProps=y,C.testAllProps=h,Modernizr.addTest("cssanimations",h("animationName","a",!0)),r(),s(g),delete C.addTest,delete C.addAsyncTest;for(var N=0;N<Modernizr._q.length;N++)Modernizr._q[N]();e.Modernizr=Modernizr}(window,document);
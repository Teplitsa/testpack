!function(e,i){"use strict";var s="0.7.12",t="",o="?",n="function",r="undefined",a="object",l="string",d="major",c="model",u="name",w="type",f="vendor",m="version",p="architecture",g="console",h="mobile",b="tablet",v="smarttv",y="wearable",z="embedded",x={extend:function(e,i){var s={};for(var t in e)i[t]&&i[t].length%2===0?s[t]=i[t].concat(e[t]):s[t]=e[t];return s},has:function(e,i){return"string"==typeof e&&i.toLowerCase().indexOf(e.toLowerCase())!==-1},lowerize:function(e){return e.toLowerCase()},major:function(e){return typeof e===l?e.replace(/[^\d\.]/g,"").split(".")[0]:i},trim:function(e){return e.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,"")}},A={rgx:function(){for(var e,s,t,o,l,d,c,u=0,w=arguments;u<w.length&&!d;){var f=w[u],m=w[u+1];if(typeof e===r){e={};for(o in m)m.hasOwnProperty(o)&&(l=m[o],typeof l===a?e[l[0]]=i:e[l]=i)}for(s=t=0;s<f.length&&!d;)if(d=f[s++].exec(this.getUA()))for(o=0;o<m.length;o++)c=d[++t],l=m[o],typeof l===a&&l.length>0?2==l.length?typeof l[1]==n?e[l[0]]=l[1].call(this,c):e[l[0]]=l[1]:3==l.length?typeof l[1]!==n||l[1].exec&&l[1].test?e[l[0]]=c?c.replace(l[1],l[2]):i:e[l[0]]=c?l[1].call(this,c,l[2]):i:4==l.length&&(e[l[0]]=c?l[3].call(this,c.replace(l[1],l[2])):i):e[l]=c?c:i;u+=2}return e},str:function(e,s){for(var t in s)if(typeof s[t]===a&&s[t].length>0){for(var n=0;n<s[t].length;n++)if(x.has(s[t][n],e))return t===o?i:t}else if(x.has(s[t],e))return t===o?i:t;return e}},k={browser:{oldsafari:{version:{"1.0":"/8",1.2:"/1",1.3:"/3","2.0":"/412","2.0.2":"/416","2.0.3":"/417","2.0.4":"/419","?":"/"}}},device:{amazon:{model:{"Fire Phone":["SD","KF"]}},sprint:{model:{"Evo Shift 4G":"7373KT"},vendor:{HTC:"APA",Sprint:"Sprint"}}},os:{windows:{version:{ME:"4.90","NT 3.11":"NT3.51","NT 4.0":"NT4.0",2e3:"NT 5.0",XP:["NT 5.1","NT 5.2"],Vista:"NT 6.0",7:"NT 6.1",8:"NT 6.2",8.1:"NT 6.3",10:["NT 6.4","NT 10.0"],RT:"ARM"}}}},E={browser:[[/(opera\smini)\/([\w\.-]+)/i,/(opera\s[mobiletab]+).+version\/([\w\.-]+)/i,/(opera).+version\/([\w\.]+)/i,/(opera)[\/\s]+([\w\.]+)/i],[u,m],[/(opios)[\/\s]+([\w\.]+)/i],[[u,"Opera Mini"],m],[/\s(opr)\/([\w\.]+)/i],[[u,"Opera"],m],[/(kindle)\/([\w\.]+)/i,/(lunascape|maxthon|netfront|jasmine|blazer)[\/\s]?([\w\.]+)*/i,/(avant\s|iemobile|slim|baidu)(?:browser)?[\/\s]?([\w\.]*)/i,/(?:ms|\()(ie)\s([\w\.]+)/i,/(rekonq)\/([\w\.]+)*/i,/(chromium|flock|rockmelt|midori|epiphany|silk|skyfire|ovibrowser|bolt|iron|vivaldi|iridium|phantomjs)\/([\w\.-]+)/i],[u,m],[/(trident).+rv[:\s]([\w\.]+).+like\sgecko/i],[[u,"IE"],m],[/(edge)\/((\d+)?[\w\.]+)/i],[u,m],[/(yabrowser)\/([\w\.]+)/i],[[u,"Yandex"],m],[/(comodo_dragon)\/([\w\.]+)/i],[[u,/_/g," "],m],[/(micromessenger)\/([\w\.]+)/i],[[u,"WeChat"],m],[/xiaomi\/miuibrowser\/([\w\.]+)/i],[m,[u,"MIUI Browser"]],[/\swv\).+(chrome)\/([\w\.]+)/i],[[u,/(.+)/,"$1 WebView"],m],[/android.+samsungbrowser\/([\w\.]+)/i,/android.+version\/([\w\.]+)\s+(?:mobile\s?safari|safari)*/i],[m,[u,"Android Browser"]],[/(chrome|omniweb|arora|[tizenoka]{5}\s?browser)\/v?([\w\.]+)/i,/(qqbrowser)[\/\s]?([\w\.]+)/i],[u,m],[/(uc\s?browser)[\/\s]?([\w\.]+)/i,/ucweb.+(ucbrowser)[\/\s]?([\w\.]+)/i,/juc.+(ucweb)[\/\s]?([\w\.]+)/i],[[u,"UCBrowser"],m],[/(dolfin)\/([\w\.]+)/i],[[u,"Dolphin"],m],[/((?:android.+)crmo|crios)\/([\w\.]+)/i],[[u,"Chrome"],m],[/;fbav\/([\w\.]+);/i],[m,[u,"Facebook"]],[/fxios\/([\w\.-]+)/i],[m,[u,"Firefox"]],[/version\/([\w\.]+).+?mobile\/\w+\s(safari)/i],[m,[u,"Mobile Safari"]],[/version\/([\w\.]+).+?(mobile\s?safari|safari)/i],[m,u],[/webkit.+?(mobile\s?safari|safari)(\/[\w\.]+)/i],[u,[m,A.str,k.browser.oldsafari.version]],[/(konqueror)\/([\w\.]+)/i,/(webkit|khtml)\/([\w\.]+)/i],[u,m],[/(navigator|netscape)\/([\w\.-]+)/i],[[u,"Netscape"],m],[/(swiftfox)/i,/(icedragon|iceweasel|camino|chimera|fennec|maemo\sbrowser|minimo|conkeror)[\/\s]?([\w\.\+]+)/i,/(firefox|seamonkey|k-meleon|icecat|iceape|firebird|phoenix)\/([\w\.-]+)/i,/(mozilla)\/([\w\.]+).+rv\:.+gecko\/\d+/i,/(polaris|lynx|dillo|icab|doris|amaya|w3m|netsurf|sleipnir)[\/\s]?([\w\.]+)/i,/(links)\s\(([\w\.]+)/i,/(gobrowser)\/?([\w\.]+)*/i,/(ice\s?browser)\/v?([\w\._]+)/i,/(mosaic)[\/\s]([\w\.]+)/i],[u,m]],cpu:[[/(?:(amd|x(?:(?:86|64)[_-])?|wow|win)64)[;\)]/i],[[p,"amd64"]],[/(ia32(?=;))/i],[[p,x.lowerize]],[/((?:i[346]|x)86)[;\)]/i],[[p,"ia32"]],[/windows\s(ce|mobile);\sppc;/i],[[p,"arm"]],[/((?:ppc|powerpc)(?:64)?)(?:\smac|;|\))/i],[[p,/ower/,"",x.lowerize]],[/(sun4\w)[;\)]/i],[[p,"sparc"]],[/((?:avr32|ia64(?=;))|68k(?=\))|arm(?:64|(?=v\d+;))|(?=atmel\s)avr|(?:irix|mips|sparc)(?:64)?(?=;)|pa-risc)/i],[[p,x.lowerize]]],device:[[/\((ipad|playbook);[\w\s\);-]+(rim|apple)/i],[c,f,[w,b]],[/applecoremedia\/[\w\.]+ \((ipad)/],[c,[f,"Apple"],[w,b]],[/(apple\s{0,1}tv)/i],[[c,"Apple TV"],[f,"Apple"]],[/(archos)\s(gamepad2?)/i,/(hp).+(touchpad)/i,/(hp).+(tablet)/i,/(kindle)\/([\w\.]+)/i,/\s(nook)[\w\s]+build\/(\w+)/i,/(dell)\s(strea[kpr\s\d]*[\dko])/i],[f,c,[w,b]],[/(kf[A-z]+)\sbuild\/[\w\.]+.*silk\//i],[c,[f,"Amazon"],[w,b]],[/(sd|kf)[0349hijorstuw]+\sbuild\/[\w\.]+.*silk\//i],[[c,A.str,k.device.amazon.model],[f,"Amazon"],[w,h]],[/\((ip[honed|\s\w*]+);.+(apple)/i],[c,f,[w,h]],[/\((ip[honed|\s\w*]+);/i],[c,[f,"Apple"],[w,h]],[/(blackberry)[\s-]?(\w+)/i,/(blackberry|benq|palm(?=\-)|sonyericsson|acer|asus|dell|huawei|meizu|motorola|polytron)[\s_-]?([\w-]+)*/i,/(hp)\s([\w\s]+\w)/i,/(asus)-?(\w+)/i],[f,c,[w,h]],[/\(bb10;\s(\w+)/i],[c,[f,"BlackBerry"],[w,h]],[/android.+(transfo[prime\s]{4,10}\s\w+|eeepc|slider\s\w+|nexus 7|padfone)/i],[c,[f,"Asus"],[w,b]],[/(sony)\s(tablet\s[ps])\sbuild\//i,/(sony)?(?:sgp.+)\sbuild\//i],[[f,"Sony"],[c,"Xperia Tablet"],[w,b]],[/(?:sony)?(?:(?:(?:c|d)\d{4})|(?:so[-l].+))\sbuild\//i],[[f,"Sony"],[c,"Xperia Phone"],[w,h]],[/\s(ouya)\s/i,/(nintendo)\s([wids3u]+)/i],[f,c,[w,g]],[/android.+;\s(shield)\sbuild/i],[c,[f,"Nvidia"],[w,g]],[/(playstation\s[34portablevi]+)/i],[c,[f,"Sony"],[w,g]],[/(sprint\s(\w+))/i],[[f,A.str,k.device.sprint.vendor],[c,A.str,k.device.sprint.model],[w,h]],[/(lenovo)\s?(S(?:5000|6000)+(?:[-][\w+]))/i],[f,c,[w,b]],[/(htc)[;_\s-]+([\w\s]+(?=\))|\w+)*/i,/(zte)-(\w+)*/i,/(alcatel|geeksphone|huawei|lenovo|nexian|panasonic|(?=;\s)sony)[_\s-]?([\w-]+)*/i],[f,[c,/_/g," "],[w,h]],[/(nexus\s9)/i],[c,[f,"HTC"],[w,b]],[/(nexus\s6p)/i],[c,[f,"Huawei"],[w,h]],[/(microsoft);\s(lumia[\s\w]+)/i],[f,c,[w,h]],[/[\s\(;](xbox(?:\sone)?)[\s\);]/i],[c,[f,"Microsoft"],[w,g]],[/(kin\.[onetw]{3})/i],[[c,/\./g," "],[f,"Microsoft"],[w,h]],[/\s(milestone|droid(?:[2-4x]|\s(?:bionic|x2|pro|razr))?(:?\s4g)?)[\w\s]+build\//i,/mot[\s-]?(\w+)*/i,/(XT\d{3,4}) build\//i,/(nexus\s6)/i],[c,[f,"Motorola"],[w,h]],[/android.+\s(mz60\d|xoom[\s2]{0,2})\sbuild\//i],[c,[f,"Motorola"],[w,b]],[/hbbtv\/\d+\.\d+\.\d+\s+\([\w\s]*;\s*(\w[^;]*);([^;]*)/i],[[f,x.trim],[c,x.trim],[w,v]],[/hbbtv.+maple;(\d+)/i],[[c,/^/,"SmartTV"],[f,"Samsung"],[w,v]],[/\(dtv[\);].+(aquos)/i],[c,[f,"Sharp"],[w,v]],[/android.+((sch-i[89]0\d|shw-m380s|gt-p\d{4}|gt-n\d+|sgh-t8[56]9|nexus 10))/i,/((SM-T\w+))/i],[[f,"Samsung"],c,[w,b]],[/smart-tv.+(samsung)/i],[f,[w,v],c],[/((s[cgp]h-\w+|gt-\w+|galaxy\snexus|sm-\w[\w\d]+))/i,/(sam[sung]*)[\s-]*(\w+-?[\w-]*)*/i,/sec-((sgh\w+))/i],[[f,"Samsung"],c,[w,h]],[/sie-(\w+)*/i],[c,[f,"Siemens"],[w,h]],[/(maemo|nokia).*(n900|lumia\s\d+)/i,/(nokia)[\s_-]?([\w-]+)*/i],[[f,"Nokia"],c,[w,h]],[/android\s3\.[\s\w;-]{10}(a\d{3})/i],[c,[f,"Acer"],[w,b]],[/android\s3\.[\s\w;-]{10}(lg?)-([06cv9]{3,4})/i],[[f,"LG"],c,[w,b]],[/(lg) netcast\.tv/i],[f,c,[w,v]],[/(nexus\s[45])/i,/lg[e;\s\/-]+(\w+)*/i],[c,[f,"LG"],[w,h]],[/android.+(ideatab[a-z0-9\-\s]+)/i],[c,[f,"Lenovo"],[w,b]],[/linux;.+((jolla));/i],[f,c,[w,h]],[/((pebble))app\/[\d\.]+\s/i],[f,c,[w,y]],[/android.+;\s(glass)\s\d/i],[c,[f,"Google"],[w,y]],[/android.+(\w+)\s+build\/hm\1/i,/android.+(hm[\s\-_]*note?[\s_]*(?:\d\w)?)\s+build/i,/android.+(mi[\s\-_]*(?:one|one[\s_]plus|note lte)?[\s_]*(?:\d\w)?)\s+build/i],[[c,/_/g," "],[f,"Xiaomi"],[w,h]],[/android.+a000(1)\s+build/i],[c,[f,"OnePlus"],[w,h]],[/\s(tablet)[;\/]/i,/\s(mobile)(?:[;\/]|\ssafari)/i],[[w,x.lowerize],f,c]],engine:[[/windows.+\sedge\/([\w\.]+)/i],[m,[u,"EdgeHTML"]],[/(presto)\/([\w\.]+)/i,/(webkit|trident|netfront|netsurf|amaya|lynx|w3m)\/([\w\.]+)/i,/(khtml|tasman|links)[\/\s]\(?([\w\.]+)/i,/(icab)[\/\s]([23]\.[\d\.]+)/i],[u,m],[/rv\:([\w\.]+).*(gecko)/i],[m,u]],os:[[/microsoft\s(windows)\s(vista|xp)/i],[u,m],[/(windows)\snt\s6\.2;\s(arm)/i,/(windows\sphone(?:\sos)*)[\s\/]?([\d\.\s]+\w)*/i,/(windows\smobile|windows)[\s\/]?([ntce\d\.\s]+\w)/i],[u,[m,A.str,k.os.windows.version]],[/(win(?=3|9|n)|win\s9x\s)([nt\d\.]+)/i],[[u,"Windows"],[m,A.str,k.os.windows.version]],[/\((bb)(10);/i],[[u,"BlackBerry"],m],[/(blackberry)\w*\/?([\w\.]+)*/i,/(tizen)[\/\s]([\w\.]+)/i,/(android|webos|palm\sos|qnx|bada|rim\stablet\sos|meego|contiki)[\/\s-]?([\w\.]+)*/i,/linux;.+(sailfish);/i],[u,m],[/(symbian\s?os|symbos|s60(?=;))[\/\s-]?([\w\.]+)*/i],[[u,"Symbian"],m],[/\((series40);/i],[u],[/mozilla.+\(mobile;.+gecko.+firefox/i],[[u,"Firefox OS"],m],[/(nintendo|playstation)\s([wids34portablevu]+)/i,/(mint)[\/\s\(]?(\w+)*/i,/(mageia|vectorlinux)[;\s]/i,/(joli|[kxln]?ubuntu|debian|[open]*suse|gentoo|(?=\s)arch|slackware|fedora|mandriva|centos|pclinuxos|redhat|zenwalk|linpus)[\/\s-]?(?!chrom)([\w\.-]+)*/i,/(hurd|linux)\s?([\w\.]+)*/i,/(gnu)\s?([\w\.]+)*/i],[u,m],[/(cros)\s[\w]+\s([\w\.]+\w)/i],[[u,"Chromium OS"],m],[/(sunos)\s?([\w\.]+\d)*/i],[[u,"Solaris"],m],[/\s([frentopc-]{0,4}bsd|dragonfly)\s?([\w\.]+)*/i],[u,m],[/(haiku)\s(\w+)/i],[u,m],[/(ip[honead]+)(?:.*os\s([\w]+)*\slike\smac|;\sopera)/i],[[u,"iOS"],[m,/_/g,"."]],[/(mac\sos\sx)\s?([\w\s\.]+\w)*/i,/(macintosh|mac(?=_powerpc)\s)/i],[[u,"Mac OS"],[m,/_/g,"."]],[/((?:open)?solaris)[\/\s-]?([\w\.]+)*/i,/(aix)\s((\d)(?=\.|\)|\s)[\w\.]*)*/i,/(plan\s9|minix|beos|os\/2|amigaos|morphos|risc\sos|openvms)/i,/(unix)\s?([\w\.]+)*/i],[u,m]]},C=function(i,s){if(this instanceof C){var o=i||(e&&e.navigator&&e.navigator.userAgent?e.navigator.userAgent:t),n=s?x.extend(E,s):E;return this.getBrowser=function(){var e=A.rgx.apply(this,n.browser);return e.major=x.major(e.version),e},this.getCPU=function(){return A.rgx.apply(this,n.cpu)},this.getDevice=function(){return A.rgx.apply(this,n.device)},this.getEngine=function(){return A.rgx.apply(this,n.engine)},this.getOS=function(){return A.rgx.apply(this,n.os)},this.getResult=function(){return{ua:this.getUA(),browser:this.getBrowser(),engine:this.getEngine(),os:this.getOS(),device:this.getDevice(),cpu:this.getCPU()}},this.getUA=function(){return o},this.setUA=function(e){return o=e,this},this}return new C(i,s).getResult()};C.VERSION=s,C.BROWSER={NAME:u,MAJOR:d,VERSION:m},C.CPU={ARCHITECTURE:p},C.DEVICE={MODEL:c,VENDOR:f,TYPE:w,CONSOLE:g,MOBILE:h,SMARTTV:v,TABLET:b,WEARABLE:y,EMBEDDED:z},C.ENGINE={NAME:u,VERSION:m},C.OS={NAME:u,VERSION:m},typeof exports!==r?(typeof module!==r&&module.exports&&(exports=module.exports=C),exports.UAParser=C):typeof define===n&&define.amd?define(function(){return C}):e.UAParser=C;var N=e.jQuery||e.Zepto;if(typeof N!==r){var S=new C;N.ua=S.getResult(),N.ua.get=function(){return S.getUA()},N.ua.set=function(e){S.setUA(e);var i=S.getResult();for(var s in i)N.ua[s]=i[s]}}}("object"==typeof window?window:this),!function(e,i){var s=i(e,e.document);e.lazySizes=s,"object"==typeof module&&module.exports&&(module.exports=s)}(window,function(e,i){"use strict";if(i.getElementsByClassName){var s,t=i.documentElement,o=e.Date,n=e.HTMLPictureElement,r="addEventListener",a="getAttribute",l=e[r],d=e.setTimeout,c=e.requestAnimationFrame||d,u=e.requestIdleCallback,w=/^picture$/i,f=["load","error","lazyincluded","_lazyloaded"],m={},p=Array.prototype.forEach,g=function(e,i){return m[i]||(m[i]=new RegExp("(\\s|^)"+i+"(\\s|$)")),m[i].test(e[a]("class")||"")&&m[i]},h=function(e,i){g(e,i)||e.setAttribute("class",(e[a]("class")||"").trim()+" "+i)},b=function(e,i){var s;(s=g(e,i))&&e.setAttribute("class",(e[a]("class")||"").replace(s," "))},v=function(e,i,s){var t=s?r:"removeEventListener";s&&v(e,i),f.forEach(function(s){e[t](s,i)})},y=function(e,s,t,o,n){var r=i.createEvent("CustomEvent");return r.initCustomEvent(s,!o,!n,t||{}),e.dispatchEvent(r),r},z=function(i,t){var o;!n&&(o=e.picturefill||s.pf)?o({reevaluate:!0,elements:[i]}):t&&t.src&&(i.src=t.src)},x=function(e,i){return(getComputedStyle(e,null)||{})[i]},A=function(e,i,t){for(t=t||e.offsetWidth;t<s.minSize&&i&&!e._lazysizesWidth;)t=i.offsetWidth,i=i.parentNode;return t},k=function(){var e,s,t=[],o=function(){var i;for(e=!0,s=!1;t.length;)i=t.shift(),i[0].apply(i[1],i[2]);e=!1},n=function(n){e?n.apply(this,arguments):(t.push([n,this,arguments]),s||(s=!0,(i.hidden?d:c)(o)))};return n._lsFlush=o,n}(),E=function(e,i){return i?function(){k(e)}:function(){var i=this,s=arguments;k(function(){e.apply(i,s)})}},C=function(e){var i,s=0,t=125,n=666,r=n,a=function(){i=!1,s=o.now(),e()},l=u?function(){u(a,{timeout:r}),r!==n&&(r=n)}:E(function(){d(a)},!0);return function(e){var n;(e=e===!0)&&(r=44),i||(i=!0,n=t-(o.now()-s),0>n&&(n=0),e||9>n&&u?l():d(l,n))}},N=function(e){var i,s,t=99,n=function(){i=null,e()},r=function(){var e=o.now()-s;t>e?d(r,t-e):(u||n)(n)};return function(){s=o.now(),i||(i=d(r,t))}},S=function(){var n,c,u,f,m,A,S,T,_,O,R,B,L,F,P,W=/^img$/i,j=/^iframe$/i,D="onscroll"in e&&!/glebot/.test(navigator.userAgent),I=0,U=0,V=0,q=-1,H=function(e){V--,e&&e.target&&v(e.target,H),(!e||0>V||!e.target)&&(V=0)},$=function(e,s){var o,n=e,r="hidden"==x(i.body,"visibility")||"hidden"!=x(e,"visibility");for(_-=s,B+=s,O-=s,R+=s;r&&(n=n.offsetParent)&&n!=i.body&&n!=t;)r=(x(n,"opacity")||1)>0,r&&"visible"!=x(n,"overflow")&&(o=n.getBoundingClientRect(),r=R>o.left&&O<o.right&&B>o.top-1&&_<o.bottom+1);return r},G=function(){var e,o,r,l,d,w,f,p,g;if((m=s.loadMode)&&8>V&&(e=n.length)){o=0,q++,null==F&&("expand"in s||(s.expand=t.clientHeight>500&&t.clientWidth>500?500:370),L=s.expand,F=L*s.expFactor),F>U&&1>V&&q>2&&m>2&&!i.hidden?(U=F,q=0):U=m>1&&q>1&&6>V?L:I;for(;e>o;o++)if(n[o]&&!n[o]._lazyRace)if(D)if((p=n[o][a]("data-expand"))&&(w=1*p)||(w=U),g!==w&&(S=innerWidth+w*P,T=innerHeight+w,f=-1*w,g=w),r=n[o].getBoundingClientRect(),(B=r.bottom)>=f&&(_=r.top)<=T&&(R=r.right)>=f*P&&(O=r.left)<=S&&(B||R||O||_)&&(u&&3>V&&!p&&(3>m||4>q)||$(n[o],w))){if(ie(n[o]),d=!0,V>9)break}else!d&&u&&!l&&4>V&&4>q&&m>2&&(c[0]||s.preloadAfterLoad)&&(c[0]||!p&&(B||R||O||_||"auto"!=n[o][a](s.sizesAttr)))&&(l=c[0]||n[o]);else ie(n[o]);l&&!d&&ie(l)}},X=C(G),K=function(e){h(e.target,s.loadedClass),b(e.target,s.loadingClass),v(e.target,J)},Y=E(K),J=function(e){Y({target:e.target})},Q=function(e,i){try{e.contentWindow.location.replace(i)}catch(s){e.src=i}},Z=function(e){var i,t,o=e[a](s.srcsetAttr);(i=s.customMedia[e[a]("data-media")||e[a]("media")])&&e.setAttribute("media",i),o&&e.setAttribute("srcset",o),i&&(t=e.parentNode,t.insertBefore(e.cloneNode(),e),t.removeChild(e))},ee=E(function(e,i,t,o,n){var r,l,c,u,m,g;(m=y(e,"lazybeforeunveil",i)).defaultPrevented||(o&&(t?h(e,s.autosizesClass):e.setAttribute("sizes",o)),l=e[a](s.srcsetAttr),r=e[a](s.srcAttr),n&&(c=e.parentNode,u=c&&w.test(c.nodeName||"")),g=i.firesLoad||"src"in e&&(l||r||u),m={target:e},g&&(v(e,H,!0),clearTimeout(f),f=d(H,2500),h(e,s.loadingClass),v(e,J,!0)),u&&p.call(c.getElementsByTagName("source"),Z),l?e.setAttribute("srcset",l):r&&!u&&(j.test(e.nodeName)?Q(e,r):e.src=r),(l||u)&&z(e,{src:r})),k(function(){e._lazyRace&&delete e._lazyRace,b(e,s.lazyClass),(!g||e.complete)&&(g?H(m):V--,K(m))})}),ie=function(e){var i,t=W.test(e.nodeName),o=t&&(e[a](s.sizesAttr)||e[a]("sizes")),n="auto"==o;(!n&&u||!t||!e.src&&!e.srcset||e.complete||g(e,s.errorClass))&&(i=y(e,"lazyunveilread").detail,n&&M.updateElem(e,!0,e.offsetWidth),e._lazyRace=!0,V++,ee(e,i,n,o,t))},se=function(){if(!u){if(o.now()-A<999)return void d(se,999);var e=N(function(){s.loadMode=3,X()});u=!0,s.loadMode=3,X(),l("scroll",function(){3==s.loadMode&&(s.loadMode=2),e()},!0)}};return{_:function(){A=o.now(),n=i.getElementsByClassName(s.lazyClass),c=i.getElementsByClassName(s.lazyClass+" "+s.preloadClass),P=s.hFac,l("scroll",X,!0),l("resize",X,!0),e.MutationObserver?new MutationObserver(X).observe(t,{childList:!0,subtree:!0,attributes:!0}):(t[r]("DOMNodeInserted",X,!0),t[r]("DOMAttrModified",X,!0),setInterval(X,999)),l("hashchange",X,!0),["focus","mouseover","click","load","transitionend","animationend","webkitAnimationEnd"].forEach(function(e){i[r](e,X,!0)}),/d$|^c/.test(i.readyState)?se():(l("load",se),i[r]("DOMContentLoaded",X),d(se,2e4)),n.length?G():X()},checkElems:X,unveil:ie}}(),M=function(){var e,t=E(function(e,i,s,t){var o,n,r;if(e._lazysizesWidth=t,t+="px",e.setAttribute("sizes",t),w.test(i.nodeName||""))for(o=i.getElementsByTagName("source"),n=0,r=o.length;r>n;n++)o[n].setAttribute("sizes",t);s.detail.dataAttr||z(e,s.detail)}),o=function(e,i,s){var o,n=e.parentNode;n&&(s=A(e,n,s),o=y(e,"lazybeforesizes",{width:s,dataAttr:!!i}),o.defaultPrevented||(s=o.detail.width,s&&s!==e._lazysizesWidth&&t(e,n,o,s)))},n=function(){var i,s=e.length;if(s)for(i=0;s>i;i++)o(e[i])},r=N(n);return{_:function(){e=i.getElementsByClassName(s.autosizesClass),l("resize",r)},checkElems:r,updateElem:o}}(),T=function(){T.i||(T.i=!0,M._(),S._())};return function(){var i,t={lazyClass:"lazyload",loadedClass:"lazyloaded",loadingClass:"lazyloading",preloadClass:"lazypreload",errorClass:"lazyerror",autosizesClass:"lazyautosizes",srcAttr:"data-src",srcsetAttr:"data-srcset",sizesAttr:"data-sizes",minSize:40,customMedia:{},init:!0,expFactor:1.5,hFac:.8,loadMode:2};s=e.lazySizesConfig||e.lazysizesConfig||{};for(i in t)i in s||(s[i]=t[i]);e.lazySizesConfig=s,d(function(){s.init&&T()})}(),{cfg:s,autoSizer:M,loader:S,init:T,uP:z,aC:h,rC:b,hC:g,fire:y,gW:A,rAF:k}}});
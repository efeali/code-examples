/*!CK:883381504!*//*1421039632,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["+ClWy"]); }

__d("QuickSandHeader",["sha256","Int64"],function(a,b,c,d,e,f,g,h){function i(j,k){"use strict";this.$QuickSandHeader0=1<<k;this.$QuickSandHeader1=this.$QuickSandHeader0/2;this.$QuickSandHeader2=h.fromInt(this.$QuickSandHeader1-1);this.$QuickSandHeader3=[];this.$QuickSandHeader4=0;this.$QuickSandHeader5=0;this.$QuickSandHeader6=0;this.$QuickSandHeader7=0;var l=g(j),m=this.$QuickSandHeader8(this.$QuickSandHeader9(l)),n=this.$QuickSandHeader8(this.$QuickSandHeader9(l).slice(8));this.$QuickSandHeader3.push(m.xor(h.fromString('736f6d6570736575',16)));this.$QuickSandHeader3.push(n.xor(h.fromString('646f72616e646f6d',16)));this.$QuickSandHeader3.push(m.xor(h.fromString('6c7967656e657261',16)));this.$QuickSandHeader3.push(n.xor(h.fromString('7465646279746573',16)));}i.prototype.sipEdge=function(j){"use strict";return [this.sipNode(j,0),this.sipNode(j,1)];};i.prototype.sipNode=function(j,k){"use strict";return this.$QuickSandHeadera(2*j+k).and(this.$QuickSandHeader2).toInt();};i.prototype.getSize=function(){"use strict";return this.$QuickSandHeader0;};i.prototype.getHalfSize=function(){"use strict";return this.$QuickSandHeader1;};i.prototype.$QuickSandHeaderb=function(j,k){"use strict";return j.shiftLeft(k).or(j.shiftRightUnsigned(64-k));};i.prototype.$QuickSandHeader8=function(j){"use strict";var k=new h.fromInt(j[0]),l=h.fromInt(j[1]).shiftLeft(8),m=h.fromInt(j[2]).shiftLeft(16),n=h.fromInt(j[3]).shiftLeft(24),o=h.fromInt(j[4]).shiftLeft(32),p=h.fromInt(j[5]).shiftLeft(40),q=h.fromInt(j[6]).shiftLeft(48),r=h.fromInt(j[7]).shiftLeft(56);return k.or(l).or(m).or(n).or(o).or(p).or(q).or(r);};i.prototype.$QuickSandHeaderc=function(){"use strict";this.$QuickSandHeader4=this.$QuickSandHeader4.add(this.$QuickSandHeader5);this.$QuickSandHeader6=this.$QuickSandHeader6.add(this.$QuickSandHeader7);this.$QuickSandHeader5=this.$QuickSandHeaderb(this.$QuickSandHeader5,13);this.$QuickSandHeader7=this.$QuickSandHeaderb(this.$QuickSandHeader7,16);this.$QuickSandHeader5=this.$QuickSandHeader5.xor(this.$QuickSandHeader4);this.$QuickSandHeader7=this.$QuickSandHeader7.xor(this.$QuickSandHeader6);this.$QuickSandHeader4=this.$QuickSandHeaderb(this.$QuickSandHeader4,32);this.$QuickSandHeader6=this.$QuickSandHeader6.add(this.$QuickSandHeader5);this.$QuickSandHeader4=this.$QuickSandHeader4.add(this.$QuickSandHeader7);this.$QuickSandHeader5=this.$QuickSandHeaderb(this.$QuickSandHeader5,17);this.$QuickSandHeader7=this.$QuickSandHeaderb(this.$QuickSandHeader7,21);this.$QuickSandHeader5=this.$QuickSandHeader5.xor(this.$QuickSandHeader6);this.$QuickSandHeader7=this.$QuickSandHeader7.xor(this.$QuickSandHeader4);this.$QuickSandHeader6=this.$QuickSandHeaderb(this.$QuickSandHeader6,32);};i.prototype.$QuickSandHeader9=function(j){"use strict";for(var k=[],l=0;l<j.length;l+=2)k.push(parseInt(j.substr(l,2),16));return k;};i.prototype.$QuickSandHeadera=function(j){"use strict";var k=h.fromInt(j);this.$QuickSandHeader4=this.$QuickSandHeader3[0];this.$QuickSandHeader5=this.$QuickSandHeader3[1];this.$QuickSandHeader6=this.$QuickSandHeader3[2];this.$QuickSandHeader7=this.$QuickSandHeader3[3].xor(k);for(var l=0;l<2;l++)this.$QuickSandHeaderc();this.$QuickSandHeader4=this.$QuickSandHeader4.xor(k);this.$QuickSandHeader6=this.$QuickSandHeader6.xor(h.fromString('ff',16));for(l=0;l<4;l++)this.$QuickSandHeaderc();return this.$QuickSandHeader4.xor(this.$QuickSandHeader5).xor(this.$QuickSandHeader6).xor(this.$QuickSandHeader7);};e.exports=i;},null);
__d("QuickSandSolver",["Base64","Form","QuickSandHeader","sha256"],function(a,b,c,d,e,f,g,h,i,j){var k={solveAndUpdateForm:function(l,m,n,o,p,q,r){var s=JSON.stringify([this.solve(l,m,n,o,p),r]),t=document.getElementById(q);h.createHiddenInputs({qsstamp:g.encode(s)},t);},solve:function(l,m,n,o,p){var q=[],r=m;for(var s=0;s<l;s++){q.push(k.solveOneIteration(r,n,o,p));r=k.hashList(q[s]);}return q;},solveOneIteration:function(l,m,n,o){var p=8192,q=new i(l,m),r=o*q.getSize()/100,s=[],t=[],u=[];for(var v=0;v<r;v++){var w=q.sipEdge(v),x=w[0],y=w[1];x+=1;y+=1+q.getHalfSize();var z=s[x],aa=s[y];if(z==y||aa==x)continue;t[0]=x;u[0]=y;var ba=k.path(z,t,s,p),ca=k.path(aa,u,s,p);if(t[ba]==u[ca]){var da=Math.min(ba,ca);for(ba-=da,ca-=da;t[ba]!=u[ca];ba++,ca++);var ea=ba+ca+1;if(ea==n)return k.recoverSolution(ba,ca,t,u,q,n,r);continue;}if(ba<ca){while(ba--)s[t[ba+1]]=t[ba];s[x]=y;}else{while(ca--)s[u[ca+1]]=u[ca];s[y]=x;}}return [];},path:function(l,m,n,o){var p=0;for(p=0;l;l=n[l]){if(++p>=o){while(p--&&m[p]!=l);if(p<0){throw "Maximum path length was exceeded";}else throw "Illegal cycle has occured";}m[p]=l;}return p;},recoverSolution:function(l,m,n,o,p,q,r){var s=function(){};s.prototype.add=function(y){this[y]=true;};s.prototype.remove=function(y){delete this[y];};var t=[],u=new s();u.add([n[0],o[0]]);while(l--)u.add([n[(l+1)&~1],n[(l|1)]]);while(m--)u.add([o[m|1],o[(m+1)&~1]]);var v=0;for(var w=0;w<r;w++){var x=[1+p.sipNode(w,0),1+p.getHalfSize()+p.sipNode(w,1)];if(x in u){t[v++]=w;u.remove(x);}}return t;},hashList:function(l){var m=l.join('-');return j(m);}};e.exports=k;},null);
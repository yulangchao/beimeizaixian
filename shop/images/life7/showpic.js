//获取文档对象
$id:function(id){return document.getElementById(id);},
$tag:function(tagName,obj){return (obj ?obj : document).getElementsByTagName(tagName);	},
$c：function (claN,obj){
   var tag = $tag('*'),reg = new RegExp('(^|\\s)'+claN+'(\\s|$)'),arr=[];
   for(var i=0;i<tag.length;i++){
    if (reg.test(tag[i].className)){
       arr.push(tag[i]);
     }
   }
   return arr;
};
//移除和添加class   
$add:function(obj,claN){
reg = new RegExp('(^|\\s)'+claN+'(\\s|$)');
if (!reg.test(obj.className)){
obj.className += ' '+claN;
}
}；
$remve:function(obj,claN){var cla=obj.className,reg="/\\s*"+claN+"\\b/g";obj.className=cla?cla.replace(eval(reg),''):''}；
//css获取方法
css:function(obj,attr,value){
if(value){
 obj.style[attr] = value;
}else{
  return  typeof window.getComputedStyle != 'undefined' ? window.getComputedStyle(obj,null)[attr] : obj.currentStyle[attr];
  }
};
//常用的esaing方法
easing ={
linear:function(t,b,c,d){return c*t/d + b;},
swing:function(t,b,c,d) {return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;},
easeIn:function(t,b,c,d){return c*(t/=d)*t*t*t + b;},
easeOut:function(t,b,c,d){return -c*((t=t/d-1)*t*t*t - 1) + b;},
easeInOut:function(t,b,c,d){return ((t/=d/2) < 1)?(c/2*t*t*t*t + b):(-c/2*((t-=2)*t*t*t - 2) + b);}
};
//初始化
config:{
   index:0,
   auto:true,
  direct:'left' //滚动方向，left,top,
},
//事件触发
init:function(){
    this.showpic = this.$id('showpic');
    this.ImgBox = this.$c('ImgBox')[0],
    this.slide_btn = this.$tag('a',this.$c('slide-btn')[0]);
    this.img_arr = this.$tag('img',this.showpic);
    if(this.config.auto) this.play();//是否自动播放
    this.hover();//绑定导航悬停切换。即mouseover,mouseout事件
}
//动画效果
animate:function(obj,attr,val){
    var d = 1000;//动画时间一秒完成。
    if(obj[attr+'timer']) clearInterval(obj[attr+'timer']);
    var start = parseInt(zBase.css(obj,attr));//动画开始位置
    //space = 动画结束位置-动画开始位置，即动画要运动的距离。
    var space =  val- start,st=(new Date).getTime(),m=space>0? 'ceil':'floor';
    obj[attr+'timer'] = setInterval(function(){
        var t=(new Date).getTime()-st;//表示运行了多少时间，
        if (t < d){//如果运行时间小于动画时间
            zBase.css(obj,attr,Math[m](zBase.easing['easeOut'](t,start,space,d)) +'px');
        }else{
            clearInterval(obj[attr+'timer']);
            zBase.css(obj,attr,top+space+'px');
        }
    },20);
};
//导航切换
hover:function(){
    for(var i=0;i<this.slide_btn.length;i++){
        this.slide_btn[i].index = i;
        this.slide_btn[i].onmouseover = function(){
            if(zBase.showpic.timer) clearInterval(zBase.showpic.timer);
            zBase.config.index =this.index; 
            for(var j=0;j<zBase.slide_btn.length;j++){
                zBase.$remve(zBase.slide_btn[j],'hover') ;
            }
            zBase.$add(zBase.slide_btn[zBase.config.index],'hover');
           zBase.animate(zBase.ImgBox,zBase.config.direct,-zBase.config.index*500);//500是轮播器的大小，当前索引乘以500，再取负，就是轮播器要滚动f到的位置。
         }
         this.slide_btn[i].onmouseout = function(){
            zBase.play();
         }
    }
};
//自动播放
play:function(){
    this.showpic.timer = setInterval(function(){
         zBase.config.index++;
        if(zBase.config.index>=zBase.img_arr.length) zBase.config.index=0;
        zBase.animate(zBase.showpic,zBase.config.direct,-zBase.config.index*500);
       for(var j=0;j<zBase.slide_btn.length;j++){
          zBase.$remve(zBase.slide_btn[j],'hover') ;
       }
       zBase.$add(zBase.slide_btn[zBase.config.index],'hover');
    },3000)
};
zBase.init();




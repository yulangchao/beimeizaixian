//��ȡ�ĵ�����
$id:function(id){return document.getElementById(id);},
$tag:function(tagName,obj){return (obj ?obj : document).getElementsByTagName(tagName);	},
$c��function (claN,obj){
   var tag = $tag('*'),reg = new RegExp('(^|\\s)'+claN+'(\\s|$)'),arr=[];
   for(var i=0;i<tag.length;i++){
    if (reg.test(tag[i].className)){
       arr.push(tag[i]);
     }
   }
   return arr;
};
//�Ƴ������class   
$add:function(obj,claN){
reg = new RegExp('(^|\\s)'+claN+'(\\s|$)');
if (!reg.test(obj.className)){
obj.className += ' '+claN;
}
}��
$remve:function(obj,claN){var cla=obj.className,reg="/\\s*"+claN+"\\b/g";obj.className=cla?cla.replace(eval(reg),''):''}��
//css��ȡ����
css:function(obj,attr,value){
if(value){
 obj.style[attr] = value;
}else{
  return  typeof window.getComputedStyle != 'undefined' ? window.getComputedStyle(obj,null)[attr] : obj.currentStyle[attr];
  }
};
//���õ�esaing����
easing ={
linear:function(t,b,c,d){return c*t/d + b;},
swing:function(t,b,c,d) {return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;},
easeIn:function(t,b,c,d){return c*(t/=d)*t*t*t + b;},
easeOut:function(t,b,c,d){return -c*((t=t/d-1)*t*t*t - 1) + b;},
easeInOut:function(t,b,c,d){return ((t/=d/2) < 1)?(c/2*t*t*t*t + b):(-c/2*((t-=2)*t*t*t - 2) + b);}
};
//��ʼ��
config:{
   index:0,
   auto:true,
  direct:'left' //��������left,top,
},
//�¼�����
init:function(){
    this.showpic = this.$id('showpic');
    this.ImgBox = this.$c('ImgBox')[0],
    this.slide_btn = this.$tag('a',this.$c('slide-btn')[0]);
    this.img_arr = this.$tag('img',this.showpic);
    if(this.config.auto) this.play();//�Ƿ��Զ�����
    this.hover();//�󶨵�����ͣ�л�����mouseover,mouseout�¼�
}
//����Ч��
animate:function(obj,attr,val){
    var d = 1000;//����ʱ��һ����ɡ�
    if(obj[attr+'timer']) clearInterval(obj[attr+'timer']);
    var start = parseInt(zBase.css(obj,attr));//������ʼλ��
    //space = ��������λ��-������ʼλ�ã�������Ҫ�˶��ľ��롣
    var space =  val- start,st=(new Date).getTime(),m=space>0? 'ceil':'floor';
    obj[attr+'timer'] = setInterval(function(){
        var t=(new Date).getTime()-st;//��ʾ�����˶���ʱ�䣬
        if (t < d){//�������ʱ��С�ڶ���ʱ��
            zBase.css(obj,attr,Math[m](zBase.easing['easeOut'](t,start,space,d)) +'px');
        }else{
            clearInterval(obj[attr+'timer']);
            zBase.css(obj,attr,top+space+'px');
        }
    },20);
};
//�����л�
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
           zBase.animate(zBase.ImgBox,zBase.config.direct,-zBase.config.index*500);//500���ֲ����Ĵ�С����ǰ��������500����ȡ���������ֲ���Ҫ����f����λ�á�
         }
         this.slide_btn[i].onmouseout = function(){
            zBase.play();
         }
    }
};
//�Զ�����
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




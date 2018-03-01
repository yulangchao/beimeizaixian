var slide_obj=$('.SlideContThe1');
var slide_html=slide_obj.find('ul');
var slide_lists=slide_html.find('li');
slide_obj.append('<div class="titles"></div>');
slide_obj.append('<div class="MinPicBox"><div class="pics"></div></div>');
slide_obj.append('<div class="actions"><div class="prev"><span></span><em>上一页</em></div><div class="next"><span></span><em>下一页</em></div></div>');
var slide_title=slide_obj.find('.titles');
var slide_minBox=slide_obj.find('.MinPicBox');
var slide_pics=slide_obj.find('.pics');
var slide_actions=slide_obj.find('.actions');
var slide_prev=slide_actions.find('.prev');
var slide_next=slide_actions.find('.next');
slide_lists.each(function(){
	slide_title.append('<div>'+$(this).find('a').attr("title")+'</div>');	
	slide_pics.append('<div>'+$(this).html()+'</div>');	
});
var slide_pichtmls=slide_pics.find('div');
var slide_picnums=slide_pichtmls.length;
var slide_minshow=6;
var slide_changenum=Math.ceil(slide_picnums/slide_minshow);
var slide_minwidth=780;
var slide_maxwidth=slide_minwidth*slide_changenum;
var slide_minveverwidth=Math.floor(slide_minwidth/slide_minshow);
var change_now=0;
function chang_functions(nums){
	var left_widths=slide_minwidth*nums;
	slide_pics.stop().animate({'left':'-'+left_widths+'px'},500);
}

slide_obj.css({'width':'830px','height':'520px','position':'relative'});
slide_html.css({'width':'830px','height':'430px','overflow':'hidden','text-align':'center','line-height':'430px'});
slide_lists.find('img').css({'width':'830px','height':'430px','border-radius':'5px','vertical-align':'middle'});
slide_title.css({'width':'830px','height':'50px','overflow':'hidden','position':'absolute','z-index':'100000','left':'0','bottom':'90px','background':'#5C656A','opacity':'0.8','border-radius':'0 0 5px 5px'});
slide_title.find('div').css({'height':'50px','overflow':'hidden','line-height':'50px','padding':'0 10px 0 15px','font-size':'16px','color':'#FFFFFF'});
slide_actions.find('div').css({'width':'25px','height':'50px','position':'absolute','overflow':'hidden','bottom':'12px'});
slide_prev.css({'left':'-5px'});
slide_next.css({'right':'-5px'});
slide_actions.find('span').css({'display':'block','position':'absolute','width':'0px','height':'0px','border':'25px solid transparent'});
slide_actions.find('em').css({'display':'block','position':'absolute','width':'0px','height':'0px','border':'25px solid transparent','overflow':'hidden'});
slide_prev.find('span').css({'border-right':'25px solid #AAA','border-left':'0','left':'0px','top':'0px'});
slide_prev.find('em').css({'border-right':'25px solid #FFF','border-left':'0','left':'5px','top':'0px'});
slide_next.find('span').css({'border-left':'25px solid #AAA','border-right':'0','left':'0px','top':'0px'});
slide_next.find('em').css({'border-left':'25px solid #FFF','border-right':'0','right':'5px','top':'0px'});
slide_minBox.css({'width':slide_minwidth+'px','height':'80px','position':'absolute','left':'25px','bottom':'0px','overflow':'hidden'});
slide_pics.css({'width':slide_maxwidth+'px','height':'80px','position':'absolute','left':'0px','top':'0px','overflow':'hidden'});
slide_pichtmls.css({'width':slide_minveverwidth+'px','height':'80px','float':'left','overflow':'hidden','opacity':'0.7'});
slide_pichtmls.find('a').css({'display':'block','width':'120px','height':'80px','margin':'auto','overflow':'hidden','text-align':'center','line-height':'80px'});
slide_pichtmls.find('img').css({'width':'120px','height':'80px','vertical-align':'middle'});

function slide_changeshow(num){
	slide_lists.css({'opacity':0});
	slide_lists.hide();
	var this_big_picurl=slide_lists.eq(num).find('img').attr("name");
	slide_lists.eq(num).find('img').attr("src",this_big_picurl);
	slide_lists.eq(num).show();
	slide_lists.eq(num).animate({'opacity':1},500);
	slide_pichtmls.css({'opacity':0.3});
	slide_pichtmls.eq(num).css({'opacity':1});
	slide_title.find('div').hide();	
	slide_title.find('div').eq(num).show();	
}

slide_pichtmls.mouseout(function(){
	autoPlaysThe1();
});
slide_prev.hover(
	function(){
		$(this).find('span').css({'border-right':'25px solid #F00'});	
	},
	function(){
		$(this).find('span').css({'border-right':'25px solid #AAA'});
	}
);
slide_next.hover(
	function(){
		$(this).find('span').css({'border-left':'25px solid #F00'});	
	},
	function(){
		$(this).find('span').css({'border-left':'25px solid #AAA'});
	}
);

var timersThe1;
function autoPlaysThe1(){
	timersThe1 = setInterval("next_slideThe1()",5000);
}
function stopPlaysThe1() {
	clearInterval(timersThe1);
}
nowListThe1=0;
slide_changeshow(nowListThe1);
function next_slideThe1(){
	nowListThe1++;
	if(nowListThe1>slide_picnums-1){
		nowListThe1=0;
	}
	slide_changeshow(nowListThe1);
	var change_now1=Math.floor(nowListThe1/slide_minshow);
	if(change_now1!=change_now){
		change_now=change_now1;
		chang_functions(change_now);
	}
}
autoPlaysThe1();
slide_pichtmls.mouseover(function(){
	nowListThe1=$(this).index();						
	slide_changeshow(nowListThe1);
	stopPlaysThe1();
});
slide_prev.click(function(){
	change_now--;
	if(change_now<0){
		change_now=slide_changenum-1;
	}
	chang_functions(change_now);
	nowListThe1=slide_minshow*change_now;
	slide_changeshow(nowListThe1);
});
slide_next.click(function(){
	change_now++;
	if(change_now>slide_changenum-1){
		change_now=0;
	}
	chang_functions(change_now);
	nowListThe1=slide_minshow*change_now;
	slide_changeshow(nowListThe1);
});
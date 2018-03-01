var slide_w=480;
var slide_h=370;
var slide_s=4;
var slide_obj=$('.ShopPicShowStyle');
var slide_html=slide_obj.find('ul');
var slide_lists=slide_html.find('li');
var slide_picnums=slide_lists.length;
var slide_changenum=Math.ceil(slide_picnums/slide_s);
var slide_maxwidth=slide_w*slide_changenum;
var slide_mW=Math.ceil(slide_w/slide_s);
slide_obj.append('<div class="ShowBigPic"><span><img src="./images/pc/icon.png"></span></div>');
var slide_bigPic=slide_obj.find('.ShowBigPic img');
slide_obj.append('<div class="actions"><div class="prev"><span></span><em>上一页</em></div><div class="next"><span></span><em>下一页</em></div></div>');
var slide_actions=slide_obj.find('.actions');
var slide_prev=slide_actions.find('.prev');
var slide_next=slide_actions.find('.next');
if(slide_picnums<slide_s){
	slide_actions.css({'display':'none'});
}
slide_obj.css({'width':slide_w+'px','height':slide_h+'px','position':'relative'});
slide_html.css({'width':slide_maxwidth+'px','height':'70px','position':'absolute','left':'0px','bottom':'0px'});
slide_lists.css({'width':slide_mW+'px','height':'70px','overflow':'hidden','float':'left','opacity':'0.7'});
slide_lists.find('span').css({'display':'block','width':'108px','height':'68px','overflow':'hidden','border':'#DDD solid 1px','margin':'auto','text-align':'center','line-height':'66px'});
slide_lists.find('img').css({'max-width':'104px','max-height':'66px','vertical-align':'middle'});
slide_actions.find('div').css({'width':'25px','height':'50px','position':'absolute','overflow':'hidden','top':'100px','opacity':'0.7'});
slide_prev.css({'left':'15px'});
slide_next.css({'right':'15px'});
slide_actions.find('span').css({'display':'block','position':'absolute','width':'0px','height':'0px','border':'25px solid transparent'});
slide_actions.find('em').css({'display':'block','position':'absolute','width':'0px','height':'0px','border':'25px solid transparent','overflow':'hidden'});
slide_prev.find('span').css({'border-right':'25px solid #AAA','border-left':'0','left':'0px','top':'0px'});
//slide_prev.find('em').css({'border-right':'25px solid #FFF','border-left':'0','left':'5px','top':'0px'});
slide_next.find('span').css({'border-left':'25px solid #AAA','border-right':'0','left':'0px','top':'0px'});
//slide_next.find('em').css({'border-left':'25px solid #FFF','border-right':'0','right':'5px','top':'0px'});
slide_obj.find('.ShowBigPic').css({'display':'table','width':slide_w+'px','height':'280px'});
slide_obj.find('.ShowBigPic span').css({'display':'table-cell','text-align':'center','vertical-align':'middle'});
slide_bigPic.css({'max-width':slide_w+'px','max-height':'280px'});

function slide_changeshow(num){
	slide_bigPic.css({'opacity':0});
	var this_big_picurl=slide_lists.eq(num).find('img').attr("src");
	slide_bigPic.attr("src",this_big_picurl);
	slide_bigPic.animate({'opacity':1},500);
	slide_lists.css({'opacity':0.5});
	slide_lists.eq(num).css({'opacity':1});
}
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
var nowListThe1=0;
var change_now=0;
slide_changeshow(nowListThe1);
function next_slideThe1(){
	nowListThe1++;
	if(nowListThe1>slide_picnums-1){
		nowListThe1=0;
	}
	slide_changeshow(nowListThe1);
	var change_now1=Math.floor(nowListThe1/slide_s);
	if(change_now1!=change_now){
		change_now=change_now1;
		chang_functions(change_now);
	}
}
function prev_slideThe1(){
	nowListThe1--;
	if(nowListThe1<0){
		nowListThe1=slide_picnums-1;
	}
	slide_changeshow(nowListThe1);
	var change_now1=Math.floor(nowListThe1/slide_s);
	if(change_now1!=change_now){
		change_now=change_now1;
		chang_functions(change_now);
	}
}
function chang_functions(nums){
	var left_widths=slide_w*nums;
	slide_html.stop().animate({'left':'-'+left_widths+'px'},500);
}
slide_prev.click(function(){
	prev_slideThe1();
});
slide_next.click(function(){
	next_slideThe1();
});
var timersThe1;
function autoPlaysThe1(){
	timersThe1 = setInterval("next_slideThe1()",5000);
}
function stopPlaysThe1() {
	clearInterval(timersThe1);
}
autoPlaysThe1();
slide_lists.hover(
	function(){
		nowListThe1=$(this).index();						
		slide_changeshow(nowListThe1);
		stopPlaysThe1();
	},
	function(){
		autoPlaysThe1();
	}
);
slide_bigPic.hover(
	function(){
		stopPlaysThe1();
	},
	function(){
		autoPlaysThe1();
	}
);
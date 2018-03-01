var slideimgnum=$('.MainSlide .slideimgs li').length;
var nowSlideList=0;
function change_slide(num){
	$('.MainSlide .slideimgs li').css({'left':'110%','z-index':'1'});
	var prev_num=num-1;
	if(prev_num<0){
		prev_num=slideimgnum-1;
	}
	$('.MainSlide .slideimgs li').eq(prev_num).css({'left':'0px'});
	$('.MainSlide .slideimgs li').eq(num).css({'z-index':'2'});
	$('.MainSlide .slideimgs li').eq(num).animate({'left':'0px','z-index':'2'},1000);
	$('.MainSlide .listnum li').removeClass('ck');
	$('.MainSlide .listnum li').eq(num).addClass('ck');
}
change_slide(0);
function prev_slide(){
	nowSlideList--;
	if(nowSlideList<0){
		nowSlideList=slideimgnum-1;
	}
	$('.MainSlide .slideimgs li').css({'left':'-110%','z-index':'1'});
	var prev_num=nowSlideList+1;
	if(prev_num>=slideimgnum){
		prev_num=0;
	}
	$('.MainSlide .slideimgs li').eq(prev_num).css({'left':'0px'});
	$('.MainSlide .slideimgs li').eq(nowSlideList).css({'z-index':'2'});
	$('.MainSlide .slideimgs li').eq(nowSlideList).animate({'left':'0px','z-index':'2'},1000);
	$('.MainSlide .listnum li').removeClass('ck');
	$('.MainSlide .listnum li').eq(nowSlideList).addClass('ck');
}
function next_slide(){
	nowSlideList++;
	if(nowSlideList>=slideimgnum){
		nowSlideList=0;
	}
	change_slide(nowSlideList);
}
var timers;
function autoPlays(){
	timers = setInterval("next_slide()",3000);
}
function stopPlays() {
	clearInterval(timers);
}
autoPlays();
$('.MainSlide').hover(
	function(){
		stopPlays();
	},
	function(){
		autoPlays();
	}
);
$('.MainSlide .listnum li').click(function(){
	nowSlideList=$(this).index();
	change_slide(nowSlideList);
});
$('.MainSlide .prev').click(function(){
	prev_slide();
});
$('.MainSlide .next').click(function(){
	next_slide();
});
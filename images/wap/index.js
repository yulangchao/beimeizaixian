$(".ChangeShow1 .cont1 li").each(function(){
	var this_W=$(this).width()-130;
	$(".ChangeShow1 .cont1 li .title").css("width",this_W+"px");
	$(".ChangeShow1 .cont1 li p").css("width",this_W+"px");
	$(".ChangeShow1 .cont1 li .more").css("width",this_W+"px");
});
$(".ChangeShow1 .cont2 .img").each(function(){
	var this_H=$(this).width()*0.7;
	$(this).css("height",this_H+"px");
});
$(".ChangeShow1 .Heads .tag1").addClass('ck');
$(".ChangeShow1 ul").hide();
$(".ChangeShow1 .cont1").show();
$(".ChangeShow1 .Heads .tag1").click(function(){
	$(".ChangeShow1 .Heads div").removeClass('ck');
	$(this).addClass('ck');
	$(".ChangeShow1 ul").hide();
	$(".ChangeShow1 .cont1").show();
});
$(".ChangeShow1 .Heads .tag2").click(function(){
	$(".ChangeShow1 .Heads div").removeClass('ck');
	$(this).addClass('ck');
	$(".ChangeShow1 ul").hide();
	$(".ChangeShow1 .cont2").show();
});
$(".ChangeShow1 .Heads .tag3").click(function(){
	$(".ChangeShow1 .Heads div").removeClass('ck');
	$(this).addClass('ck');
	$(".ChangeShow1 ul").hide();
	$(".ChangeShow1 .cont3").show();
});
$(".ChangeShow1 .Heads .tag4").click(function(){
	$(".ChangeShow1 .Heads div").removeClass('ck');
	$(this).addClass('ck');
	$(".ChangeShow1 ul").hide();
	$(".ChangeShow1 .cont4").show();
});


$(".ChangeShow2 .Heads .tag2").addClass('ck');
$(".ChangeShow2 ul").hide();
$(".ChangeShow2 .cont2").show();
$(".ChangeShow2 .Heads .tag1").click(function(){
	$(".ChangeShow2 .Heads div").removeClass('ck');
	$(this).addClass('ck');
	$(".ChangeShow2 ul").hide();
	$(".ChangeShow2 .cont1").show();
	$(".ChangeShow2 .cont1 li a").each(function(){
		var this_H=$(this).width()*0.6;
		$(this).css("height",this_H+"px");
	});
});
$(".ChangeShow2 .Heads .tag2").click(function(){
	$(".ChangeShow2 .Heads div").removeClass('ck');
	$(this).addClass('ck');
	$(".ChangeShow2 ul").hide();
	$(".ChangeShow2 .cont2").show();
});
$(".ChangeShow2 .cont1 li").each(function(){
	var this_W=$(".ChangeShow2 .cont1").width()/3;
	$(this).css("width",this_W+"px");
});
$(".ChangeShow2 .cont1 li a").each(function(){
	var this_H=$(this).width()*0.6;
	$(this).css("height",this_H+"px");
});
$(".ShowMiddleAd ul li img").each(function(){
	var this_H=$(".ShowMiddleAd ul").width()/4.2;
	$(this).css("height",this_H+"px");
});
$(".ChangeShow3 ul li a").each(function(){
	var this_H=$(this).width()*0.6;
	$(this).css("height",this_H+"px");
});

$(".ChangeShow3 .Heads .tag1").addClass('ck');
$(".ChangeShow3 ul").hide();
$(".ChangeShow3 .cont1").show();
$(".ChangeShow3 .Heads .tag1").click(function(){
	$(".ChangeShow3 .Heads div").removeClass('ck');
	$(this).addClass('ck');
	$(".ChangeShow3 ul").hide();
	$(".ChangeShow3 .cont1").show();
});
$(".ChangeShow3 .Heads .tag2").click(function(){
	$(".ChangeShow3 .Heads div").removeClass('ck');
	$(this).addClass('ck');
	$(".ChangeShow3 ul").hide();
	$(".ChangeShow3 .cont2").show();
});
$(".MoreModule ul li a").each(function(){
	var this_H=$(this).width();
	$(this).css("padding-top",this_H+"px");
});
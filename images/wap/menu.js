$(".showmenu .tag").click(function(){
	if($(".showmenu .menus1").hasClass('showhidden')&&$(".showmenu .menus2").hasClass('showhidden')){
		$(".showmenu .menus1").removeClass('showhidden');
	}else{
		$(".showmenu .menus1").addClass('showhidden');
		$(".showmenu .menus2").addClass('showhidden');
	}
});
$(".showmenu .menus1 .more2").click(function(){
	$(".showmenu .menus2").removeClass('showhidden');
	$(".showmenu .menus1").addClass('showhidden');	
});
$(".showmenu .menus2 .more1").click(function(){
	$(".showmenu .menus1").removeClass('showhidden');
	$(".showmenu .menus2").addClass('showhidden');	
});
$('.header .user').click(function(){
	if($('.header .MemberCont').hasClass("showThis")){
		$('.header .MemberCont').removeClass('showThis');
		//$('.header .MemberCont').hide('slow');
		$('.header .MemberCont').animate({'height':'0px'},300);
	}else{
		$('.header .MemberCont').addClass('showThis');
		//$('.header .MemberCont').show('slow');
		$('.header .MemberCont').animate({'height':'60px'},300);
	}								  
});
$('.header .search').click(function(){
	if ($(window).height() > $("body").height()) {
		$('.SearchbgFull').height($(window).height());
	} else {
		$('.SearchbgFull').height($("body").height());
	}
	$('.SearchbgFull').css({'display':'block'}).stop().animate({'opacity':.6},300);
	$('.TopSearch').stop().animate({'height':'60px'},300);
});
$('.SearchbgFull').click(function(){
	$('.SearchbgFull').animate({'opacity':0},300,function(){
		$(this).css({'display':'none'});
	});
	setTimeout(function(){
		$('.TopSearch').stop().animate({'height':'0px'},300);
	}, 500)							  
});
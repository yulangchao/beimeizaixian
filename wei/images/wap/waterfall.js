function loadMore(urls){	
	$.ajax({
		url : urls+'&'+Math.random(),
		dataType : 'json',
		success : function(json){
			if(typeof json == 'object'){
				var oProduct,show_html,onerrors="this.src='../images/default/nopic.jpg'";;
				if(json.length>0){					
					loading=1;
				}else{
					loading=0;					
				}
				show_html="";
				for(var i=0; i<json.length; i++){
					oProduct = json[i];
					show_html+='<dl>';
					if(oProduct.picurl){
						show_html+='<dt><a href="bencandy.php?fid='+oProduct.fid+'&id='+oProduct.id+'"><img src="'+oProduct.picurl+'" onerror="'+onerrors+'"></a></dt>';
					}
					show_html+='<dd>';
					show_html+='<h4><a href="bencandy.php?fid='+oProduct.fid+'&id='+oProduct.id+'">'+oProduct.title+'</a></h4>';
					show_html+='<p>'+oProduct.content+'</p>';
					show_html+='<div>发布者(<a href="$webdb[www_url]/member/homepage.php?uid='+oProduct.uid+'">'+oProduct.username+'</a>) 点击(<span>'+oProduct.hits+'</span>)</div>';
					show_html+='</dd>';
					show_html+='</dl>';
				}
				if(show_html!=''){
					$(".ListWei").append(show_html);
				}				
			}
		}
	});
}
function format_height(node,fullbg){
	if(node.height()<$(window).height()||node.height()<$("body").height()){
		if ($(window).height() > $("body").height()) {
			fullbg.height($(window).height());
			node.height($(window).height());
		} else {
			fullbg.height($("body").height());
			node.height($("body").height());
		}
	}else{
		node.height(node.height());
		fullbg.height(node.height());
	}
}
function show_nav(node,fullbg){
	fullbg.css({'display':'block'}).stop().animate({'opacity':.6},500,function(){
		node.stop().animate({'width':'250px'},300);
	});
}
function hide_nav(node,fullbg){
	fullbg.animate({'opacity':0},300,function(){$(this).css({'display':'none'});});
	setTimeout(function(){
		node.stop().animate({'width':'1px'},300);
	}, 500)
}

$("#show_sort").click(function(){
	format_height($('#navbox_sort'),$('#fullbg1'));
	show_nav($('#navbox_sort'),$('#fullbg1'));
});
$('#fullbg1').click(function(){
	hide_nav($('#navbox_sort'),$(this));
});
$("#show_zone").click(function(){
	format_height($('#navbox_zone'),$('#fullbg2'));
	show_nav($('#navbox_zone'),$('#fullbg2'));
});
$('#fullbg2').click(function(){
	hide_nav($('#navbox_zone'),$(this));
});
$("#navbox_sort dl dt").click(function(){	
	$("#navbox_sort dl").removeClass("ck");
	$(this).parent().addClass("ck");
	format_height($('#navbox_sort'),$('#fullbg1'));
});
$("#navbox_zone dl dt").click(function(){	
	$("#navbox_zone dl").removeClass("ck");
	$(this).parent().addClass("ck");
	format_height($('#navbox_zone'),$('#fullbg2'));
});
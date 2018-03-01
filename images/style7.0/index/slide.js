var table=document.getElementById("pictable");
var table_tr=table.rows.length;
var tb_list=table_tr-1;		   
var picarry1 = {};
var lnkarry1 = {};
var ttlarry1 = {};
function FixCodes(str){
	return str.replace("'","=");
}
for(var i=0;i<table_tr;i++){
	try{
		picarry1[i]=table.rows[i].cells[0].childNodes[0].src;
		lnkarry1[i]=table.rows[i].cells[2].innerHTML;
		lnkarry1[i] = lnkarry1[i].replace(/&amp;/g, "&"); 
		ttlarry1[i]=FixCodes(table.rows[i].cells[1].innerHTML);
	}catch(e){

	}
}  
document.write("<div class='slideConts'>");
document.write("<ul class='ListPic'>");
for(var k=0;k<table_tr;k++){
	document.write("<li><a href='"+lnkarry1[k]+"' title='"+ttlarry1[k]+"'><img src='"+picarry1[k]+"'><\/a><p>"+ttlarry1[k]+"<\/p><\/li>");
}
document.write("<\/ul><ul class='ListNum'>");
for(var j=1;j<table_tr+1;j++){
	document.write("<li>"+j+"<\/li>");
}
document.write("<\/ul><\/div>");
document.close();

var slide_nums=$('.slideConts .ListPic li').length;
var beginnum=0;
function changSlide(num){
	$('.slideConts .ListPic li').css({'opacity':0,'left':'760px'});
	$('.slideConts .ListPic li').eq(num).css({'opacity':1});
	$('.slideConts .ListPic li').eq(num).stop().animate({'left':'0px'},300);
	$('.slideConts .ListNum li').removeClass('ck');
	$('.slideConts .ListNum li').eq(num).addClass('ck');
}
function next_changSlide(){
	beginnum++;
	if(beginnum>slide_nums-1){
		beginnum=0;
	}
	changSlide(beginnum);
}
changSlide(beginnum);
var slideing;
function autoSlide(){
	slideing = setInterval("next_changSlide()",5000);
}
function stopSlide() {
	clearInterval(slideing);
}
autoSlide();
$('.slideConts').hover(
	function(){
		stopSlide();
	},
	function(){
		autoSlide();
	}
);
$('.slideConts .ListNum li').click(function(){
	beginnum=$(this).index();
	changSlide(beginnum);
});





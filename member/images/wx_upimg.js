wx.config({
	debug: false,
	appId: appId_str,
	timestamp: timestamp_num,
	nonceStr: nonceStr_str,
	signature: signature_str,
	jsApiList: [
		'checkJsApi',
		'chooseImage',
		'previewImage',
		'uploadImage',
	  ]
});
wx.ready(function () {
  // 5 图片接口
  // 5.1 拍照、本地选图
  var images = {
    localId: [],
    serverId: []
  };
  document.querySelector('#chooseImage').onclick = function () {
    wx.chooseImage({
      success: function (res) {
        images.localId = res.localIds;
        //alert('已选择 ' + res.localIds.length + ' 张图片，请点击上传按钮开始上传。');
		$('.upflieBox p').html('已选择'+res.localIds.length+'张图片<br/><font color="red">请点击下面第二个按键上传图片</font>');
		$('#uploadImage').css({"background":"#F60","border":"#F30 solid 1px"});
		$('#chooseImage').css({"background":"#EFEFEF","border":"#DDD solid 1px"});
		postimg();
      }
    });
  };

  // 5.3 上传图片
  document.querySelector('#uploadImage').onclick = function () {
    if (images.localId.length == 0) {
      //alert('请先选择图片');
	  $('.upflieBox p').html('请先点击下面第一个按键选择图片');
      return;
    }
    var i = 0, length = images.localId.length;
    images.serverId = [];
    function upload() {
      wx.uploadImage({
        localId: images.localId[i],
        success: function (res) {
          i++;
          //alert('已上传：' + i + '/' + length);
		  alert('已上传：第 ' + i + ' 张图片，请点击确认继续上传。' );
		  //window.location.href="u.php?urls="+res.serverId
		  document.querySelector('#morepicurl').value+=","+res.serverId;
          images.serverId.push(res.serverId);
          if (i < length) {
            upload();
          }else{
		  	$('.upflieBox p').html('请选择上传的图片');
			SharHiddenMessage();
			//对微信上传的图片时行后处理
			get_weixin_up_file();
		  }
        },
        fail: function (res) {
          alert(JSON.stringify(res));
        }
      });	  
    }
    upload();	
  };
});
wx.error(function (res) {
  alert(res.errMsg);
});
function Atcupimg(){
	$('.Share_Message').fadeIn();
	$('.upflieBox').fadeIn();
}
function SharHiddenMessage() {
	$('.Share_Message').fadeOut();
	$('.upflieBox').fadeOut();
	$('.upflieBox p').html('请选择上传的图片');
	$('#chooseImage').css({"background":"#F60","border":"#F30 solid 1px"});
	$('#uploadImage').css({"background":"#EFEFEF","border":"#DDD solid 1px"});
}
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
  // 5 ͼƬ�ӿ�
  // 5.1 ���ա�����ѡͼ
  var images = {
    localId: [],
    serverId: []
  };
  document.querySelector('#chooseImage').onclick = function () {
    wx.chooseImage({
      success: function (res) {
        images.localId = res.localIds;
        //alert('��ѡ�� ' + res.localIds.length + ' ��ͼƬ�������ϴ���ť��ʼ�ϴ���');
		$('.upflieBox p').html('��ѡ��'+res.localIds.length+'��ͼƬ<br/><font color="red">��������ڶ��������ϴ�ͼƬ</font>');
		$('#uploadImage').css({"background":"#F60","border":"#F30 solid 1px"});
		$('#chooseImage').css({"background":"#EFEFEF","border":"#DDD solid 1px"});
		postimg();
      }
    });
  };

  // 5.3 �ϴ�ͼƬ
  document.querySelector('#uploadImage').onclick = function () {
    if (images.localId.length == 0) {
      //alert('����ѡ��ͼƬ');
	  $('.upflieBox p').html('���ȵ�������һ������ѡ��ͼƬ');
      return;
    }
    var i = 0, length = images.localId.length;
    images.serverId = [];
    function upload() {
      wx.uploadImage({
        localId: images.localId[i],
        success: function (res) {
          i++;
          //alert('���ϴ���' + i + '/' + length);
		  alert('���ϴ����� ' + i + ' ��ͼƬ������ȷ�ϼ����ϴ���' );
		  //window.location.href="u.php?urls="+res.serverId
		  document.querySelector('#morepicurl').value+=","+res.serverId;
          images.serverId.push(res.serverId);
          if (i < length) {
            upload();
          }else{
		  	$('.upflieBox p').html('��ѡ���ϴ���ͼƬ');
			SharHiddenMessage();
			//��΢���ϴ���ͼƬʱ�к���
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
	$('.upflieBox p').html('��ѡ���ϴ���ͼƬ');
	$('#chooseImage').css({"background":"#F60","border":"#F30 solid 1px"});
	$('#uploadImage').css({"background":"#EFEFEF","border":"#DDD solid 1px"});
}
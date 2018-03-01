<?php
/**
 * ������ѯ-demo
 * ====================================================
 * �ýӿ��ṩ����΢��֧�������Ĳ�ѯ��
 * ��֧��֪ͨ�����쳣��ʧ��������̻�����ͨ���ýӿڲ�ѯ����֧��״̬��
 * 
*/ 

include_once(dirname(__FILE__).'/'."lib/WxPayPubHelper.php");

//ʹ�ö�����ѯ�ӿ�


function check_order_query($out_trade_no='0000008787'){
	
	$orderQuery = new OrderQuery_pub();

	if (!$out_trade_no){

		return ('�����Ų����ڣ�');

	}else{

		//���ñ������
		//appid����,�̻������ظ���д
		//mch_id����,�̻������ظ���д
		//noncestr����,�̻������ظ���д
		//sign����,�̻������ظ���д
		$orderQuery->setParameter("out_trade_no","$out_trade_no");//�̻������� 
		//�Ǳ���������̻��ɸ���ʵ�����ѡ��
		//$orderQuery->setParameter("sub_mch_id","XXXX");//���̻���  
		//$orderQuery->setParameter("transaction_id","XXXX");//΢�Ŷ�����
		
		//��ȡ������ѯ���
		$orderQueryResult = $orderQuery->getResult();

		//�̻�����ʵ�����������Ӧ�Ĵ�������,�˴���������
		if ($orderQueryResult["return_code"] == "FAIL") {
			if(WEB_LANG==gb2312){
				$orderQueryResult['return_msg'] = utf82gbk($orderQueryResult['return_msg']);
			}
			$str =  "ͨ�ų���".$orderQueryResult['return_msg']."<br>";
		}
		elseif($orderQueryResult["result_code"] == "FAIL"){
			if(WEB_LANG==gb2312){
				$orderQueryResult['err_code_des'] = utf82gbk($orderQueryResult['err_code_des']);
			}
			$str = "������룺".$orderQueryResult['err_code']."<br>";
			$str .= "�������������".$orderQueryResult['err_code_des']."<br>";
		
		}elseif($orderQueryResult["result_code"] == "SUCCESS"){/*
			echo "����״̬��".$orderQueryResult['trade_state']."<br>";
			echo "�豸�ţ�".$orderQueryResult['device_info']."<br>";
			echo "�û���ʶ��".$orderQueryResult['openid']."<br>";
			echo "�Ƿ��ע�����˺ţ�".$orderQueryResult['is_subscribe']."<br>";
			echo "�������ͣ�".$orderQueryResult['trade_type']."<br>";
			echo "�������У�".$orderQueryResult['bank_type']."<br>";
			echo "�ܽ�".$orderQueryResult['total_fee']."<br>";
			echo "�ֽ�ȯ��".$orderQueryResult['coupon_fee']."<br>";
			echo "�������ࣺ".$orderQueryResult['fee_type']."<br>";
			echo "΢��֧�������ţ�".$orderQueryResult['transaction_id']."<br>";
			echo "�̻������ţ�".$orderQueryResult['out_trade_no']."<br>";
			echo "�̼����ݰ���".$orderQueryResult['attach']."<br>";
			echo "֧�����ʱ�䣺".$orderQueryResult['time_end']."<br>";*/
			if(WEB_LANG==gb2312){
				$orderQueryResult['trade_state_desc'] = utf82gbk($orderQueryResult['trade_state_desc']);
			}
			$array = $orderQueryResult;
		}else{
			$str='��ȡ����ʧ��';
		}
	}

	if( is_array($array) ){
		return $array;
	}else{
		return $str;
	}
}

/*
Array
(
    [return_code] => SUCCESS
    [return_msg] => OK
    [appid] => wx4cbbd72ba92b7dc5
    [mch_id] => 1272238101
    [nonce_str] => Z4q2Kxzl7GldAjuS
    [sign] => AAA9E8FA51D574F9D273724799CD5BED
    [result_code] => FAIL
    [err_code] => ORDERNOTEXIST
    [err_code_des] => order not exist
)


Array
(
    [return_code] => SUCCESS
    [return_msg] => OK
    [appid] => wx4cbbd72ba92b7dc5
    [mch_id] => 1272238101
    [nonce_str] => Wg61H0RVRO67roPo
    [sign] => 3636F876BC195A082EC524B31111402D
    [result_code] => SUCCESS
    [openid] => oCT6tuBIcwxuH4gCwBGZeKOfv1LI
    [is_subscribe] => Y
    [trade_type] => JSAPI
    [bank_type] => CFT
    [total_fee] => 100
    [fee_type] => CNY
    [transaction_id] => 4001262001201603304396117899
    [out_trade_no] => 0000008787
    [attach] => bFNXVFEQIBO|EDD0c71fbac0f
    [time_end] => 20160330130333
    [trade_state] => SUCCESS
    [cash_fee] => 100
)


Array
(
    [return_code] => SUCCESS
    [return_msg] => OK
    [appid] => wx4cbbd72ba92b7dc5
    [mch_id] => 1272238101
    [nonce_str] => qzO05QZ2w8TCSeWK
    [sign] => BE5BA41F19F93FA940A27DA11121104E
    [result_code] => SUCCESS
    [openid] => oCT6tuBe0DBWvrzyVcB27QtKl5y8
    [is_subscribe] => Y
    [trade_type] => JSAPI
    [bank_type] => ICBC_DEBIT
    [total_fee] => 1
    [fee_type] => CNY
    [transaction_id] => 4002302001201603284344899395
    [out_trade_no] => 0000009470
    [attach] => bQECU1MQIBO|EDDd19cf02964
    [time_end] => 20160328130909
    [trade_state] => SUCCESS
    [cash_fee] => 1000
    [trade_state_desc] => 
)
*/

?>
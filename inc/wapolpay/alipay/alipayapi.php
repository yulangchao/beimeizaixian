<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>֧�����ֻ���վ֧���ӿڽӿ�</title>
</head>
<?php
!function_exists('html') && exit('ERR');

/* *
 * ���ܣ��ֻ���վ֧���ӿڽ���ҳ
 * �汾��3.3
 * �޸����ڣ�2012-07-23
 * ˵����
 * ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 * �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

 *************************ע��*************************
 * ������ڽӿڼ��ɹ������������⣬���԰��������;�������
 * 1���̻��������ģ�https://b.alipay.com/support/helperApply.htm?action=consultationApply�����ύ���뼯��Э�������ǻ���רҵ�ļ�������ʦ������ϵ��Э�����
 * 2���̻��������ģ�http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9��
 * 3��֧������̳��http://club.alipay.com/read-htm-tid-8681712.html��
 * �������ʹ����չ���������չ���ܲ�������ֵ��
 */

require_once(dirname(__FILE__)."/alipay.config.php");
require_once(dirname(__FILE__)."/lib/alipay_submit.class.php");

/**************************�������**************************/

        //֧������
        $payment_type = "1";
        //��������޸�
        //�������첽֪ͨҳ��·��
        $notify_url = $array['notify_url'];
        //��http://��ʽ������·�������ܼ�?id=123�����Զ������
        //ҳ����תͬ��֪ͨҳ��·��
        $return_url = $array['return_url'];
        //��http://��ʽ������·�������ܼ�?id=123�����Զ������������д��http://localhost/
        //�̻�������
        $out_trade_no = $array['numcode'];
        //�̻���վ����ϵͳ��Ψһ�����ţ�����
        //��������
        $subject = $array['title'];
        //����
        //������
        $total_fee = $array['money'];
        //����
        //��Ʒչʾ��ַ
        $show_url = $webdb[www_url];
        //�������http://��ͷ������·�������磺http://www.�̻���ַ.com/myorder.html
        //��������
        $body = $array['content'];
        //ѡ��
        //��ʱʱ��
        $it_b_pay = '';
        //ѡ��
        //Ǯ��token
        $extern_token = '';
        //ѡ��


/************************************************************/

//����Ҫ����Ĳ������飬����Ķ�
$parameter = array(
		"service" => "alipay.wap.create.direct.pay.by.user",
		"partner" => trim($alipay_config['partner']),
		"seller_id" => trim($alipay_config['seller_id']),
		"payment_type"	=> $payment_type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"total_fee"	=> $total_fee,
		"show_url"	=> $show_url,
		"body"	=> $body,
		"it_b_pay"	=> $it_b_pay,
		"extern_token"	=> $extern_token,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

//��������
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "ȷ��");
echo $html_text;

?>
</body>
</html>
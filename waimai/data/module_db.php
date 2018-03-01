<?php unset($module_DB);
$module_DB[1]=array (
  'id' => '1',
  'sort_id' => '0',
  'name' => '外卖商品',
  'list' => '4',
  'style' => '',
  'config' => false,
  'config2' => false,
  'comment_type' => '1',
  'ifdp' => '0',
  'template' => '',
  'field' => 
  array (
    'content' => 
    array (
      'id' => '86',
      'mid' => '1',
      'title' => '商品介绍',
      'field_name' => 'content',
      'field_type' => 'mediumtext',
      'field_leng' => '0',
      'orderlist' => '1',
      'form_type' => 'ieedit',
      'field_inputwidth' => '600',
      'field_inputheight' => '250',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'shoptype' => 
    array (
      'id' => '80',
      'mid' => '1',
      'title' => '种类',
      'field_name' => 'shoptype',
      'field_type' => 'varchar',
      'field_leng' => '50',
      'orderlist' => '7',
      'form_type' => 'text',
      'field_inputwidth' => '10',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'market_price' => 
    array (
      'id' => '78',
      'mid' => '1',
      'title' => '市场价格',
      'field_name' => 'market_price',
      'field_type' => 'varchar',
      'field_leng' => '10',
      'orderlist' => '9',
      'form_type' => 'text',
      'field_inputwidth' => '12',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '元',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '1',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'storage' => 
    array (
      'id' => '153',
      'mid' => '1',
      'title' => '库存量',
      'field_name' => 'storage',
      'field_type' => 'int',
      'field_leng' => '7',
      'orderlist' => '6',
      'form_type' => 'text',
      'field_inputwidth' => '50',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '999',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
  ),
);
$module_db[1]="外卖商品";
$module_DB[2]=array (
  'id' => '2',
  'sort_id' => '0',
  'name' => '订购表单',
  'list' => '1',
  'style' => '',
  'config' => false,
  'config2' => false,
  'comment_type' => '0',
  'ifdp' => '0',
  'template' => 'a:4:{s:4:"list";s:12:"joinlist.htm";s:4:"show";s:12:"joinshow.htm";s:4:"post";s:8:"join.htm";s:6:"search";s:0:"";}',
  'field' => 
  array (
    'order_paytype' => 
    array (
      'id' => '151',
      'mid' => '2',
      'title' => '支付方式',
      'field_name' => 'order_paytype',
      'field_type' => 'int',
      'field_leng' => '1',
      'orderlist' => '2',
      'form_type' => 'radio',
      'field_inputwidth' => '0',
      'field_inputheight' => '0',
      'form_set' => '1|货到付款 
2|银行电汇或ATM转帐
3|邮局汇款
4|网上即时支付',
      'form_value' => '1',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '0',
      'form_js' => '',
    ),
    'order_address' => 
    array (
      'id' => '152',
      'mid' => '2',
      'title' => '联系地址',
      'field_name' => 'order_address',
      'field_type' => 'varchar',
      'field_leng' => '100',
      'orderlist' => '1',
      'form_type' => 'text',
      'field_inputwidth' => '200',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'content' => 
    array (
      'id' => '142',
      'mid' => '2',
      'title' => '附注留言',
      'field_name' => 'content',
      'field_type' => 'mediumtext',
      'field_leng' => '0',
      'orderlist' => '-1',
      'form_type' => 'textarea',
      'field_inputwidth' => '400',
      'field_inputheight' => '50',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'order_phone' => 
    array (
      'id' => '145',
      'mid' => '2',
      'title' => '联系电话',
      'field_name' => 'order_phone',
      'field_type' => 'varchar',
      'field_leng' => '20',
      'orderlist' => '8',
      'form_type' => 'text',
      'field_inputwidth' => '100',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'order_username' => 
    array (
      'id' => '144',
      'mid' => '2',
      'title' => '顾客姓名',
      'field_name' => 'order_username',
      'field_type' => 'varchar',
      'field_leng' => '20',
      'orderlist' => '9',
      'form_type' => 'text',
      'field_inputwidth' => '100',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'order_mobphone' => 
    array (
      'id' => '146',
      'mid' => '2',
      'title' => '联系手机',
      'field_name' => 'order_mobphone',
      'field_type' => 'varchar',
      'field_leng' => '15',
      'orderlist' => '7',
      'form_type' => 'text',
      'field_inputwidth' => '100',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'order_email' => 
    array (
      'id' => '147',
      'mid' => '2',
      'title' => '联系邮箱',
      'field_name' => 'order_email',
      'field_type' => 'varchar',
      'field_leng' => '50',
      'orderlist' => '6',
      'form_type' => 'text',
      'field_inputwidth' => '100',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'order_qq' => 
    array (
      'id' => '148',
      'mid' => '2',
      'title' => '联系QQ',
      'field_name' => 'order_qq',
      'field_type' => 'varchar',
      'field_leng' => '11',
      'orderlist' => '5',
      'form_type' => 'text',
      'field_inputwidth' => '100',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'order_postcode' => 
    array (
      'id' => '149',
      'mid' => '2',
      'title' => '邮政编码',
      'field_name' => 'order_postcode',
      'field_type' => 'varchar',
      'field_leng' => '6',
      'orderlist' => '4',
      'form_type' => 'text',
      'field_inputwidth' => '100',
      'field_inputheight' => '0',
      'form_set' => '',
      'form_value' => '',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
    'order_sendtype' => 
    array (
      'id' => '150',
      'mid' => '2',
      'title' => '配送方式',
      'field_name' => 'order_sendtype',
      'field_type' => 'int',
      'field_leng' => '1',
      'orderlist' => '3',
      'form_type' => 'radio',
      'field_inputwidth' => '0',
      'field_inputheight' => '0',
      'form_set' => '1|上门取货
2|平邮
3|普通快递
4|EMS快递',
      'form_value' => '3',
      'form_units' => '',
      'form_title' => '',
      'mustfill' => '0',
      'listshow' => '0',
      'listfilter' => '0',
      'search' => '0',
      'allowview' => '',
      'allowpost' => '',
      'js_check' => '',
      'js_checkmsg' => '',
      'classid' => '31',
      'form_js' => '',
    ),
  ),
);
$module_db[2]="订购表单";
?>
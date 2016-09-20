<?php
/**
 * 微信模板消息设置基础配置
 * @author Max.Yu <max@jipu.com>
 */

return array(
  
  //订单 开始 ****************************************
  'order' => array(
    //付款成功
    'pay_success' => array(
      'title' => '订单付款成功通知',
      'content' => '{{first.DATA}}
          订单号：{{keyword1.DATA}}
          支付时间：{{keyword2.DATA}}
          支付金额：{{keyword3.DATA}}
          支付方式：{{keyword4.DATA}}
          {{remark.DATA}}',
      'demo' => '您好，您的订单已付款成功
          订单号:88888888
          支付时间:2014年8月20日 11:10
          支付金额:88元
          支付方式:信用卡
          感谢您的惠顾'
    ),
    
    //发货通知
    'delivery_notice' => array(
      'title' => '订单发货通知',
      'content' => '{{first.DATA}}
          订单编号：{{keyword1.DATA}}
          快递公司：{{keyword2.DATA}}
          快递单号：{{keyword3.DATA}}
          {{remark.DATA}}',
      'demo' => '您好，您的订单已发货
          订单编号：100003456
          快递公司：顺丰快递
          快递单号：10000004567
          点击查看订单详情。'
    ),

    //代理返现通知
    'union_back' => array(
      'title' => '获得返现通知',
      'content' => '{{first.DATA}}
          返现原因：{{keyword1.DATA}}
          返现金额：{{keyword2.DATA}}
          返现方式：{{keyword3.DATA}}
          {{remark.DATA}}',
      'demo' => '恭喜您，获得返现！
          返现原因：成交学员梁某某
          返现金额：100
          返现方式：微信支付
          感谢您的支持。'
    ),
    'union_back_no' => array(
      'title' => '返现通知',
      'content' => '{{first.DATA}}
          订单号：{{keyword1.DATA}}
          订单金额：{{keyword2.DATA}}
          返现金额：{{keyword3.DATA}}
          {{remark.DATA}}',
      'demo' => '您好，返现金额已经充值到您的账户。
          订单号：201502078888
          订单金额：888元
          返现金额：20元
          祝您购物愉快！'
    ),
    'order_confirm' => array(
      'title' => '订单确认收货通知',
      'content' => '{{first.DATA}}
          订单号：{{keyword1.DATA}}
          商品名称：{{keyword2.DATA}}
          下单时间：{{keyword3.DATA}}
          发货时间：{{keyword4.DATA}}
          确认收货时间：{{keyword5.DATA}}
          {{remark.DATA}}',
      'demo' => '亲：您在我们商城买的宝贝已经确认收货。
          订单号：323232323232
          商品名称：最新款男鞋
          下单时间：2015 01 01 12:00
          发货时间：2015 01 01 14:00
          确认收货时间：2015 01 02 14:00
          感谢您的支持与厚爱。'
    ),
  ),
  'union' => array(
    //新代理加入通知
    'new_applyer' => array(
      'title' => '您好，XX。XX通过扫描您的专属二维码成为您的X级代理成员',
      'content' => '{{first.DATA}}
          姓名：{{keyword1.DATA}}
          时间：{{keyword2.DATA}}
          {{remark.DATA}}',
      'demo' => '您好，您的xx有新成员加入
        姓名：李永强
        时间：2015.10.1 8:00
        您可以到xx管理后台管理您的源网成员。'
    ),
  ),
  //订单 结束 ****************************************
);
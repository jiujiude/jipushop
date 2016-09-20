!(function(seajs) {
  'use strict';
  seajs.config({
    // Sea.js 的基础路径
    base: C.JS + '/',
    // 别名配置
    alias: {
      //Jquery模块 & 插件
      'jquery': 'lib/jquery/1.10.1/jquery',
      'jquery.superslide': 'plugin/jquery.superslide.2.1.1',
      'jquery.validform': 'plugin/jquery.validform.5.3.2',
      'jquery.loadtype': 'plugin/jquery.loadtype',
      'jquery.zclip': 'lib/zclip/jquery.zclip.min',
      'jquery.uploadify': 'plugin/uploadify/jquery.uploadify.min',
      'jquery.imglazyload': 'plugin/jquery.imglazyload',
      'echarts': 'lib/echarts/echarts',
      
      //Public Module
      'core': 'module/core',
      'common': 'module/common',

      //App Module
      'index': 'module/index', //首页
      'item': 'module/item', //商品
      'coupon': 'module/coupon', //优惠券
      'cart': 'module/cart', //购物车
      'order': 'module/order', //订单
      'receiver': 'module/receiver', //收货地址
      'user': 'module/user', //用户
      'crowdfunding': 'module/crowdfunding', //众筹
      'comment': 'module/comment', //评价
      'sdp': 'module/sdp', //分销
      'shop': 'module/shop', //分销店铺
      'union': 'module/union', //推广联盟
      'userAccount': 'module/userAccount', //账户
      'message': 'module/message', //站内消息
      'delivery': 'module/delivery', //物流
      'invoice': 'module/invoice', //订单发票
    },

    // 路径配置
    paths: {
      'base' : '/Public/Home/default/js',
      'pulgins' : '/Public/Js/Pulgins/'
    },

    // 变量配置
    vars: {
      'locale': 'zh-cn'
    },

    //加版本号
    map: [
      [ /^(.*\.(?:css|js))(.*)$/i, '$1?1512311525']
    ],


    // 预加载项
    preload: ['jquery', 'core', 'common'],

    // 调试模式
    debug: true,
    // 文件编码
    charset: 'utf-8'
  });
}(window.seajs));

  
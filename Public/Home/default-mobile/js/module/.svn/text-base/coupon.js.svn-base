/**
 *优惠券模块：优惠券领取
 */
define('module/coupon', function(require, exports, module){
  'use strict';

  var common = require('common');

  var coupon = {
    init: function(){
      var _self = this;
      // 初始化
      $('.Z_get_coupon').tap(function(){
        var coupon_id = $(this).data('id');
        var get_coupon = _self.get(coupon_id);
        if(get_coupon){
          $(this).find('.J_coupon_info').html('<em class="text-danger">领取成功</em><i class="iconfont text-danger">&#xe60a;</i>');
          $.ui.success('优惠券领取成功');
          common.reload();
        }else{
          $.ui.error('您已领过该优惠券了，立即去下单使用');
        }
      });
    },
    get: function(id){
      var result;
      if(C.UID <= 0){
        window.location.href = $.U('User/login');
        return false;
      }
      $.ajax({
        type: 'POST',
        async: false,
        url: $.U('CouponUser/add'),
        data: 'id=' + id,
        dataType: 'json',
        success: function(res){
          result = (res.status === 1) ? true: false;
        }
      });
      return result;
    }
  };

  module.exports = coupon;
});
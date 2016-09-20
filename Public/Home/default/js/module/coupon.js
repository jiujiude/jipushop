/**
 *优惠券模块：优惠券领取
 */
define('module/coupon', function(require, exports, module){

  'use strict';

  var common = require('common'),
      user = require('user');

  var coupon = {
    /*初始化*/
    init: function(){
      var _self = this;
      //初始化
      $('.J_get_coupon').click(function(){
        if(C.UID <= 0){
          user.quickLogin();
          return false;
        }
        var coupon_id = $(this).attr('data-id');
        var get_coupon = _self.get(coupon_id);
        if(get_coupon){
          if(get_coupon.status == 1){
            if(get_coupon.info){
              var get_coupon_id = get_coupon.info.split(',');
              $.each(get_coupon_id, function(i, item){
                $('#J_coupon_item_' + item).addClass('has-get').removeClass('J_get_coupon').find('.J_coupon_info').html('<em>已经领取</em><i class="icon icon-round-check-fill"></i>');
              });
            }
            $.ui.success('优惠券领取成功');
            common.reload();
          }else{
            $.ui.error('优惠券已被抢空！');
          }
        }
      });
    },
    /*领取优惠券*/
    get: function(id){
      var result;
      if(C.UID <= 0){
        user.quicklogin();
        return false;
      }
      $.ajax({
        type: 'POST',
        async: false,
        url: $.U('CouponUser/add'),
        data: 'id=' + id,
        dataType: 'json',
        success: function(res){
          result = res;
        }
      });
      return result;
    }
  }

  coupon.init();

  module.exports = coupon;
});
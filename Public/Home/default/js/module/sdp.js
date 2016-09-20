/**
 *分销模块：分销提现
 */
define('module/sdp', function(require, exports, module){
  'use strict';

  var common = require('common');

  var sdp = {
    init: function(finance, sdp_withdraw_limit, bind){
      var _self = this;
      $('#J_sdp_withdraw').on('click', function(){
        _self.dealWithdraw(finance, sdp_withdraw_limit, bind);
      });
    },
    dealWithdraw: function(finance, sdp_withdraw_limit, bind){
      var _self = this;

      //检测是否绑定银行卡或支付宝
      var has_bind = bind;
      
      //判断是否达到提现金额
      if(finance < sdp_withdraw_limit){
        $.ui.error('尚未达到最低提现额度' + sdp_withdraw_limit + '元');
        return;
      }
      
      if(has_bind == 1){
        common.redirect($.U('Withdraw/add'), 0);
      }else{
        //_self.chooseBindType();
        common.redirect($.U('UserAccount/add/type/alipay'), 0);
      }
    },
    /*选择绑定类型*/
    chooseBindType: function(){
      
    }
  }

  module.exports = sdp;
});
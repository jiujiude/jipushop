/**
 *商品模块：商品规格选择，加入收藏，更改数量
 */
define('module/crowdfunding', function(require, exports, module){
  'use strict';

  var core = require('core'),
      common = require('common');

  var crowdfunding = {
    init: function(){
      var _self=this;
      var msg = $("input[name='msg']");
      $('.Z_help_pay').click(function(){_self.share();});
      $('.Z_crowdfunding_pay').click(function(){_self.pay();});
      $('.Z_minus_update').click(function(){_self.update('minus',$(this).attr('data-value'));})
      $('.Z_plus_update').click(function(){_self.update('plus',$(this).attr('data-value'));})
      msg.next('input').on('tap', function(){
        if(!msg.val()){
          var txt = msg.attr('placeholder');
          msg.val(txt);
          msg.parentsUntil('form').submit();
        }
      });
    },
    //百分比进度条样式显示,percent:百分号前面的数字，id为众筹id，oid为订单id
    progress: function(percent, id, oid){
      // percent = '100%';
      //设置进度条百分比
      $('.Z_progress').addClass('active').find('.Z_percent').css('width', percent);
      if(percent == '100%'){
        $('.Z_progress').addClass('active-full');
      }
    },
    /*分享众筹*/
    share: function(){
      $.ui.share.show('share');
    },
    /*众筹提交支付*/
    pay: function(){
      var username = $('.Z_crowdfunding_username'),
          payment_type = $('#Z_payment_type');
      
      if($.is_weixin() && (payment_type.val()=='alipaywap'|| payment_type.val()=='alipay')){
        $.ui.share.show('alipaywap');
        return false;
      }
      var doPay = function(){
        $('#Z_crowdfunding_pay').submit();
      }
      if(username.val() == ''){
        $.ui.confirm('悄悄付款不留名字？', doPay);
        return false;
      }
      $('#Z_crowdfunding_pay').submit();
    },
    //取消众筹
    remove: function(id){
      var doRemove = function(){
        $.post($.U('Crowdfunding/remove'), 'id=' + id, function(res){
          if(res.status == 1){
            $.ui.success(res.msg);
            common.redirect($.U('Index/index'));
          }else{
            $.ui.error(res.msg);
          }
        }, 'json');
      }
      $.ui.confirm('您确定取消众筹？', doRemove);
    },
    //增减支付金额
    update: function(flag, sum){
      var money = $(".Z_fund_money input[name='pay_money']"),
          num = parseFloat(money.val());
      if(flag == 'edit'){
        num = num.replace(/[^0-9\.]/gi, "");
      }else{
        num += (flag == 'minus' ? -1 : 1);
      }
      num = parseFloat(num) ? parseFloat(num) : 0;
      num = Math.min(num, sum);
      num = Math.max(num, 0);
      money.val(num);
    }

  }
  module.exports = crowdfunding;
});
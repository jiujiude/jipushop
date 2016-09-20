define('module/member', function(require, exports, module){

  'use strict';

  var member = {
    init: function(){
      // 初始化充值方式
      $('.J_recharge > li').on('tap', function(){
        $(this).addClass('selected').children('input').prop('checked', true);
        $(this).siblings().removeClass('selected');
      });
    },
    /*手机绑定*/
    mobile_bind: function(){
      $('.Z_binded p a').click(function(){
        $('.Z_binded').hide();
        $('.Z_bind-form').removeClass('hide');
        $('.Z_mobile').focus();
      });
      var form = $('.Z_bind-form');
      form.on('submit', function(){
        $('.Z_submit').attr('disabled', true).val('处理中...');
        $.post(form.attr('action'), form.serialize(), function(json){
          if(json.status === 1){
            UI.success(json.info);
            core.reload(1000);
          }else{
            UI.error(json.info);
            $('.Z_submit').removeAttr('disabled').val('提交验证');
          }
        }, 'json');
        return false;
      });
    }
  };
  
  member.init();
  module.exports = member;
});
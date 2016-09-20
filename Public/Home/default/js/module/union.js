/**
 *推广联盟
 */
define('module/union', function(require, exports, module){
  'use strict';

  var common = require('common');

  var union = {
    
    /*开启联盟*/
    open: function(){
      $('.J_open_union').on('click', function(){
        var _this = $(this);
        //阻止进行中的任务
        if(_this.hasClass('disabled')){
          return false;
        }
        //设置任务开始样式
        _this.addClass('disabled').removeClass('btn-positive').html('处理中...');
        $.ajax({
          type: 'POST',
          url: $.U('Union/add'),
          dataType: 'json',
          success: function(res){
            if(res.status === 1){
              $.ui.success(res.info);
              _this.text('开通成功');
              common.reload();
            }else if(res.status === 0){
              $.ui.error(res.info);
              _this.removeClass('disabled').addClass('btn-positive').text('立即开通');
            }
          }
        });
      });
    }
  }

  module.exports = union;
});
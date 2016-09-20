/**
 * 红包模块
 */
define('module/redpackage', function(require, exports, module){
  'use strict';

  var common = require('common');

  var redpackage = {
    //拆红包
    open: function(data){
      $('.Z_open_redpackage').on('tap', function(){
        
        if(data.subscribe_status){
          window.location.href = $.U('RedPackage/open', '_code='+ data.code);
        }else{
          $.ui.load($.U('RedPackage/info', '_code='+ data.code), '红包领取说明');
        }
      });
    }
  };
  module.exports = redpackage;
});
/**
 *推广联盟
 */
define('module/union', function(require, exports, module){
  'use strict';

  var common = require('common');

  var union = {
    
    /*开启联盟*/
    open: function(){
      $('.Z_open_union').on('click', function(){
        $.ajax({
          type: 'POST',
          url: $.U('Union/add'),
          dataType: 'json',
          success: function(res){
            if(res.status == 1){
              $.ui.success(res.info);
              common.reload();
            }else if(res.status === 0){
              $.ui.error(res.info);
            }
          }
        });
      });
    }
  }

  module.exports = union;
});
/**
 * 获取快递单信息
 */
define('module/delivery', function (require, exports, module){
  'use strict';

  var delivery = {
    /*获取快递单信息*/
    getDeliveryInfo: function(dname, dsn){
      var obj = $('.J_delivery_log');
      var get_info = function(type, postid){
        
        obj.removeClass('hide').html('<div><img src="'+ C.IMG +'/loading.gif" width="16"> 正在加载物流动态...</div>');
        $.ajax({
          type: 'GET',
          url: $.U('Api/getDeliveryInfo'),
          data: {type: type, 'postid': postid},
          timeout: 3000,
          dataType: 'json',
          success: function(res){
            if(res.status === 1){
              var html = '', ftime, context;
              for(var i in res['result']){
                ftime = res['result'][i]['ftime'];
                context = res['result'][i]['context'];
                if(i === '0'){
                  context += '<i class="is-new">New</i>';
                }
                html += '<div><span>'+ ftime +'</span><p>'+ context +'</p></div>';
              }
              obj.html(html);
            }else{
              obj.html('：( 该单号暂无物流进展，请稍后再试');
            }
          }
        });
      };
      
      //查询条件
      if(dname.indexOf('汇通') > -1){
        get_info('百世汇通', dsn);
      }
    }
  };
  
  module.exports = delivery;
});
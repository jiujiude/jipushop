define('module/address', function (require, exports, module){

  'use strict';
  var common = require('common');
  var address = {
    config: '',
    saveData: function (address){
      var _self = this,
          arcode,
          postdata,
          post_url;
      if(address.err_msg !== 'edit_address:ok'){
        $.ui.error('选择失败，请重新选择');
        return false;
      }
      arcode = address.nationalCode;
      postdata = {
        name: address.userName,
        province: arcode.substr(0, 2) + '0000',
        district: arcode.substr(0, 4) + '00',
        city: arcode,
        address: address.addressDetailInfo,
        mobile: address.telNumber,
        zipcode: address.addressPostalCode
      };
      post_url = $.U("Receiver/update");
      $.post(post_url, postdata, function (json){
        var url;
        if(json.status === 1){
          if(_self.config.tourl){
            $.ui.success('地址库更新成功！');
            url = _self.config.tourl;
          }else{
            $.ui.success('正在返回订单..');
            url = $.U('Receiver/detail', 'id=' + json.id);
          }
          window.location.href = url;
        }else{
          $.ui.error('请重新选择地址');
        }
      }, 'json');
    },
    select: function (url, by_api){
      var _self = this, src;
      $.ui.success('正在请求...');
      by_api = (typeof by_api === 'undefined') ? 0 : by_api;
      if(parseInt(by_api) === 0){
        src = url;
        if(C.selectConfig.length > 10){
          var onBridgeReady = function (){
            var config = JSON.parse(C.selectConfig);
            _self.config = config;
            WeixinJSBridge.invoke('editAddress', config, function (res){
              _self.saveData(res);
            });
          };
          if(typeof WeixinJSBridge === "undefined"){
            if(document.addEventListener){
              document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
            }
          }else{
            onBridgeReady();
          }
          return;
        }
      }else{
        auth_url = url.substr(0, url.length - 1);
        src = auth_url + $.U("Api/wechatAddress");
        src += (src.indexOf('?') > -1 ? '&' : '?') + 'call_url=' + encodeURIComponent(location.href);
      }
      window.location.href = src;
    },
    addrRemove : function(){
      $(".Z_addr_remove").tap(function(){
        var id = $(this).data('id');
        $.ui.confirm('要删除吗', function(){
          $.ajax({
            type: 'POST',
            url: $.U('Receiver/remove', 'id=' + id),
            dataType: 'json',
            success: function(res){
              if(res.status == 1){
                common.reload();
              }else{
                $.ui.error(res.msg);
              }
            }
          })
        });
      })
    }
  };

  module.exports = address;
});
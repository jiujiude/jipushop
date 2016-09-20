/**********微信模块**********/
define('module/wechat' ,function(require, exports, module){

  'use strict';

  var wx = require('wx');

  var wechat = {
    title: '', // 分享标题
    desc: '', // 分享描述
    link: '', // 分享链接
    imgUrl: '', // 分享图标
    type: '', // 分享类型,music、video或link，不填默认为link
    openid: '', //用户openid
    appid: '', //应用id
    images: {
      localId: []
    },
    init: function(param){
      if('undefined' === typeof param){
        param = {};
      }
      var _self = this;
      if(!C.WECHAT_CONFIG){
        return false;
      }
      // 初始化分享内容
      var sharecontent = $('meta[name="share-content"]');
      _self.title = param.title ? param.title : sharecontent.attr('data-title');
      _self.desc = param.desc ? param.desc : sharecontent.attr('data-desc');
      _self.link = param.link ? param.link : sharecontent.attr('data-link');
      _self.imgUrl = param.imgUrl ? param.imgUrl : sharecontent.attr('data-img_url');
      _self.type = param.type ? param.type : sharecontent.attr('data-type');
      wx.config(C.WECHAT_CONFIG);
      wx.ready(function(){
        // 分享到朋友圈
        wx.onMenuShareTimeline({
          title: _self.title, // 分享标题
          link: _self.link, // 分享链接
          imgUrl: _self.imgUrl, // 分享图标
        });
        // 发送给好友
        wx.onMenuShareAppMessage({
          title: _self.title, // 分享标题
          desc: _self.desc, // 分享描述
          link: _self.link, // 分享链接
          imgUrl: _self.imgUrl, // 分享图标
          type: _self.type, // 分享类型,music、video或link，不填默认为link
          dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        });
        //分享到QQ
        wx.onMenuShareQQ({
          title: _self.title, // 分享标题
          desc: _self.desc, // 分享描述
          link: _self.link, // 分享链接
          imgUrl: _self.imgUrl // 分享图标
        });
      });
    },
    //选择图片
    chooseImage: function(fun){
      var _self = this;
      //初始化配置
      wx.config(C.WECHAT_CONFIG);
      //选择图片
      wx.ready(function(){
        wx.chooseImage({
          success: function(res){
            var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
            _self.images.localId = localIds;
            $.ui.loading('正在启动上传...');
            _self.uploadImage(fun);
          },
          fail: function(res){
            //alert(JSON.stringify(res));
          }
        });
      });
//      wx.error(function(res){
//        alert(JSON.stringify(res));
//      });
    },
    //上传图片
    uploadImage: function(fun){
      var _self = this;
      $.ui.loading('正在上传...');
      wx.ready(function(){
        wx.uploadImage({
          localId: _self.images.localId[0],
          success: function(res){
            if(typeof fun === 'function'){
              fun(_self.images.localId[0], res.serverId);
            }
          },
          fail: function(res){
            //alert(JSON.stringify(res));
          }
        });
      });
    },
    //预览图片
    preview: function(current, urls){
      if(!current || !urls){
        $.ui.loading.tip('图片路径错误');
      }
      wx.ready(function(){
        wx.previewImage({
          current: current,
          urls: urls
        });
      });
    },
    //获取坐标位置&执行回调
    getLatLng: function(fun){
      if($.is_weixin()){
        $.ui.loading('正在获取您的位置...');
      }else{
        $.ui.loading('无法获取到您的位置');
        //默认小寨
        fun(34.223502, 108.946696);
      }
      wx.ready(function(){
        wx.getLocation({
          success: function(res){
            $.ui.box.close();
            var _nowlat = res.latitude; // 纬度
            var _nowlng = res.longitude; //经度
            if(typeof fun === 'function'){
              fun(_nowlat, _nowlng);
            }
            sessionStorage.setItem('lat_lng', _nowlat+','+_nowlng);
          }
        });
      });
    },
    //扫描条码
    scanQRCode: function(fun){
      wx.ready(function(){
        wx.scanQRCode({
          needResult: 1,
          scanType: ["qrCode","barCode"],
          success: function (res) {
            var result = res.resultStr;
            if(typeof fun === 'function'){
              fun(result);
            }
          }
        });
      });
    }
  };

  module.exports = wechat; //导出模块
});
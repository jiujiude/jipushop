define('module/common', function(require, exports, module){

  'use strict';
  
  require('zepto.loadmore');
  
  var common = {
    regx: {
      name: /^[a-zA-Z\u4e00-\u9fa5]+$/,
      number: /^[1-9]+\d*$/,
      mobile: /^(13[0-9]{9})|(18[0-9]{9})|(15[0-9]{9})|(14[57][0-9]{8})|(17[0-9]{9})$/,
      email: /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/g,
      zipcode: /^\d{6}$/,
    },
    /*页面延迟刷新*/
    reload: function(time){
      var t = 'undefined' == typeof (time) ? 1e3 : time;
      window.setTimeout(function(){
        window.location.reload();
      }, t);
    },
    /*页面延迟跳转*/
    redirect: function(url, time){
      var t = 'undefined' == typeof (time) ? 1e3 : time;
      window.setTimeout(function(){
        window.location.href = url;
      }, t);
    },

    /*更换验证码*/
    reloadVerify: function(){
      var verifyimg = $('.J_reload_verify').attr('src');
      $('.J_reload_verify').click(function(){
        if(verifyimg.indexOf('?') > 0){
          $('.J_reload_verify').attr('src', verifyimg + '&random=' + Math.random());
        }else{
          $('.J_reload_verify').attr('src', verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
        }
      });
    },

    /*下拉加载更多*/
    loadmore: function(url){
      var _self = this;
      if(!url || C.has_more !== '1'){
        return false;
      }
      var getMore = function(page){
        $.ajax({
          type: 'GET',
          url: url,
          data: 'p=' + page,
          success: function(res){
            $('#Z_load_itemlist').append(res);
            _self.imgLazyLoad();
            if(!res || C.has_more !== '1'){
              $('#Z_list_loadmore').remove();
            }
          }
        });
      }
      $('#Z_load_itemlist').LoadMore({
        normal_text: '加载更多…',
        loading_text: '正在获取',
        error_text: '没有了',
        auto_loadmore: true,
        onLoadMore: function(){
          setTimeout(function(){
            $('#Z_load_itemlist').LoadMore('pushHTML', getMore($('#Z_load_itemlist').LoadMore('pageNumber')));
          }, 100);
        },
      });
    },
    /*单按钮的ajaxpost*/
    ajaxPostBtn: function(obj){
      obj = typeof obj === 'undefined' ? $('.J_ajax_post') : obj;
      obj.click(function(){
        var nodeName = $(this)[0].nodeName,
        action = nodeName === 'A' ? $(this).attr('href') : $(this).data('action');
        //$.ui.alert('删除成功了');
        $.ui.confirm('确定执行该操作吗？', function(){
          var formhash = $('meta[property="formhash"]').attr('content');
          $.ajax({
            type: 'POST',
            url: action,
            data: {formhash: formhash},
            dataType: 'json',
            success: function(res){
              if(res.status === 1){
                $.ui.success(res.info);
                if(res.url){
                  common.redirect(res.url);
                }else{
                  common.reload();
                }
              }else{
                $.ui.error(res.info);
              }
            }
          });
        });
        return false;
      });
    },
    /*页面表单提交*/
    formSubmit: function(){
      var _self_obj = this;
      //form提交注入hash 
      var formhash = $('meta[property="formhash"]').attr('content');
      $('form[method="POST"]').each(function(){
        var _self = $(this), formhash_size;
        formhash_size =_self.find('input[name="formhash"]').size();
        if(formhash_size === 0){
          $('<input type="hidden" name="formhash" value="'+ formhash +'"/>').appendTo(_self);
        }
        var checked = _self.data('checked_ajaxpost');
        if(!checked){
          _self.data('checked_ajaxpost', 1);
          
          _self.submit(function(){
            var _form = $(this);
            if(_form.data('ajax') === false){
              return true;
            }
            $(this).find('.btn').attr('disabled', true);
            $.ajax({
              type: 'POST',
              url: $(this).attr('action'),
              data: $(this).serialize(),
              dataType: 'json',
              success: function(res){
                if(res.status === 1){
                  $.ui.success(res.info);
                  //如果存在全局提交回调 则执行回调方法并销毁
                  if(typeof $.submitCallback === 'function'){
                    $.submitCallback();
                    $.submitCallback = null;
                    return ;
                  }else{
                    if(res.url){
                      _self_obj.redirect(res.url);
                    }else{
                      _self_obj.reload();
                    }
                  }
                }else{
                  //$.ui.error(res.info);
                  $.ui.alert(res.info);
                  _form.find('.btn').removeAttr('disabled');
                }
              }
            });
            return false;
          });
        }
      });
      
    },
    /*开关按钮效果*/
    toggle: function(){
      $('.toggle').each(function(){
        var obj_name = $(this).data('for');
        var obj = $('input[name="'+ obj_name +'"]');
        if(obj.size() === 1){
          if(obj.val() === '1'){
            $(this).addClass('active');
          }
          $(this).on('tap',function(){
            if(obj.val() === '1'){
              obj.val(0);
              $(this).removeClass('active');
            }else{
              obj.val(1);
              $(this).addClass('active');
            }
          });
        }
      });
    },
    /*图片延时加载*/
    imgLazyLoad: function(){
      var sl = require('zepto.imglazyload');
      $('img').imglazyload({threshold: 100});
    },
    //初始化
    init: function(){
      var _self = this;
      //Form自动用POST提交
      _self.formSubmit();
      //开关效果
      _self.toggle();
      //单按钮的AJAXPOST
      _self.ajaxPostBtn();
      _self.imgLazyLoad();
      _self.reloadVerify();
    }
  };
  
  common.init();
  module.exports = common;
});
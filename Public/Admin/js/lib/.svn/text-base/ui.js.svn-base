;(function($){
  var UI;
  if('undefined' === typeof(window.UI)){
    UI = window.UI = new Object();
  }else{
    UI = window.UI;
  }
  /**
   * 提示消息API
   */
  UI.message = {
    WRAPPER: '<div class="modal" id="J_message_box">\
        <div class="modal-backdrop fade in"></div>\
        <div class="modal-message">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h4 class="modal-title">温馨提示</h4>\
            </div>\
            <div class="modal-body J_box_content"></div>\
          </div>\
        </div>\
      </div>',
    init: function(type, callback, lazytime){
      //如果有正在队列中的动画效果，立即结束动画，移除提示消息框
      if('undefined' !== typeof($('#J_message_box').queue())){
        $('#J_message_box').stop().remove();
      }
      var _self = this;
      // 判断弹窗是否存在
      if($('#J_message_box').length > 0){
        return false;
      }else{
        $('body').prepend(this.WRAPPER).addClass('modal-open');
      }

      if(type == 'confirm'){
        $('<div class="modal-footer"><button type="button" class="btn btn-default J_confirm_close">取消</button><button type="button" class="btn btn-primary J_confirm_submit">确定</button></div>').insertAfter($('#J_message_box .J_box_content'));
      }

      // 设置标题
      if('undefined' != typeof(title)){
        $('#J_message_box modal-title').text(title);
      }

      // 显示弹窗
      $('#J_message_box').fadeIn(300);

      // confirm事件绑定
      $('.J_confirm_close').one('click', function(){
        _self.close();
      });
      $('.J_confirm_submit').one('click', function(){
        _self.close();
        if('function' == typeof(callback)){
          callback();
        }else{
          eval(callback);
        }
      });

      // 自动关闭
      if((type != 'confirm') && (lazytime > 0)){
        this.autoclose(lazytime);
      }
    },
    setcontent: function(type, message){
      var html = '<p class="info"><i class="icon icon-'+ type +'"></i>'+ message +'</p>';
      $('#J_message_box .J_box_content').html(html);
    },
    show: function(message, type, lazytime, callback){
      this.init(type, callback, lazytime);
      this.setcontent(type, message);
    },
    close: function(){
      // 移除J_message_box
      $('#J_message_box').animate({top: '-50%', opacity: '0.2'}, 300, function(){
        //移除J_loadbox
        $('#J_message_box').remove();
      });
      // 移除body样式
      $('body').removeClass('modal-open');
    },
    /*弹窗自动关闭*/
    autoclose: function(lazytime){
      var _self = this, t = ('undefined' == typeof(lazytime)) ? 2 : lazytime;
      setTimeout(function(){
        _self.close();
      }, lazytime*1000);
    }
  }

  /**
   * 自定义弹窗API
   */
  UI.box = {
    WRAPPER: '<div class="modal" id="J_load_box">\
        <div class="modal-backdrop fade in"></div>\
        <div class="modal-dialog">\
          <div class="modal-content">\
            <div class="modal-header">\
              <button type="button" class="close J_modal_close" data-dismiss="modal"><i class="icon icon-close"></i></button>\
              <h4 class="modal-title">操作提示</h4>\
            </div>\
            <div class="modal-body J_box_content">\
              <img src="'+ Core.IMG +'/loading-green-fast.gif" class="loading" width="80">\
            </div>\
          </div>\
        </div>\
      </div>',
    init: function(title, callback){
      this.callback = callback;
      if($('#J_load_box').length > 0){
        return false;
      }else{
        $('body').prepend(this.WRAPPER).addClass('modal-open');
      }
      // 设置标题
      if('undefined' != typeof(title)){
        $('#J_load_box .modal-title').text(title);
      }

      // 显示弹窗
      $('#J_load_box').fadeIn(300);

      // 关闭弹窗，回调函数
      $('#J_load_box').find('.J_modal_close').click(function() {
        UI.box.close(callback);
        return false;
      });
    },
    setcontent: function(content){
      $('#J_load_box .J_box_content').html(content);
    },
    show: function(content, title, callback){
      this.init(title, callback);
      this.setcontent(content);
    },
    close: function(fn){
      // 移除J_message_box
      $('#J_load_box').animate({top: '-50%', opacity: '0.2'}, 300, function(){
        //移除J_loadbox
        $('#J_load_box').remove();
      });
      // 移除body样式
      $('body').removeClass('modal-open');
      // 处理回调函数
      var back ='';
      if('undefined' != typeof(fn)){
        back = fn;
      }else if('undefined' != typeof(this.callback)){
        back = this.callback;
      }
      if('function' == typeof(back)){
        back();
      }else{
        eval(back);
      }
    }
  }

  /**
   * 成功提示
   * @param string message 信息内容
   * @param integer time 展示时间
   * @return void
   */
  UI.success = function(message, time){
    var t = ('undefined' == typeof(time)) ? 1 : time;
    UI.message.show(message, 'success', t);
  }

  /**
   * 错误提示
   * @param string message 信息内容
   * @param integer time 展示时间
   * @return void
   */
  UI.error = function(message, time){
    var t = ('undefined' == typeof(time)) ? 2 : time;
    UI.message.show(message, 'error', t);
  }

  /**
   * 警告提示
   * @param string message 信息内容
   * @param integer time 展示时间
   * @return void
   */
  UI.warning = function(message, time){
    var t = ('undefined' == typeof(time)) ? 2 : time;
    UI.message.show(message, 'warning', t);
  }

  /**
   * 确认操作提示 - 浮窗型
   * @example
   * 可以加入callback，回调函数
   * @param string message 提示信息
   * @param string|function callback 回调函数名称
   * @return void
   */
  UI.confirm = function(message, callback){
    UI.message.show(message, 'confirm', 0, callback);
  }
  
  /**
   * 加载中提示层
   */
  UI.loading = function(message){
    var obj_name = 'J_message_loading';
    if($('.'+obj_name).size() === 0){
      $('<div onclick="UI.loading.hide()"></div>').addClass('message-loading '+ obj_name).appendTo('body');
    }
    var default_html = '<div class="spinner"><div class="rect1"></div><div class="rect2"></div>';
        default_html += '<div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
    $('.'+obj_name).html(default_html + '<p>'+message+'</p>');
  };
  
  /**
   * 隐藏加载中
   */
  UI.loading.hide = function(){
    $('.J_message_loading').remove();
  };

  /**
   * 自定义弹窗API
   * @param string url 请求地址
   * @param string title 弹窗标题
   * @param string callback 窗口关闭后的回调事件
   * @param object request requestData
   * @param string type Ajax请求协议，默认为GET
   * @return void
   */
  UI.load = function(url, title, callback, request, type){
    var ajaxType = 'GET', requestData = {};
    UI.box.init(title, callback);
    if('undefined' != typeof(type)){
      ajaxType = type;
    }
    if('undefined' != request) {
      requestData = request;
    }
    
    $.ajax({
      url: url,
      type: ajaxType,
      data: requestData,
      cache: false,
      dataType: 'html',
      success: function(res){
        UI.box.setcontent(res);
        
        //load页面后，加载formhash
        var formhash = $('meta[property="formhash"]').attr('content');
        $('#J_load_box form[method="post"]').each(function(){
          var _self = $(this);
          formhash_size =_self.find('input[name="formhash"]').size();
          if(formhash_size === 0){
            $('<input type="hidden" name="formhash" value="'+ formhash +'"/>').appendTo(_self);
          }
        });
      }
    });
  }
})($);

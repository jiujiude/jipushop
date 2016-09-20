define(function(require){
  var C = window.C;

  // 基础配置检测
  C || $.error('基础配置没有正确加载！');

  // 定义私有方法
  var parse_url, parse_str, parse_name;

  /**
   * 解析URL
   * @param {string} url 被解析的URL
   * @return {object} 解析后的数据
   */
  parse_url = function(url){
    var parse = url.match(/^(?:([a-z]+):\/\/)?([\w-]+(?:\.[\w-]+)+)?(?::(\d+))?([\w-\/]+)?(?:\?((?:\w+=[^#&=\/]*)?(?:&\w+=[^#&=\/]*)*))?(?:#([\w-]+))?$/i);
    parse || $.error('url格式不正确！');
    return{
      'scheme': parse[1],
      'host': parse[2],
      'port': parse[3],
      'path': parse[4],
      'query': parse[5],
      'fragment': parse[6]
    };
  }

  parse_str = function(str){
    var value = str.split('&'), vars = {}, param;
    for(val in value){
      param = value[val].split('=');
      vars[param[0]] = param[1];
    }
    return vars;
  }

  parse_name = function(name, type){
    if(type){
      /*下划线转驼峰*/
      name.replace(/_([a-z])/g, function($0, $1){
        return $1.toUpperCase();
      });

      /*首字母大写*/
      name.replace(/[a-z]/, function($0){
        return $0.toUpperCase();
      });
    }else{
      /*大写字母转小写*/
      name = name.replace(/[A-Z]/g, function($0){
        return '_' + $0.toLowerCase();
      });

      /*去掉首字符的下划线*/
      if(0 === name.indexOf('_')){
        name = name.substr(1);
      }
    }
    return name;
  }

  /*以下方法对外暴露*/
  //scheme://host:port/path?query#fragment
  $.U = function(url, vars, suffix){
    var info = parse_url(url), path = [], param = {}, reg;

    /* 验证info */
    info.path || $.error('url格式错误！');
    url = info.path;

    /* 组装URL */
    if(0 === url.indexOf('/')){ //路由模式
      C.MODEL[0] == 0 && $.error("该URL模式不支持使用路由!(" + url + ")");

      /* 去掉右侧分割符 */
      if("/" == url.substr(-1)){
        url = url.substr(0, url.length - 1)
      }
      url = ('/' == C.DEEP) ? url.substr(1) : url.substr(1).replace(/\//g, C.DEEP);
      url = '/' + url;
    }else{ //非路由模式
      /* 解析URL */
      path = url.split('/');
      path = [path.pop(), path.pop(), path.pop()].reverse();
      path[1] || $.error("$.U(" + url + ")没有指定控制器");

      if(path[0]){
        param[C.VAR[0]] = C.MODEL[1] ? path[0].toLowerCase() : path[0];
      }

      param[C.VAR[1]] = C.MODEL[1] ? parse_name(path[1]) : path[1];
      param[C.VAR[2]] = path[2];

      url = '?' + $.param(param);
    }

    /* 解析参数 */
    if(typeof vars === 'string'){
      vars = parse_str(vars);
    }else if(!$.isPlainObject(vars)){
      vars = {};
    }

    /* 解析URL自带的参数 */
    info.query && $.extend(vars, parse_str(info.query));

    if(vars){
      url += '&' + $.param(vars);
    }

    if(0 != C.MODEL[0]){
      url = url.replace('?' + (path[0] ? C.VAR[0] : C.VAR[1]) + '=', '/')
        .replace('&' + C.VAR[1] + '=', C.DEEP)
        .replace('&' + C.VAR[2] + '=', C.DEEP)
        .replace(/(\w+=&)|(&?\w+=$)/g, '')
        .replace(/[&=]/g, C.DEEP)
        .replace(/\/$/, '');

      /*添加伪静态后缀*/
      if(false !== suffix){
        suffix = suffix || C.MODEL[2].split('|')[0];
        if(suffix){
          url += '.' + suffix;
        }
      }
    }

    url = C.APP + url;
    return url;
  }

  /**
   * 实现类似PHP implode的函数，将对象组合为字符串
   * 
   * @param needle 事件节点
   * @param haystack 事件节点
   * @param argStrict 事件节点
   */
  $.implode = function(glue, pieces){
    //  discuss at: http://phpjs.org/functions/implode/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Waldo Malqui Silva
    // improved by: Itsacon (http://www.itsacon.net/)
    // bugfixed by: Brett Zamir (http://brett-zamir.me)
    // example 1: implode(' ', ['Kevin', 'van', 'Zonneveld']);
    // returns 1: 'Kevin van Zonneveld'
    // example 2: implode(' ', {first:'Kevin', last: 'van Zonneveld'});
    // returns 2: 'Kevin van Zonneveld'

    var i = '';
    var retVal = '';
    var tGlue = '';
    if(arguments.length === 1){
      pieces = glue;
      glue = '';
    }
    if(typeof pieces === 'object'){
      if(Object.prototype.toString.call(pieces) === '[object Array]'){
        return pieces.join(glue);
      }
      for(i in pieces){
        retVal += tGlue + pieces[i];
        tGlue = glue;
      }
      return retVal;
    }
    return pieces;
  }

  /**
   * 获取字符串的真实长度
   */
  $.get_str_length = function(str){
    return str.replace(/[\u0391-\uFFE5]/g, 'aa').length;
  }

  /**
   * 判断是否通过微信访问
   */
  $.is_weixin = function(){
    var user_agent = navigator.userAgent.toLowerCase();
    if(user_agent.match(/MicroMessenger/i) == 'micromessenger'){
      return true;
    }else{
      return false;
    }
  }

  /**
   * ui弹窗、组件、消息提示模块
   */
  ui = {
    /**
     * 显示遮罩
     */
    showBackdrop: function(opacity){
      opacity || 0.5;
      if($('#J_modal_backdrop').length <= 0){
        $('<div class="modal-backdrop fade in" id="J_modal_backdrop"></div>').css({
          width: '100%',
          opacity: opacity
        }).appendTo(document.body);
      }
    },

    /**
     * 隐藏遮罩
     */
    hideBackdrop: function(){
      $('#J_modal_backdrop').remove();
    },

    /**
     * 提示消息API
     */
    message: {
      wrapper: '<div class="modal show fade in" id="J_message_box">\
        <div class="modal-dialog modal-message">\
          <div class="modal-content">\
            <div class="modal-header">\
              <button type="button" class="close J_modal_close" data-dismiss="modal"><i class="icon icon-close"></i></button>\
              <h4 class="modal-title">温馨提示</h4>\
            </div>\
            <div class="modal-body" id="J_box_content"></div>\
          </div>\
        </div>\
      </div>',
      timer: '',
      init: function(type, callback, lazytime){
        //如果有正在队列中的动画效果，立即结束动画，移除提示消息框
        if('undefined' !== typeof($('#J_message_box').queue())){
          $('#J_message_box').stop().remove();
        }
        var _self = this;
        // 判断弹窗是否存在
        if($('#J_message_box').length > 0){
          window.clearTimeout(_self.timer);
          //return false;
        }else{
          $('body').prepend(this.wrapper).addClass('modal-open');
        }

        if(type == 'confirm'){
          $('<div class="modal-footer"><button type="button" class="btn btn-positive J_confirm_submit">确定</button><button type="button" class="btn btn-default J_confirm_close">取消</button></div>').insertAfter($('#J_message_box #J_box_content'));
        }

        // 设置标题
        if('undefined' != typeof(title)){
          $('#J_message_box .modal-title').text(title);
        }

        // 显示弹窗
        $('#J_message_box').fadeIn(300);
        ui.showBackdrop();

        // confirm事件绑定
        $('.J_modal_close, .J_confirm_close').one('click', function(){
          _self.close();
        });
        $('.J_confirm_submit').one('click', function(){
          _self.close(1);
          if('function' == typeof(callback)){
            callback();
          }else{
            eval(callback);
          }
        });

        // 自动关闭
        if((type != 'confirm') && (lazytime > 0)){
          this.autoClose(lazytime);
        }
      },
      setContent: function(type, message){
        var html = '<p class="info"><i class="icon icon-'+ type +'"></i>'+ message +'</p>';
        $('#J_message_box #J_box_content').html(html);
      },
      show: function(message, type, lazytime, callback){
        this.init(type, callback, lazytime);
        this.setContent(type, message);
      },
      close: function(remove){
        var _self = this;
        if(remove){
          $('#J_message_box, #J_modal_backdrop').remove();
        }else{
          // 移除J_message_box
          $('#J_message_box').removeClass('in');
          $('#J_modal_backdrop').fadeOut(250);
          _self.timer = setTimeout(function(){
            $('#J_message_box').remove();
            ui.hideBackdrop();
          }, 250);
        }
        // 移除body样式
        $('body').removeClass('modal-open');
      },
      /*弹窗自动关闭*/
      autoClose: function(lazytime){
        var _self = this, t = ('undefined' == typeof(lazytime)) ? 2 : lazytime;
        _self.timer = setTimeout(function(){
          _self.close();
        }, lazytime*1000);
      }
    },
    layer: {
      wrapper: '<div class="modal show fade in" id="J_message_box">\
        <div class="modal-dialog modal-message">\
            <div class="modal-body" id="J_box_content"></div>\
        </div>\
      </div>',
      timer: '',
      init: function(type, callback, lazytime){
        //如果有正在队列中的动画效果，立即结束动画，移除提示消息框
        if('undefined' !== typeof($('#J_message_box').queue())){
          $('#J_message_box').stop().remove();
        }
        var _self = this;
        // 判断弹窗是否存在
        if($('#J_message_box').length > 0){
          window.clearTimeout(_self.timer);
          //return false;
        }else{
          $('body').prepend(this.wrapper).addClass('modal-open');
        }

        if(type == 'confirm'){
          $('<div class="modal-footer"><button type="button" class="btn btn-positive J_confirm_submit">确定</button><button type="button" class="btn btn-default J_confirm_close">取消</button></div>').insertAfter($('#J_message_box #J_box_content'));
        }

        // 设置标题
        if('undefined' != typeof(title)){
          $('#J_message_box .modal-title').text(title);
        }

        // 显示弹窗
        $('#J_message_box').fadeIn(300);
        ui.showBackdrop();

        // confirm事件绑定
        $('#J_modal_backdrop').one('click', function(){
          _self.close();
        });
        $('.J_confirm_submit').one('click', function(){
          _self.close(1);
          if('function' == typeof(callback)){
            callback();
          }else{
            eval(callback);
          }
        });

        // 自动关闭
        if((type != 'confirm') && (lazytime > 0)){
          this.autoClose(lazytime);
        }
      },
      setContent: function(type, message){
        var html = '<p class="info"><i class="icon icon-'+ type +'"></i>'+ message +'</p>';
        $('#J_message_box #J_box_content').html(html);
      },
      show: function(message, type, lazytime, callback){
        this.init(type, callback, lazytime);
        this.setContent(type, message);
      },
      close: function(remove){
        var _self = this;
        if(remove){
          $('#J_message_box, #J_modal_backdrop').remove();
        }else{
          // 移除J_message_box
          $('#J_message_box').removeClass('in');
          $('#J_modal_backdrop').fadeOut(250);
          _self.timer = setTimeout(function(){
            $('#J_message_box').remove();
            ui.hideBackdrop();
          }, 250);
        }
        // 移除body样式
        $('body').removeClass('modal-open');
      },
      /*弹窗自动关闭*/
      autoClose: function(lazytime){
        var _self = this, t = ('undefined' == typeof(lazytime)) ? 2 : lazytime;
        _self.timer = setTimeout(function(){
          _self.close();
        }, lazytime*1000);
      }
    },

    /**
     * 窗体对象接口
     */
    box: {
      wrapper: '<div class="modal show fade in" id="J_load_box">\
          <div class="modal-dialog modal-box">\
            <div class="modal-content">\
              <div class="modal-header">\
                <button type="button" class="close J_modal_close" data-dismiss="modal"><i class="icon icon-close"></i></button>\
                <h4 class="modal-title">操作提示</h4>\
              </div>\
              <div class="modal-body clearfix" id="J_box_content">\
                <img src="'+ C.IMG +'/loading.gif" class="loading" width="32">\
              </div>\
            </div>\
          </div>\
        </div>\
        <div class="modal-backdrop fade in" id="J_modal_backdrop"></div>',
      init: function(title, callback){
        this.callback = callback;
        if($('#J_load_box').length > 0){
          return false;
        }else{
          $('body').prepend(this.wrapper).addClass('modal-open');
        }
        // 设置标题
        if('undefined' != typeof(title)){
          $('#J_load_box .modal-title').text(title);
        }

        // 显示弹窗
        $('#J_load_box').fadeIn(300);

        // 关闭弹窗，回调函数
        $('#J_load_box').find('.J_modal_close').click(function() {
          ui.box.close(callback);
          return false;
        });
      },
      /**
       * 显示box
       * @param string content 信息数据
       * @param string title 标题信息
       * @return void
       */
      show: function(content, title,callback){
        this.init(title, callback);
        this.setContent(content);
      },
      /**
       * 关闭box
       * @param function fn 回调函数名称
       * @return void
       */
      close: function(fn){
        // 移除J_load_box
        $('#J_load_box').removeClass('in');
        $('#J_modal_backdrop').fadeOut(250);
        setTimeout(function(){
          $('body').removeClass('modal-open');
          $('#J_load_box, #J_modal_backdrop').remove();
        }, 250);

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
      },
      /**
       * 设置box中的内容
       * @param string content 内容信息
       * @return void
       */
      setContent: function(content){
        $('#J_load_box #J_box_content').html(content);
      },
    },

    alert: function(message, time){
      var t = ('undefined' === typeof(time)) ? 2000 : time;
      var wrapper = '<div class="modal-alert" id="J_alert_box"><i class="icon icon-info"></i>'+ message +'</div>';
      if($('#J_alert_box').length > 0){
        return false;
      }else{
        $('body').prepend(wrapper);
        var ol = ($(document).width()-$('#J_alert_box').outerWidth())/2;
        $('#J_alert_box').css('left', ol);
        $('#J_alert_box').animate({
          height: '36px',
          lineHeight: '34px'
        }, 200, 'linear');
        setTimeout(function(){
          $('#J_alert_box').animate({
            height: 0
          }, 200, 'linear', function(){
            $(this).remove();
          });
        }, t);
      }
    },

    /**
     * 成功提示 - 弹窗型
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    success: function(message, time){
      var t = ('undefined' === typeof(time)) ? 1 : time;
      ui.message.show(message, 'success', t);
    },

    /**
     * 错误提示 - 弹窗型
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    error: function(message, time){
      var t = ('undefined' === typeof(time)) ? 2 : time;
      ui.message.show(message, 'error', t);
    },

    /**
     * 加载中 - 弹窗型
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    loading: function(message, time){
      var t = ('undefined' === typeof(time)) ? 2 : time;
      ui.message.show(message, '', t);
    },

    /**
     * 确认框显示API - 弹窗型
     * @param string message 提示语言
     * @param string|function callback 回调函数名称
     * @return void
     */
    confirm: function(message, callback){
      ui.message.show(message, 'confirm', 0, callback);
    },

    /**
     * 加载url
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    load: function(url, title, callback, request, type){
      var ajax_type = 'GET',
          request_data = {};
      ui.box.init(title, callback);
      if('undefined' != typeof(type)){
        ajax_type = type;
      }
      if('undefined' != request) {
        request_data = request;
      }
      $.ajax({
        url: url,
        type: ajax_type,
        data: request_data,
        cache: false,
        dataType: 'html',
        success: function(res){
          ui.box.setContent(res);
          // 绑定内容中的关闭按钮
          $('#J_box_content .J_modal_close').click(function(){
            $('.modal-header .J_modal_close').click();
          });
          var common = require('common');
          common.init();
        }
      });
    }
  }

  $.ui = ui;
});
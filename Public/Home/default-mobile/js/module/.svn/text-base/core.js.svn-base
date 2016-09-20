define('module/core', function(require){

  'use strict';
  
  var C = window.C,
      ui = {};

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
    var val = null;
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
   * 判断是否为正确的手机号码格式 
   */
  $.is_mobile_number = function (value) {
    var validateReg = /^[1][34578]\d{9}$/;
    return validateReg.test(value);
  }
  
  /**
   * 让函数只执行一次
   */
  $.runonce = function(func, time){
    var timer;
    return function(){
      window.clearTimeout(timer);
      timer = window.setTimeout(function(){
        func();
      }, time || 300);
    };
  }
  
  ui = {
    /**
     * 显示遮罩
     */
    showBackdrop: function(opacity){
      opacity || 0.5;
      if($('.modal-backdrop').length <= 0){
        $('<div class="modal-backdrop"></div>').css({
          width: '100%',
          opacity: opacity
        }).appendTo(document.body);
      }
    },

    /**
     * 隐藏遮罩
     */
    hideBackdrop: function(){
      $('.modal-backdrop').remove();
    },

    /**
     * 提示消息API
     */
    message: {
      wrapper: '<div class="modal-message" id="Z_message_box">\
          <div class="body"></div>\
        </div>',
      timer : '',
      init: function(type, callback, lazytime){
        var _self = this;
        if($('#Z_message_box').size() > 0){
          window.clearTimeout(_self.timer);
          $('#Z_message_box').remove();
        }
        
        //判断弹窗是否存在
        if($('#Z_message_box').length > 0){
          return false;
        }else{
          $('body').prepend(this.wrapper);
        }

        if(type == 'confirm'){
          $('#Z_message_box').hasClass('modal-confirm') || $('#Z_message_box').addClass('modal-confirm');
          $('<div class="modal-footer"><button type="button" id="Z_confirm_close" class="btn btn-link">取消</button><button type="button" id="Z_confirm_submit" class="btn btn-link">确定</button></div>').insertAfter($('#Z_message_box .body'));
          $.ui.showBackdrop();
        }

        if(type == 'select'){
          $('#Z_message_box').hasClass('modal-select') || $('#Z_message_box').addClass('modal-select');
          $.ui.showBackdrop();
        }

        //设置标题
        if('undefined' != typeof(title)){
          $('#Z_message_box modal-title').text(title);
        }

        //显示弹窗
        $('#Z_message_box').fadeIn(150);

        //confirm事件绑定
        $('#Z_confirm_close').one('click', function(){
          _self.close();
        });
        $('#Z_confirm_submit').one('click', function(){
          _self.close();
          if('function' == typeof(callback)){
            callback();
          }else{
            eval(callback);
          }
        });

        //自动关闭
        if((type != 'confirm') && (lazytime > 0)){
          _self.autoClose(lazytime);
        }
      },
      setContent: function(type, message){
        var html = '<p class="info"><i class="icon icon-'+ type +'"></i>'+ message +'</p>';
        $('#Z_message_box .body').html(html);
      },
      show: function(message, type, lazytime, callback){
        this.init(type, callback, lazytime);
        this.setContent(type, message);
      },
      close: function(){
        //移除Z_message_box
        if($('#Z_message_box').size() == 1){
          $('#Z_message_box').fadeOut(150);
          this.timer = setTimeout(function(){
            $('#Z_message_box').remove();
            $.ui.hideBackdrop();
          }, 150);
        }
      },
      /*弹窗自动关闭*/
      autoClose: function(lazytime){
        var _self = this,
          t = ('undefined' == typeof(lazytime)) ? 2 : lazytime;
        _self.timer = setTimeout(function(){
          _self.close();
        }, lazytime*1000);
      }
    },

    /**
     * 窗体对象接口
     */
    box: {
      wrapper: '<div class="modal" id="Z_load_box">\
        <header class="bar bar-nav">\
          <a href="javascript:;" class="icon icon-close pull-right" id="Z_modal_close"></a>\
          <h1 class="title" id="Z_box_title">操作</h1>\
        </header>\
        <div class="content" id="Z_box_content"><img src="'+ C.IMG +'/loading.gif" class="loading" width="16"></div>\
      </div>',
      init: function(title, callback){
        var _self = this;

        if($('#Z_load_box').length >0){
          return false;
        }else{
          //显示遮罩
          $.ui.showBackdrop();
          //添加弹窗
          $('body').append(this.wrapper);
        }

        //设置标题
        if('undefined' != typeof(title)){
          $('#Z_box_title').text(title);
        }

        $('#Z_modal_close').on('click', function(){
          _self.close();
        });
      },
      /**
       * 显示box
       * @param string content 信息数据
       * @param string title 标题信息
       * @return void
       */
      show: function(content, title){
        this.init(content, title);
        this.setContent(content);

        //显示弹窗
        $('#Z_load_box').addClass('active');
      },
      /**
       * 关闭box
       * @param function fn 回调函数名称
       * @return void
       */
      close: function(fn){
        $('#Z_load_box').removeClass('active');
        //移除Z_loadbox
        setTimeout(function(){
          $('#Z_load_box').remove();
          $.ui.hideBackdrop();
        }, 150);
        //移除遮罩
        $.ui.hideBackdrop();
        var back = '';
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
        $('#Z_box_content').html(content);
      }
    },

    /**
     * 成功提示
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    success: function(message, time){
      var t = ('undefined' === typeof(time)) ? 1 : time;
      ui.message.show(message, 'success', t);
    },
    
    /**
     * 顶部提示
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    alert: function(message, time){
      var t = ('undefined' === typeof(time)) ? 3 : time,
          html = '<div class="tip">'+ message +'</div>';
      ui.message.show('', 'alert', t);
      $('#Z_message_box').addClass('modal-alert').html(html);
      $('.modal-alert').click(function(){
        ui.message.close();
      });
    },

    /**
     * 错误提示
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    error: function(message, time){
      var t = ('undefined' === typeof(time)) ? 2 : time;
      ui.message.show(message, 'error', t);
    },
    
    /**
     * 处理中提示
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    loading: function(message, time){
      var t = ('undefined' === typeof(time)) ? 3 : time;
      ui.message.show(message, 'loading', t);
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
     * 弹出选择框API - 弹窗型
     * @param 窗体对象
     * 
     */
    select: function(o){
      ui.message.show('', 'select', 0);
      $('#Z_message_box').html($(o).html()).append('<a class="cancel-btn" href="javascript:$.ui.message.autoClose(0.001);">取消</a>');
      $('#Z_message_box').find('.modal-select').show();
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
          ui.box.show(res, title);
          // 绑定内容中的关闭按钮
          $('#Z_box_content .Z_modal_close').click(function(){
            $('.modal-header .Z_modal_close').click();
          });
        }
      });
    },

    share: {
      wrapper: {
        wechatshare: '<div id="Z_share_guide" class="modal-wechat-share">\
            <div class="hd"><i class="icon icon-close"></i></div>\
            <div class="bd">\
              <p>请点击右上角</p>\
              <p>通过<em class="icon-share-friend"></em>【发送给朋友】功能</p>\
              <p>或<em class="icon-share-timeline"></em>【分享到朋友圈】功能</p>\
              <p>把消息告诉小伙伴哦~</p>\
            </div>\
            <div class="arrow"></div>\
          </div>',
        alipaywap: '<div id="Z_share_guide" class="modal-wechat-share">\
            <div class="hd"><i class="icon icon-close"></i></div>\
            <div class="bd">\
              <p>抱歉，由于支付宝屏蔽了微信</p>\
              <p>请点击右上角</p>\
              <p>通过<em class="text-danger">【在浏览器中打开】</em></p>\
              <p>在浏览器中打开该页面完成支付~</p>\
            </div>\
            <div class="arrow"></div>\
          </div>',
      },
      init: function(option){
        var _self = this;
        if($('#Z_share_guide').length >0){
          return false;
        }else{
          switch(option){
            case 'wechatshare':
              $('body').append(this.wrapper.wechatshare);
              break;
            case 'alipaywap':
              $('body').append(this.wrapper.alipaywap);
              break;
            default:
              $('body').append(this.wrapper.wechatshare);
          }
        }
        $('#Z_share_guide').on('click', function(){
          _self.close();
        });
      },
      show: function(option){
        'undefined' == typeof(option) ? 'wechatshare' : option;
        ui.showBackdrop('0.9');
        this.init(option);
      },
      close: function(){
        //移除Z_share_guide
        $('#Z_share_guide').remove();
        ui.hideBackdrop();
      }
    }
  }

  $.ui = ui;
});
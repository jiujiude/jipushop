/**
 *用户模块：用户快速登录、发送手机短信验证码、微信PC安全登录
 */
define('module/user', function(require, exports, module){
  'use strict';

  var common = require('common');
  require('jquery.validform');

  var user = {
    /*快速登录*/
    quickLogin: function(){
      var url = $.U('User/quickLogin');
      $.ui.load(url, '快速登录');
    },

    /*获取短信验证码*/
    getRandCode: function(reqtype, autorun){
      var button = $('.J_mobile_randcode_button'),
          mobile = $('.J_mobile'),
		  verify = $('#verify'),
          is_mobile_number = function(value){
            var validateReg = /^[1][34578]\d{9}$/;
            return validateReg.test(value);
          };
      var check_time = function(){
        var now_time = new Date().getTime(),
            s_mobile_no = sessionStorage.getItem('mobile_no'),
            s_mobile_endtime = sessionStorage.getItem('mobile_endtime'),
            time;
        if(s_mobile_no === mobile.val() && s_mobile_endtime > now_time){
          time = parseInt((s_mobile_endtime - now_time)/1000);
          button.addClass('disabled').attr('disabled', true).html('<i>'+ time +'</i> 秒后可重新获取');
          window.setTimeout(check_time, 1000);
          return false;
        }else{
          button.removeClass('disabled').removeAttr('disabled').html('获取验证码');
          return true;
        }
      };
      mobile.on('change', check_time);
      button.on('click', function(){
        var mobile_no = mobile.val();
		var img_verify = verify.val();
        if(!is_mobile_number(mobile_no)){
          $.ui.error('请填写正确的手机号码');
          mobile.focus();
          return false;
        }
		if(!img_verify || img_verify == undefined || img_verify == ''){
          $.ui.error('请填写图形验证码');
          verify.focus();
          return false;
        }
        var res = check_time();
        $('.J_randcode').focus();
        if(res){
          var formhash = $('meta[property="formhash"]').attr('content');
          $.ajax({
            type: 'POST',
            url: $.U('/User/getRandCode', 'type='+reqtype),
            data: {mobile: mobile_no, formhash: formhash, verify:img_verify },
            dataType: 'json',
            success: function(res){
              if(res.status === 1){
                if($('.modal-open').size() === 1){
                  $.ui.alert(res.info);
                }else{
                  $.ui.success(res.info);
                }
                sessionStorage.setItem('mobile_no', mobile_no);
                sessionStorage.setItem('mobile_endtime', res.endtime * 1000);
                check_time();
              }else{
				  //图形验证码错误
				  if(res.status == 2){
					  $('.J_reload_verify').click();
				  }
                if($('.modal-open').size() === 1){
                  $.ui.alert(res.info);
                }else{
                  $.ui.error(res.info);
                }
              }
            }
          });
        }
      });
      //自动执行
      if(autorun === true){
        button.click();
      }
    },
    /*发送手机验证码*/
//    send_mobilecode: function(obj_base, send_type){
//      var button = $('.' + obj_base + '_button'),
//          code = $('.' + obj_base + '_randcode'),
//          mobile = $('.' + obj_base),
//          lazytime = 120,
//          time_;
//      var get_time = function(){
//        var expires = new Date();
//        return parseInt(expires.getTime() / 1000);
//      };
//      var check_old = function(){
//        if(mobile.data('old')){
//          if(mobile.data('old') == mobile.val()){
//            button.data('showonly', 1).attr('disabled', true).text('请输入新手机号');
//            return false;
//          }else{
//            button.data('showonly', 0).attr('disabled', false).text('发送验证码');
//          }
//        }
//      }
//      var wait_fun = function(){
//        clearInterval(time_);
//        button.data('showonly', 1).attr('disabled', true);
//        mobile.attr('readonly', true);
//        var _fun = function(){
//          if(lazytime > 0){
//            button.data('showonly', 1);
//            msg = lazytime + ' 秒后重新发送';
//            button.html(msg);
//            lazytime--;
//          }else{
//            mobile.attr('readonly', false);
//            button.html('重发验证码').data('showonly', 0).attr('disabled', false);
//            clearInterval(time_);
//          }
//        };
//        return setInterval(_fun, 1000);
//      };
//      mobile.blur(function(){
//        if(mobile.attr('readonly') == 'readonly'){
//          return false;
//        }
//        check_old();
//        ovtime = $.cookies.get('mobile_code_' + send_type + '_' + mobile.val());
//        if(ovtime){
//          lazytime = ovtime - get_time();
//          if(lazytime > 0){
//            wait_fun(lazytime);
//          }
//        }
//      });
//      //发送按钮
//      button.click(function(){
//        check_old();
//        if($(this).data('showonly') == 1){
//          return;
//        }
//        if(mobile.next('span').hasClass('validform-wrong')){
//          mobile.focus();
//        }else{
//          button.html('验证码发送中…').data('showonly', 1).attr('disabled', true);
//          $.ajax({
//            type: 'POST',
//            url: $.U('/User/sendMobileCode'),
//            data: {mobile: mobile.val(), send_type: send_type},
//            dataType: 'json',
//            success: function(json){
//              if(json.code == 200){
//                $.cookies.set('mobile_code_' + send_type + '_' + mobile.val(), get_time() + json.over_sec);
//                if(json.send_new === 1){
//                  $.ui.success(json.msg);
//                }
//                lazytime = json.over_sec;
//                //重新发送倒计时
//                time_ = wait_fun();
//                code.focus();
//              }else{
//                $.ui.error(json.msg);
//                mobile.focus();
//                button.html('发送验证码').data('showonly', 0).attr('disabled', false);
//              }
//            }
//          });
//        }
//      });
//      check_old();
//    },
    /*礼品卡绑定*/
    cardBind: function(){
      $('#J_card_bind').on('click', function(e){
        e.preventDefault();
        var number = $('#J_card_number').val();
        var password = $('#J_card_password').val();
        if(number == ''){
          $('#J_card_number').focus();
          return false;
        }
        if(password == ''){
          $('#J_card_password').focus();
          return false;
        }
        $.ajax({
          type: 'POST',
          url: $.U('Member/cardBind'),
          data: {number: number, password: password},
          dataType: 'json',
          success: function(res){
            if(res.status == 1){
              $.ui.success(res.info);
              var html = '<div class="col-xs-12">\
                <div class="bd clearfix">\
                  <div class="col-xs-3">'+ res.data.number +'</div>\
                  <div class="col-xs-2">' + res.data.name + '</div>\
                  <div class="col-xs-1">' + res.data.amount + '</div>\
                  <div class="col-xs-1">' + res.data.balance + '</div>\
                  <div class="col-xs-2">' + res.data.bind_time + '</div>\
                  <div class="col-xs-2">' + res.data.expire_time + '</div>\
                  <div class="col-xs-1">未使用</div>\
                </div>\
              </div>';
              $('#J_card_list').after(html);
              if($('.list-empty').length > 0){
                $('.list-empty').remove();
              }
            }else{
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*礼品卡绑定*/
    couponBind: function(){
      $('#J_card_bind').on('click', function(e){
        e.preventDefault();
        var number = $('#J_card_number').val();
        if(number == ''){
          $('#J_card_number').focus();
          return false;
        }

        $.ajax({
          type: 'POST',
          url: $.U('Member/couponactive'),
          data: {number: number},
          dataType: 'json',
          success: function(res){
            if(res.status == 1){
              $.ui.success(res.info);
              var urll = $.U('Item/search','coupon='+res.data.id);
              var html = '<div class="col-xs-12">\
                <div class="bd clearfix">\
                  <div class="col-xs-2">'+ res.data.number +'</div>\
                  <div class="col-xs-2"><a href="'+urll+'" title="查看可用商品">' + res.data.name + '</a></div>\
                  <div class="col-xs-1 np"><strong class="text-danger">' + res.data.amount + '元</strong></div>\
                  <div class="col-xs-2">' + res.data.norm + '</div>\
                  <div class="col-xs-2">' + res.data.create_time + '</div>\
                  <div class="col-xs-2">' + res.data.expire_time + '</div>\
                  <div class="col-xs-1">未使用</div>\
                </div>\
              </div>';
              $('.counpon_list').after(html);
              if($('.list-empty').length > 0){
                $('.list-empty').remove();
              }
            }else{
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*微信登录PC控制*/
    wechatLoginPc: function(){
      var login_timer = window.setInterval(function(){
        $.ajax({
          type: 'POST',
          url: $.U('User/wechatLoginPc'),
          dataType: 'json',
          success: function(json){
            if(json.code === 300){
              if(json.msg){
                window.clearInterval(login_timer);
              }
            }else if(json.code === 200){
              window.clearInterval(login_timer);
              $.ui.success(json.msg, 2);
              common.redirect(json.url, 2000);
            }
          }
        });
      }, 1500);
    },
    //第三方登录tabs
    tabs : function(){
      $('.nav-tabs a').click(function (e) { 
        e.preventDefault();
        var tab = $(this).attr('aria-controls');
        
        $('.tab-pane').hide();
        $('.nav-tabs li').removeClass('active');
        
        $('#' + tab).show();
        $(this).closest('li').addClass('active');

      })
    },
    
    init: function(){
      $('#J_login_form, #J_changepwd, #J_mobile_bind, #J_edit_nickname').Validform();
    }
  }
    

  user.init();
 
  //更换验证码
  common.reloadVerify();

  module.exports = user;
});
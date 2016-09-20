/**
 * 订单模块：选择收货地址、支付方式、配送方式，使用优惠券、礼品卡、账户余额
 */
define('module/order', function (require, exports, module){
  'use strict';

  var user = require('user'),
          common = require('common');

  var order;
  order = {
    total_price: 0, //订单商品总价格
    init_price: 0, //订单初始价格
    total_delivery: 0, //订单总运费
    sub_price: 0, //小计
    finance_price: 0, //电子账户使用金额
    coupon_price: 0, //优惠券使用金额
    card_price: 0, //礼品卡使用金额
    score_price: 0, //使用积分抵扣金额
    promote_price: 0, //营销优惠（满减等）
    card_amount: 0,
    card_id: {}, //礼品卡ID
    card_number: {}, //礼品卡卡号
    /*订单初始化*/
    init: function (total_price) {
      var _self = this;
      _self.selectAddress();
      _self.selectOption();
      _self.useFinance();
      _self.useCoupon();
      _self.useCard();
      _self.useScore();
      //_self.promote_price = $('#J_promote_desc').data('price');
      _self.total_price = _self.init_price = total_price;
      _self.selectDelivery();
      //配送方式改变
      $('.J_delivery_id').on('change', function () {
        _self.selectDelivery();
      });
      $('#J_order_submit').on('click', function () {
        _self.submit();
      });//营销优惠
    },
    /*选择收货地址*/
    selectAddress: function () {
      var _self = this,
          item = $('.J_address_item');
      item.on('click', function () {
        $(this).addClass('selected').siblings().removeClass('selected');
        $(this).find('.J_receiver_id').prop('checked', !0);
        $(this).siblings().find('.J_receiver_id').prop('checked', !1);
        _self.setAddrState();
      });
    },
    /*设置选中状态*/
    selectOption: function () {
      $('#J_order_pay .item, #J_order_delivery .item').on('click', function () {
        $(this).addClass('selected').children('input').prop('checked', !0);
        $(this).siblings().removeClass('selected');
      });
    },
    /*选择配送方式*/
    selectDelivery: function () {
      var _self = this,
          price = 0;
      $('.J_delivery_id option:selected').each(function () {
        price += $(this).data('price') * 1;
      });
      _self.total_delivery = price;
      $('#J_delivery_fee').html(price.toFixed(2) + ' 元');
      _self.countTotalPrice();
    },
    /*设置收货地址状态*/
    setAddrState: function (state) {
      if (state === '1') {
        $('#addrState').val(state);
      } else {
        var checkbox = $('#J_address_list').find('.selected'),
            isnew = checkbox.attr('data-isnew');
        $('#addrState').val('true' === isnew ? '1' : '0')
      }
    },
    /*统计购物车使用某一种支付方式后的剩余金额*/
    countSubPrice: function () {
      this.sub_price = parseFloat(this.init_price) + parseFloat(this.total_delivery) - this.finance_price - this.promote_price - this.coupon_price - this.score_price - this.card_price;
      if (this.sub_price <= 0) {
        this.sub_price = 0;
      }
      return (this.sub_price).toFixed(2);
    },
    /*满减规则*/
    checkManjianRule: function (total_price) {
      var promote_price = 0;
      for (var i in C.manjianRule) {
        if (i <= total_price) {
          promote_price = C.manjianRule[i];
        }
      }
      this.promote_price = promote_price;
      if (promote_price > 0) {
        $('#J_promote_desc').html('-' + promote_price + ' 元').closest('li').removeClass('hidden');
      } else {
        $('#J_promote_desc').html('-0 元').closest('li').addClass('hidden');
      }
      $('#J_promote_desc');
      return total_price - promote_price;
    },
    /*统计购物车商品总价格=商品总价格 + 运费 - 账户余额 - 优惠券 - 礼品卡*/
    countTotalPrice: function () {
      //检查满减规则
      this.total_price = parseFloat(this.init_price) + parseFloat(this.total_delivery) - this.coupon_price - this.score_price - this.card_price;

      if (this.total_price <= 0) {
        this.total_price = 0;
      } else {
        this.total_price = this.checkManjianRule(this.total_price);
      }
      //使用余额
      this.total_price -= this.finance_price;

      $('#J_total_price').html((this.total_price).toFixed(2));
    },
    /*使用账户余额*/
    useFinance: function () {
      var _self = this;
      var use_fun = function (bool) {
        if (bool) {
          $('#J_use_finance').prop('checked', false);
          _self.cancelFinance();
        } else {
          $('#J_use_finance').prop('checked', true);
          _self.getFinanceInfo();
        }
      };
      $('#J_order_finance_box').on('click', function (e) {
        if ($(e.toElement).attr('id') !== 'J_use_finance') {
          var is_check = $('#J_use_finance').prop('checked');
          use_fun(is_check);
        }
      });
      $('#J_use_finance').on('click', function () {
        var checked = $(this).prop('checked');
        use_fun(!checked);
      });
    },
    /*选择账户余额*/
    getFinanceInfo: function () {
      var _self = this;
      $.ajax({
        type: 'POST',
        url: $.U('Member/getFinanceByAjax'),
        dataType: 'json',
        success: function (res) {
          $('#J_is_use_finance').val(1);
          var finance = parseFloat(res.finance);
          var count_sub_price = _self.countSubPrice();
          //订单还需支付金额大于等于账户余额，全部使用账户余额，否则使用还需支付金额
          var amount = (count_sub_price >= finance) ? finance : count_sub_price;
          if (amount > 0) {
            $('#J_order_finance_box').addClass('selected');
            $('#J_finance_desc').html('-' + amount + ' 元');
          } else {
            $('#J_use_finance').prop('checked', false);
          }

          _self.finance_price = parseFloat(amount);
          _self.countTotalPrice();
        }
      });
    },
    /*取消账户余额*/
    cancelFinance: function () {
      var _self = this;
      $('#J_order_finance_box').removeClass('selected');
      $('#J_finance_desc').html('-0 元');
      $('#J_is_use_finance').val('');
      _self.total_price += _self.finance_price; //取消后，支付总额加账户余额使用金额
      _self.finance_price = 0;
      _self.countTotalPrice();
    },
    /*使用积分抵扣*/
    useScore: function () {
      var _self = this;
      var use_fun = function (bool) {
        if (bool) {
          $('#J_use_score').prop('checked', false);
          _self.cancelScore();
        } else {
          $('#J_use_score').prop('checked', true);
          _self.getScoreInfo();
        }
      };
      $('#J_order_score_box').on('click', function (e) {
        if ($(e.toElement).attr('id') !== 'J_use_score') {
          var is_check = $('#J_use_score').prop('checked');
          use_fun(is_check);
        }
      });
      $('#J_use_score').on('click', function () {
        var checked = $(this).prop('checked');
        use_fun(!checked);
      });
    },
    /*取消积分勾选*/
    cancelScore: function () {
      var _self = this;
      $('#J_order_score_box').removeClass('selected');
      $('#J_score_desc').addClass('hidden');
      $('#J_score_desc span').html('-0 元');
      $('#J_use_score_number').val('0');
      $('#J_use_score_amount').val('0');
      _self.total_price += _self.finance_price; //取消后，支付总额加账户余额使用金额
      _self.score_price = 0;
      _self.countTotalPrice();
    },
    /*选择积分余额*/
    getScoreInfo: function () {
      var _self = this;
      $.ajax({
        type: 'POST',
        url: $.U('Member/getScoreByAjax'),
        dataType: 'json',
        success: function (res) {
          $('#J_is_use_score').val(1);
          var score = parseFloat(res.score);
          var count_sub_price = parseFloat(_self.countSubPrice());
          var amount = parseFloat(res.score_amount);
          amount = (count_sub_price >= amount) ? amount : count_sub_price;

          if (amount > 0) {
            $('#J_score_desc').removeClass('hidden');
            $('#J_order_score_box').addClass('selected');
            $('#J_score_desc span').html('-' + amount.toFixed(2) + ' 元');
            $('#J_use_score_amount').val(amount);
            $('#J_use_score_number').val(Math.round(amount * res.score_exchange));
          } else {
            $('#J_use_score').prop('checked', false);
          }
          _self.score_price = parseFloat(amount);
          _self.countTotalPrice();
        }
      });
    },
    /*使用优惠券*/
    useCoupon: function () {
      var _self = this;
      _self.chooseCoupon();
      _self.checkCoupon();

      $('#J_order_coupon_box').on('click', function () {
        $('#J_coupon_box').toggle();
        $('#J_card_box').hide();
      });

      $('#J_cancel_coupon_btn').on('click', function (e) {
        if (e && e.preventDefault) {
          e.preventDefault();
        } else {
          window.event.returnValue = false;
        }
        _self.restoreCoupon();
      });

      $('.J_cancel_activate_btn').on('click', function (e) {
        if (e && e.preventDefault) {
          e.preventDefault();
        } else {
          window.event.returnValue = false;
        }
        $('#J_coupon_box').hide();
      });
    },
    /*选择优惠券*/
    chooseCoupon: function () {
      $('#J_coupon_tab_bav').children('.nav-item').click(function () {
        var a = $(this).index();
        if ($(this).addClass('current').siblings().removeClass('current'), $('#J_coupon_tab_con').children('.con-item').eq(a).show().siblings().hide(), $(this).hasClass('couponList')) {
          var b = $('#J_coupon_tab_con').find('.item-box').eq(a).find('.list'),
              c = b.find('.item').length,
              d = b.find('.item').outerHeight();
          c > 6 && b.css({
            height: 8 * d,
            overflow: 'auto'
          })
        }
      });
    },
    /*优惠券验证*/
    checkCoupon: function () {
      var _self = this;
      $('#J_activate_coupon_btn').on('click', function () {
        var couponCode = $.trim($('#J_coupon_code').val());
        var regxCode = /^[A-Za-z0-9]+$/;
        if (couponCode == '') {
          $('#J_coupon_code').focus();
          return false;
        }
        if (regxCode.test(couponCode)) {
          _self.activateCoupon(couponCode);
        } else {
          _self.setMsg($('#J_activate_coupon_tip'), '请输入正确的激活码');
        }
        return false;
      });
      $('#J_select_coupon_btn').on('click', function () {
        var number = $('input[name="order[coupons][]"]:checked').val();
        if (number) {
          _self.checkSelectedCoupon(number);
        } else {
          _self.setMsg($('#J_my_coupon_tip'), '请选择优惠券');
        }
        return false;
      })
    },
    /*激活优惠券*/
    activateCoupon: function (number) {
      var _self = this;
      var items=$('#J_item_ids').val();
      $.ajax({
        type: 'POST',
        url: $.U('Coupon/activateCoupon'),
        data: 'number=' + number+'&items='+items,
        dataType: 'json',
        success: function (res) {
          var msgTip = $('#J_activate_coupon_tip');
          if (res.status === 1) {
            var norm_text = '';
            if (res.data.norm == 0) {
              norm_text = '（全场不限制）';
            } else {
              norm_text = '（满' + res.data.norm + '元使用）';
            }
            var html = '<li>\
                        <label for="coupons_item_' + res.data.id + '">\
                        <input type="radio" name="order[coupons][]" value="' + res.data.number + '" id="coupons_item_' + res.data.id + '" checked="checked" />\
                        <i>' + res.data.name + norm_text + '</i>\
                        <span>' + res.data.amount + ' 元</span>\
                        </label>\
                        </li>';
            $('.J_couponList').prepend(html);
            $('#J_coupon_id').val(res.data.id);

            //更新优惠券数量
            var coupon_total = parseInt($('.J_coupon_total').html()) + 1;
            $('.J_coupon_total').html(coupon_total);

            //激活我的优惠券面板按钮
            $('#J_select_coupon_btn').parent().show();

            _self.setMsg(msgTip, res.info);
            _self.dealWithCouponInfo(res.data);
            $('#J_order_coupon_box').addClass('selected').children('input').prop('checked', true);
          } else {
            _self.setMsg(msgTip, res.info);
          }
        }
      });
    },
    /*验证用户选择的优惠券*/
    checkSelectedCoupon: function (number) {
      var _self = this;
      $.ajax({
        type: 'POST',
        url: $.U('Coupon/checkSelectedCoupon'),
        data: 'number=' + number,
        dataType: 'json',
        success: function (res) {
          var msgTip = $('#J_my_coupon_tip');
          if (res.status === 1) {
            $('#J_coupon_id').val(res.data.id);
            $('#J_order_coupon_box').addClass('selected').children('input').prop('checked', true);
            _self.setMsg(msgTip);
            _self.dealWithCouponInfo(res.data);
          } else {
            _self.setMsg(msgTip, res.msg);
          }
        }
      });
    },
    /*优惠券使用最终结算*/
    dealWithCouponInfo: function (data) {
      var _self = this;
      if (data) {
        $('#J_coupon_box').hide();

        /*使用优惠券*/
        var coupon_amount = parseFloat(data.amount);
        var count_sub_price = _self.countSubPrice();

        /*订单还需支付金额大于等于优惠券余额，全部使用优惠券，否则使用还需支付金额*/
        var amount = (count_sub_price >= coupon_amount) ? coupon_amount : count_sub_price;
        _self.coupon_price = parseFloat(amount);
        if (amount > 0) {
          $('#J_coupon_desc').html('-' + amount + ' 元');
        }
        _self.countTotalPrice();
      } else {
        _self.restoreCoupon();
      }
    },
    /*重置优惠券使用*/
    restoreCoupon: function () {
      var _self = this;
      $('#J_order_coupon_box').removeClass('selected');
      $('#J_use_coupon').prop('checked', false);
      $('.J_couponList input').prop('checked', false);
      $('#J_coupon_box').hide();
      $('#J_coupon_desc').html('-0 元');
      $('#J_coupon_id').val('');
      _self.total_price += this.coupon_price; //取消后，支付总额加优惠券使用金额
      _self.coupon_price = 0;
      _self.countTotalPrice();
    },
    /*使用礼品卡*/
    useCard: function () {
      var _self = this;
      _self.chooseCard();
      _self.checkCard();

      $('#checkoutCardBox').on('click', function () {
        $('#J_card_box').toggle();
        $('#J_coupon_box').hide();
      });

      $('.J_cancelCardBtn').on('click', function (e) {
        _self.restoreCard();

        if (e && e.preventDefault) {
          e.preventDefault();
        } else {
          window.event.returnValue = false;
        }
      });

      $('.J_cancelBindBtn').on('click', function (e) {
        if (e && e.preventDefault) {
          e.preventDefault();
        } else {
          window.event.returnValue = false;
        }
        $('#J_card_box').hide();
      });
    },
    /*选择礼品卡*/
    chooseCard: function () {
      var _self = this;
      _self.card_amount = 0;
      $('#cardTabNav').children('.nav-item').click(function () {
        var a = $(this).index();
        if ($(this).addClass('current').siblings().removeClass('current'), $('#cardTabCon').children('.con-item').eq(a).show().siblings().hide(), $(this).hasClass('cardList')) {
          var b = $('#cardTabCon').find('.item-box').eq(a).find('.list'),
              c = b.find('.item').length,
              d = b.find('.item').outerHeight();
          c > 6 && b.css({
            height: 8 * d,
            overflow: 'auto'
          })
        }
      });
    },
    /*礼品卡验证*/
    checkCard: function () {
      var _self = this;

      //绑定礼品卡
      $('#bindCardBtn').on('click', function () {
        var card_number = $.trim($('#card_number').val());
        var card_password = $.trim($('#card_password').val());
        var regxCode = /^[0-9a-zA-Z]/;
        if (card_number == '') {
          $('#card_number').focus();
          return false;
        }
        if (card_password == '') {
          $('#card_password').focus();
          return false;
        }
        if (regxCode.test(card_number)) {
          _self.bindCard(card_number, card_password);
        } else {
          _self.setMsg($('#bindCardTip'), '卡号不正确');
        }
        return false;
      });

      //选择我的礼品卡
      $('#selectCardBtn').on('click', function () {
        //选中的礼品卡变量值初始化
        _self.card_number = {};

        //处理礼品卡使用多张的情况
        var card_items = $('#J_card_list li').find('input[name="order[cards][]"]:checked');
        if (card_items.length > 0) {
          for (var i = 0; i < card_items.length; i++) {
            var card_number = $(card_items[i]).val();
            if ((card_number !== undefined) && (card_number !== '')) {
              _self.card_number[i] = card_number;
            }
          }
          var selected_card_number = $.implode(',', _self.card_number);
          _self.checkSelectedCard(selected_card_number);
        } else {
          _self.setMsg($('#myCardTip'), '请选择礼品卡');
        }
        return false;
      });
    },
    /*绑定礼品卡*/
    bindCard: function (number, password) {
      var _self = this;
      $.ajax({
        type: 'POST',
        url: $.U('Card/bindCard'),
        data: 'number=' + number + '&password=' + password,
        dataType: 'json',
        success: function (res) {
          var msgTip = $('#bindCardTip');
          if (res.status === 1) {
            $.each(res.data, function (i, item) {
              var html = '<li>\
                          <label for="cards_item_' + item.id + '">\
                          <input type="checkbox" name="order[cards][]" value="' + item.number + '" id="cards_item_' + item.id + '" checked="checked" />\
                          <i>' + item.name + '</i>\
                          <span>' + item.balance + ' 元</span>\
                          </label>\
                          </li>';
              $('#J_card_list').prepend(html);
              _self.card_id[item.id] = item.id;
            });
            $('#J_card_id').val($.implode(',', _self.card_id));

            //更新礼品卡的数量
            var card_total = parseInt($('.J_card_total').html()) + 1;
            $('.J_card_total').html(card_total);

            //激活我的优惠券面板按钮
            $('#selectCardBtn').parent().show();

            _self.setMsg(msgTip, res.info);
            _self.dealwithCardInfo(res.data);
            $('#checkoutCardBox').addClass('selected').children('input').prop('checked', true);
          } else {
            _self.setMsg(msgTip, res.info);
          }
        }
      });
    },
    /*验证用户选择的礼品卡*/
    checkSelectedCard: function (number) {
      var _self = this;

      //选中的礼品卡ID值初始化
      _self.card_id = {};

      $.ajax({
        type: 'POST',
        url: $.U('Card/checkSelectedCard'),
        data: 'number=' + number,
        dataType: 'json',
        success: function (res) {
          var msgTip = $('#myCardTip');
          if (res.status === 1) {
            $.each(res.data, function (i, item) {
              _self.card_id[item.id] = item.id;
            });
            $('#J_card_id').val($.implode(',', _self.card_id));
            $('#checkoutCardBox').addClass('selected').children('input').prop('checked', true);

            _self.setMsg(msgTip);
            _self.dealwithCardInfo(res.data);
          } else {
            _self.setMsg(msgTip, res.msg);
          }
        }
      });
    },
    /*处理礼品卡详情*/
    dealwithCardInfo: function (data) {
      var _self = this;
      if (data) {
        $('#J_card_box').hide();

        //礼品卡使用金额清零
        _self.card_amount = 0;
        _self.card_price = 0;

        //遍历礼品卡获取可使用余额
        $.each(data, function (i, item) {
          _self.card_amount += parseFloat(item.balance);
        });

        //订单还需支付金额
        var count_sub_price = _self.countSubPrice();

        //订单还需支付金额大于等于礼品卡余额，全部使用礼品卡，否则使用还需支付金额
        var amount = (count_sub_price >= _self.card_amount) ? _self.card_amount : count_sub_price;
        _self.card_price = parseFloat(amount);
        if (amount > 0) {
          $('#J_card_desc').html('-' + amount + ' 元');
        }
        _self.countTotalPrice();
      } else {
        _self.restoreCard();
      }
    },
    /*重置礼品卡使用*/
    restoreCard: function () {
      $('#checkoutCardBox').removeClass('selected');
      $('#J_card_list input').prop('checked', false);
      $('#J_card_box').hide();
      $('#J_card_desc').html('-0 元');
      $('#J_card_id').val('');
      this.card_price = 0;
      this.card_amount = 0;
      this.card_id = {};
      this.card_number = {};
      this.countTotalPrice();
    },
    /*提交订单*/
    submit: function () {
      var receiver_id = $('input[type="radio"][name="receiver_id"]:checked').val();
      var item_ids = $('#J_item_ids').val();
      //验证订单商品
      if (item_ids == null || item_ids == '') {
        $.ui.error('订单商品为空！');
        return false;
      }
      //验证收货地址
      if (receiver_id == null || receiver_id == '') {
        $.ui.error('请您选择收货地址！');
        return false;
      }
      $('#J_checkout_form').submit();
    },
    selectPayment: function () {
      $('.J_payment_type').on('click', function () {
        $('#J_third_pay li').removeClass('active').find('input[type="radio"]').prop('checked', !1);
        $(this).addClass('active').find('input[type="radio"]').prop('checked', 1);
      });
      $('input[name="payment_type"]').on('click', function () {
        $('input[name="bank_name"]').prop('checked', !1);
      });
      $('input[name="bank_name"]').on('click', function () {
        $('input[name="payment_type"]').prop('checked', !1);
      });
    },
    /*执行支付*/
    pay: function (order_id) {
      $('#J_payment_' + order_id).on('submit', function () {
        var paymeny_type = $('input[name="payment_type"]:checked').val();
        if (paymeny_type === 'wechatpay') {
          var order_id = $('input[name="order_id"]').val();
          $.ui.load($.U('/Pay/weixinPayCode', 'order_id=' + order_id), '请用微信扫码支付');
          return false;
        } else {
          var pay_redirect = function () {
            window.location.href = $.U('Member/order');
          }
          $.ui.confirm('是否支付成功？', pay_redirect);
        }
      });
    },
    /*PC端微信支付*/
    weixinPay: function (order_id) {
      var get_pay_status = function () {
        if ($('#J_weixinpay_qrcode').size() === 0) {
          return false;
        }
        $.ajax({
          type: 'GET',
          url: $.U('Pay/weixinPayCode'),
          data: {order_id: order_id, get_status: 1},
          dataType: 'json',
          success: function (res) {
            if (res.status === 1) {
              $('#J_load_box').remove();
              $.ui.success(res.info);
              if (res.url) {
                common.redirect(res.url);
              } else {
                common.reload();
              }
            } else {
              setTimeout(function () {
                get_pay_status();
              }, 1000);
            }
          }
        });
      };
      get_pay_status();
    },
    /*发送手机余额支付验证码*/
    send_paymobilecode: function (obj_base, send_type, mobile) {
      var button = $('.' + obj_base + '_button');
      var code = $('.' + obj_base + '_randcode');
      var lazytime = 120;
      var time_;
      var get_time = function () {
        var expires = new Date();
        return parseInt(expires.getTime() / 1000);
      };
      var wait_fun = function () {
        clearInterval(time_);
        button.data('showonly', 1).attr('disabled', true);
        var _fun = function () {
          if (lazytime > 0) {
            var msg = lazytime + ' 秒后重新发送';
            button.html(msg);
            lazytime--;
          } else {
            button.html('重发验证码').data('showonly', 0).attr('disabled', false);
            clearInterval(time_);
          }
        };
        return setInterval(_fun, 1000);
      };
      var ovtime = $.cookies.get('mobile_code_' + send_type + '_' + mobile);
      if (ovtime) {
        lazytime = ovtime - get_time();
        if (lazytime > 0) {
          wait_fun(lazytime);
        }
      }
      //发送按钮
      button.click(function () {
        if ($(this).data('showonly') === 1) {
          return;
        }
        button.html('验证码发送中…').data('showonly', 1).attr('disabled', true);
        $.post($.U('/User/sendMobileCode'), {"mobile": mobile, send_type: send_type}, function (json) {
          if (json.code == 200) {
            $.cookies.set('mobile_code_' + send_type + '_' + mobile, get_time() + json.over_sec);
            if (json.send_new === 1) {
              $.ui.success(json.msg);
            }
            lazytime = json.over_sec;
            //重新发送倒计时
            time_ = wait_fun();
            code.focus();
          } else {
            $.ui.error(json.msg);
            button.html('发送验证码').data('showonly', 0).attr('disabled', false);
          }
        }, 'json');
      });
    },
    /*快速支付，非第三方支付*/
    quickPay: function (order_id) {
      var randcode = $('.J_mobile_randcode'),
          randcode_val = randcode.val();
      if (randcode_val == null || randcode_val == '') {
        $.ui.error('请您输入支付验证码');
        randcode.focus();
        return false;
      }
      $('#J_payment_' + order_id).submit();
    },
    /*修改订单状态*/
    setStatus: function (order_id, type) {
      var text = {'cancel': '取消', 'recycle': '删除', 'delete': '永久删除', 'restore': '还原'};
      $.ui.confirm('您确定' + text[type] + '该订单？', function () {
        $.ajax({
          type: 'POST',
          url: $.U('Order/setStatus'),
          data: {'order_id': order_id, 'type': type},
          dataType: 'json',
          success: function (res) {
            if (res.status == 1) {
              $.ui.success(res.info);
              common.reload();
            } else {
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*确认收货*/
    confirm: function (order_id) {
      $.ui.confirm('您确认已收货？', function () {
        $.ajax({
          type: 'POST',
          url: $.U('Order/confirm'),
          data: {'order_id': order_id},
          dataType: 'json',
          success: function (res) {
            if (res.status == 1) {
              $.ui.success(res.info);
              common.reload();
            } else {
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*申请退款*/
    refund: function (order_id) {
      $.ui.confirm('您确定申请退货/退款？', function () {
        $.ajax({
          type: 'POST',
          url: $.U('Order/refund'),
          data: {order_id: order_id},
          dataType: 'json',
          success: function (res) {
            if (res.status === 1) {
              $.ui.success(res.info);
              common.reload();
            } else {
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*取消退款*/
    unrefund: function (order_id) {
      $.ui.confirm('您确定撤销退款申请？', function () {
        $.ajax({
          type: 'POST',
          url: $.U('Order/unrefund'),
          data: {order_id: order_id},
          dataType: 'json',
          success: function (res) {
            if (res.status === 1) {
              $.ui.success(res.info);
              common.reload();
            } else {
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*退货信息提交*/
    reShip: function (order_id) {
      var company_name = $("input[name='company_name']").val();
      var ship_number = $("input[name='ship_number']").val();
      if (company_name.length < 2) {
        $.ui.alert('请输入快递公司名称！');
        $("input[name='company_name']").focus();
        return;
      }
      if (ship_number.length < 5) {
        $.ui.alert('请输入快递单号！');
        $("input[name='ship_number']").focus();
        return;
      }
      $.ajax({
        type: 'POST',
        url: $.U('Order/reShip'),
        data: {order_id: order_id, company_name: company_name, ship_number: ship_number},
        dataType: 'json',
        success: function (res) {
          $.ui.alert(res.info);
          if (res.status === 1) {
            common.reload();
          }
        }
      });
    },
    /*提醒发货*/
    notice: function (order_id) {
      $.ui.success('提醒成功');
    },
    /*设置提示信息*/
    setMsg: function (a, b) {
      b ? a.html(b).show() : a.html('').hide();
    },
    listsBind: function () {
      var _self = this;
      $('.J_order_confirm').on('click', function () {
        var id = $(this).data('id');
        _self.confirm(id);
      });
      $('.J_order_cancel').on('click', function () {
        var id = $(this).data('id');
        _self.setStatus(id, 'cancel');
      });
      $('.J_order_recycle').on('click', function () {
        var id = $(this).data('id');
        _self.setStatus(id, 'recycle');
      });
      $('.J_order_restore').on('click', function () {
        var id = $(this).data('id');
        _self.setStatus(id, 'restore');
      });
      $('.J_order_delete').on('click', function () {
        var id = $(this).data('id');
        _self.setStatus(id, 'delete');
      });
      $('.J_order_refund').on('click', function () {
        var id = $(this).data('id');
        _self.refund(id);
      });
      $('.J_order_unrefund').on('click', function () {
        var id = $(this).data('id');
        _self.unrefund(id);
      });
      $('.J_crowdfunding_pay').on('click', function () {
        $.ui.error('请使用微信管理众筹订单！', 5000);
      });

      $('.J_order_reship').on('click', function () {
        var order_id = $(this).data('id');
        var url = $.U('Order/reShip', 'order_id=' + order_id);
        $.ui.load(url, '退货信息');
      });
    }
  };
  module.exports = order;
});
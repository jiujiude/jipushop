/**
 * 订单模块：选择收货地址、支付方式、配送方式，使用优惠券、礼品卡、账户余额
 */
define('module/order', function (require, exports, module){

  'use strict';

  var user = require('user'),
      comment = require('comment'),
      common = require('common');

  var order = {
    total_price: 0, //订单商品总价格
    init_price: 0, //订单初始价格
    total_delivery: 0, //订单总运费
    sub_price: 0, //小计
    finance_price: 0, //电子账户使用金额
    coupon_price: 0, //优惠券使用金额
    card_price: 0, //礼品卡使用金额
    score_price : 0, //积分抵扣金额
    promote_price: 0, //营销优惠（满减等）
    card_amount: 0,
    card_id: {}, //礼品卡ID
    card_number: {}, //礼品卡卡号
    /*初始化*/
    init: function(total_price){
      var _self = this;

      _self.total_price = _self.init_price = total_price;
      _self.useFinance();
      _self.useCoupon();
      _self.useCard();
      _self.useScore();
      _self.useRecipt();
     //营销优惠
     // _self.promote_price = $('#Z_promote_desc').size() === 0 ? 0 : $('#Z_promote_desc').data('price');
      // 初始化支付方式
      $('#Z_payment > .item').on('tap', function(){
        $(this).addClass('selected').children('input').prop('checked', true);
        $(this).siblings().removeClass('selected');
      });

      // 初始化配送方式
      _self.selectDelivery();
      //配送方式改变
      $('.Z_delivery_id').on('change', function(){
        _self.selectDelivery();
      });

      //绑定事件
      $('#Z_order_submit').on('tap', function(){
        if($(this).hasClass('disabled')){
          return false;
        }
        $(this).addClass('disabled');
        $.ui.loading('正在提交订单...', 30);
        _self.submit();
      });
      
      // 初始化发票信息
      var invoice = $('input[id^="invoice-"]');
      invoice.on('click', function(){
        var isInvoice = $(this).val();
        (isInvoice == 1) ? $('.invoice-info').show() : $('.invoice-info').hide();
      });

      var invoiceType = $('input[name="invoice_type"]');
      invoiceType.tap(function(){
        var type = $(this).val();
      });
      
      //商品清单切换
      $('#Z_toggle_items').on('tap', function(){
         _self.toggleItems();
      });
    },
    /*选择配送方式*/
    selectDelivery: function(){
      var _self = this,
              price = 0;
      $('.Z_delivery_id option').each(function(){
        if($(this).attr('selected')){
          price += $(this).data('price') * 1;
        }
      });
      _self.total_delivery = price;
      $('#Z_delivery_fee').html(price.toFixed(2) + ' 元');
      _self.countTotalPrice();
    },
    /*统计购物车使用某一种支付方式后的剩余金额*/
    countSubPrice: function(){
      this.sub_price = parseFloat(this.init_price) + parseFloat(this.total_delivery) - this.finance_price - this.promote_price - this.coupon_price - this.score_price - this.card_price;
      if(this.sub_price <= 0){
        this.sub_price = 0;
      }
      return (this.sub_price).toFixed(2);
    },
    /*满减规则*/
    checkManjianRule: function (total_price){
      var promote_price = 0;
      for(var i in C.manjianRule){
        if(i <= total_price){
          promote_price = C.manjianRule[i];
        }
      }
      this.promote_price = promote_price;
      $('#Z_promote_desc').html('-' + promote_price +' 元');
      if(promote_price == 0){
        $('#Z_promote_desc').closest('li').addClass('hide');
      }else{
        $('#Z_promote_desc').closest('li').removeClass('hide');
      }
      return total_price - promote_price;
    },
    /*统计购物车商品价格*/
    countTotalPrice: function(){
      //检查满减规则
      this.total_price = parseFloat(this.init_price) + parseFloat(this.total_delivery) - this.coupon_price - this.score_price - this.card_price;

      if(this.total_price <= 0){
        this.total_price = 0;
      }else{
        this.total_price = this.checkManjianRule(this.total_price);
      }
      //使用余额
      this.total_price -= this.finance_price;
      $('#Z_total_price').html('<em class="yen">&yen;</em> ' + (this.total_price).toFixed(2));
    },
    /*使用积分*/
    useScore: function(){
      var _self = this;
      $('#Z_order_score_box').on('tap', function(){
        var isCheck = $('#Z_use_score').prop('checked');
        if(isCheck){
          _self.cancelScore();
        }else{
          _self.getScoreInfo();
        }
      });
    },
    /*选择发票*/
    useRecipt: function(){
      var _self = this;
      $('#Z_order_receipt_box').on('tap', function(){
        var isChecked = $('#Z_use_receipt').prop("checked");
        if(isChecked){
            $('#Z_use_receipt').prop('checked', false);
            $('#Z_order_receipt_box').removeClass('selected').children('input').prop('checked', false);
            $('#Z_order_receipt_box').children('.icon-square-check-fill').addClass('icon-square').removeClass('icon-square-check-fill');
        }else{
            $('#Z_use_receipt').prop('checked', true);
            $('#Z_order_receipt_box').addClass('selected').children('input').prop('checked', true);
            $('#Z_order_receipt_box').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
        }
      });
    },
    /*取消使用积分*/
    cancelScore: function(){
      $('#Z_use_score').prop('checked', false);
      $('#Z_score_desc').addClass('hide');
      $('#Z_order_score_box').removeClass('selected').children('input').prop('checked', false);
      $('#Z_order_score_box').children('.icon-square-check-fill').addClass('icon-square').removeClass('icon-square-check-fill');
      this.score_price = 0;
      this.countTotalPrice();
    },
    /*选择积分余额*/
    getScoreInfo: function(){
      var _self = this;
      $.ajax({
        type: 'POST',
        url: $.U('Member/getScoreByAjax'),
        dataType: 'json',
        success: function (res){
          var count_sub_price = parseFloat(_self.countSubPrice());
          var amount = parseFloat(res.score_amount);
          amount = (count_sub_price >= amount) ? amount : count_sub_price;
          if(amount > 0){
            $('#Z_use_score').prop('checked', true);
            $('#Z_score_desc').removeClass('hide');
            $('#Z_order_score_box').addClass('selected').children('input').prop('checked', true);
            $('#Z_order_score_box').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
            
            $('#Z_score_desc span').html('-' + amount.toFixed(2) + ' 元');
            $('#Z_use_score_amount').val(amount);
            $('#Z_use_score_number').val(Math.round(amount * res.score_exchange));
          }else{
            _self.cancelScore();
            return false;
          }
          _self.score_price = parseFloat(amount);
          _self.countTotalPrice();
        }
      });
    },
    /*使用账户余额*/
    useFinance: function(){
      var _self = this;
      $('#Z_finance_desc').html('0.00 元').closest('li').addClass('hide');
      $('#Z_order_finance_box').on('tap', function(){
        var isCheck = $('#Z_use_finance').prop('checked');
        if(isCheck){
          _self.cancelFinance();
        }else{
          _self.getFinanceInfo();
        }
      });
    },
    /*选择账户余额*/
    getFinanceInfo: function(){
      var _self = this;
      $.ajax({
        type: 'POST',
        url: $.U('Member/getFinanceByAjax'),
        dataType: 'json',
        success: function(res){
          $('#Z_is_use_finance').val(1);
          var finance = parseFloat(res.finance);
          var sub_amount = _self.countSubPrice();

          // 订单还需支付金额大于等于账户余额，全部使用账户余额，否则使用还需支付金额
          var amount = (sub_amount >= finance) ? finance : sub_amount;
          if(amount > 0){
            $('#Z_use_finance').prop('checked', true);
            $('#Z_order_finance_box').addClass('selected').children('input').prop('checked', true);
            $('#Z_order_finance_box').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
            $('#Z_finance_desc').html('-' + amount + ' 元').closest('li').removeClass('hide');
          }else{
            $.ui.alert('暂无可用的余额');
            _self.cancelFinance();
          }

          _self.finance_price = parseFloat(amount);
          _self.countTotalPrice();
        }
      });
    },
    /*取消账户余额*/
    cancelFinance: function(){
      var _self = this;
      $('#Z_use_finance').prop('checked', false);
      $('#Z_order_finance_box').removeClass('selected').children('.icon-square-check-fill').addClass('icon-square').removeClass('icon-square-check-fill');
      $('#Z_finance_desc').html('-0 元').closest('li').addClass('hide');
      $('#Z_is_use_finance').val('');
      _self.finance_price = 0;
      _self.countTotalPrice();
    },
    /*使用优惠券*/
    useCoupon: function(){
      var _self = this;
      _self.checkCoupon();
      $('#Z_coupon_desc').closest('li').addClass('hide');
      $('#Z_order_coupon_box').on('tap', function(){
        (!$('#Z_coupon_box').hasClass('hide')) ? _self.hideCoupon() : _self.showCoupon();
      });

      $('#Z_coupon_cancel').on('click', function(e){
        e.preventDefault();
        _self.restoreCoupon();
      });
    },
    /*显示/隐藏优惠券内容*/
    showCoupon: function(){
      $('#Z_coupon_box').removeClass('hide');
      $('#Z_coupon_arrow').attr('class', 'icon icon-arrow-top');
    },
    hideCoupon: function(){
      $('#Z_coupon_box').addClass('hide');
      $('#Z_coupon_arrow').attr('class', 'icon icon-arrow-bottom');
    },
    /*优惠券验证*/
    checkCoupon: function(){
      var _self = this;
      $('#Z_coupon_check').on('click', function(){
        var number = $('input[name="order[coupons][]"]:checked').val();
        if(number){
          _self.getCouponInfo(number);
        }else{
          $.ui.error('请您选择优惠券');
        }
        return false;
      })
    },
    /*获取优惠券信息*/
    getCouponInfo: function(number){
      var _self = this;
      $.ajax({
        type: 'POST',
        url: $.U('Coupon/checkSelectedCoupon'),
        data: 'number=' + number,
        dataType: 'json',
        success: function(res){
          if(res.status === 1){
            $('#Z_coupon_id').val(res.data.id);
            $('#Z_order_coupon_box').addClass('selected').children('input').prop('checked', true);
            $('#Z_order_coupon_box').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
            _self.dealCouponInfo(res.data);
          }else{
            $.ui.error(res.msg);
          }
        }
      });
    },
    /*处理优惠券信息*/
    dealCouponInfo: function(data){
      var _self = this;
      if(data){
        _self.hideCoupon();

        // 使用优惠券
        var coupon_amount = parseFloat(data.amount);
        var sub_amount = _self.countSubPrice();

        // 订单还需支付金额大于等于优惠券余额，全部使用优惠券，否则使用还需支付金额
        var amount = (sub_amount >= coupon_amount) ? coupon_amount : sub_amount;
        _self.coupon_price = parseFloat(amount);
        if(amount > 0){
          $('#Z_coupon_desc').html('-' + amount.toFixed(2) + ' 元').closest('li').removeClass('hide');
        }
        _self.countTotalPrice();
      }else{
        _self.restoreCoupon();
      }
    },
    /*重置优惠券使用*/
    restoreCoupon: function(){
      $('#Z_coupon_box').addClass('hide');
      $('#Z_order_coupon_box').removeClass('selected').children('.icon-square-check-fill').attr('class', 'icon icon-square');
      $('#Z_coupon_arrow').attr('class', 'icon icon-arrow-bottom');
      $('#Z_coupon_list input').prop('checked', false);
      $('#Z_coupon_desc').html('-0 元');
      $('#Z_coupon_id').val('');
      $('#Z_coupon_desc').closest('li').addClass('hide');
      this.coupon_price = 0;
      this.countTotalPrice();
    },
    /*使用礼品卡*/
    useCard: function(){
      var _self = this;
      _self.checkCard();

      $('#Z_order_card_box').on('tap', function(){
        (!$('#Z_card_box').hasClass('hide')) ? _self.hideCard() : _self.showCard();
      });

      $('#Z_card_cancel').on('click', function(e){
        e.preventDefault();
        _self.restoreCard();
      });
    },
    /*显示/隐藏礼品卡内容*/
    showCard: function(){
      $('#Z_card_box').removeClass('hide');
      $('#Z_card_arrow').attr('class', 'icon icon-arrow-top');
    },
    hideCard: function(){
      $('#Z_card_box').addClass('hide');
      $('#Z_card_arrow').attr('class', 'icon icon-arrow-bottom');
    },
    /*礼品卡验证*/
    checkCard: function(){
      var _self = this;

      $('#Z_card_check').on('click', function(){
        // 选中的礼品卡变量值初始化
        _self.card_number = {};

        // 处理礼品卡使用多张的情况
        var card_items = $('#Z_card_list li').find('input[name="order[cards][]"]:checked');
        if(card_items.length > 0){
          for(var i = 0; i < card_items.length; i++){
            var card_number = $(card_items[i]).val();
            if((card_number !== undefined) && (card_number !== '')){
              _self.card_number[i] = card_number;
            }
          }
          var selected_card_number = $.implode(',', _self.card_number);
          _self.checkSelectedCard(selected_card_number);
        }else{
          $.ui.error('请选择礼品卡');
        }
        return false;
      });
    },
    /*验证用户选择的礼品卡*/
    checkSelectedCard: function(number){
      var _self = this;

      // 选中的礼品卡ID值初始化
      _self.card_id = {};

      $.ajax({
        type: 'POST',
        url: $.U('Card/checkSelectedCard'),
        data: 'number=' + number,
        dataType: 'json',
        success: function(res){
          if(res.status === 1){
            $.each(res.data, function(i, item){
              _self.card_id[item.id] = item.id;
            });
            $('#Z_card_id').val($.implode(',', _self.card_id));
            $('#Z_order_card_box').addClass('selected').children('input').prop('checked', true);
            $('#Z_order_card_box').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
            _self.dealCardInfo(res.data);
          }else{
            $.ui.error(res.msg);
          }
        }
      });
    },
    /*处理礼品卡详情*/
    dealCardInfo: function(data){
      var _self = this;
      if(data){
        _self.hideCard();

        // 礼品卡使用金额清零
        _self.card_amount = 0;
        _self.card_price = 0;

        // 遍历获取礼品卡可使用余额
        $.each(data, function(i, item){
          _self.card_amount += parseFloat(item.balance);
        });

        // 订单还需支付金额
        var sub_amount = _self.countSubPrice();

        // 订单还需支付金额大于等于礼品卡余额，全部使用礼品卡，否则使用还需支付金额
        var amount = (sub_amount >= _self.card_amount) ? _self.card_amount : sub_amount;
        _self.card_price = parseFloat(amount);
        if(amount > 0){
          $('#Z_card_desc').html('-' + amount + ' 元');
        }
        _self.countTotalPrice();
      }else{
        _self.restoreCard();
      }
    },
    /*重置礼品卡使用*/
    restoreCard: function(){
      $('#Z_card_box').addClass('hide');
      $('#Z_order_card_box').removeClass('selected');
      $('#Z_order_card_box').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
      $('#Z_card_arrow').attr('class', 'arrow-bottom');
      $('#Z_card_list input').prop('checked', false);
      $('#Z_card_desc').html('-0 元');
      $('#Z_card_id').val('');
      this.card_price = 0;
      this.card_amount = 0;
      this.card_id = {};
      this.card_number = {};
      this.countTotalPrice();
    },
    /*提交订单*/
    submit: function(){
      var has_submit = false, //订单是否已经提交过
          receiver_id = $('input[type="radio"][name="receiver_id"]:checked').val(),
          payment_type = $('input[type="radio"][name="payment_type"]:checked').val(),
          item_ids = $('#Z_item_ids').val();
      // 验证订单商品
      if(item_ids == null || item_ids == ''){
        $.ui.error('订单商品为空！');
        return false;
      }
      // 验证收货地址
      if(receiver_id == null || receiver_id == ''){
        $.ui.error('请您选择收货地址！');
        return false;
      }
      // 验证支付方式
      if(payment_type == null || payment_type == ''){
        $.ui.error('请您选择付款方式！');
        return false;
      }

      if(has_submit){
        $.ui.error('订单已提交，请勿重复提交！');
        return false;
      }
      has_submit = true;
      $('#Z_checkout_form').submit();
    },
    /*快速支付，非第三方支付*/
    quick_pay: function(orderId){
      var randcode = $('.Z_mobile_randcode'),
              randcode_val = randcode.val();
      if(randcode_val == null || randcode_val == ''){
        $.ui.error('请您输入支付验证码');
        randcode.focus();
        return false;
      }
      $('#payment_' + orderId).submit();
    },
    /*确认收货*/
    confirm: function(order_id){
      $.ui.confirm('确认已经收货?', function(){
        $.ajax({
          type: 'POST',
          url: $.U('Order/confirm'),
          data: {'order_id': order_id},
          dataType: 'json',
          success: function(res){
            if(res.status == 1){
              $.ui.success(res.info);
              common.reload();
//              return;
//              var self = $('#Z_order_' + order_id);
//              var zep = $('#Z_order_' + order_id);
//              if(self.size() == 1){
//                
//                var html = '<em class="msg">交易成功</em>';
//                self.children('a').remove();
//                self.html(html);
//              }else if(zep.size() == 1){
//                var html = '<p>交易成功</p>';
//                zep.html(html);
//              }
            }else{
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*提醒发货*/
    notice: function(orderId){
      $.ui.success('提醒成功！');
    },
    /*修改订单状态*/
    setStatus: function(order_id, type){
      var text = {'cancel' : '取消', 'recycle' : '删除', 'delete' : '永久删除', 'restore' : '还原'};
      $.ui.confirm('您确定' + text[type] + '该订单？', function(){
        $.ajax({
          type: 'POST',
          url: $.U('Order/setStatus'),
          data: {'order_id': order_id, 'type' : type},
          dataType: 'json',
          success: function(res){
            if(res.status == 1){
              $.ui.success(res.info);
              common.reload();
            }else{
              $.ui.error(res.info);
            }
          } 
        });
      });
    },
    /*申请退款*/
    refund: function(order_id){
      var doRefund = function(){
        $.post($.U('Order/refund'), 'order_id=' + order_id, function(res){
          if(res.status === 1){
            $.ui.success(res.info);
            window.setTimeout(function(){
              window.location.reload();
            }, 1e3);
          }else{
            $.ui.error(res.info);
          }
        }, 'json');
      }
      $.ui.confirm('您确定申请退货/退款？', doRefund);
    },
    /*申请退款*/
    unrefund: function(order_id){
      var doUnRefund = function(){
        $.post($.U('Order/unrefund'), 'order_id=' + order_id, function(res){
          if(res.status === 1){
            $.ui.success(res.info);
            window.setTimeout(function(){
              window.location.reload();
            }, 1e3);
          }else{
            $.ui.error(res.info);
          }
        }, 'json');
      }
      $.ui.confirm('您确定撤销退款申请？', doUnRefund);
    },
    /*订单列表绑定视觉*/
    bindList: function(){
      $('.Z_order_comment').on('tap', function(){
        var item_id = $(this).data('item_id');
        if(item_id){
          comment.add(item_id);
        }else{
          $.ui.error('商品ID不能为空');
        }
      });
    }
  }
  module.exports = order;
});
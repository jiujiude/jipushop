/*全局模块*/
var core = {
  /*定义全局验证规则*/
  regx: {
    mobile: /^(13[0-9]{9})|(18[0-9]{9})|(15[0-9]{9})|(14[57][0-9]{8})|(17[0-9]{9})$/,
    email: /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/g,
  },
  /*全局初始化*/
  init: function(){
    //初始化商品列表链接
    this.initItemlist();
    //初始化input绑定事件
    this.initInputBind();
  },
  /*初始化商品列表链接*/
  initItemlist: function(){
    $('.Z_item_list').tap(function(){
      var url = $(this).data('href');
      window.location.href = url;
    });
  },
  /*input绑定清空事件*/
  initInputBind: function(){
    $('input[type=text], input[type=number], input[type=password], input[type=tel], input[type=email], input[type=search]')
            .on('focus', function(){
              if($(this).val() != ''){
                if($(this).next('.Z_input_clear').length <= 0){
                  $(this).after('<a href="javascript:core.clearInput();" class="clear Z_input_clear"><i class="icon icon-close"></i></a>');
                }
              }
            })
            .on('blur', function(){
              if($(this).val() == ''){
                $(this).next('.Z_input_clear').remove();
              }
            })
            .on('keyup', function(){
              if($(this).val() != ''){
                if($(this).next('.Z_input_clear').length <= 0){
                  $(this).after('<a href="javascript:core.clearInput();" class="clear Z_input_clear"><i class="icon icon-close"></i></a>');
                }
              }else{
                $(this).next('.Z_input_clear').remove();
              }
            });
  },
  /*清空input内容*/
  clearInput: function(){
    $('.Z_input_clear')
            .on('tap', function(){
              $(this).prev('input').val('');
              $(this).remove();
            });
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
  /*下拉加载更多*/
  loadmore: function(url){
    if(!url){
      return false;
    }
    var getMore = function(page){
      $.ajax({
        type: 'GET',
        url: url,
        data: 'p=' + page,
        success: function(res){
          $('#Z_load_itemlist').append(res);
          if(!res){
            $('#Z_list_loadmore').attr('class', 'error').find('span').text('没有了');
          }
        }
      });
    }
    $('#Z_load_itemlist').LoadMore({
      normalText: '加载更多…',
      loadingText: '正在获取',
      errorText: '没有了',
      autoLoadMore: true,
      onLoadMore: function(){
        setTimeout(function(){
          $('#Z_load_itemlist').LoadMore('pushHTML', getMore($('#Z_load_itemlist').LoadMore('pageNumber')));
        }, 100);
      },
    });
  }
}

/*商品*/
var item = {
  sku_choose_size: 0, //统计待选规格项数量
  sku_selected_size: 0, //已选规格项数量
  item_is_buynow: false, //是否立即购买
  init: function(spc_data){
    var _self = this;
    //显示、隐藏底部浮动购买按钮
    _self.toggleFixedBar();
    //统计待选规格项数量
    _self.sku_choose_size = $('.Z_sku_box').size();
    _self.sku_selected_size = 0;
    //绑定遮罩切换
    _self.toggleBackdrop();

    //绑定规格选择事件
    $('.Z_sku_box li.Z_sale_porp').not('.out-of-stock').on('click', function(){
      //选中的规格项样式控制
      $(this).addClass('select').siblings('.Z_sale_porp').removeClass('select');
      //根据当前页面用户所选规格的code组合获取对应价格和库存数量重新设置页面显示的价格和库存
      var data_code = $(this).attr('data-code'),
              specifiction_selected = $('.Z_spec_item li.select'),
              specifiction_all_code = '',
              specifiction_all_args = '',
              selected_spc_info = '';
      //统计已选规格项数量
      _self.sku_selected_size = specifiction_selected.size();
      if((_self.sku_choose_size > 0) && (_self.sku_selected_size == _self.sku_choose_size)){
        //拼接已选规格信息
        var i = 0;
        $.each(specifiction_selected, function(k, v){
          if(i == 0){
            specifiction_all_code = $(v).attr('data-code');
            specifiction_all_args = $(v).attr('args');
            selected_spc_info = selected_spc_info + $(v).attr('title');
          }else{
            specifiction_all_code = specifiction_all_code + '-' + $(v).attr('data-code');
            specifiction_all_args = specifiction_all_args + '&' + $(v).attr('args');
            selected_spc_info = selected_spc_info + "，" + $(v).attr('title');
          }
          i++;
        });

        var price = 0;
        var quantity = 0;
        if(spc_data){
          //获取当前用户选择的规格组合对应的价格和库存数量
          if(spc_data[specifiction_all_code]){
            price = spc_data[specifiction_all_code]['price'];
            quantity = spc_data[specifiction_all_code]['quantity'];

            //设置页面显示的价格与库存数量
            $('.Z_spec_price').html(price);
            $('.Z_spec_quantity').html(quantity);
            $('#Z_item_price').val(price);
            $('#Z_item_stock').val(quantity);
          }
        }

        //判断用户所选商品是否还有库存
        if($('#Z_item_stock').val() <= 0){
          UI.error('抱歉，您选购的商品库存不足')
        }

        //设置已选规格内容
        $('#Z_selected_info').html('已选：' + selected_spc_info + "，数量 <span id=\"Z_selected_quantity\">" + $('#Z_item_quantity').val() + '</span>');

        //隐藏表单传值
        $('#Z_item_spec').val(specifiction_all_args);
        $('#Z_item_code').val(specifiction_all_code);
      }
    });

    //绑定规格选择确认事件
    $('#Z_spec_submit').on('click', function(){
      if(_self.sku_selected_size < _self.sku_choose_size){
        UI.error('请您选择规格');
      }else{
        //关闭规格选择弹窗
        _self.closeSpecModal();
        //立即购买 or 加入购物车
        item.addCart(_self.sku_choose_size, _self.sku_selected_size, _self.item_is_buynow);
        //重置数量和已选规格
        _self.resetNumSpec();
      }
    });

    //绑定添加购物车事件
    $('.Z_add2cart').on('tap', function(){
      item.addCart(_self.sku_choose_size, _self.sku_selected_size);
      _self.item_is_buynow = false;
    });

    //绑定立即购买事件
    $('.Z_quick_buy').on('tap', function(){
      item.addCart(_self.sku_choose_size, _self.sku_selected_size, true);
      _self.item_is_buynow = true;
    });

    //绑定收藏事件
    $('.fav').click(function(){
      item.addFav();
    });
  },
  /*重置已选择的商品数量和规格*/
  resetNumSpec: function(){
    var _self = this;
    $('#Z_item_quantity').val(1);
    $('.Z_spec_item li').removeClass('select');
    _self.sku_selected_size = 0;
  },
  toggleFixedBar: function(){
    //显示/隐藏底部浮动购买按钮
    window.addEventListener('touchmove', function(event){
      if(!event.touches.length){
        return;
      }
      var touch = event.touches[0],
              startY = touch.pageY,
              height = $('#Z_buy_box').offset().top,
              fixed_navbar = $('.Z_fixed_navbar');
      if(startY - height > 300){
        fixed_navbar.addClass('layer-show');
      }else{
        fixed_navbar.removeClass('layer-show');
      }
    });
  },
  toggleBackdrop: function(){
    //遮罩切换
    window.addEventListener('touchend', function(event){
      var spec_modal = document.querySelector('#Z_spec_info');
      if(spec_modal){
        spec_modal.classList.contains('active') ? UI.showBackdrop() : UI.hideBackdrop();
      }
    });
  },
  /*打开规格选择面板*/
  openSpcModal: function(){
    var spec_modal = document.querySelector('#Z_spec_info');
    if(spec_modal && spec_modal.classList.contains('modal')){
      spec_modal.classList.add('active');
      UI.showBackdrop();
    }
  },
  /*关闭规格选择弹窗*/
  closeSpecModal: function(){
    var spec_modal = document.querySelector('#Z_spec_info');
    if(spec_modal && spec_modal.classList.contains('modal')){
      spec_modal.classList.remove('active');
      UI.hideBackdrop();
    }
  },
  /*切换规格选择面板中商品图片*/
  switchPic: function(src){
    $('#Z_item_spc .img img').attr("src", src);
  },
  emptyTips: function(){
    UI.message('抱歉，该商品暂时缺货！');
  },
  /*更改数量*/
  quantity: function(op){
    var quantity = $('#Z_item_quantity').val();
    var stock = $('#Z_item_stock').val();
    var newQuantity = (op == 'plus') ? parseInt(quantity) + 1 : parseInt(quantity) - 1;

    if(newQuantity <= 1){
      $('.minus').addClass('disabled');
    }else if(newQuantity >= stock){
      $('.plus').addClass('disabled');
    }else{
      $('.minus, .plus').removeClass('disabled');
    }

    if(newQuantity >= 1 && newQuantity <= stock){
      $('#Z_item_quantity').val(newQuantity);
      $('#Z_selected_quantity').html(newQuantity);
    }
  },
  /*添加购物车*/
  addCart: function(sku_choose_size, sku_selected_size, is_buynow){
    if(sku_choose_size > 0){
      //判断用户是否选齐规格
      if(sku_selected_size < sku_choose_size){
        //打开规格选项面板
        item.openSpcModal();

        //规格选择提示
        $('#Z_item_spc .tb-key').addClass('tb-attention');
        $('#Z_item_spc .tb-note-title').show();
        return false;
      }

      //判断用户所选商品是否还有库存
      if($('#Z_item_stock').val() <= 0){
        //打开规格选项面板
        item.openSpcModal();
        //无库存提示
        UI.error('抱歉，您选购的商品库存不足');
        return false;
      }
    }else{
      //判断用户所选商品是否还有库存
      if($('#Z_item_stock').val() <= 0){
        //无库存提示
        UI.error('抱歉，您选购的商品库存不足');
        return false;
      }
    }

    //AJAX提交购物车数据
    var item_code = $('#Z_item_code').val();
    var item_id = $('#Z_item_id').val();
    var quantity = $('#Z_item_quantity').val();
    var price = $('#Z_item_price').val();

    var url = Core.U('Cart/add');
    var buynow_param = is_buynow ? 'buynow=1&' : '';
    var param = 'item_code=' + item_code + '&item_id=' + item_id + '&quantity=' + quantity + '&price=' + price + '&' + buynow_param + $('#Z_item_spec').val();
    $.post(url, param, function(res){
      if(res.status == 1){
        //是否立即购买
        if(is_buynow){
          window.location.href = Core.U('Order/index', 'buynow=1');
        }else{
          //购物车添加成功提示信息
          $('#Z_global_cart_quantity').html(res.total.total_num);
          UI.message('商品成功加入购物车');
          // core.reload();
        }
      }else if(res.status === 0){
        window.location.href = res.url;
      }
    }, 'json');
  },
  /* 添加收藏夹 */
  addFav: function(id){
    var uid = Core.UID;
    if(typeof (uid) == "undefined" || uid <= 0){
      window.location.href = Core.U('User/login');
      return false;
    }else{
      var item_id = $('#Z_item_id').val();
      $.post(Core.U('Fav/add'), 'id=' + item_id, function(res){
        if(res.status == 1){
          $('#item_' + item_id).addClass('has-fav');
          UI.success('收藏成功');
        }else if(res.status === 0){
          UI.error('您已收藏过该商品了');
        }
      }, 'json');
    }
  },
  /*删除收藏*/
  removeFav: function(id){
    $.post(Core.U('Fav/remove'), 'id=' + id, function(res){
      if(res.status === 1){
        $('#Z_favitem_' + id).fadeOut(500);
      }else if(res.status === 0){
        UI.error('删除失败');
      }
    },
            'json');
  }
}

/*优惠券*/
var coupon = {
  init: function(){
    var _self = this;
    // 初始化
    $('.Z_get_coupon').tap(function(){
      var coupon_id = $(this).attr('data-id');
      var get_coupon = _self.get(coupon_id);
      if(get_coupon){
        $(this).find('.Z_coupon_info').html('<em class="text-danger">领取成功</em><i class="icon icon-success"></i>');
        UI.success('优惠券领取成功');
        core.reload();
      }
    });
  },
  // 领取优惠券
  get: function(id){
    var result;
    if(Core.UID <= 0){
      window.location.href = Core.U('User/login');
      return false;
    }
    $.ajax({
      type: 'POST',
      async: false,
      url: Core.U('CouponUser/add'),
      data: 'id=' + id,
      dataType: 'json',
      success: function(res){
        result = res;
      }
    });
    return result;
  }
}

/*购物车*/
var cart = {
  init: function(){
  },
  /*购物车为空时信息提示*/
  setEmptyTips: function(){
    var buyUrl = Core.U('Index/index');
    var emptyTips = '<div class="content-padded cart-empty"><a href="' + buyUrl + '" class="img"><i class="icon icon-cart"></i></a><p class="tips">您的购物车内没有任何商品</p><p><a class="btn btn-block btn-positive" href="' + buyUrl + '"><span class="ui-btn-text">马上去选购</span></a></p></div>';
    $('.content').html(emptyTips);
  },
  /*修改购物车商品数量*/
  update: function(op, item_code, price){
    var quantity = $('#item_num_' + item_code).val();
    var sum_price;
    var total_quantity = 0;
    if(op == 'plus'){
      total_quantity = parseInt(quantity) + 1;
    }else if(op == 'minus'){
      total_quantity = parseInt(quantity) - 1;
    }else if(op == 'input'){
      total_quantity = parseInt($('#item_num_' + item_code).val());
    }

    var minusItem = $('#item_box_' + item_code).find('.minus');
    (total_quantity <= 1) ? minusItem.addClass('no-minus') : minusItem.removeClass('no-minus');

    if(total_quantity < 1){
      return false;
    }else{
      sum_price = parseFloat(total_quantity) * price;
      $('#item_num_' + item_code).val(total_quantity);
      $('#item_price_' + item_code).html('¥' + sum_price.toFixed(2));

      /*服务端更新商品数量，返回购物车商品总价格*/
      $.post(Core.U('Cart/update'), 'item_code=' + item_code + '&quantity=' + total_quantity, function(res){
        if(res.status == 1){
          $('.total-amount').html('<em>¥</em>' + res.total.total_amount);
          $('#Z_global_cart_quantity').html(res.total.total_num);
        }
      }, 'json');
    }
  },
  /*删除购物车内商品*/
  remove: function(item_code){
    if(confirm('确定从购物车中删除吗？')){
      $.post(Core.U('Cart/remove'), 'item_code=' + item_code, function(res){
        if(res.status == 1){
          /*购物车为空时更新提示信息*/
          if(res.total.total_quantity == 0){
            console.log('empty');
            cart.setEmptyTips();
          }
          /*更新购物车商品总价*/
          $('#cart-group-' + item_code).remove();
          $('.total-amount').html('<em>¥</em>' + res.total.total_amount);

          /*更新导航栏购物车商品数量*/
          $('#Z_global_cart_quantity').html(res.total.total_num);
        }
      }, 'json');
    }
  },
  /*清空购物车*/
  clear: function(){
    if(confirm('确定清空购物车？')){
      $.post(Core.U('Cart/clear'), '', function(res){
        if(res.status == 1){
          $('#Z_global_cart_quantity').html(0);
          /*购物车为空时提示信息*/
          cart.setEmptyTips();
        }
      }, 'json');
    }
  }
}

/*订单*/
var order = {
  total_price: 0,
  total_delivery: 0,
  sub_price: 0,
  finance_price: 0,
  coupon_price: 0,
  card_price: 0,
  card_amount: 0,
  card_id: {},
  card_number: {},
  /*初始化*/
  init: function(total_price){
    var _self = this;
    _self.total_price = total_price;
    _self.use_finance();
    _self.use_coupon();
    _self.use_card();
    // 初始化商品清单切换
    _self.toggle_items();

    // 初始化支付方式
    $('.J_payment > .item').on('tap', function(){
      $(this).addClass('selected').children('input').prop('checked', true);
      $(this).siblings().removeClass('selected');
    });

    // 初始化配送方式
    $('.J_delivery > .item').on('tap', function(){
      $(this).addClass('selected').children('input').prop('checked', true);
      $(this).siblings().removeClass('selected');
      _self.delivery();
    });
    if($('.J_delivery > .item').size() === 1){
      $('.J_delivery > .item').addClass('selected').children('input').prop('checked', true);
      _self.delivery();
    }

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
  },
  /*配送方式*/
  delivery: function(){
    var _self = this;
    window.setTimeout(function(){
      delivery_obj = $('input[name="delivery_id"]:checked');
      if(delivery_obj.size() == 1){
        price = delivery_obj.data('price');
        _self.total_delivery = price;
        $('.J_delivery_fee').html(price + ' 元');
        _self.count_total_price();
      }
    }, 50);
  },
  /*统计购物车使用某一种支付方式后的剩余金额*/
  count_sub_price: function(){
    var total_price = order.total_price,
            total_delivery = order.total_delivery;
    sub_price = parseFloat(total_price) + parseFloat(total_delivery) - this.finance_price - this.coupon_price - this.card_price;
    if(sub_price <= 0){
      sub_price = 0;
    }
    return sub_price.toFixed(2);
  },
  /*统计购物车商品价格*/
  count_total_price: function(){
    var total_price = order.total_price,
            total_delivery = order.total_delivery;
    total_price = parseFloat(total_price) + parseFloat(total_delivery) - this.finance_price - this.coupon_price - this.card_price;

    if(total_price <= 0){
      // 隐藏其他付款方式
      $('.Z_payment_list .Z_payment_item:not(.selected)').parent('.Z_itemlist').hide();
      $('.J_coupon_con').addClass('hide');
      $('.J_card_con').addClass('hide');
      total_price = 0;
    }else{
      if($('.Z_payment_list .nav').size() > 0){
        $('.Z_payment_list .nav:not(.selected)').parent('.Z_payment_list').show();
      }
    }
    $('.J_total_price').html(total_price.toFixed(2));
  },
  /*使用账户余额*/
  use_finance: function(){
    var _self = this;
    $('.J_use_finance').on('tap', function(){
      var isCheck = $('#use_finance').prop('checked');
      if(isCheck){
        $('#use_finance').prop('checked', false);
        _self.cancel_finance();
      }else{
        $('#use_finance').prop('checked', true);
        _self.get_finance_info();
      }
    });
  },
  /*选择账户余额*/
  get_finance_info: function(){
    var _self = this;
    $.ajax({
      type: 'POST',
      url: Core.U('Member/getFinanceByAjax'),
      dataType: 'json',
      success: function(res){
        $('#is_use_finance').val(1);
        var finance = parseFloat(res.finance);
        var sub_amount = _self.count_sub_price();

        // 订单还需支付金额大于等于账户余额，全部使用账户余额，否则使用还需支付金额
        var amount = (sub_amount >= finance) ? finance : sub_amount;
        if(amount > 0){
          $('.J_use_finance').addClass('selected').children('input').prop('checked', true);
          $('.J_use_finance').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
          $('.J_finance_desc').html('-' + amount + ' 元');
        }else{
          $('#use_finance').prop('checked', false);
          $('.J_use_finance').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
        }

        _self.finance_price = parseFloat(amount);
        _self.count_total_price();
      }
    });
  },
  /*取消账户余额*/
  cancel_finance: function(){
    var _self = this;
    $('.J_use_finance').removeClass('selected').children('.icon-square-check-fill').addClass('icon-square').removeClass('icon-square-check-fill');
    $('.J_finance_desc').html('-0 元');
    $('#is_use_finance').val('');
    _self.finance_price = 0;
    _self.count_total_price();
  },
  /*使用优惠券*/
  use_coupon: function(){
    var _self = this;
    _self.check_coupon();

    $('.J_use_coupon').on('tap', function(){
      (!$('.J_coupon_con').hasClass('hide')) ? _self.hide_coupon() : _self.show_coupon();
    });

    $('.J_cancel_coupon').on('click', function(e){
      e.preventDefault();
      _self.restore_coupon();
    });
  },
  /*显示/隐藏优惠券内容*/
  show_coupon: function(){
    $('.J_coupon_con').removeClass('hide');
    $('#J_coupon_arrow').attr('class', 'arrow-top');
  },
  hide_coupon: function(){
    $('.J_coupon_con').addClass('hide');
    $('#J_coupon_arrow').attr('class', 'arrow-bottom');
  },
  /*优惠券验证*/
  check_coupon: function(){
    var _self = this;
    $('.J_check_coupon').on('click', function(){
      var number = $('input[name="order[coupons][]"]:checked').val();
      if(number){
        _self.get_coupon_info(number);
      }else{
        UI.error('请您选择优惠券');
      }
      return false;
    })
  },
  /*获取优惠券信息*/
  get_coupon_info: function(number){
    var _self = this;
    $.ajax({
      type: 'POST',
      url: Core.U('Coupon/checkSelectedCoupon'),
      data: 'number=' + number,
      dataType: 'json',
      success: function(res){
        if(res.status === 1){
          $('#coupon_id').val(res.data.id);
          $('.J_use_coupon').addClass('selected').children('input').prop('checked', true);
          $('.J_use_coupon').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
          _self.deal_coupon_info(res.data);
        }else{
          UI.error(res.msg);
        }
      }
    });
  },
  /*处理优惠券信息*/
  deal_coupon_info: function(data){
    var _self = this;
    if(data){
      _self.hide_coupon();

      // 使用优惠券
      var coupon_amount = parseFloat(data.amount);
      var sub_amount = _self.count_sub_price();

      // 订单还需支付金额大于等于优惠券余额，全部使用优惠券，否则使用还需支付金额
      var amount = (sub_amount >= coupon_amount) ? coupon_amount : sub_amount;
      _self.coupon_price = parseFloat(amount);
      if(amount > 0){
        $('.J_coupon_desc').html('-' + amount + ' 元');
      }
      _self.count_total_price();
    }else{
      _self.restore_coupon();
    }
  },
  /*重置优惠券使用*/
  restore_coupon: function(){
    $('.J_coupon_con').addClass('hide');
    $('.J_use_coupon').removeClass('selected');
    $('.J_use_coupon').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
    $('#J_coupon_arrow').attr('class', 'arrow-bottom');
    $('.J_coupon_list input').prop('checked', false);
    $('.J_coupon_desc').html('-0 元');
    $('#coupon_id').val('');

    this.coupon_price = 0;
    this.count_total_price();
  },
  /*使用礼品卡*/
  use_card: function(){
    var _self = this;
    _self.check_card();

    $('.J_use_card').on('tap', function(){
      (!$('.J_card_con').hasClass('hide')) ? _self.hide_card() : _self.show_card();
    });

    $('.J_cancel_card').on('click', function(e){
      e.preventDefault();
      _self.restore_card();
    });
  },
  /*显示/隐藏礼品卡内容*/
  show_card: function(){
    $('.J_card_con').removeClass('hide');
    $('#J_card_arrow').attr('class', 'arrow-top');
  },
  hide_card: function(){
    $('.J_card_con').addClass('hide');
    $('#J_card_arrow').attr('class', 'arrow-bottom');
  },
  /*礼品卡验证*/
  check_card: function(){
    var _self = this;

    $('.J_check_card').on('click', function(){
      // 选中的礼品卡变量值初始化
      _self.card_number = {};

      // 处理礼品卡使用多张的情况
      var card_items = $('.J_card_list li').find('input[name="order[cards][]"]:checked');
      if(card_items.length > 0){
        for(var i = 0; i < card_items.length; i++){
          var card_number = $(card_items[i]).val();
          if((card_number !== undefined) && (card_number !== '')){
            _self.card_number[i] = card_number;
          }
        }
        var selected_card_number = Core.implode(',', _self.card_number);
        _self.checkSelectedCard(selected_card_number);
      }else{
        UI.error('请选择礼品卡');
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
      url: Core.U('Card/checkSelectedCard'),
      data: 'number=' + number,
      dataType: 'json',
      success: function(res){
        if(res.status === 1){
          $.each(res.data, function(i, item){
            _self.card_id[item.id] = item.id;
          });
          $('#card_id').val(Core.implode(',', _self.card_id));
          $('.J_use_card').addClass('selected').children('input').prop('checked', true);
          $('.J_use_card').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
          _self.deal_card_info(res.data);
        }else{
          UI.error(res.msg);
        }
      }
    });
  },
  /*处理礼品卡详情*/
  deal_card_info: function(data){
    var _self = this;
    if(data){
      _self.hide_card();

      // 礼品卡使用金额清零
      _self.card_amount = 0;
      _self.card_price = 0;

      // 遍历获取礼品卡可使用余额
      $.each(data, function(i, item){
        _self.card_amount += parseFloat(item.balance);
      });

      // 订单还需支付金额
      var sub_amount = _self.count_sub_price();

      // 订单还需支付金额大于等于礼品卡余额，全部使用礼品卡，否则使用还需支付金额
      var amount = (sub_amount >= _self.card_amount) ? _self.card_amount : sub_amount;
      _self.card_price = parseFloat(amount);
      if(amount > 0){
        $('.J_card_desc').html('-' + amount + ' 元');
      }
      _self.count_total_price();
    }else{
      _self.restore_card();
    }
  },
  /*重置礼品卡使用*/
  restore_card: function(){
    $('.J_card_con').addClass('hide');
    $('.J_use_card').removeClass('selected');
    $('.J_use_card').children('.icon-square').addClass('icon-square-check-fill').removeClass('icon-square');
    $('#J_card_arrow').attr('class', 'arrow-bottom');
    $('.J_card_list input').prop('checked', false);
    $('.J_card_desc').html('-0 元');
    $('#card_id').val('');
    this.card_price = 0;
    this.card_amount = 0;
    this.card_id = {};
    this.card_number = {};
    this.count_total_price();
  },
  /*切换商品清单*/
  toggle_items: function(){
    var package_items = $('.Z_package_items'),
            package_arrow = $('#Z_package_arrow');
    if(package_items.hasClass('hide')){
      package_items.removeClass('hide');
      package_arrow.attr('class', 'arrow-top');
    }else{
      package_items.addClass('hide');
      package_arrow.attr('class', 'arrow-bottom');
    }
  },
  /*提交订单*/
  submit: function(){
    var has_submit = false, //订单是否已经提交过
            delivery_id = $('input[type="radio"][name="delivery_id"]:checked').val(),
            receiver_id = $('input[type="radio"][name="receiver_id"]:checked').val(),
            payment_type = $('input[type="radio"][name="payment_type"]:checked').val(),
            item_ids = $('#item_ids').val();
    // 验证订单商品
    if(item_ids == null || item_ids == ''){
      UI.error('订单商品为空！');
      return false;
    }
    // 验证配送方式
    if(delivery_id == null || delivery_id == ''){
      UI.error('请选择配送方式！');
      return false;
    }
    // 验证收货地址
    if(receiver_id == null || receiver_id == ''){
      UI.error('请您选择收货地址！');
      return false;
    }
    // 验证支付方式
    if(payment_type == null || payment_type == ''){
      UI.error('请您选择付款方式！');
      return false;
    }

    if(has_submit){
      UI.error('订单已提交，请勿重复提交！');
      return false;
    }
    has_submit = true;
    $('.J_checkout_form').submit();
  },
  /*执行支付*/
  pay: function(order_id){

    var total_amount = $('.Z_total_amount').val(),
            payment_type = $('.Z_payment_type').val();

    //微信中使用支付宝支付提示在浏览器中打开
    //APP内使用原生支付方式
    if(window.android !== undefined){
      if(window.android.is_mobile() === true){
        //获取订单支付方式
        switch(payment_type){
          case 'alipaywap':
            window.android.paybyalipay(order_id, total_amount);
            break;
          case 'wechatpay':
            window.android.paybywechat(order_id, total_amount);
            break;
          default:
            ;
        }
      }
    }else{
      $('#payment_' + order_id).submit();
    }
  },
  /*快速支付，非第三方支付*/
  quick_pay: function(orderId){
    var randcode = $('.Z_mobile_randcode'),
            randcode_val = randcode.val();
    if(randcode_val == null || randcode_val == ''){
      UI.error('请您输入支付验证码');
      randcode.focus();
      return false;
    }
    $('#payment_' + orderId).submit();
  },
  /*确认收货*/
  confirm: function(orderId){
    UI.confirm('确认已经收货?', function(){
      $.post(Core.U('Order/confirm'), {'orderId': orderId}, function(res){
        if(res.status == 1){
          UI.success(res.info);
          var self = $('#order-' + orderId);
          var zep = $('#Z-order-' + orderId);
          if(self.size() == 1){
            var html = '<em class="msg">交易成功</em><a href="javascript:order.remark(' + orderId + ');" class="ui-btn ui-btn-sm btn-act ui-state-hover" hl-cls="ui-state-hover">立即评价</a>';
            self.children('a').remove();
            self.html(html);
          }else if(zep.size() == 1){
            var html = '<p>交易成功</p><a href="javascript:order.remark(' + orderId + ');" class="btn btn-block btn-positive">立即评价</a>';
            zep.html(html);
          }
        }else{
          UI.error(res.info);
        }
      }, 'json');
    });
  },
  /*提醒发货*/
  notice: function(orderId){
    UI.success('提醒成功！');
  },
  /*取消订单*/
  cancel: function(order_id){
    if(confirm('您确认取消该订单？')){
      $.post(Core.U('Order/cancel'), 'order_id=' + order_id, function(res){
        if(res.status == 1){
          UI.success(res.msg);
          var html = '<p class="btn btn-block">订单已取消</p>';
          $('#Z_order_act').html(html);
        }else{
          UI.error(res.msg);
        }
      }, 'json');
    }
  },
  /*评价*/
  remark: function(orderId){
    UI.success('评价功能正在开发！');
  },
  /*申请退款*/
  refund: function(order_id){
    var doRefund = function(){
      $.post(Core.U('Order/refund'), 'order_id=' + order_id, function(res){
        if(res.status === 1){
          UI.success(res.info);
          window.setTimeout(function(){
            window.location.reload();
          }, 1e3);
        }else{
          UI.error(res.info);
        }
      }, 'json');
    }
    UI.confirm('您确定申请退货/退款？', doRefund);
  },
}

/*收货地址*/
var receiver = {
  init: function(){
    /*表单提交*/
    $('form').submit(function(){
      $('.setdefaultaddress').val($('.Z_is_default_ck').hasClass('active') ? 1 : 0);
      var self = $(this);
      $.post(self.attr('action'), self.serialize(), success, 'json');
      return false;
      function success(data){
        if(data.status){
          if(data.url){
            window.location.href = data.url;
          }
        }else{
          UI.error(data.info);
        }
      }
    });
    /*设置默认收货地址*/
    $('#isDefault').tap(function(){
      var setDefault = $('.setdefaultaddress');
      (setDefault.val() == 1) ? setDefault.val(0) : setDefault.val(1);
    });
    /*选择收货地址*/
    $('.J_select').click(function(){
      var args = Core.get_args($(this));
      window.location.href = Core.U('Order/index', 'receiver_id=' + args.receiver_id);
    });
  }
}

/*会员*/
var member = {
  /**
   * @param int interval 验证码发送间隔时间
   */
  init: function(interval){
    var interval = interval;
    // 表单验证
    $('form').submit(function(){
      var self = $(this);
      $.post(self.attr('action'), self.serialize(), success, 'json');
      return false;

      function success(data){
        if(data.status){
          UI.success(data.info);
          core.redirect(data.url);
        }else{
          UI.error(data.info);
        }
      }
    });

    // 初始化充值方式
    $('.J_recharge > li').on('tap', function(){
      $(this).addClass('selected').children('input').prop('checked', true);
      $(this).siblings().removeClass('selected');
    });
  },
  sendRandcode: function(){
    var regxMobile = /(^[^1][0-9\-]{6,20}$)|(^(134|135|136|137|138|139|150|151|152|157|158|159|182|183|187|188|147|130|131|132|155|156|185|186|145|133|153|180|189|181|170)\d{8}$)/;
    var mobile = $('#mobile').val();
    if(!regxMobile.test(mobile)){
      UI.error('请您输入正确的手机号码');
      $('#mobile').focus();
      return false;
    }
    // 发送手机验证码
    $.post(Core.U('Member/sendsms'), 'mobile=' + mobile, function(res){
      $('.J_sendCode').html('验证码发送中…');
      if(res.status === 1){
        UI.success(res.info);
        var lazytime = 5;
        // 重新发送倒计时
        var waithtml = '<span class="ui-btn ui-btn-lg ui-btn-flex J_sendCode disabled">' + lazytime + '秒后重新发送</span>';
        var wait = setInterval(function(){
          if(lazytime > 0){
            $('.J_sendCode').replaceWith(waithtml);
            msg = lazytime + ' 秒后重新发送';
            $('.J_sendCode').html(msg);
            --lazytime;
          }else{
            clearInterval(wait);
            $('.J_sendCode').replaceWith('<a href="javascript:member.sendRandcode();" class="ui-btn ui-btn-lg ui-btn-flex ui-btn-danger J_sendCode">重新发送验证码</a>');
            $('.J_randcode').hide();
          }
        }, 1000);
        $('.J_randcode').show();
      }else{
        UI.error(res.info);
      }
    }, 'json');
  },
  /*手机绑定*/
  mobile_bind: function(){
    $('.Z_binded p a').click(function(){
      $('.Z_binded').hide();
      $('.Z_bind-form').removeClass('hide');
      $('.Z_mobile').focus();
    });
    var form = $('.Z_bind-form');
    form.on('submit', function(){
      $('.Z_submit').attr('disabled', true).val('处理中...');
      $.post(form.attr('action'), form.serialize(), function(json){
        if(json.status === 1){
          UI.success(json.info);
          core.reload(1000);
        }else{
          UI.error(json.info);
          $('.Z_submit').removeAttr('disabled').val('提交验证');
        }
      }, 'json');
      return false;
    });
  },
}

/*用户*/
var user = {
  init: function(){
    var _self = this;
    // 表单验证
    $('form').submit(function(){
      var self = $(this);
      $.post(self.attr('action'), self.serialize(), success, 'json');
      return false;

      function success(data){
        if(data.status){
          window.location.href = data.url;
        }else{
          UI.error(data.info);
        }
      }
    });
    //表单验证绑定
    $('#Z_input_username').on('blur, keyup', function(){
      var mobile = $(this).val();
      var check = _self.checkMobile(mobile);
      $(this).data('check_status', check.status);
      if(check.status > 0){
        //未注册，注册账号
        if(check.status == 1){
          $('#Z_bind_form').attr('action', Core.U('User/register'));
          $('#Z_bind_submit').text('创建账号');
          $('#Z_input_password').attr('placeholder', '请您设置密码');
          $('#Z_bind_password, #Z_bind_repassword, #Z_bind_randcode').removeClass('hide');
          $('#Z_input_password').hasClass('last') || $('#Z_input_password').removeClass('last');
          $('#Z_input_password').focus();
          //已注册，绑定账号
        }else if(check.status == 2){
          if($("input[name='oauth_type']").size() == 1){
            UI.message('该手机号已经注册，请您输入登录密码', 2);
            $('#Z_bind_form').attr('action', Core.U('User/bind'));
            $('#Z_bind_submit').text('绑定账号');
            $('#Z_input_username').removeClass('single').addClass('first');
            $('#Z_input_password').addClass('last').attr('placeholder', '请您输入密码');

            if($('#Z_bind_password .find_password').size() == 0){
              url = Core.U('User/forgetpwd');
              console.log(url);
              $('<a href="' + url + '" class="find_password">忘记密码</a>').appendTo('#Z_bind_password');
            }
            $('#Z_bind_password').removeClass('hide');
            $('#Z_bind_repassword').hasClass('hide') || $('#Z_bind_repassword').addClass('hide') || $('#Z_bind_randcode').addClass('hide');
            $('#Z_input_password').focus();
          }
        }
      }else{
        $('#Z_input_password, #Z_input_repassword').val('');
        $('#Z_bind_passes').addClass('hide');
      }
    });
    $('#Z_bind_submit').on('tap', function(){
      var username = $('#Z_input_username'),
              password = $('#Z_input_password');
      if(username.val() == ''){
        username.focus();
        return false;
      }
      if(password.val() == ''){
        password.focus();
        return false;
      }
      $('#Z_bind_form').submit();
    });
  },
  wechatCallback: function(){
    var username = $('#Z_input_username'),
            password = $('#Z_input_password'),
            repassword = $('#Z_input_repassword');
    if(username.val() == ''){
      username.focus();
      return false;
    }

    if(!core.regx.mobile.test(username.val())){
      UI.error('抱歉，您输入的手机号码不正确');
      return false;
    }
    //判断手机号是否已注册
    check_mobile = this.checkMobile(username.val());
  },
  checkMobile: function(mobile){
    var _selef = this,
            result = {};
    if(!mobile){
      result.status = 0;
      result.info = '手机号不能为空';
    }else if(!core.regx.mobile.test(mobile)){
      result.status = -1;
      result.info = '手机号格式不正确';
    }else{
      $.ajax({
        type: 'POST',
        async: false,
        url: Core.U('User/checkUsername'),
        data: 'param=' + mobile,
        dataType: 'json',
        success: function(data){
          if(data.status == 'y'){
            result.status = 1;
            result.info = data.info;
          }else{
            result.status = 2;
            result.info = data.info;
          }
        }
      });
    }
    return result;
  },
  quicklogin: function(){
    // 快速登录随后实现 :TODO
    var url = Core.U('User/quicklogin');
    UI.load(url, {
      'title': '快速登录',
      'modalClose': false,
    });
  },
  
  /*获取短信验证码*/
  getRandCode: function(reqtype){
    var button = $('.Z_mobile_randcode_button');
    var mobile = $('.Z_mobile');
    var check_time = function(){
      var now_time = new Date().getTime(),
          s_mobile_no = sessionStorage.getItem('mobile_no'),
          s_mobile_endtime = sessionStorage.getItem('mobile_endtime'),
          time;
      if(s_mobile_no === mobile.val() && s_mobile_endtime > now_time){
        time = parseInt((s_mobile_endtime - now_time)/1000);
        button.removeClass('btn-primary').attr('disabled', true).html('<i>'+ time +'</i> 秒后重试');
        window.setTimeout(check_time, 1000);
        return false;
      }else{
        button.addClass('btn-primary').removeAttr('disabled').html('获取验证码');
        return true;
      }
    };
    mobile.on('change', check_time);
    button.on('tap', function(){
      var mobile_no = mobile.val(),
          is_mobile_number = function(value){
            var validateReg = /^[1][34578]\d{9}$/;
            return validateReg.test(value);
          };
      if(!is_mobile_number(mobile_no)){
        UI.error('请填写正确的手机号码');
        mobile.focus();
        return false;
      }
      var res = check_time();
      $('.Z_randcode').focus();
      if(res){
        button.removeClass('btn-primary').attr('disabled', true).html('正在请求.');
        var formhash = $('meta[property="formhash"]').attr('content');
        $.ajax({
          type: 'POST',
          url: Core.U('/User/getRandCode', 'type='+reqtype),
          data: {mobile: mobile_no, formhash: formhash},
          dataType: 'json',
          success: function(res){
            if(res.status === 1){
              UI.success(res.info);
              sessionStorage.setItem('mobile_no', mobile_no);
              sessionStorage.setItem('mobile_endtime', res.endtime * 1000);
              check_time();
            }else{
              UI.error(res.info);
              check_time();
            }
          }
        });
      }
    });
  },
//  send_mobilecode: function(send_type){
//    var _self = this;
//    var check_old = function(){
//      if($('.Z_mobile').data('old') == $('.Z_mobile').val()){
//        $('.Z_mobile_button').html('请输入新手机号').data('showonly', 1).prop('disabled', true);
//      }else{
//        $('.Z_mobile_button').html('发送验证码').data('showonly', 0).prop('disabled', false);
//      }
//    };
//    if($("input[name='oauth_type']").size() == 0 && send_type === 'register'){
//      $('.Z_mobile').on('blur', function(){
//        var mobile = $(this).val();
//        var check = _self.checkMobile(mobile);
//        $('.Z_mobile').data('check_status', check.status);
//        //已注册
//        if(check.status == 2){
//          UI.confirm('该手机号已经注册，是否去登录？', function(){
//            core.redirect($('.Z_tologin').attr('href'));
//          });
//        }else if(check.status !== 1){
//          check.info ? UI.error(check.info) : '';
//        }
//      });
//    }else if(send_type === 'forget'){
//      $('.Z_mobile').data('check_status', 1);
//    }else if(send_type === 'mobile_bind'){
//      $(this).data('bind_check', 0);
//      $('.Z_mobile').on('blur', function(){
//        $(this).data('bind_check', 0);
//        $.post(Core.U('Member/checkMobileBind'), {'param': $(this).val()}, function(json){
//          if(json.status === 'n'){
//            UI.error(json.info);
//            $('.Z_mobile').data('bind_check', 0);
//            $('.Z_mobile_button').html('请输入新手机号').data('showonly', 1).prop('disabled', true);
//          }else{
//            $('.Z_mobile').data('bind_check', 1);
//            $('.Z_mobile_button').html('发送验证码').data('showonly', 0).prop('disabled', false);
//          }
//        }, 'json');
//      });
//    }
    //获取短信验证码
//    $('.Z_mobile_button').tap(function(){
//      var mobile = $('.Z_mobile').val();
//      $(this).prop('disabled', true);
//      if($('.Z_bind-form').size() > 0 && $('.Z_mobile').data('bind_check') == 0){
//        return false;
//      }
//      if($(this).data('showonly') === 1){
//        return;
//      }
//      if($('.Z_mobile').data('check_status') !== 1){
//        return;
//      }
//      if($.trim(mobile) == ''){
//        UI.error('请输入您的手机号');
//        return;
//      }
//      if(!core.regx.mobile.test(mobile)){
//        UI.error('手机号格式不正确');
//        return;
//      }
//      var time_;
//      $(this).html('验证码发送中…').data('showonly', 1).prop('disabled', true);
//      $.post($(this).data('geturl'), {mobile: mobile, send_type: send_type}, function(json){
//        clearInterval(time_);
//        if(json.code === 200){
//          if(json.send_new === 1){
//            UI.success(json.msg);
//          }
//          var lazytime = json.over_sec;
//          // 重新发送倒计时
//          window.clearInterval(time_);
//          $('.Z_mobile_button').data('showonly', 1).prop('disabled', true);
//          $('.Z_mobile').prop('readonly', true);
//          $('.Z_mobile').next('.Z_input_clear').remove();
//          time_ = window.setInterval(function(){
//            if(lazytime > 0){
//              msg = lazytime + ' 秒后重新发送';
//              $('.Z_mobile_button').html(msg);
//              lazytime--;
//            }else{
//              window.clearInterval(time_);
//              $('.Z_mobile').prop('readonly', false);
//              $('.Z_mobile_button').html('重发验证码').data('showonly', 0).prop('disabled', false);
//            }
//          }, 1000);
//          $('.Z_mobile_randcode').focus();
//        }else{
//          UI.error(json.msg);
//          $('.Z_mobile').focus();
//          $('.Z_mobile_button').html('短信验证码').data('showonly', 0).prop('disabled', false);
//        }
//      }, 'json');
//    });
//  },
  //找回密码初始化
  forget: function(){
    // 表单验证
    $('form').submit(function(){
      var self = $(this);
      $.post(self.attr('action'), self.serialize(), success, 'json');
      return false;

      function success(data){
        if(data.status){
          if(self.data('showwaitmsg') === 1){
            UI.success(data.info);
            core.redirect(data.url);
          }else{
            window.location.href = data.url;
          }
        }else{
          UI.error(data.info);
        }
      }
    });
  }
}

/**********微信分享模块**********/
var wechat = {
  title: '', // 分享标题
  desc: '', // 分享描述
  link: '', // 分享链接
  imgUrl: '', // 分享图标
  type: '', // 分享类型,music、video或link，不填默认为link
  init: function(config){
    var _self = this;
    if(!config){
      return false;
    }
    // 初始化分享内容
    var sharecontent = $('meta[name="share-content"]');
    _self.title = sharecontent.data('title');
    _self.desc = sharecontent.data('desc');
    _self.link = sharecontent.data('link');
    _self.imgUrl = sharecontent.data('img_url');
    _self.type = sharecontent.data('type');
    wx.config(config);
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
  }
}

/**********众筹模块**********/
var crowdfunding = {
  init: function(){
    var msg = $("input[name='msg']");
    msg.next('input').on('tap', function(){
      if(!msg.val()){
        var txt = msg.attr('placeholder');
        msg.val(txt);
        msg.parentsUntil('form').submit();
      }
    });
  },
  //百分比进度条样式显示,percent:百分号前面的数字，id为众筹id，oid为订单id
  progress: function(percent, id, oid){
    // percent = '100%';
    //设置进度条百分比
    $('.Z_progress').addClass('active').find('.Z_percent').css('width', percent);
    if(percent == '100%'){
      $('.Z_progress').addClass('active-full');
    }
  },
  /*分享众筹*/
  share: function(){
    UI.shareGuide.show('share');
  },
  /*众筹提交支付*/
  pay: function(){
    var username = $('.Z_crowdfunding_username'),
            payment_type = $('#Z_payment_type');
    if(Core.is_weixin() && (payment_type.val().indexOf('alipay') >= 0)){
      UI.shareGuide.show('alipaywap');
      return false;
    }
    var doPay = function(){
      $('#Z_crowdfunding_pay').submit();
    }
    if(username.val() == ''){
      UI.confirm('悄悄付款不留名字？', doPay);
      return false;
    }
    $('#Z_crowdfunding_pay').submit();
  },
  //取消众筹
  remove: function(id){
    var doRemove = function(){
      $.post(Core.U('Crowdfunding/remove'), 'id=' + id, function(res){
        if(res.status == 1){
          UI.success(res.msg);
          core.redirect(Core.U('Index/index'));
        }else{
          UI.error(res.msg);
        }
      }, 'json');
    }
    UI.confirm('您确定取消众筹？', doRemove);
  },
  //增减支付金额
  update: function(flag, sum){
    var money = $(".Z_fund_money input[name='pay_money']"),
            num = parseFloat(money.val());
    if(flag == 'edit'){
      num = num.replace(/[^0-9\.]/gi, "");
    }else{
      num += (flag == 'minus' ? -1 : 1);
    }
    num = parseFloat(num) ? parseFloat(num) : 0;
    num = Math.min(num, sum);
    num = Math.max(num, 0);
    money.val(num);
  }
}

/**********红包模块**********/
var redpacket = {
  //包红包
  pay: function(){
    var quantity = $('#Z_redpacket_quantity'),
            amount = $('#Z_redpacket_amount'),
            msg = $('#Z_redpacket_msg');
    if(quantity.val() < 0){
      quantity.focus();
      return false;
    }
    if(amount.val() < 0){
      amount.focus();
      return false;
    }
    if(!msg.val()){
      msg.val(msg.attr('placeholder'));
    }
    $('form.Z_redpacket_form').submit();
  },
  //红包金额改变时，改变下面对应的显示金额
  changeAmount: function(){
    var amount = $('#Z_redpacket_amount'),
            quantity = $('#Z_redpacket_quantity'),
            type = $('#Z_redpacket_type')
    total = '0.00',
            max = 0;

    if(type.val() == 'single'){
      var total_quantity = parseInt(quantity.val());
      total = parseFloat(total_quantity * amount.val()).toFixed(2);
      max = amount.val();
    }else{
      total = parseFloat(amount.val()).toFixed(2);
      max = amount.val();
    }
    if(total > 0){
      $('#Z_redpacket_max').text(max);
      $('#Z_show_amount').text(total);
    }

    $('#Z_redpacket_amount, #Z_redpacket_quantity').on('keyup', function(){
      (amount.val() > 0 && quantity.val() > 0) ? $('#Z_redpacket_pay').removeClass('disabled') : $('#Z_redpacket_pay').addClass('disabled');
    });
  },
  /*分享红包*/
  share: function(){
    UI.shareGuide.show();
  },
  /*打开红包*/
  open: function(uid, out, amount){
    if(!uid){
      UI.message('请您先登录或注册后打开红包', 2);
      if(Core.is_weixin()){
        core.redirect(Core.U('User/wechatlogin'));
      }
    }else{
      out != 0 ? UI.message('红包金额' + amount + '元已转入你的个人余额帐户', 2) : '';
    }
  },
  /*提交答谢*/
  thanks: function(){
    msg = $("form.Z_redpacket_thanks input[name='msg']");
    if(!msg.val()){
      msg.val(msg.attr('placeholder'));
    }
    $('.Z_redpacket_thanks').submit();
  },
};

/* 从微信选择收货地址 */
var wechat_address = {
  config: '',
  saveData: function(address){
    var _self = this;
    if(address.err_msg !== 'edit_address:ok'){
      UI.error('选择失败，请重新选择');
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
    post_url = Core.U("Receiver/update");
    $.post(post_url, postdata, function(json){
      if(json.status === 1){
        if(_self.config.tourl){
          UI.success('地址库更新成功！');
          url = _self.config.tourl;
        }else{
          UI.success('正在返回订单..');
          url = Core.U('Receiver/detail', 'id=' + json.id);
        }
        window.location.href = url;
      }else{
        UI.error('请重新选择地址');
      }
    }, 'json');
  },
  select: function(url, by_api){
    var _self = this;
    UI.success('正在请求...');
    by_api = (typeof by_api === 'undefined') ? 0 : by_api;
    if(parseInt(by_api) === 0){
      src = url;
      if(Core.selectConfig.length > 10){
        var onBridgeReady = function(){
          config = JSON.parse(Core.selectConfig);
          _self.config = config;
          WeixinJSBridge.invoke('editAddress', config, function(res){
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
      src = auth_url + Core.U("Api/wechatAddress");
      src += (src.indexOf('?') > -1 ? '&' : '?') + 'call_url=' + encodeURIComponent(location.href);
    }
    location.href = src;
  }
};

/*全局初始化*/
$(function(){
  //全局初始化
  core.init();
  //引入fastclick，解决click延迟问题
  if(Core.is_weixin()){
    window.addEventListener('load', function(){
      FastClick.attach(document.body);
    }, false);
  }
});
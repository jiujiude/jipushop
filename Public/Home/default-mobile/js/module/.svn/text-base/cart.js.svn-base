/**
 *购物车模块：商品加入购物车、修改数量、删除购物车内商品、清空购物车
 */
define('module/cart', function(require, exports, module){
  'use strict';

  var common = require('common');

  var cart = {
    init: function(second_pieces_item){
      var _self = this;
      $('#Z_cart_clear').on('tap', function(){
        _self.clear();
      });
      //商品选择
      _self.itemCheck();

      //更改购物车商品数量 + -
      $('.Z_cart_update').on('tap', function(){
        if($(this).hasClass('disabled')){return false;}
        var option = $(this).data('option'),
            item_code = $(this).attr('data-item_code'), //之所以不用.data()属性 是要获取到非16进制的真实字符串值
            price = $(this).data('price'),
            stock = $(this).data('stock'),
            quantity = $('#Z_item_num_' + item_code).val(),
            quantity_to = '';

        quantity_to = (option == 'plus') ? parseInt(quantity) + 1 : parseInt(quantity) - 1;
        _self.update(item_code, quantity_to, price, stock, second_pieces_item);
      });
      
      //结算
      $('#Z_cart_checkout').on('tap', function(){
        var item_count = $('.cart-group').size();
        if(item_count === 0){
          $.ui.error('购物车为空，无法结算！');
          return ;
        }
        if($(this).hasClass('disabled')){
          $.ui.error('请选择需要结算的商品！');
          return ;
        }
        var item_checked = $('.Z_cart_checkitem.active'),
            item_ids = [];
        if(item_checked.length > 0){
          $.each(item_checked, function(i, item){
            item_ids.push($(item).data('id'));
          });
        }
        common.redirect($.U('Order/index') + '?item_ids=' + item_ids, 0);
      });
      
      //移出购物车
      $('.Z_cart_remove').on('tap', function(){
        var item_code = $(this).attr('data-item_code');
        _self.remove(item_code);
      });
      
      //第二个折扣初始化
      _self.secondPiecesPriceInit(second_pieces_item);
      _self.setTotal();
    },
    
    /*第二件折扣初始化*/
    secondPiecesPriceInit: function(second_pieces_item){
      if('undefined' === typeof second_pieces_item){
        return ;
      }
      $.each(second_pieces_item, function(i, val){
        var obj = $('.Z_cart_' + val.item_id),
            code = obj.attr('data-code'),
            num = $('#Z_item_num_' + code).val(),
            second_price,
            price = parseFloat($('.Z_cart_' + val.item_id + ' .Z_cart_change').data('price'));
        if(num >= 2){
          second_price = parseFloat(price * val.discount);
          
          $('#Z_item_price_' + code + ' > .Z_item_price').text((second_price + price).toFixed(2));
          $('#Z_second_pieces_' + code).text(val.name).removeClass('hide');
        }
      });
    },
    
    /*购物车商品选择*/
    itemCheck: function(){
      var _self = this,
          item_list = $('.Z_cart_checkitem'),
          item_len = $('.Z_cart_checkitem').length,
          supplier_list = $('.Z_cart_supplier');
  
      var check_end = function (){
        var item_checked_len = $('.Z_cart_checkitem.active').length,
                checkall = $('#Z_cart_checkall'),
                checkout = $('#Z_cart_checkout');
        //全选按钮样式
        if(item_checked_len === item_len){
          checkall.addClass('active');
          $('.Z_cart_supplier').addClass('active');
        }else{
          checkall.removeClass('active');
        }
        //去结账按钮状态
        if(item_checked_len === 0){
          checkout.addClass('disabled');
          $('.Z_cart_supplier').removeClass('active');
        }else{
          checkout.removeClass('disabled');
        }

        _self.setTotal();
      };
  
  
      item_list.on('tap', function(){
        var supplier_id = $(this).data('supplier_id'),
                supplier = $('.Z_cart_supplier[data-supplier_id="' + supplier_id + '"]');
        $(this).blur();
        $(this).toggleClass('active');
        var supp_len = $('.Z_cart_checkitem[data-supplier_id="' + supplier_id + '"]').length,
                supp_active = $('.Z_cart_checkitem.active[data-supplier_id="' + supplier_id + '"]').length;
        (supp_len === supp_active) ? supplier.addClass('active') : supplier.removeClass('active');
        check_end(); //组样式
      });
      
      supplier_list.on('tap', function (){
        var supplier_id = $(this).data('supplier_id'),
                supplier_item = $('.Z_cart_checkitem[data-supplier_id="' + supplier_id + '"]');
        $(this).toggleClass('active');
        $(this).hasClass('active') ? supplier_item.addClass('active') : supplier_item.removeClass('active');
        check_end(); //组样式
      });
      $('#Z_cart_checkall').on('click', function (){
        $(this).toggleClass('active');
        if($(this).hasClass('active')){
          item_list.addClass('active');
        }else{
          item_list.removeClass('active');
        }
        check_end(); //组样式
      });
    },
    /*计算购物车选择结算的商品总价*/
    setTotal: function(){
      var total_amount = 0,
          total_num = 0;
      $('.Z_cart_change').each(function(){
        var price = $(this).data('price'),
            item_code = $(this).attr('data-item_code'),
            num = $(this).val() *1,
            bool = $(this).closest('.cart-group').find('.Z_cart_checkitem').hasClass('active');
        if(bool){
          //total_amount += price*num;
          var _p = $('#Z_item_price_' + item_code + ' > .Z_item_price').text();
          total_amount += _p * 1;
          total_num += num;
        }
      });
     
      $('#Z_cart_total').html(total_amount.toFixed(2));
      //赠品
      this.getSendItem();
    },
    /*获取赠品信息*/
    getSendItem: function(buynow){
      if('undefined' === typeof buynow){
        buynow = 0;
      }
      $.ajax({
        type: 'GET',
        url: $.U('Cart/getSendItem', 'buynow=' + buynow),
        data: {},
        dataType: 'json',
        success: function (res){
          if(res.status === 1){
            $('.send-items').html('');
            var item, supp_id, html;
            for(var i in res.send){
              item = res['send'][i];
              supp_id = item['item_info']['supplier_id'];
              if($('.Z_send_supp_'+supp_id).html() == ''){
                $('.Z_send_supp_'+supp_id).html('<i>赠品</i>');
              }
              html = '<span><a href="'+ $.U('Item/detail', 'id=' + item['id']) +'" target="_blank">';
              html +='<img src="'+ item['item_info']['thumb'] +'" title="'+ item['item_info']['name'] +'"></a>';
              html+= '<s> × '+ item['num']+ '</s></span>';
              $(html).appendTo('.Z_send_supp_'+supp_id);
            }
          }
        }
      });
    },
    /*购物车为空时信息提示*/
    setEmptyTips: function(){
      var buyUrl = $.U('Index/index');
      var emptyTips = '<div class="content-padded cart-empty"><a href="' + buyUrl + '" class="img"><i class="icon icon-cart"></i></a><p class="tips">您的购物车内没有任何商品</p><p><a class="btn btn-block btn-positive" href="' + buyUrl + '"><span class="ui-btn-text">马上去选购</span></a></p></div>';
      $('.content').html(emptyTips);
    },
    /*购物车动画*/
    animate: function(image_src, image_top, image_left){
      var cartbox = '<img src="' + image_src + '" id="Z_item_cartbox" class="cart-box-img" style="top: '+ image_top / 2 +'px; left: 50%" width="120" height="120" />';
      var cart_top = $('#Z_global_cart_quantity').offset().top; //取得加购物车的高度
      var cart_left = $('#Z_global_cart_quantity').offset().left; //取得加购物车的宽度
      $('body').append(cartbox);
      $('body').children('#Z_item_cartbox').animate({
        width: '24px',
        height: '24px',
        top: cart_top,
        left: parseInt(cart_left - 20),
        opacity: .2
      }, 800, 'swing', function(){
        $(this).remove();
      });
    },
    /*商品加入购物车*/
    add: function(is_buynow){
      var _self = this,
        item_code = $('#Z_item_code').val(),
        item_id = $('#Z_item_id').val(),
        item_image = $('#Z_item_image').val(),
        quantity = $('#Z_item_quantity').val(),
        price = $('#Z_item_price').val(),
        spec = $('#Z_item_spec').val(),
        sdp_code = $('#Z_sdp_code').val(),
        url = $.U('Cart/add'),
        data = {item_code: item_code, item_id: item_id, quantity: quantity, buynow: is_buynow, price:price ,sdp_code:sdp_code};
        
        //分离属性
        var specArray = spec.split('&');
        var i = 0, newSpecArray = [];
        for(i; i < specArray.length; i++){
          spec = specArray[i].split('=');
          newSpecArray[spec[0]] = spec[1];
        }
        //追加属性数组到data数组
        $.extend(data, newSpecArray);
          
      //加购物车
      $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        success: function(res){
          if(res.status == 1){
            if(is_buynow){
              window.location.href = $.U('Order/index', 'buynow=1');
            }else{
              $('#Z_global_cart_quantity').html(res.total.total_num);
              var image_top = $('#Z_add2cart').offset().top; //开始位置的高度
              var image_left = $('#Z_add2cart').offset().left; //开始位置的宽度
              var image_src = C.SITE_URL + item_image;
              _self.animate(image_src);
              $.ui.success('商品成功加入购物车');
              _self.getCartCount();
            }
          }else if(res.status == 0){
            $.ui.error(res.info);
           // window.location.href = res.url;
          }
        }
      });
    },
    
    /*修改购物车商品数量*/
    update: function(item_code, quantity, price, stock, second_pieces_item){
      var sum_price = '0.00',
          _self = this,
          item_minus = $('#Z_item_box_' + item_code).find('.minus'),
          item_plus = $('#Z_item_box_' + item_code).find('.plus'),
          item_id = $('#cart-group-' + item_code).data('id'),
          is_second_pieces_discount,
          second_pieces_item_ids = [],
          second_price;
          
      if('undefined' !== typeof second_pieces_item){
        //是否第二件折扣商品
        $.each(second_pieces_item, function(i, val){
          second_pieces_item_ids.push(val.item_id);
        });

        is_second_pieces_discount = $.inArray(item_id.toString(), second_pieces_item_ids) > -1 ? true : false ;
        if(is_second_pieces_discount){
          stock = 2;
        }
      }
      
      item_minus.removeClass('disabled');
      item_plus.removeClass('disabled');
      if(quantity <= 1){
        item_minus.addClass('disabled')
      }
      if(quantity >= stock){
        item_plus.addClass('disabled')
      }
      if(quantity < 1){
        quantity = 1;
      }
      if(quantity >= stock){
        quantity = stock;
      }
      
//      sum_price = parseFloat(quantity) * price;
//      $('#Z_item_num_' + item_code).val(quantity);
//      $('#Z_item_price_' + item_code).html('&yen; '+ sum_price.toFixed(2));
      
      //第二件折扣
      if(quantity >= 2 && is_second_pieces_discount){
        second_price = parseFloat(price * second_pieces_item[item_id]['discount']);
        sum_price = parseFloat(second_price) + parseFloat(price);
        $('#Z_item_num_' + item_code).val(quantity);
        $('#Z_item_price_' + item_code + ' > .Z_item_price').text(sum_price.toFixed(2));
        //$('#J_item_price_' + item_code).html('<em class="yen">&yen;</em> ' + sum_price.toFixed(2));
        $('#Z_second_pieces_' + item_code).text(second_pieces_item[item_id]['name']).removeClass('hide');
      }else{
        sum_price = parseFloat(quantity) * price;
        $('#Z_item_num_' + item_code).val(quantity);
        $('#Z_item_price_' + item_code + ' > .Z_item_price').text(sum_price.toFixed(2));
        $('#Z_second_pieces_' + item_code).addClass('hide');
      }

      //服务端更新商品数量，返回购物车商品总价格
      $.ajax({
        type: 'POST',
        url: $.U('Cart/update'),
        data: {item_code: item_code, quantity: quantity},
        dataType: 'json',
        success: function(res){
          if(res.status == 1){
            _self.setTotal();
          }
        }
      });
    },
    /*删除购物车内商品*/
    remove: function(item_code){
      var _self = this;
      $.ui.confirm('确定从购物车中删除吗？', function(){
        $.post($.U('Cart/remove'), 'item_code=' + item_code, function(res){
          if(res.status == 1){
            /*购物车为空时更新提示信息*/
            if(res.total.total_quantity == 0){
              cart.setEmptyTips();
            }
            var supp = $('#cart-group-' + item_code).closest('.supplier-box');
            if(supp.find('.cart-group').size() === 1){
              supp.remove();
            }else{
              $('#cart-group-' + item_code).remove();
            }
            _self.setTotal();
          }
        }, 'json');
      });
    },
    /*清空购物车*/
    clear: function(){
      $.ui.confirm('确定清空购物车？', function(){
        $.post($.U('Cart/clear'), '', function(res){
          if(res.status == 1){
            $('#Z_cart_total').html('0.00');
            /*购物车为空时提示信息*/
            cart.setEmptyTips();
          }
        }, 'json');
      });
    },
    /* 获取购物车产品数量:解决返回按钮导致的页面显示数量不正确 */
    getCartCount: function(){
      $.ajax({
        type: 'POST',
        url: $.U('Api/getCartCount'),
        dataType: 'json',
        success: function(res){
          var q_obj = $('#Z_global_cart_quantity').html(res.cart_count);
          if(res.cart_count > 0){
            q_obj.removeClass('hide');
          }else{
            q_obj.addClass('hide');
          }
          //站内消息
          var m_obj = $('#Z_global_message');
          if(res.message_count > 0){
            m_obj.removeClass('hide');
          }else{
            m_obj.addClass('hide');
          }
        }
      });
    }
  };

//  cart.init();

  module.exports = cart;
});
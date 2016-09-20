/**
 *购物车模块：商品加入购物车、修改数量、删除购物车内商品、清空购物车
 */
define('module/cart', function (require, exports, module){
  'use strict';

  var common = require('common');

  var cart = {
    init: function (second_pieces_item){
      var _self = this;
      _self.itemChcek();

      $('#J_cart_clear').on('click', function (){
        _self.clear();
      });

      //更改购物车商品数量
      $('.J_cart_update').on('click', function (){
        var option = $(this).data('option'),
                item_code = $(this).data('item_code'),
                price = $(this).data('price'),
                stock = $(this).data('stock'),
                quantity = $('#J_item_num_' + item_code).val(),
                quantity_to = '';
        $(this).blur();
        if($(this).hasClass('disabled')){
          return;
        }
        quantity_to = (option == 'plus') ? parseInt(quantity) + 1 : parseInt(quantity) - 1;
        _self.update(item_code, quantity_to, price, stock, second_pieces_item);
      });
      $('.J_cart_change').on('change keyup', function (){
        var quantity = $(this).val(),
                item_code = $(this).data('item_code'),
                price = $(this).data('price'),
                stock = $(this).data('stock');
        //移除非数字字符
        var quantity_r = quantity.replace(/[^0-9]/ig, "");
        if(quantity_r !== quantity){
          quantity = quantity_r;
        }
        //为空或小于1 则更正为1
        if(quantity_r === '' || quantity < 1){
          quantity = 1;
        }
        //如果大于库存 则更正为库存最大值
        if(quantity > stock){
          quantity = stock;
        }

        $(this).val(quantity);
        _self.update(item_code, quantity, price, stock, second_pieces_item);
      });

      //删除购物车商品
      $('.J_cart_remove').on('click', function (){
        var item_code = $(this).data('item_code');
        _self.remove(item_code);
      });

      $('#J_cart_checkout').on('click', function (){
        var item_checked = $('.J_cart_checkitem.active'),
                item_ids = [];
        if(item_checked.length > 0){
          $.each(item_checked, function (i, item){
            item_ids.push($(item).data('id'));
          });
          common.redirect($.U('Order/index') + '?item_ids=' + item_ids, 0);
        }

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
        var obj = $('.J_cart_' + val.item_id),
            code = obj.data('code'),
            num = $('#J_item_num_' + code).val(),
            second_price,
            price = parseFloat($('.J_cart_' + val.item_id + ' .J_cart_change').data('price'));
        if(num >= 2){
          second_price = parseFloat(price * val.discount);
          $('#J_item_price_' + code + ' > .J_item_price').text((second_price + price).toFixed(2));
          $('#J_second_pieces_' + code).text(val.name).removeClass('hide');
        }
      });
    },
    
    /*购物车商品选择*/
    itemChcek: function (){
      var _self = this,
              item_list = $('.J_cart_checkitem'),
              item_len = item_list.length,
              supplier_list = $('.J_cart_supplier');

      var check_end = function (){
        var item_checked_len = $('.J_cart_checkitem.active').length,
                checkall = $('#J_cart_checkall'),
                checkout = $('#J_cart_checkout');
        //全选按钮样式
        if(item_checked_len === item_len){
          checkall.addClass('active');
          $('.J_cart_supplier').addClass('active');
        }else{
          checkall.removeClass('active');
        }
        //去结账按钮状态
        if(item_checked_len === 0){
          checkout.addClass('disabled');
          $('.J_cart_supplier').removeClass('active');
        }else{
          checkout.removeClass('disabled');
        }

        _self.setTotal();
      };

      item_list.on('click', function (){
        var supplier_id = $(this).data('supplier_id'),
                supplier = $('.J_cart_supplier[data-supplier_id="' + supplier_id + '"]');
        $(this).blur();
        $(this).toggleClass('active');
        var supp_len = $('.J_cart_checkitem[data-supplier_id="' + supplier_id + '"]').length,
                supp_active = $('.J_cart_checkitem.active[data-supplier_id="' + supplier_id + '"]').length;
        (supp_len === supp_active) ? supplier.addClass('active') : supplier.removeClass('active');
        check_end(); //组样式
      });

      supplier_list.on('click', function (){
        var supplier_id = $(this).data('supplier_id'),
                supplier_item = $('.J_cart_checkitem[data-supplier_id="' + supplier_id + '"]');
        $(this).toggleClass('active');
        $(this).hasClass('active') ? supplier_item.addClass('active') : supplier_item.removeClass('active');
        check_end(); //组样式
      });
      $('#J_cart_checkall').on('click', function (){
        $(this).blur();
        $(this).toggleClass('active');
        if($(this).hasClass('active')){
          item_list.addClass('active');
        }else{
          item_list.removeClass('active');
        }
        check_end(); //组样式
      });

    },
    /*购物车动画*/
    animate: function (image_src, image_top, image_left){
      var cartbox = '<img src="' + image_src + '" id="J_item_cartbox" class="cart-box-img" style="top:' + image_top + 'px; left:' + image_left + 'px" width="100" height="100" />';
      var cart_top = $('#J_cart_quantity').offset().top; //取得购物车的高度
      var cart_left = $('#J_cart_quantity').offset().left; //取得购物车的宽度 
      $('body').append(cartbox);
      $('body').children('#J_item_cartbox').animate({
        width: '24px',
        height: '24px',
        top: cart_top,
        left: cart_left,
        opacity: .2
      }, 800, 'swing', function (){
        $(this).remove();
        $('#J_cart_plus').removeClass().addClass('fade-out').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (){
          $(this).removeClass();
        });
      });
    },
    /*添加购物车*/
    add: function (is_buynow){
      var _self = this,
              item_code = $('#J_item_code').val(),
              item_id = $('#J_item_id').val(),
              item_image = $('#J_item_image').val(),
              quantity = $('#J_item_quantity').val(),
              price = $('#J_item_price').val(),
              spec = $('#J_item_spec').val(),
              sdp = $('#J_sdp_secret').val(),
              url = $.U('Cart/add'),
              sdp_code = $('#J_sdp_code').val(),
              data = {item_code: item_code ,adp:sdp, item_id: item_id, quantity: quantity, buynow: is_buynow, price: price ,sdp_code:sdp_code};

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
        success: function (res){
          if(res.status == 1){
            if(is_buynow){
              window.location.href = $.U('Order/index', 'buynow=1');
            }else{
              //购物车添加成功提示动画&信息
              var image_src = C.SITE_URL + item_image;
              var image_top = $('.add2cart').offset().top; //开始位置的高度
              var image_left = $('.add2cart').offset().left; //开始位置的宽度
              _self.animate(image_src, image_top, image_left);

              $('#J_cart_quantity').html(res.total.total_num);
              $('#J_footer_cart').html(res.total.total_num);
              $('#J_footer_cart').show();
            }
          }else if(res.status == 0){
            $.ui.error(res.info);
            //window.location.href = res.url;
          }
        }
      });
    },
    /*购物车为空时信息提示*/
    setEmptyTips: function (){
      var buy_url = $.U('Item/search');
      var empty_html = '<div class="cart-empty">\
        <h2>你的购物车还是空的赶紧行动吧！</h2>\
        <a href="' + buy_url + '" class="btn btn-positive">马上去购物</a>\
      </div>';
      $('#J_cart_list').html(empty_html);
    },
    /*计算购物车选择结算的商品总价*/
    setTotal: function (){
      var total_amount = 0,
              total_num = 0;
             // free_delivery = parseFloat($('#J_free_delivery').data('freedelivery'));

      $('.J_cart_change').each(function (){
        var price = $(this).data('price'),
            item_code = $(this).data('item_code'),
            num = $(this).val(),
            bool = $(this).closest('dd').find('.J_cart_checkitem').hasClass('active');
        if(bool){
          //total_amount += price * num;
          total_amount += parseFloat($('#J_item_price_' + item_code + ' > .J_item_price').text());
          total_num += parseInt(num);
        }
      });

      $('#J_cart_total').html(total_amount.toFixed(2));
      $('.J_item_count').text(total_num);
      //$('#J_cart_quantity').html(total_num);
      //免运费设置
//      var gs = (free_delivery - total_amount).toFixed(2);
//      if(gs > 0){
//        $('#J_free_delivery').html('再购物 <em class="text-danger J_free_delivery_amount">' + gs + '元</em> 就可免运费哦 (仅限陕西地区)');
//      }else{
//        $('#J_free_delivery').html('免运费(仅限陕西地区)');
//      }
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
              if($('.J_send_supp_'+supp_id).html() == ''){
                $('.J_send_supp_'+supp_id).html('<i>赠品</i>');
              }
              html = '<span><a href="'+ $.U('Item/detail', 'id=' + item['id']) +'" target="_blank">';
              html +='<img src="'+ item['item_info']['thumb'] +'" title="'+ item['item_info']['name'] +'"></a>';
              html+= ' × '+ item['num']+ '</span>';
              $(html).appendTo('.J_send_supp_'+supp_id);
            }
          }
        }
      });
    },
    /*修改购物车商品数量*/
    update: function (item_code, quantity, price, stock, second_pieces_item){
      var sum_price = '0.00',
          _self = this,
          item_minus = $('#J_item_box_' + item_code).find('.minus'),
          item_plus = $('#J_item_box_' + item_code).find('.plus'),
          item_id = $('#J_item_box_' + item_code).closest('dd').data('id'),
          is_second_pieces_discount,
          second_pieces_item_ids = [],
          second_price;
        
      //是否第二件折扣商品
      if(typeof second_pieces_item !== 'undefined'){
        $.each(second_pieces_item, function(i, val){
          second_pieces_item_ids.push(val.item_id);
        });

        is_second_pieces_discount = $.inArray(item_id.toString(), second_pieces_item_ids) > -1 ? true : false ;
        if(is_second_pieces_discount){
          stock = 2;
        }  
      }

      (quantity <= 1) ? item_minus.addClass('disabled') : item_minus.removeClass('disabled');
      (quantity >= stock) ? item_plus.addClass('disabled') : item_plus.removeClass('disabled');
      if(quantity < 1){
        quantity = 1;
      }
      if(quantity >= stock){
        quantity = stock;
        var gt_obj = $('#J_item_box_' + item_code).closest('dd').find('.J_gt_freenum_tip');
        if(gt_obj.size() === 1){
          gt_obj.slideUp();
        }
      }

      //第二件折扣
      if(quantity >= 2 && is_second_pieces_discount){
        second_price = parseFloat(price * second_pieces_item[item_id]['discount']);
        sum_price = parseFloat(second_price) + parseFloat(price);
        $('#J_item_num_' + item_code).val(quantity);
        $('#J_item_price_' + item_code + ' > .J_item_price').text(sum_price.toFixed(2));
        //$('#J_item_price_' + item_code).html('<em class="yen">&yen;</em> ' + sum_price.toFixed(2));
        $('#J_second_pieces_' + item_code).text(second_pieces_item[item_id]['name']).removeClass('hide');
      }else{
        sum_price = parseFloat(quantity) * price;
        $('#J_item_num_' + item_code).val(quantity);
        $('#J_item_price_' + item_code + ' > .J_item_price').text(sum_price.toFixed(2));
        $('#J_second_pieces_' + item_code).addClass('hide');
      }
        
      //服务端更新商品数量，返回购物车商品总价格
      $.ajax({
        type: 'POST',
        url: $.U('Cart/update'),
        data: {item_code: item_code, quantity: quantity},
        dataType: 'json',
        success: function (res){
          if(res.status === 1){
            _self.setTotal();
          }
        }
      });
    },
    /*删除购物车内商品*/
    remove: function (item_code){
      var _self = this;
      var doRemove = function (){
        $.ajax({
          type: 'POST',
          url: $.U('Cart/remove'),
          data: {item_code: item_code},
          dataType: 'json',
          success: function (res){
            if(res.status == 1){
              //购物车为空时更新提示信息
              if(res.total.total_quantity == 0){
                cart.setEmptyTips();
              }
              var supp = $('#J_cart_' + item_code).closest('.supplier-box');
              if(supp.find('.item').size() === 1){
                supp.remove();
              }else{
                $('#J_cart_' + item_code).remove();
              }
              _self.setTotal();
              //更新导航栏购物车商品数量
              $('#J_cart_quantity').html(res.total.total_num);
              $('#J_footer_cart').html(res.total.total_num);
              $('#J_footer_cart').hide();
            }
          }
        });
      }
      $.ui.confirm('确定从购物车中删除该商品吗？', doRemove);
    },
    /*清空购物车*/
    clear: function (){
      var doClear = function (){
        $.ajax({
          type: 'POST',
          url: $.U('Cart/clear'),
          dataType: 'json',
          success: function (res){
            if(res.status == 1){
              $('#J_cart_quantity').html(0);
              $('#J_footer_cart').html(0);
              $('#J_footer_cart').hide();
              /*购物车为空时提示信息*/
              cart.setEmptyTips();
            }
          }
        });
      };
      $.ui.confirm('确定清空购物车？', doClear);
    },
  };

  //cart.init();

  module.exports = cart;
});
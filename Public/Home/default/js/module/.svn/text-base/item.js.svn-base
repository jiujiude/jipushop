/**
 *商品模块：商品规格选择，加入收藏，更改数量
 */
define('module/item', function(require, exports, module){

  'use strict';

  var user = require('user'),
      cart = require('cart'),
      common = require('common');
  require('jquery.superslide');

  var item = {
    sku_choose_size: 0,
    sku_selected_size: 0,
    spc_data_info: '',
    init: function(spc_data_info, seckill){
      var _self = this,
          fixed_navbar = $('#J_fixed_navbar');
  
      //事件绑定
      $('.J_item_add2cart').on('click', function(){
        //fixed_navbar情况下需要回到顶部
        if(fixed_navbar.hasClass('show')){
          $('#J_goto_top').trigger('click');
        }
        //判断是否已经选齐规格
        if(_self.checkSpecSelect() == 1){
          _self.removeSpecAttention();
          cart.add();
        }else{
          _self.addSpecAttention();
          return false;
        }
      });
      
      //秒杀
      _self.seckill(seckill);
      
      $('.J_item_buynow').on('click', function(){
        //判断是否已经选齐规格
        if(_self.checkSpecSelect() == 1){
          _self.removeSpecAttention();
          cart.add(1);
        }else{
          _self.addSpecAttention();
          return false;
        }
      });
      //加入收藏
      $('.J_add_fav').on('click', function(){
        $(this).blur();
        _self.addFav($(this).data('id'));
      });
      
      $('.J_quantity_act').on('click', function(){
        $(this).blur();
        var option = $(this).data('option');
        _self.quantity(option);
      });
      $('#J_item_quantity').on('keyup ', function(){
        _self.quantity();
      });
      
      //初始化
      _self.sku_choose_size = $('.J_sku_box').size();
      _self.spc_data_info = spc_data_info;

      //商品颜色、尺码选择
      $('.J_sku_box li.J_sale_prop').not('.out-of-stock').click(function(){
        //选中的规格项样式控制
        $(this).addClass('select').siblings('.J_sale_prop').removeClass('select');
        //根据当前页面用户所选规格的code组合获取对应价格和库存数量重新设置页面显示的价格和库存
        var data_code = $(this).attr('data-code'),
            spec_selected = $('.J_spec_item li.select'),
            spec_all_code = '',
            is_second = $('#J_item_stock').attr('data-stock'),
            spec_all_args = '';

        _self.sku_selected_size = spec_selected.size();

        //判断用户是否选齐全部规格
        if((_self.sku_choose_size > 0) && (_self.sku_selected_size == _self.sku_choose_size)){
          //删除规格选择提示样式
          _self.removeSpecAttention();
          var i = 0;
          $.each(spec_selected, function(k, v){
            if(i == 0){
              spec_all_code = $(v).attr('data-code');
              spec_all_args = $(v).attr('args');
            }else{
              spec_all_code = spec_all_code + '-' + $(v).attr('data-code');
              spec_all_args = spec_all_args + '&' + $(v).attr('args');
            }
            i++;
          });

          var price = 0;
          var quantity = 0;
          var spec_data = new Array();

          if(_self.spc_data_info){
            var spec_data_info = $.parseJSON(_self.spc_data_info);
            if(!$.isEmptyObject(spec_data_info)){
              $.each(spec_data_info, function(k, spec){
                spec_data[spec.spc_code] = new Array();
                spec_data[spec.spc_code]['price'] = spec.price;
                spec_data[spec.spc_code]['quantity'] = spec.quantity;
              });
            }

            //获取当前用户选择的规格组合对应的价格和库存数量
            if(spec_data[spec_all_code]){
              price = spec_data[spec_all_code]['price'];
              quantity = spec_data[spec_all_code]['quantity'];
              

              //设置页面显示的价格与库存数量
              $('.J_spec_price').html(price);
              $('.J_spec_quantity').html(quantity);
              $('#J_item_price').val(price);
            //第二件折扣限购2件
              if(is_second == 2){
                  quantity = 2;
              }
              $('#J_item_stock').val(quantity);
            }
          }
          
          //控制购买数量
          _self.quantity();
          //判断用户所选商品是否还有库存
          if($('#J_item_stock').val() <= 0){
            $('.buy .btn').addClass('disabled');
            $('.J_item_add2cart').last().addClass('disabled');
            $.ui.alert('抱歉，您选购的商品库存不足。', 2000);
            return false;
          }else{
            $('.buy .btn').removeClass('disabled');
            $('.J_item_add2cart').last().removeClass('disabled');
            //隐藏规格选择提示
            _self.removeSpecAttention();
          }
          
          $('#J_item_spec').val(spec_all_args);
          $('#J_item_code').val(spec_all_code);
        }
      });

      //关闭规格选择提醒
      $('#J_skubox_close').on('click', function(){
        _self.removeSpecAttention();
      });

      //初始化商品图片幻灯
      _self.initPicSlide();

      //显示or隐藏浮动购买按钮
      _self.toggleNavbar();
    },
    
    /*秒杀*/
    seckill : function(seckill){
      if(seckill){
        var now = Math.round(new Date().getTime()/1000);
        if(now > seckill.expire_time){
          //过期
          return;
        }else{
          if(now < seckill.start_time){
            //尚未开始
            $('.J_seckill_time').html('距秒杀开始<span class="text-danger day">00</span>天<span class="text-danger hour">00</span>小时<span class="text-danger minute">00</span>分钟<span class="text-danger second">00</span>秒');
            this.countDown(this.unix_to_datetime(seckill.start_time), ".J_seckill_time");
          }else{
            //进行中
            $('.J_seckill_time').html('距秒杀结束<span class="text-danger day">00</span>天<span class="text-danger hour">00</span>小时<span class="text-danger minute">00</span>分钟<span class="text-danger second">00</span>秒');
            this.countDown(this.unix_to_datetime(seckill.expire_time), ".J_seckill_time");
            $('.J_seckill_btn').removeClass('disabled').addClass('J_item_buynow');
          }
        }
      }
    },
    
    /*
     * 时间戳转字符串
     * return 2015-10-16 15:10:32
     */
    unix_to_datetime : function(unix) {
        var now = new Date(parseInt(unix) * 1000);
        return now.toISOString().substring(0, 10) + ' ' + now.toTimeString().substring(0, 8);
    },

    /*倒计时*/
    countDown : function(time, id){
      var day_elem = $(id).find('.day');
      var hour_elem = $(id).find('.hour');
      var minute_elem = $(id).find('.minute');
      var second_elem = $(id).find('.second');
      var end_time = new Date(time).getTime(),//月份是实际月份-1
      sys_second = (end_time - new Date().getTime())/1000;
      var timer = setInterval(function(){
        if (sys_second > 1) {
          sys_second -= 1;
          var day = Math.floor((sys_second / 3600) / 24);
          var hour = Math.floor((sys_second / 3600) % 24);
          var minute = Math.floor((sys_second / 60) % 60);
          var second = Math.floor(sys_second % 60);
          day_elem && $(day_elem).text(day);//计算天
          $(hour_elem).text(hour<10 ? "0" + hour : hour);//计算小时
          $(minute_elem).text(minute < 10 ? "0" + minute : minute);//计算分钟
          $(second_elem).text(second < 10 ? "0" + second : second);//计算秒杀
        } else { 
          clearInterval(timer);
          common.reload();
        }
      }, 1000);
    },
    
    /*初始化商品图片幻灯*/
    initPicSlide: function(){
      $('#J_item_slider').slide({
        mainCell: '.bd ul',
        prevCell: 'a.prev',
        nextCell: 'a.next'
      });
    },
    /*判断是否选齐规格*/
    checkSpecSelect: function(){
      var _self = this;
      return (_self.sku_selected_size < _self.sku_choose_size) ? 0 : 1;
    },
    /*添加规格选择提示*/
    addSpecAttention: function(){
      $('#J_skubox_lists').addClass('attention');
    },
    /*删除规格选择提示*/
    removeSpecAttention: function(){
      $('#J_skubox_lists').removeClass('attention');
    },
    /*显示or隐藏浮动购买按钮*/
    toggleNavbar: function(){
      var _body = document.body,
          tab_height = $('#J_item_navtabs').offset().top,
          fixed_navbar = $('#J_fixed_navbar');
      document.addEventListener('scroll', function(){
        if(_body.scrollTop > tab_height){
          fixed_navbar.addClass('show');
        }else{
          fixed_navbar.removeClass('show');
        }
      });
    },
    /*添加收藏*/
    addFav: function(id){
      if(C.UID <= 0){
        window.location.href = $.U('User/login');
        return false;
      }
      if($('#J_item_'+id).find('.icon').hasClass('icon-like-fill')){
        $.ui.error('已经收藏过了！');
        return false;
      }
      $.ajax({
        type: 'POST',
        url: $.U('/Fav/add'),
        data: {id: id},
        dataType: 'json',
        success: function(res){
          if(res.status == 1){
            $.ui.success(res.info);
            $('#J_item_' + id).html('<i class="icon icon-like-fill"></i> 已收藏');
          }else if(res.status === 0){
            $.ui.error(res.info);
          }
        }
      });
    },
    /*删除收藏*/
    removeFav: function(id){
      $.ui.confirm('确定从收藏夹移除吗？', function(){
        $.ajax({
          type: 'POST',
          url: $.U('/Fav/remove'),
          data: {id: id},
          dataType: 'json',
          success: function(res){
            if(res.status === 1){
              $.ui.success(res.info);
              $('#J_favitem_' + id).fadeOut(500, function(){
                $(this).remove();
                if($('.J_item').size() === 0){
                  $('.J_item_list').html('<p class="list-empty">您暂时没有收藏商品！</p>');
                }
              });
            }else if(res.status === 0){
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*更改数量*/
    quantity: function(op){
      var quantity = $('#J_item_quantity').val(),
          new_quantity = 1,
          stock = parseInt($('#J_item_stock').val()),
          quota = $('.J_quota_num').size() === 1 ? parseInt($('.J_quota_num').text()) : stock;
          stock = quota < stock? quota : stock;
          
      if(typeof op === 'undefined'){
        new_quantity = quantity;
      }else{
        new_quantity = (op === 'plus') ? (parseInt(quantity) + 1) : (parseInt(quantity) - 1);
      }
      $('.minus, .plus').removeClass('disabled');
      
      new_quantity =  parseInt(new_quantity);
      if(new_quantity < 1){
        new_quantity = 1;
      }
      if(new_quantity > stock){
        new_quantity = stock;
      }
      new_quantity = isNaN(new_quantity) ? 1 : new_quantity;
      //样式处理
      if(new_quantity <= 1){
        $('.minus').addClass('disabled');
      }
      if(new_quantity >= stock){
        $('.plus').addClass('disabled');
      }
      $('#J_item_quantity').val(new_quantity);
    }
  }

  module.exports = item;
});
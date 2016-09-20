var item = {
  init: function (){
    //自动设置市场价
    this.setMarketPrice();
    //获取运费模板
    //this.setDelivery(selected_id);
    //改变分销类型事件
    this.changeSdpType();
  },
  /*市场价自动设置*/
  setMarketPrice: function (){
    $('#price').keyup(function (){
      var mprice = ($(this).val()) * 1.5;
      $('#mprice').val(mprice.toFixed(2));
      $('#credit').val($(this).val());
    });
  },
  setDelivery: function (selected_id){
    var _self = this;
    $.ajax({
      type: 'GET',
      url: Core.U('Admin/DeliveryTpl/ajaxList'),
      dataType: 'json',
      success: function (data){
        if(data){
          var html = '';
          $.each(data, function (i, item){
            html += '<option value="' + item.id + '">' + item.name + '</option>';
          });
          //编辑
          if(selected_id){
            $('#J_item_delivery').append(html).find('option[value=' + selected_id + ']').attr('selected', true);
            _self.changeDelivery(selected_id);
          }else{
            $('#J_item_delivery').append(html)
          }
        }
      }
    });
    $('#J_item_delivery').on('change', function (){
      var delivery_id = $(this).val();
      _self.changeDelivery(delivery_id);
    });
  },
  /*更换运费模板*/
  changeDelivery: function (id){
    if(!id){
      return false;
    }else if(id > 0){
      $.ajax({
        type: 'POST',
        url: Core.U('Admin/DeliveryTpl/ajaxDetail'),
        data: {id: id},
        dataType: 'json',
        success: function (data){
          if(data){
            $('#J_delivery_field').show();
            $('.J_express_start').text(data.express_start);
            $('.J_express_postage').text(data.express_postage);
            $('.J_express_plus').text(data.express_plus);
            $('.J_express_postageplus').text(data.express_postageplus);
            $('.J_express_unit').text(data.express_unit);
          }else{
            UI.error('抱歉，获取运费模板失败');
          }
        }
      });
    }else if(id == 0){
      $('#J_delivery_field').hide();
    }
  },
  changeSdpType: function (){
    var change = function (){
      var sdp_type = $('input[name="sdp_type"]:checked').val();
      if(0 == sdp_type){
        $('.J_sdp_label').text('金额');
        $('.J_sdp_unit').text('元');
      }else{
        $('.J_sdp_label').text('比例');
        $('.J_sdp_unit').text('%');
      }
    }
    change();
    $('input[name="sdp_type"]').on('change', change);
  }
}

/***********物流模块***********/
var delivery = {
  /*初始化*/
  init: function (){
    this.togglePricetype();
  },
  /*计价方式toggle*/
  togglePricetype: function (){
    $('.J_express_pricetype').on('click', function (){
      var type = $(this).data('type');
      (type == 'quantity') ? $('.J_express_unit').text('件') : $('.J_express_unit').text('kg');
    });
  }
}

/***********专场模块***********/
var activity = {
  init: function (){
    /* 实时更新商品信息 */
    $('.J_form_table')
            .on('submit', 'form', function (){
              var self = $(this);
              $.post(self.attr('action'), self.serialize(), function (data){
                // 提示信息
                var name = data.status ? 'success' : 'error', msg;
                msg = self.find('.msg').addClass(name).text(data.info).css('display', 'inline-block');
                setTimeout(function (){
                  msg.fadeOut(function (){
                    msg.text('').removeClass(name);
                  });
                }, 1000);
              }, 'json');
              return false;
            })
            .on('focus', 'input', function (){
              $(this).data('param', $(this).closest('form').serialize());
            })
            .on('blur', 'input', function (){
              if($(this).data('param') != $(this).closest('form').serialize()){
                $(this).closest('form').submit();
              }
            });



  }
}

/***********用户模块***********/
var user = {
  /*获取微信粉丝*/
  get_wechat_user: function (){
    var _self = this;
    $('.J_get_wechat_user').text('正在同步……').addClass('disabled');
    var add_user = _self.add_wechat_user(Core.U('Admin/User/getWechatUser'));
    if((add_user.status == true) && (add_user.info !== '')){
      _self.add_wechat_user(add_user.info);
      $('.J_get_wechat_user').text('成功，继续同步下一位').removeClass('disabled');
    }
  },
  add_wechat_user: function (url){
    var _self = this;
    var result = {
      status: false,
      info: ''
    };
    $.ajax({
      type: 'POST',
      async: false,
      url: url,
      dataType: 'json',
      success: function (res){
        result.status = (res.status === 1) ? true : false;
        result.info = res.url;
        if(res.url){
          _self.add_wechat_user(res.url);
        }
      }
    });
    return result;
  }
}

/***********自定义菜单模块***********/
var wechat_menu = {
  init: function (){
    /*自定义菜单微信界面模拟*/
    $('.J_menulist ul li')
            .mouseover(function (){
              $(this).children('.sub_button').show();
            })
            .mouseout(function (){
              $(this).children('.sub_button').hide();
            });

    /*表单提交*/
    $('#submit').click(function (){
      $('#form').submit();
    });

    /*选择链接地址*/
    $('.J_selecturl').on('change', function (){
      $('input[name="url"]').val($(this).val());
    });
  }
}

/***********微信消息模块***********/
var wechat_msg = {
  init: function (){
    /*事件类型*/
    $('.J_selectEvent').on('change', function (){
      if($(this).val() == 'subscribe'){
        $('.J_keyword').fadeOut();
      }else{
        $('.J_keyword').fadeIn();
      }
    });

    $('.J_title').on('keyup', function (){
      $('.J_title_view').html($(this).val());
    });
    $('.J_desc').on('keyup', function (){
      $('.J_desc_view').html($(this).val());
    });
  },
}

/***********抢红包列表模块***********/
var redpacket = {
  init: function (){
    $('input[type=radio][name=type]').on('click', function (){
      if($(this).val() == 'single'){
        $('#J_redpacket_amount').html('单个金额<span class="check-tips">（每人可领1个，金额固定）</span>');
        $('#J_redpacket_limit_money_title').text("红包总金额");
        $('input[type=text][name=limit_money]').attr("disabled", "").val("0.00");
        $('input[type=text][name=quantity]').val("");
        $('input[type=text][name=amount]').val("");
      }else{
        $('#J_redpacket_amount').html('总金额<span class="check-tips">（每人可领1个，金额随机）</span>');
        $('#J_redpacket_limit_money_title').text("最大领取金额");
        $('input[type=text][name=limit_money]').removeAttr("disabled").val("");
        $('input[type=text][name=quantity]').val("");
        $('input[type=text][name=amount]').val("");
      }
    });
  },
  //红包金额改变时，改变下面对应的显示金额
  changeAmount: function (){
    var $type = $('input[type=radio][name=type]:checked').val();
    if($type != 'single'){
      return;
    }
    var total_quantity = parseInt($('input[type=text][name=quantity]').val()),
            single_amount = parseFloat($('input[type=text][name=amount]').val()),
            total_quantity = total_quantity ? total_quantity : 0,
            single_amount = single_amount ? single_amount : 0.00,
            total = parseFloat(total_quantity * single_amount).toFixed(2),
            total = total ? total : '0.00';
    $('input[type=text][name=limit_money]').attr("disabled", "").val(total);
  },
  search: function (url){
    var val = $("input.J_redpacket_search").val();
    if(val){
      window.location.href = url + "?keywords=" + val;
    }
  },
  qrcode: function (id){
    var url = Core.U('Admin/Redpacket/qrcode') + '?id=' + id;
    UI.load(url);
  },
  //已领取的红包按领取金额排序
  amountSort: function (sort, id){
    var url = Core.U('Admin/Redpacket/receive') + '?id=' + id + '&sort=';
    url += sort == 'desc' ? 'asc' : 'desc';
    $("#J_amount_sort").attr('href', url);
  }

}

/***************** 订单模块 *********************/
var order = {
  //处理退款操作
  refund: function (){
    var form_data = $('#J_refund_form').serialize();
    //console.log(form_data);
    $.post(Core.U('/Admin/Order/refund'), form_data, function (res){
      //console.log(res);
      if(res.status == 1){
        UI.success(res.info);
        window.setTimeout(function (){
          window.location.reload();
        }, 1e3);
      }else{
        UI.error(res.info);
      }
    }, 'json');
  },
  //灵通打单
  bestmart: function(){
    var ids = $('.ids').serialize();
    if(ids === ''){
      updateAlert('请选择要操作的数据');
      return false;
    }
    var url = Core.U('/Admin/Order/bestmart');
    url += (url.indexOf('?') > -1 ? '&' : '?') + ids;
    window.open(url);
  }
}

/***************** 提现模块 *********************/
var withdraw = {
  init: function (){
    this.statusBind();
  },
  statusBind: function (){
    var checkStatus = function (){
      //获取当前状态
      var status = $('select').val();
      switch(status){
        case '101':
          $('.J_memo').removeClass('hidden');
          break;
        case '200':
          $('.J_memo').addClass('hidden');
          $('.J_fee').removeClass('hidden');
          break;
        case '201':
          $('.J_fee').addClass('hidden');
          $('.J_memo').removeClass('hidden');
          break;
        default:
          $('.J_memo').addClass('hidden');
          $('.J_fee').addClass('hidden');
      }
    };
    checkStatus();
    $('select').on('change', checkStatus);
  }
};

/******************* 系统消息模块 **************************/
var message = {
  // 预览
  preview: function(){
    var data = {
      title: $('input[name="title"]').val(),
      content: $('.J_content').val()
    };
    $.post(Core.U('/Admin/Message/preview'), data, function(html){
      $('.J_preview').html(html);
    });
  }
};



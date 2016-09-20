
/***********自定义菜单模块***********/
var wechat_menu = {
  init: function(){
    /*自定义菜单微信界面模拟*/
    $('.J_menulist ul li')
            .mouseover(function(){
              $(this).children('.sub_button').show();
            })
            .mouseout(function(){
              $(this).children('.sub_button').hide();
            });

    /*表单提交*/
    $('#submit').click(function(){
      $('#form').submit();
    });

    /*选择链接地址*/
    $('.J_selecttype').on('change', function(){
      var seltype = $(this).val();
      if(seltype === 'view'){
        $('.J_view').show();
        $('.J_click').hide();
        $('.J_text').hide();
        $('input[name="event"]').val('');
      }else if(seltype === 'location_select'){
        $('.J_view,.J_click').hide();
        $('.J_text').hide();
        $('input[name="url"]').val('');
        $('input[name="event"]').val('');
      }else if(seltype === 'click'){
        $('.J_click').show();
        $('.J_view').hide();
        $('.J_text').hide();
        $('input[name="url"]').val('');
      }else if(seltype === 'text'){
        $('.J_click').hide();
        $('.J_view').hide();
        $('.J_text').show();
      }
    });
  }
};

/***********微信消息模块***********/
var wechat_msg = {
  init: function(){
    _self = this;
    /*事件类型*/
    $('.J_selecttype').on('change', function(){
      _self.typeChangeEvent($(this).val());
    });
       
    $('.J_title').on('keyup', function(){
      $('.J_title_view').html($(this).val());
    });
    $('.J_desc').on('keyup', function(){
      $('.J_desc_view').html($(this).val());
    });
    $('.J_selectEvent').change();
  },
  
  typeChangeEvent : function(objval){
    if(objval == 'single'){
      //图文
      $('.J_text').hide();
      $('.J_single').show();
      $('input[name=type]').val('news');
    }else{
      //文本
      $('.J_single').hide();
      $('.J_text').show();
      $('input[name=type]').val('text');
    }
  },
  
};
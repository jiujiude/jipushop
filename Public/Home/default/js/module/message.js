/**
 *站内消息
 */
define('module/message', function (require, exports, module){
  'use strict';

  var message = {
    //初始化
    init: function (){
      //读取站内消息内容
      $('.J_message_title').click(function (){
        var m_id = $(this).data('id'),
                url = $.U('Message/detail'),
                info_id = 'J_message_' + m_id;
          //如果之前未读取
          if($('#' + info_id).size() === 0){
            var html = '<div class="message-info" id="'+ info_id +'">';
            html +='<img src="'+ C.IMG +'/loading.gif" width="20"> 正在获取内容...</div>';
                $('#J_message_li_' + m_id).after(html);
            $.ajax({
              type: 'POST',
              url: url,
              data: {message_id: m_id},
              dataType: 'json',
              success: function (res){
                $('#'+info_id).html(res.content);
                $('.J_message_status_'+ m_id).html('已读');
                //右下角未读数量
                if(res.unread_num === '0'){
                  $('#J_message_unread').remove();
                }else{
                  $('#J_message_unread').html(res.unread_num);
                }
              }
            });
          }else{
            $('#' + info_id).toggle();
          }
      });
      //选择操作
      this.checkall();
      //设为已读操作
      $('.J_set_readed').click(function(){
        if($(this).hasClass('disabled') === false){
          var ids = [];
          $('.J_check:checked').each(function(){
            ids.push($(this).val());
          });
          $.ajax({
            type: 'POST',
            url: $.U('Message/setReadStatus'),
            data: {message_id: ids.join(',')},
            dataType: 'json',
            success: function (res){
              
              if(res.status === 1){
                for(var i in ids){
                  $('.J_message_status_'+ ids[i]).html('已读');
                }
                $.ui.success('设置成功！');
                //刷新
                window.setTimeout(function(){
                  window.location.reload();
                }, 1e3);
              }else{
                $.ui.alert(res.info);
              }
            }
          });
        }
      });
      
      //设为已读操作
      $('.J_set_delete').click(function(){
        if($(this).hasClass('disabled') === false){
          var set_delete = function(){
            var ids = [];
            $('.J_check:checked').each(function(){
              ids.push($(this).val());
            });
            $.ajax({
              type: 'POST',
              url: $.U('Message/delete'),
              data: {message_id: ids.join(',')},
              dataType: 'json',
              success: function (res){
                if(res.status === 1){
                  for(var i in ids){
                    $('#J_message_li_'+ ids[i]).remove();
                  }
                  $.ui.success('删除成功！');
                  //刷新
                  window.setTimeout(function(){
                    window.location.reload();
                  }, 1e3);
                }else{
                  $.ui.alert(res.info);
                }
              }
            });
          };
          $.ui.confirm('确定删除所选消息吗？', set_delete);
        }
      });
      
    },
    //全选操作联动
    checkall: function(){
      var btn_fun = function(){
        if($('.J_check:checked').size() > 0){
          $('.J_set_readed, .J_set_delete').removeClass('disabled');
        }else{
          $('.J_set_readed, .J_set_delete').addClass('disabled');
        }
      };
      //全选
      $('.J_checkall').click(function(){
        $('.J_check').prop('checked', $(this).prop('checked'));
        btn_fun();
      });
      //单个选
      $('.J_check').click(function(){
        if($(this).prop('checked')){
          if($('.J_check:checked').size() === $('.J_check').size()){
            $('.J_checkall').prop('checked', true);
          }
        }else{
          $('.J_checkall').prop('checked', false);
        }
        btn_fun();
      });
    }
  };

  module.exports = message;
});
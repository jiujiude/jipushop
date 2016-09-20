/**
 *站内消息
 */
define('module/message', function (require, exports, module){
  'use strict';

  var message = {
    /*显示商品详情*/
    showDetail: function (){
      //读取站内消息内容
      $(document).on('tap', '.Z_message_title', function(){
        var m_id = $(this).data('id'),
                url = $.U('Message/detail');
        $('.Z_modal_title').data('id', m_id);     
        $('.Z_modal_title, .Z_modal_desc').html('');
        $('.Z_modal_content').html('<img src="'+ C.IMG +'/loading.gif" width="16"> 加载中...');
        //如果之前未读取
        $.ajax({
          type: 'POST',
          url: url,
          data: {message_id: m_id},
          dataType: 'json',
          success: function (res){
            $('.Z_modal_title').html(res.title);
            $('.Z_modal_desc').html('时间：'+ res.create_time);
            $('.Z_modal_content').html(res.content);
            $('#Z_message_readdot_'+ m_id).removeClass('active');
            //右下角未读数量
            if(res.unread_num === '0'){
              $('#Z_global_message').remove();
            }
          }
        });
      });
    },
    /*删除消息*/
    delete: function(){
      $('#Z_message_delete').click(function(){
        var m_id = $('.Z_modal_title').data('id');
        var set_delete = function(){
          $.ajax({
            type: 'POST',
            url: $.U('Message/delete'),
            data: {message_id: m_id},
            dataType: 'json',
            success: function (res){
              if(res.status === 1){
                $('#Z_message_'+ m_id).remove();
                if($('#Z_load_itemlist dl').size() === 0){
                  $('#Z_load_itemlist').html('<p class="list-empty">暂无消息记录</p>');
                }
                $.ui.success('删除成功！');
                $('#messageDetail').removeClass('active');
              }else{
                $.ui.alert(res.info);
              }
            }
          });
        };
        $.ui.confirm('确定删除这条消息吗？', set_delete);
        //遮罩层级提升
        $('.modal-backdrop').css({zIndex: 22});
      });
    }
  };

  module.exports = message;
});
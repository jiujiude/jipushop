/**
 *评价模块：商品评价添加、评价列表
 */

define('module/comment', function(require, exports, module){

  'use strict';

  var common = require('common');

  var comment = {
    add: function(item_id, order_id){
      var url = $.U('ItemComment/add', 'item_id=' + item_id + '&order_id=' + order_id);
      $.ui.load(url, '添加评价');
    },
    update: function(){
      var item_id = $('#Z_comment_itemid').val(),
          content = $('#Z_comment_content').val(),
          order_id = $('#Z_comment_orderid').val(),
          star_amount = $('#Z_comment_star .icon-star-large-full').length;
      
      if(!item_id){
        return false;
      }

      if(content.length <= 0){
        $.ui.error('请您输入评价内容');
        return false;
      }

      $.ajax({
        type: 'POST',
        url: $.U('ItemComment/add'),
        data: {item_id: item_id, order_id: order_id, star_amount: star_amount, content: content},
        dataType: 'json',
        success: function(res){
          if(res.status){
            $.ui.box.close();
            $.ui.success('评价成功，感谢您的支持！');
            common.reload();
          }else{
            $.ui.box.close();
            $.ui.error('抱歉，评价失败！');
          }
        }
      });
    },
    detail: function(uid, item_id, order_id){
      var url = $.U('ItemComment/detail', 'uid=' + uid + '&item_id=' + item_id + '&order_id=' + order_id);
      $.ui.load(url, '评价详情');
    },
    setStar: function(star_amount){
      for(var i = 0; i < star_amount; i++){
        $('#Z_comment_star .Z_star').eq(i).addClass('icon-star-large-full').removeClass('icon-star-large-blank');
      }
      $('#Z_comment_close').on('click', function(){
        $.ui.box.close();
      });
    },
    init: function(){
      var _self = this;
      $('#Z_comment_star .Z_star').on('click', function(){
        $(this).siblings('.Z_star').removeClass('icon-star-large-full').addClass('icon-star-large-blank');
        var item = $(this).index();
        for(var i = 0; item >= i; i++){
          $('#Z_comment_star .Z_star').eq(i).addClass('icon-star-large-full').removeClass('icon-star-large-blank');
        }
      });

      $('#Z_comment_update').on('click', function(){
        _self.update();
      });
    }
  };
  
  module.exports = comment;
});
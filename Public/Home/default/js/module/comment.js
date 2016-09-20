/**
 *个人中心
 */
define('module/comment', function(require, exports, module){
  'use strict';
  
  var comment = {
    
    /*评价*/
    add : function(item_id, order_id){
      var url = $.U('ItemComment/add', 'item_id=' + item_id + '&order_id=' + order_id);
      $.ui.load(url, '添加评价');
    },
    
    update : function(){
      var star_amount = 0;
      var item_id = $('.J_comment_itemid').val();
      var order_id = $('.J_comment_orderid').val();
      var content = $('.J_comment_content').val();
      var comment = {};
      $('.J_comment_star').find('.J_star').each(function(){
        $(this).hasClass('icon-star-large-full') && star_amount++;
      });
      if(item_id == ''){
        return false;
      }
      if(star_amount == 0){
        $('.J_star_error').removeClass('hide');
        return false;
      }else{
        $('.J_star_error').addClass('hide');
      }
      if(content.length < 2){
        $('.J_comment_error').removeClass('hide');;
        return false;
      }else{
        $('.J_comment_error').addClass('hide');
      }
      comment = {
        item_id: item_id,
        order_id: order_id,
        star_amount: star_amount,
        content: content
      }
      $.post($.U('ItemComment/add'), comment, function(res){
        if(res.status){
          $.ui.box.close();
          $.ui.success('评价成功，感谢您的支持！');
          window.location.reload();
        }else{
          $.ui.box.close();
          $.ui.error('抱歉，评价失败！');
        }
      }, 'json');
    },
    
    detail: function(uid, item_id, order_id){
      var url = $.U('ItemComment/detail', 'uid=' + uid + '&item_id=' + item_id + '&order_id=' + order_id);
      $.ui.load(url, '评价详情');
    }
  }
  module.exports = comment;
});
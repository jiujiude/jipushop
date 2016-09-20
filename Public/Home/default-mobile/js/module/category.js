define('module/category',function(require, exports, module){
  'use strict';
  var category={
	  all_init:function(){	/*全部分类页-初始化*/
		var sub_cate=0;
		$('.category .cateTree').click(function(e) {
			if(sub_cate != $(this).next() && sub_cate != 0){
				sub_cate.animate({'height':1},200);
				sub_cate.prev().find('.arrow').removeClass('icon-arrow-bottom');
				sub_cate.prev().find('.arrow').addClass('icon-arrow-right');
			}
			sub_cate=$(this).next();
			if(sub_cate.height()>5){
           		sub_cate.animate({'height':1},200);
				sub_cate.prev().find('.arrow').removeClass('icon-arrow-bottom');
				sub_cate.prev().find('.arrow').addClass('icon-arrow-right');
			}else{
				sub_cate.animate({'height':sub_cate.children('div').height()},200);
				sub_cate.prev().find('.arrow').removeClass('icon-arrow-right');
				sub_cate.prev().find('.arrow').addClass('icon-arrow-bottom');
			}
			return false;
        });
		var parent_div;
		$('.sub>div>a').click(function(e){
			$('.sub>div>div>p').hide();
			parent_div=$(this).parent();
			parent_div.find('a.on').removeClass('on');
			$(this).addClass('on');
			parent_div.find('p[cid-'+$(this).attr('cid')+']').show();
			parent_div.parent().animate({'height':parent_div.height()},200);
		})
		$('.category .cateTree i.arrow').click(function(e) {
            window.location.href=$(this).attr('url');
        });
	  },
	  search_init:function(){	/*搜索页-初始化*/
		  $('.category').height($(window).height()-50);
		  $('.category>div>.close_btn').height($(window).height()-50);
		  var category_bg=$('.category .close_btn');
		  var category_item=$('.category ul');
		  $('.category-top-fix .r i').click(function(e) {
            if(category_item.width()>5){
           		category_item.animate({'width':0},200,function(){$('.category').hide();});
				category_bg.fadeOut(200);
			}else{
				$('.category').show();
				category_item.animate({'width':$(window).width()-20},200);
				category_bg.fadeIn(200);
			}
        });
		  category_bg.click(function(){
			  category_item.animate({'width':0},200,function(){$('.category').hide();});
			  category_bg.fadeOut(200);
		  });
		  $('.category ul a[cid]').click(function(){
			  return false;
		  })
		  $('.category ul').click(function(){
			  category_item.animate({'width':0},200,function(){$('.category').hide();});
			  category_bg.fadeOut(200);
		  });
	  }
  }
  module.exports = category;
});
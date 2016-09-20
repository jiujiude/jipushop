/*!
 * jquery.imgLazyLoad.js
 */
((function (factory){
  if(typeof define === 'function'){
    // 如果define已被定义，模块化代码
    define('jquery.imglazyload', ['jquery'], function (require, exports, moudles){
      factory(require('jquery')); // 初始化插件
      return jQuery; // 返回jQuery
    });
  }else{
    // 如果define没有被定义，正常执行jQuery
    factory(jQuery);
  }
}(function ($){
  $.extend({
    imgLazyLoad: function (options){
      
      var config = {
        container: 'body',
        tabItemSelector: '',
        carouselItemSelector: '',
        attrName: 'data-url',
        diff: 0
      };
      $.extend(config, options || {});

      var $container = $(config.container),
              offsetObj = $container.offset(),
              compareH = $(window).height() + $(window).scrollTop(),
              // 判断容器是否为body子元素
              bl = $.contains(document.body, $container.get(0)),
              // 过滤缓存容器中的图片
              notImgSelector = jQuery.imgLazyLoad.selectorCache ? ':not(' + jQuery.imgLazyLoad.selectorCache + ')' : '',
              imgSelector = 'img[' + config.attrName + ']:visible' + notImgSelector,
              $filterImgs = $container.find(imgSelector),
              // 用于阻止事件处理
              isStopEventHandle = false,
              // 是否自动懒加载，为true时，绑定滚动事件
              isAutoLazyload = false;

      // 缓存容器为body子元素的图片选择器
      jQuery.imgLazyLoad.selectorCache = bl ? (jQuery.imgLazyLoad.selectorCache ? (jQuery.imgLazyLoad.selectorCache + ',' + config.container + ' img') : config.container + ' img') : jQuery.imgLazyLoad.selectorCache;

      function handleImgLoad(idx){
        if(isStopEventHandle){
          return;
        }
        /**
         处理Tab切换，图片轮播，在处理$filterImgs时，没有过滤img:not(.img-loaded)，因为只是在一个面板中，
         还有其他面板，如果再次触发，可能$filterImgs.length为0，因此只能在外围容器中判断过滤图片length
         */
        if($container.find('img:not(.img-loaded)').length === 0){
          isStopEventHandle = true;
        }

        var itemSelector = config.tabItemSelector || config.carouselItemSelector || '';
        if(itemSelector){
          if(typeof idx !== undefined && idx >= 0){
            $filterImgs = $container.find(itemSelector).eq(idx).find('img');
          }
          else{
            if(itemSelector === config.carouselItemSelector){
              $filterImgs = $container.find(itemSelector).eq(0).find('img');
            }
            else{
              $filterImgs = $container.find(itemSelector + ':visible').find('img');
            }
          }
        }
        else{
          $filterImgs = $filterImgs.not('.img-loaded'); // 自动懒加载，过滤已加载的图片
          isAutoLazyload = true;
        }

        // 当外围容器位置发生变化，需更新
        offsetObj = $container.offset();

        if($filterImgs.length > 0){
          $filterImgs.each(function (idx, elem){
            var $target = $(elem),
                    targetTop = $target.offset().top,
                    viewH = $(window).height() + $(window).scrollTop() + config.diff;

            if(bl){
              $target.attr('src', $target.attr(config.attrName)).removeAttr(config.attrName).addClass('img-loaded');
            }
            // 内容在视窗中
            if(viewH > targetTop){
              $target.attr('src', $target.attr(config.attrName)).removeAttr(config.attrName).addClass('img-loaded');
            }
          });
        }
        else{
          // 处理滚动事件
          isStopEventHandle = true;
          $(window).unbind('resize scroll', handleImgLoad);
        }
      }

      handleImgLoad();
      if(isAutoLazyload){
        $(window).bind('resize scroll', handleImgLoad);
      }

      // 提供事件处理函数
      return {
        handleImgLoad: handleImgLoad
      }
    }
  });

  // 保存非body子元素容器下的图片选择器
 // jQuery.scrollLoading.selectorCache = '';
})));
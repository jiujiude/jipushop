(function(factory){
  if (typeof define === 'function') {
    // 如果define已被定义，模块化代码
    define('zepto.imglazyload', ['zepto'], function(require, exports, moudles){
      factory(require('zepto')); // 初始化插件
      return Zepto; // 返回Zepto
    });
  }else{
    // 如果define没有被定义，正常执行Zepto
    factory(Zepto);
  }
}(function(){
    $.fn.imglazyload = function(settings){
        var $this = $(this),
            _winScrollTop = 0,
            _winHeight = $(window).height();

        settings = $.extend({
            threshold: 0, // 提前高度加载
            placeholder: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC'
        }, settings||{});

        // 执行懒加载图片
        lazyLoadPic();

        // 滚动触发换图
        $(window).on('scroll',function(){
            _winScrollTop = $(window).scrollTop();
            lazyLoadPic();
        });

        // 懒加载图片
        function lazyLoadPic(){
            $this.each(function(){
                var $self = $(this);
                // 如果是img
                if($self.is('img')){
                    if($self.attr('data-url')){
                        var _offsetTop = $self.offset().top;
                        if((_offsetTop - settings.threshold) <= (_winHeight + _winScrollTop)){
                            $self.attr('src',$self.attr('data-url'));
                            $self.removeAttr('data-url');
                        }
                    }
                // 如果是背景图
                }else{
                    if($self.attr('data-url')){
                        // 默认占位图片
                        if($self.css('background-image') == 'none'){
                            $self.css('background-image','url('+settings.placeholder+')');
                        }
                        var _offsetTop = $self.offset().top;
                        if((_offsetTop - settings.threshold) <= (_winHeight + _winScrollTop)){
                            $self.css('background-image','url('+$self.attr('data-url')+')');
                            $self.removeAttr('data-url');
                        }
                    }
                }
            });
        }
    }
}));
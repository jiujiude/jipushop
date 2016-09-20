(function(factory){
  if (typeof define === 'function') {
    // 如果define已被定义，模块化代码
    define('zepto.loadmore', ['zepto'], function(require, exports, moudles){
      factory(require('zepto')); // 初始化插件
      return Zepto; // 返回Zepto
    });
  }else{
    // 如果define没有被定义，正常执行Zepto
    factory(Zepto);
  }
}(function(){
  var LoadMore = function(element, option){
    this.element = element;
    this.option = option;
    _page_num = 1;//默认初始页码
    this.init();
  }

  LoadMore.prototype={
    element:null,
    load_btn:null,
    option:null,

    init:function(){
      this.createBtn();
      this.bindEvt();
    },

    createBtn:function(){
      var data_bn_ipg = this.option.ipg ? 'data-bn-ipg="' + this.option.ipg + '"':'';
      this.load_btn = $('<p class="loadmore" id="Z_list_loadmore"><a href="javascript:void(0)" class="btn btn-large btn-block" ' + data_bn_ipg + '><span>'+(this.option.normal_text||'加载更多')+'</span></a></p>');
      this.element.after(this.load_btn);
    },

    bindEvt:function(){
      var _this = this;
      this.load_btn.on('tap', function(e){
        //e.stopPropagation();为了统计需要冒泡到body
        if( _this._state == 0 || _this._state == 2 ){
          _this.setState(1);
          if(_this.option.onLoadMore){
            _this.option.onLoadMore.call(_this.element[0], ++_this._page_number);
          }
        }
      });

//      if(this.option.auto_loadmore){
//        (window).addEventListener('touchend', $.runonce(function(){
//          // if($(window).scrollTop() + $(window).height() >= _this.element.next('p#Z_list_loadmore').offset().top) {
//            // _this.load_btn.trigger('tap');
//          // }
//        }, 100));
//      }
      if(this.option.auto_loadmore){
        var check_loadmore = function(){
         var bh = $('body').height(),
          wh = $(window).height(),
          bt = $('body').scrollTop();
          if((bh - wh - 20) < bt) {
            _this.load_btn.trigger('tap');
          }
        };
        $(window).on('scroll', check_loadmore);
        (window).addEventListener('touchend', $.runonce(check_loadmore, 100));
      }
      
    },

    _state:0,   // 0 正常，1 加载中， 2，错误，请重试
    setState:function(state){
      var s = ['loadmore', 'loading', 'error'];
      this.load_btn[0].className = s[0];
      if(state == 0){
        this.load_btn.find('span').html(this.option.normal_text || '加载更多');
      }else if(state == 1){
        this.load_btn.find('span').html(this.option.loading_text || '正在加载');
        this.load_btn.addClass(s[1]);
      }
      if(state == 2){
        this.load_btn.find('span').html(this.option.error_text||'加载失败，点击重新加载');
        this.load_btn.addClass(s[2]);
      }
      this._state = state;
    },

    pushHTML:function(html){
      if(this.option.el && this.element.find(this.option.el).size() > 0){
        this.element.find(this.option.el).append(html);
      }else{            
        $(html).insertBefore(this.load_btn);
      }
      this.setState(0);
    },

    removeBtn:function(){
      this.load_btn.hide();
    },

    addBtn:function(){
      this.load_btn.show();
    },

    _page_number:1,
    setPageNumber:function(pageNum){
      this._page_number = typeof pageNum === 'number' ? pageNum : 1;
    },
    pageNumber:function(num){
      if(typeof num !== 'undefined'){
        this.setPageNumber(num);
      }            
      return this._page_number;          
    }
  };

  // ====================================================
  Zepto.extend(Zepto.fn, {
    LoadMore:function(option, param1, param2){
      var option_type = $.type(option);
      var $this;
      if(option_type== 'object' || option_type== 'undefined'){
        $.each(this,function(){
          $this = $(this);
          if(!this.__loadMore__){
            this.__loadMore__ = new LoadMore($this, option);
          }
        });
      }else if(option_type == 'string'){
        var cmds = ['pushHTML','setState','removeBtn','addBtn','pageNumber'],pageNumber=[];
        if(cmds.indexOf(option) != -1){
          $.each(this,function(){
            $this = $(this);
            pageNumber.push(this.__loadMore__[option](param1, param2));
          });
          switch (option){
            case 'pageNumber':
              return pageNumber;
            default:
              return this;
          }
        }
      }
    }
  });
}));


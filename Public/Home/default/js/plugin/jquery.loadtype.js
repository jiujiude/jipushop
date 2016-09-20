/**
 * 获取分类信息插件  
 * @author Max.Yu <max@winhu.com>
 * @version 2015041517
 * @example 
 * $('#item_type_div').loadtype({
      type: 'area',  //【商品分类】itemCategory | 【地区】area
      name1: 'prov',
      name2: 'city',
      name3: 'area',
      value1: 230000,
      value2: 230700,
      value3: 230703
    });
 **/
(function(factory){
  if (typeof define === 'function') {
    // 如果define已被定义，模块化代码
    define('jquery.loadtype', ['jquery'], function(require, exports, moudles){
      factory(require('jquery')); // 初始化插件
      return jQuery; // 返回jQuery
    });
  }else{
    // 如果define没有被定义，正常执行jQuery
    factory(jQuery);
  }
}(function($){
  $.fn.extend({
    loadtype : function(options){
      //定义默认参数
      var defaults = {
        type: 'itemCategory', 
        name1: 'cid_1',
        name2: 'cid_2', 
        name3: 'cid_3',
        value1: '',
        value2: '',
        value3: '',
        changed:function(){}
      };
      var data_init = function(obj, options, data){
        var rand = Math.random()* 1e17;
        var select_default = '<option value="">请选择</option>';
        var html1 = html2 = html3 = select_default;
        if(data[0]){
          for(var i = 0; i <data[0].length; i++){
            html1 += '<option value="'+ data[0][i].id +'">'+ data[0][i].name +'</option>';
          }
        }
        var initHtml = '';
        initHtml = '<select name="'+ options.name1 +'" class="'+ options.name1 +'" id="n1_'+ rand +'">' + html1 + '</select>\r\n';
        initHtml += '<select name="'+ options.name2 +'" class="'+ options.name2 +'" id="n2_'+ rand +'" style="display:none;">'+ html2 +'</select>\r\n';
        initHtml += '<select name="'+ options.name3 +'" class="'+ options.name3 +'" id="n3_'+ rand +'" style="display:none;">'+ html3 +'</select>\r\n';
        $(obj).html(initHtml);
        var changed = function(){
          if(typeof options.changed === 'function'){
            options.changed($('#n1_'+rand).val(), $('#n2_'+rand).val(), $('#n3_'+rand).val());
          }
        };
        $('#n1_' + rand).change(function(){
          var value = $(this).val() === '' ? NaN : $(this).val() * 1;
          $('#n2_' + rand).html(select_default).hide();
          $('#n3_' + rand).html(select_default).hide();
          if(value !== NaN){
            if(typeof data[value] !== 'undefined'){
              html2 = select_default;
              for(var i = 0; i < data[value].length; i++){
                html2 += '<option value="'+ data[value][i].id +'">'+ data[value][i].name +'</option>';
              }
              $('#n2_' + rand).html(html2).show();
            }
          }
          changed();
        });
        $('#n2_' + rand).change(function(){
          var value = $(this).val() === '' ? NaN : $(this).val() * 1;
          $('#n3_' + rand).html(select_default).hide();
          if(value !== NaN){
            if(typeof data[value] !== 'undefined'){
              html3 = select_default;
              for(var i = 0; i < data[value].length; i++){
                html3 += '<option value="'+ data[value][i].id +'">'+ data[value][i].name +'</option>';
              }
              $('#n3_' + rand).html(html3).show();
            }
          }
          changed();
        });
        $('#n3_' + rand).change(function(){
          changed();
        });
        
        if(options.value1 !== ''){
          $('#n1_' + rand+' option[value="'+ options.value1 +'"]').attr('selected', true);
          $('#n1_' + rand).change();
        }
        if(options.value2 !== ''){
          $('#n2_' + rand+' option[value="'+ options.value2 +'"]').attr('selected', true);
          $('#n2_' + rand).change();
        }
        if(options.value3 !== ''){
          $('#n3_' + rand+' option[value="'+ options.value3 +'"]').attr('selected', true);
          $('#n3_' + rand).change();
        }
        
      };
      //合并参数
      options = $.extend(defaults, options);
      this.each(function(){
        var _self = this;
        //处理插件逻辑
        try{
          var type_data = eval(options.type);
          data_init(_self, options, type_data);
        }catch(e){
          $.getScript($.U('/Api/getTypeData', 'type=' + options.type), function(){
            data_init(_self, options, eval(options.type));
          });
        }
      });
    }
  });
}));
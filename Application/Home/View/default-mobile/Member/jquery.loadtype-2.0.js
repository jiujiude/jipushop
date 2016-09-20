/**
 * 获取分类信息插件  
 * @author Max.Yu <max@winhu.com>
 * @version 15072115
 * @example 
 * $('#item_type_div').loadtype({
 *    type: 'Category',  //【文档分类】Category | 【地区】area
 *    name: 'cid',
 *    value: 11,
 *    defaultChangeCall: false, //默认数据不触发callback
 *    callback: function(cid){}
 *  });
 **/ 
(function($){
  $.fn.extend({
    loadtype : function(options){
      //定义默认参数
      var defaults = {
        type: 'Category', 
        name: 'cid',
        value: '',
        defaultChangeCall: true,
        callback: function(){}
      };
      var data_init = function(obj, options, data){
        var rand = Math.random()* 1e20,
            default_option = '<option value="">请选择</option>';
        var default_value = '',
            values = [0];
        //获取父级分类
        var get_parents = function(){
          var v = default_value.split(',')[0];
          for(i in data){
            for(j in data[i]){
              if(data[i][j].id === v){
                if(parseInt(data[i][j].pid) > 0){
                  default_value = data[i][j].pid+','+default_value;
                  get_parents();
                }
                break;
              }
            }
          }
        };
        //赋默认值
        if(options.value > 0){
          default_value = options.value.toString();
          get_parents();
          values = default_value.split(',');
        }
        //组装分类option
        var create_html = function(array_data){
          var html = '';
          if(array_data){
            html = default_option;
            for(var i = 0; i < array_data.length; i++){
              add_str = $.inArray(array_data[i].id, values) > -1 ? ' selected' : '';
              html += '<option value="'+ array_data[i].id +'"'+ add_str +'>'+ array_data[i].name +"</option>\n";
            }
          }
          return html;
        }; 
        //第一个分类
        var html = create_html(data[0]);
        $(obj).html('<select name="'+ options.name +'" class="'+ options.name +'" id="'+ options.name +'_1_'+ rand +'">'+ html +'</select>');
        //创建之后的分类
        var add_type = function(id){
          if(id === '' || parseInt(id) === 0){ return ;}
          if(typeof data[id] !== 'undefined'){
            html = create_html(data[id]);
            $('<select name="'+ options.name +'" class="'+ options.name +'" >'+ html +'</select>').appendTo(obj);
          }
          var html = default_option;
        };
        //改变事件
        $(obj).on("change", "select", function(){
          var val = $(this).val();
          $(this).nextAll().remove();
          add_type(val);
          if(typeof options.callback === 'function'){
            options.callback(val);
          }
        });
        //选择默认值
        if(values){
          for(var i in values){
            add_type(values[i]);
          }
          if(typeof options.callback === 'function' && options.defaultChangeCall === true){
            options.callback(options.value);
          }
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
          $.getScript(Core.U('/Api/getTypeData', 'type=' + options.type), function(){
            data_init(_self, options, eval(options.type));
          });
        }
      });
    }
  });
})(jQuery);
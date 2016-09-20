/* ajaxpost */
var ajaxpost = {
  init: function(formobj){
    $(formobj).submit(function(){
      action = $(formobj).attr('action');
      data = $(formobj).serialize();
      $.post(action, data, function(json){
        if(json.status === 1){
          UI.success(json.info);
          window.setTimeout(function(){
            if(json.url){
              window.location.href = json.url; 
            }else{
              window.location.reload();
            }
          }, 1e3);
        }else{
          UI.error(json.info);
        }
      },'json');
      return false;
    });
  }
};
//dom加载完成后执行的js
$(function(){
  //全选的实现
  $(".check-all").click(function(){
    $(".ids").prop("checked", this.checked);
  });
  $(".ids").click(function(){
    var option = $(".ids");
    option.each(function(i){
      if(!this.checked){
        $(".check-all").prop("checked", false);
        return false;
      }else{
        $(".check-all").prop("checked", true);
      }
    });
  });

  //搜索的实现
  $("#J_search_form #J_search").click(function(){
    $("#J_search_form").submit();
  });
  //回车自动提交
  $('#J_search_form').find('input').keyup(function(event){
    if(event.keyCode === 13){
      $("#J_search_form").submit();
    }
  });
  //搜索下拉菜单及时过滤
  $('#J_search_form select').change(function(){
    $("#J_search_form").submit();
  });

  //ajax get请求
  $('.ajax-get').click(function(){
    var target,
        that = this,
        _self = $(this);
    
    var sub_fun = function(){
      if((target = _self.attr('href')) || (target = _self.attr('url'))){
        $.get(target).success(function(data){
          if(data.status == 1){
            if(data.url){
              updateAlert(data.info + ' 页面即将自动跳转~', 'alert-success');
            }else{
              updateAlert(data.info, 'alert-success');
            }
            setTimeout(function(){
              if(data.url){
                location.href = data.url;
              }else if(_self.hasClass('no-refresh')){
                $('#top-alert').find('button').click();
              }else{
                location.reload();
              }
            }, 1500);
          }else{
            updateAlert(data.info);
            setTimeout(function(){
              if(data.url){
                location.href = data.url;
              }else{
                $('#top-alert').find('button').click();
              }
            }, 1500);
          }
        });

      }
    }
           
    if($(this).hasClass('confirm')){
      UI.confirm('确定要执行该操作吗？', function(){
        sub_fun();
      });
    }else{
      sub_fun();
    }
    
    return false;
  });

  //ajax post submit请求
  $('.ajax-post').click(function(){
    var target,
        that = this,
        _self = $(this);
    
    var sub_fun = function(){
      var query, form;
      var target_form = _self.attr('target-form');
      var nead_confirm = false;
      if((_self.attr('type') == 'submit') || (target = _self.attr('href')) || (target = _self.attr('url'))){
        form = $('.' + target_form);
        //执行提交前预处理
        if(typeof $.beforeSubmit === 'object'){
          for(var i in $.beforeSubmit){
            if(typeof $.beforeSubmit[i] == 'function'){
              $.beforeSubmit[i]();
            }
          }
        }
        if(_self.attr('hide-data') === 'true'){//无数据时也可以使用的功能
          form = $('.hide-data');
          query = form.serialize();
        }else if(form.get(0) == undefined){
          return false;
        }else if(form.get(0).nodeName == 'FORM'){
          if(_self.attr('url') !== undefined){
            target = _self.attr('url');
          }else{
            target = form.get(0).action;
          }
          query = form.serialize();
        }else if(form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA'){
          form.each(function(k, v){
            if(v.type == 'checkbox' && v.checked == true){
              nead_confirm = true;
            }
          })
          query = form.serialize();
        }else{
          query = form.find('input,select,textarea').serialize();
        }
        //console.log(query);
        var formhash = $('meta[property="formhash"]').attr('content');
        query += '&formhash='+formhash;
        $(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
        $.post(target, query).success(function(data){
          if(data.status === 1){
            if(data.url){
              updateAlert(data.info + ' 页面即将自动跳转~', 'alert-success');
            }else{
              updateAlert(data.info, 'alert-success');
            }
            setTimeout(function(){
              $(that).removeClass('disabled').prop('disabled', false);
              if(data.url){
                location.href = data.url;
              }else if($(that).hasClass('no-refresh')){
                $('#top-alert').find('button').click();
              }else{
                location.reload();
              }
            }, 1500);
          }else{
            updateAlert(data.info);
            setTimeout(function(){
              $(that).removeClass('disabled').prop('disabled', false);
              if(data.url){
                location.href = data.url;
              }else{
                $('#top-alert').find('button').click();
              }
            }, 1500);
          }
        });
      }
    };
    
    // 是否为顶部删除- 如果是，则先检测是否选值
    if($(this).attr('target-form') === 'ids'){
      var sels = $('.ids').serialize();
      if(sels === ''){
        updateAlert('请选择要操作的数据');
        return false;
      }
    }
    
    if($(this).hasClass('confirm')){
      UI.confirm('确定要执行该操作吗？', function(){
        sub_fun();
      });
    }else{
      sub_fun();
    }
    
    return false;
  });

  /**顶部警告栏*/
  var content = $('#main');
  var top_alert = $('#top-alert');
  var alert_timer;
  top_alert.find('.close').on('click', function(){
    top_alert.removeClass('block').slideUp(200);
    window.clearTimeout(alert_timer);
    // content.animate({paddingTop:'-=55'},200);
  });

  window.updateAlert = function(text, c){
    text = text || 'default';
    c = c || false;
    if(text != 'default'){
      top_alert.find('.alert-content').html(text);
      if(top_alert.hasClass('block')){
      }else{
        top_alert.addClass('block').slideDown(200);
        // content.animate({paddingTop:'+=55'},200);
      }
    }else{
      if(top_alert.hasClass('block')){
        top_alert.removeClass('block').slideUp(200);
        // content.animate({paddingTop:'-=55'},200);
      }
    }
    if(c != false){
      top_alert.removeClass('alert-error alert-warn alert-info alert-success').addClass(c);
    }
    window.clearTimeout(alert_timer);
    alert_timer = setTimeout(function(){
      top_alert.removeClass('block alert-success').addClass('alert-error');
    }, 1111500);
  };
  
  //实时更新字段
  $('.J_ajax_updatefield').each(function(){
    var _self = $(this),
        itype = _self.attr('type');
    _self.on('change', function(){
      _name = _self.attr('name');
      if(itype === 'checkbox'){
        _val = _self.is(':checked') ? _self.val() : '0';
      }else if(itype === 'text'){
        _val = _self.val();
      }
      _data = {id: _self.data('id'), field: _name, value: _val};
      eval('_data.'+_name +'="'+_val+'";');
      $.ajax({
        type: 'POST',
        url: _self.data('action'),
        data: _data,
        dataType: 'json',
        success: function(res){
//          if(res.status === 1){
//            UI.success(res.info);
//          }else{
//            UI.error(res.info);
//            
//          }
          updateAlert(res.info, res.status === 1 ? 'alert-success': 'alert-erro');
        }
      });
    });
  });

  //按钮组
  (function(){
    //按钮组(鼠标悬浮显示)
    $(".btn-group").mouseenter(function(){
      var userMenu = $(this).children(".dropdown ");
      var icon = $(this).find(".btn i");
      icon.addClass("btn-arrowup").removeClass("btn-arrowdown");
      userMenu.show();
      clearTimeout(userMenu.data("timeout"));
    }).mouseleave(function(){
      var userMenu = $(this).children(".dropdown");
      var icon = $(this).find(".btn i");
      icon.removeClass("btn-arrowup").addClass("btn-arrowdown");
      userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
      userMenu.data("timeout", setTimeout(function(){userMenu.hide()}, 100));
    });

    //按钮组(鼠标点击显示)
    // $(".btn-group-click .btn").click(function(){
    //     var userMenu = $(this).next(".dropdown ");
    //     var icon = $(this).find("i");
    //     icon.toggleClass("btn-arrowup");
    //     userMenu.toggleClass("block");
    // });
    $(".btn-group-click .btn").click(function(e){
      if ($(this).next(".dropdown").is(":hidden")) {
        $(this).next(".dropdown").show();
        $(this).find("i").addClass("btn-arrowup");
        e.stopPropagation();
      }else{
        $(this).find("i").removeClass("btn-arrowup");
      }
    })
    $(".dropdown").click(function(e) {
      e.stopPropagation();
    });
    $(document).click(function() {
      $(".dropdown").hide();
      $(".btn-group-click .btn").find("i").removeClass("btn-arrowup");
    });
  })();

  // 独立域表单获取焦点样式
  $(".text").focus(function(){
    $(this).addClass("focus");
  }).blur(function(){
    $(this).removeClass('focus');
  });
  $("textarea").focus(function(){
    $(this).closest(".textarea").addClass("focus");
  }).blur(function(){
    $(this).closest(".textarea").removeClass("focus");
  });
});

/* 上传图片预览弹出层 */

//标签页切换(无下一步)
function showTab() {
  $(".tab-nav li").click(function(){
    var self = $(this), target = self.data("tab");
    self.addClass("current").siblings(".current").removeClass("current");
    window.location.hash = "#" + target.substr(3);
    $(".tab-pane.in").removeClass("in");
    $("." + target).addClass("in");
  }).filter("[data-tab=tab" + window.location.hash.substr(1) + "]").click();
}

//标签页切换(有下一步)
function nextTab() {
   $(".tab-nav li").click(function(){
    var self = $(this), target = self.data("tab");
    self.addClass("current").siblings(".current").removeClass("current");
    window.location.hash = "#" + target.substr(3);
    $(".tab-pane.in").removeClass("in");
    $("." + target).addClass("in");
    showBtn();
  }).filter("[data-tab=tab" + window.location.hash.substr(1) + "]").click();

  $("#submit-next").click(function(){
    $(".tab-nav li.current").next().click();
    showBtn();
  });
}

// 下一步按钮切换
function showBtn() {
  var lastTabItem = $(".tab-nav li:last");
  if( lastTabItem.hasClass("current") ) {
    $("#submit").removeClass("hidden");
    $("#submit-next").addClass("hidden");
  } else {
    $("#submit").addClass("hidden");
    $("#submit-next").removeClass("hidden");
  }
}

//导航高亮
function highlight_subnav(url){
  $('.side-sub-menu').find('a[href="'+url+'"]').closest('li').addClass('current');
}

//显示选择的用户
function showUsers(users){
  var url = '/Addons/execute/_addons/UserSel/_controller/UserSel/_action/index/tpl/show.html';
  url += (url.indexOf('?') > -1 ? '&' : '?') + 'users='+ users;
  UI.load(url, '用户列表');
}

//显示选择的商品
function showItems(items){
  var url = '/Addons/execute/_addons/ItemSel/_controller/ItemSel/_action/index/tpl/show.html';
  url += (url.indexOf('?') > -1 ? '&' : '?') + 'items='+ items;
  UI.load(url, '商品列表');
}

//打印console.log日志
function p(obj){
  console.log(obj);
}

  
//离开页面时提示处理中...
$(window).bind('beforeunload',function(){
  UI.loading('页面正在切换....');
});
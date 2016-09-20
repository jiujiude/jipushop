/**
 * 发票信息、修改、删除
 */
define('module/invoice', function(require, exports, module){
  'use strict';
  var common = require('common');

  var invoice = {
    /*收货信息初始化*/
    init: function(){
      var _self = this;
      //弹出框内容
      $('#edit_receipt').on('click',function(){
          $('#J_load_box').show();
          $.ui.showBackdrop();
      });
      //关闭弹窗
      $('.J_modal_close').on('click',function(){
          $('#J_load_box').hide();
          $.ui.hideBackdrop();
      });
      //发票类型选择
      $('#invoice-nav ul li').on('click',function(){
          var index = $(this).index();
          var type = $(this).attr('data-key');
          $(this).addClass('select').siblings('li').removeClass('select');
          $('div.form').eq(index).show().siblings('.form').hide();
          $('div#invoice-main input[name="type"]').val(type);
      });
      //发票内容
      $("ul.electro_book li").on('click',function(){
          var content = $(this).attr('data');
          $(this).addClass('select').siblings('li').removeClass('select');
          //发票内容
          if($('#invoice-nav ul li.select').index() == 0){
              $('div#invoice-main input[name="normal_content"]').val(content);
          }else if($('#invoice-nav ul li.select').index() == 1){
              $('div#invoice-main input[name="ele_content"]').val(content);
          }else{
              $('div#invoice-main input[name="inc_content"]').val(content);
          }
      });
      
      //提交
      $('.btn-submit').on('click',function(){
          var type = $(this).attr('data-type');
          if(_self.validation(type)){
              _self.submit();
          }
          return false;
      });
    },
    
    /*发票表单验证*/
    validation: function(type){
      var _self = this,
      type = $('div#invoice-main input[name="type"]').val(),
      /*普通发票内容*/
      normal_title = $('div#invoice-main input[name="normal_title"]').val(),
      normal_content = $('div#invoice-main input[name="normal_content"]').val(),
      /*电子发票内容*/
      ele_title = $('div#invoice-main input[name="ele_title"]').val(),
      ele_content = $('div#invoice-main input[name="ele_content"]').val(),
      /*增值税发票内容*/
      unit = $('div#invoice-main input[name="unit"]').val(),
      code = $('div#invoice-main input[name="code"]').val(),
      address = $('div#invoice-main input[name="address"]').val(),
      tel = $('div#invoice-main input[name="tel"]').val(),
      bank = $('div#invoice-main input[name="bank"]').val(),
      account = $('div#invoice-main input[name="account"]').val(),
      inc_content = $('div#invoice-main input[name="inc_content"]').val();
      
      if(type == '' || typeof type=='undefined'){
          $.ui.alert('请选择类型');
          return false;
      }
      
      /*普通发票内容*/
      if(type == '1'){
          if(normal_title == ''){
              $.ui.alert('请填写发票title');
              return false;
          }
          if(normal_content == ''){
              $.ui.alert('请选择发票内容');
              return false;
          }
      }
      /*电子发票内容检测*/
      if(type == '2'){
          if(ele_title == ''){
              $.ui.alert('请填写发票title');
              return false;
          }
          if(ele_content == ''){
              $.ui.alert('请选择发票内容');
              return false;
          }
      }
      /*电子发票内容检测*/
      if(type == '3'){
          if(unit == ''){
              $.ui.alert('请填写单位名称');
              return false;
          }
          if(code == ''){
              $.ui.alert('请填写纳税人识别码');
              return false;
          }
          if(address == ''){
              $.ui.alert('请填写注册地址');
              return false;
          }
          if(!common.regx.mobile.test(tel)){
              $.ui.alert('请填写正确的注册电话');
              return false;
          }
          if(bank == ''){
              $.ui.alert('请填写开户银行');
              return false;
          }
          if(account == ''){
              $.ui.alert('请填写银行账户');
              return false;
          }
          if(inc_content == ''){
              $.ui.alert('请选择发票内容');
              return false;
          }
      }

      return true;
    },
    /*提交发票信息*/
    submit: function(){
      var data = {
        type: $('div#invoice-main input[name="type"]').val(),
        normal_title : $('div#invoice-main input[name="normal_title"]').val(),
        normal_content : $('div#invoice-main input[name="normal_content"]').val(),
        
        ele_title : $('div#invoice-main input[name="ele_title"]').val(),
        ele_content : $('div#invoice-main input[name="ele_content"]').val(),
        
        /*增值税发票内容*/
        unit : $('div#invoice-main input[name="unit"]').val(),
        code : $('div#invoice-main input[name="code"]').val(),
        address : $('div#invoice-main input[name="address"]').val(),
        tel : $('div#invoice-main input[name="tel"]').val(),
        bank : $('div#invoice-main input[name="bank"]').val(),
        account : $('div#invoice-main input[name="account"]').val(),
        inc_content : $('div#invoice-main input[name="inc_content"]').val()
        
      }
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: $.U('Invoice/save'),
        data: data,
        success: function(res){
          if(res.status === 1){
            $('#J_load_box').hide();
            $.ui.hideBackdrop();
            $.ui.alert('恭喜，操作成功！');
            $('#invoice_title').val(res.rtn);
            $('div#invoice_html .col-xs-3').html(res.html);
          }else{
              $.ui.alert(res.info);
          }
        }
      });
    },
  }

  module.exports = invoice;
});
/**
 * 收货地址模块：添加、编辑、删除收货地址
 */
define('module/receiver', function(require, exports, module){
  'use strict';

  var common = require('common');
  require('jquery.loadtype');

  var receiver = {
    /*收货信息初始化*/
    init: function(){
      var _self = this;
      _self.addAddress.init();
      $('.J_add_address').on('click', function(){
        $('#J_address_edit_box').show();
        $.ui.showBackdrop();
      });
      $('#J_edit_address_ok').on('click', function(){
        if(_self.validation()){
          _self.submitAddress();
          $('#J_edit_address_ok').hasClass('disabled') || $('#J_edit_address_ok').addClass('disabled');
        }
      });
    },
    /*添加收货地址*/
    addAddress: {
      strLen: function(a){
        return a.replace(/[^\x00-\xff]/g, '**').length
      },
      /*删除收货地址*/
      delAddress: function(e){
        var item = e.closest('.J_address_item');
        var id = e.data('id');
        $.ajax({
          type: 'POST',
          url: $.U('Receiver/remove', 'id=' + id),
          dataType: 'json',
          success: function(res){
            if(res.status == 1){
              item.fadeOut(500);
            }else{
              $.ui.error(res.msg);
            }
          }
        })
      },
      /*编辑收货地址*/
      editAddress: function(e){
        var _self = this,
            item = e.closest('.address-item'),
            id = item.data('id'),
            editbox = $('#J_address_edit_box'),
            province_id = item.data('province'),
            district_id = item.data('district'),
            city_id = item.data('city');
        _self.areaSelect(province_id, district_id, city_id);
        editbox.find('#J_address_id').val(id);
        editbox.find('#J_input_name').val(item.data('name'));
        editbox.find('#J_input_mobile').val(item.data('mobile'));
        editbox.find('#J_input_area').val(item.data('area'));
        editbox.find('#J_input_address').val(item.data('address'));
        editbox.find('#J_input_zipcode').val(item.data('zipcode'));
        editbox.find('#J_is_default').prop('checked', (item.data('is_default') == 1) ? true : false);
        editbox.find('#J_edit_address_ok').unbind().on('click', function(e){
          if(e && e.preventDefault){
            e.preventDefault();
          }else{
            window.event.returnValue = false;
          }
          if(_self.validation()){
            _self.submitAddress();
            $('#J_edit_address_ok').hasClass('disabled') || $('#J_edit_address_ok').addClass('disabled');
          }
        });
      },
      /*提交收货地址*/
      submitAddress: function(e){
        var _self = this;
        var is_default = $('#J_is_default:checked').val();
        if(is_default === undefined){
          is_default = 0;
        }
        var data = {
          id: $('#J_address_id').val(),
          name: $('#J_input_name').val(),
          mobile: $('#J_input_mobile').val(),
          address: $('#J_input_address').val(),
          zipcode: $('#J_input_zipcode').val(),
          province: $('#J_address_area select[name="province"]').val(),
          district: $('#J_address_area select[name="district"]').val(),
          city: $('#J_address_area select[name="city"]').val(),
          is_default: is_default
        }
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: $.U('Receiver/update'),
          data: data,
          success: function(res){
            if(res.status === 1){
              $('#J_edit_address_ok').removeClass('disabled');
              _self.resetForm();
              $.ui.success('恭喜，操作成功！');
              common.reload();
            }
          }
        });
      },
      /*收货地址表单重置*/
      resetForm: function(){
        $('#J_input_name').val('');
        $('#J_input_mobile').val('');
        $('#J_input_address').val('');
        $('#J_input_zipcode').val('');
        $('#J_address_area select[name="province"]').find('option').eq(0).attr('selected', !0);
        $('#J_address_area select[name="district"]').find('option').eq(0).attr('selected', !0);
        $('#J_address_area select[name="city"]').find('option').eq(0).attr('selected', !0);
        $('#J_is_default').attr('checked', !1);
        $('.tipMsg').html('').hide();
        $('.J_address_item').removeClass('selected');
        $('#J_address_edit_box').css({
          top: 0,
          left: '20px'
        }).hide();
        $('#J_edit_address_ok').removeClass('disabled');
      },
      /*收货地址表单验证*/
      validation: function(){
        var _self = this,
        name = $('#J_input_name'),
        mobile = $('#J_input_mobile'),
        province = $('#J_address_area select[name="province"]'),
        district = $('#J_address_area select[name="district"]'),
        city = $('#J_address_area select[name="city"]'),
        address = $('#J_input_address'),
        zipcode = $('#J_input_zipcode'),
        regx_name = common.regx.name,
        regx_number = common.regx.number,
        regx_zipcode = common.regx.zipcode,
        regx_mobile = common.regx.mobile;

        if(name.val() == ''){
          _self.setMsg(name, '请您填写收货人姓名');
          return false;
        }else if(name.val().length < 2){
          _self.setMsg(name, '收货人姓名太短 (最小值为 2 个中文字)');
          return false;
        }else if(!regx_name.test(name.val())){
          _self.setMsg(name, '收货人姓名不正确');
          return false;
        }else{
          _self.setMsg(name, '');
        }

        if(!regx_mobile.test(mobile.val())){
          _self.setMsg(mobile, '手机号格式不正确');
          return false;
        }else{
          _self.setMsg(mobile, '');
        }

        if(province.val() == ''){
          province.focus();
          return false;
        }

        if(district.val() == ''){
          district.focus();
          return false;
        }

        if(city.val() == ''){
          city.focus();
          return false;
        }

        if(!(address.val().length >= 5 && address.val().length <= 32)){
          _self.setMsg(address, '详细地址长度不对，最小为 5 个字，最大32个字');
          return false;
        }else{
          _self.setMsg(address, '');
        }

        return true;
      },
      /*提示信息*/
      setMsg: function(item, msg){
        if(item){
          item.focus();
        }
        var tips = item.closest('.item');
        (item && msg) ? tips.find('.tipMsg').html(msg).show() : tips.find('.tipMsg').html('').hide();
      },
      areaSelect: function(province_id, district_id, city_id){
        $('#J_address_area').loadtype({
          type: 'area',
          name1: 'province',
          name2: 'district',
          name3: 'city',
          value1: province_id,
          value2: district_id,
          value3: city_id
        });
      },
      /*添加模块初始化*/
      init: function(){
        var _self = this;
        $('#J_edit_address_cancel').unbind().on('click', function(){
          $('#J_address_edit_box').hide();
          _self.resetForm();
          $.ui.hideBackdrop();
        });

        $('.J_edit_address, .J_add_address').on('click', function(e){
          if(e && e.preventDefault){
            e.preventDefault();
          }else{
            window.event.returnValue = false;
          }

          $.ui.showBackdrop();

          $('#J_address_edit_box').css({
            top: $(this).closest('.address-item').position().top,
            left: $(this).closest('.address-item').position().left + 5 + 'px'
          }).show();
          _self.editAddress($(this));
        });

        $('.J_del_address').on('click', function(e){
          if(e && e.preventDefault){
            e.preventDefault();
          }else{
            window.event.returnValue = false;
          }
          if(confirm('确定删除该地址吗？')){
            _self.delAddress($(this));
          }
        });
      }
    }
  }

  module.exports = receiver;
});
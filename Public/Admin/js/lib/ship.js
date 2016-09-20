/**
 * 发货单模块
 * @author Max.Yu <max@winhu.com>
 */
var ship = {
  
  //发货-
  add: function(){
    //快递公司自定义处理
    $('.J_diy_delivery').click(function(){
      var sel = $('select[name="delivery_name"]');
      var delivery = prompt("快递公司名称：", sel.find('option:selected').attr('value'));
      if($.trim(delivery) === ''){
        return false;
      }
      sel.find('option').each(function(){
        if($(this).attr('value') === delivery){
          sel.val(delivery);
          return true;
        }
      });
      $('<option value="'+ delivery +'">'+ delivery +'</option>').appendTo(sel);
      sel.val(delivery);
    });
  }
}
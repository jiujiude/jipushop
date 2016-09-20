/**
 *分销模块：店铺
 */
define('module/shop', function (require, exports, module){
  'use strict';

  var shop = {
    /*选择商品页面*/
    selectItem: function (){
      $(document).on('tap', '.Z_item_add', function (){
        var _obj = $(this),
                itemid = _obj.data('itemid');

        $.ajax({
          type: 'POST',
          url: $.U('Shop/addItem'),
          dataType: 'json',
          data: {itemid: itemid},
          success: function (res){
            if(res.status === 1){
              $.ui.success(res.info);
              _obj.addClass('added').removeClass('Z_item_add').html('<i class="icon icon-check"></i>');
            }else{
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*删除已选商品*/
    removeItem: function (){
      $(document).on('tap', '.Z_item_remove', function (){
        var _self = this,
                itemid = $(this).data('itemid');
        var doRemoveItem = function (){
          if(!itemid){
            return false;
          }else{
            $.ajax({
              type: 'POST',
              url: $.U('Shop/removeItem'),
              data: {itemid: itemid},
              dataType: 'json',
              success: function (res){
                if(res.status === 1){
                  $.ui.success(res.info);
                  $(_self).parents('.Z_item_list').fadeOut();
                }else{
                  $.ui.error(res.info);
                }
              }
            });
          }
        }

        $.ui.confirm('确定删除该商品？', doRemoveItem);
      });
    },
    /*统计图表展示*/
    statChart: function (id, data){
      require('echarts');
      
      var myChart = echarts.init(document.getElementById(id));
      var option = {
        tooltip: {
          trigger: 'axis'
        },
        grid: {
          x: 40,
          x2: 30,
          y: 30,
        },
        calculable: true,
        xAxis: [
          {
            type: 'category',
            boundaryGap: false,
            data: data.labels
          }
        ],
        yAxis: [
          {
            type: 'value',
            axisLabel: {
              formatter: '{value}'
            }
          }
        ],
        series: [
          {
            name: '',
            type: 'line',
            data: data.datas
          }
        ]
      };

      myChart.setOption(option);

    }
  };

  module.exports = shop;
});
/**
 *分销模块：店铺
 */
define('module/shop', function (require, exports, module){
  'use strict';

  var shop = {
    /*选择商品页面*/
    selectItem: function(){
      $(document).on('click', '.J_item_add', function(){
        var _obj = $(this),
            itemid = _obj.data('itemid');

        $.ajax({
          type: 'POST',
          url: $.U('Shop/addItem'),
          dataType: 'json',
          data: {itemid: itemid},
          success: function (res){
            if(res.status === 1){
              $.ui.alert(res.info);
              _obj.addClass('disabled').removeClass('btn-positive');
            }else{
              $.ui.error(res.info);
            }
          }
        });
      });
    },
    /*删除已选商品*/
    removeItem: function(){
      $('.J_item_remove').on('click', function(){
        var _self = this,
            itemid = $(this).data('itemid');
        var doRemoveItem = function(){
          if(!itemid){
            return false;
          }else{
            $.ajax({
              type: 'POST',
              url: $.U('Shop/removeItem'),
              data: {itemid: itemid},
              dataType: 'json',
              success: function(res){
                if(res.status === 1){
                  $.ui.success(res.info);
                  $(_self).parents('.J_item_list').fadeOut();
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
    /*加入或取消分销商品*/
    changeItem:function(){
      $('.shop_item_btn').on('click',function(){
        if($(this).attr('class').indexOf('J_item_remove')>0){
          var _obj = $(this),
              itemid = _obj.data('itemid');

          $.ajax({
            type: 'POST',
            url: $.U('Shop/removeItem'),
            dataType: 'json',
            data: {itemid: itemid},
            success: function (res){
              if(res.status === 1){
                $.ui.alert(res.info);
                _obj.addClass('J_item_add').removeClass('J_item_remove');
              }else{
                $.ui.error(res.info);
              }
            }
          });
        };
        if($(this).attr('class').indexOf('J_item_add')>0){
          var _obj = $(this),
              itemid = _obj.data('itemid');

          $.ajax({
            type: 'POST',
            url: $.U('Shop/addItem'),
            dataType: 'json',
            data: {itemid: itemid},
            success: function (res){
              if(res.status === 1){
                $.ui.alert(res.info);
                _obj.addClass('J_item_remove').removeClass('J_item_add');
              }else{
                $.ui.error(res.info);
              }
            }
          });
        };
      })
    },
    /*统计图表展示*/
    statChart: function (id, data){
      require('echarts');
      
      var myChart = echarts.init(document.getElementById(id));
      var option = {
        title: {
          text: '',
          subtext: ''
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            crossStyle: {
              type: 'dashed'
            }
          }
        },
        legend: {
          data: ['']
        },
        grid: {
          x: 50,
          x2: 20,
          y: 30,
        },
        toolbox: {
          show: false
        },
        calculable: true,
        xAxis: [
          {
            type: 'category',
            boundaryGap: false,
            data: data.labels,
            splitLine : {
              show: true,
              lineStyle: {
                  color: '#DDD',
                  type: 'dotted',
                  width: 1
              }
            },
          }
        ],
        yAxis: [
          {
            type: 'value',
            axisLabel: {
              formatter: '{value}'
            },
            splitLine : {
              show: true,
              lineStyle: {
                  color: '#DDD',
                  type: 'dotted',
                  width: 1
              }
            },
          }
        ],
        series: [
          {
            name: '最高',
            type: 'line',
            data: data.datas
          }
        ]
      };

      myChart.setOption(option);

    }
  }

  module.exports = shop;
});
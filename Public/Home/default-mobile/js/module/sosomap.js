/*腾讯地图模块*/
define('module/sosomap', function(require, exports, module){

  'use strict';

  var sosomap = {
    id: '',
    height: 0,
    map: '',
    center: '',
    marker: '',
    start_marker: '',
    end_marker: '',
    dire: '',
    info_win: '',
    mapdata: '',
    init: function(id, mapdata){
      var _self = this, content, href;
      _self.mapdata = mapdata;
      _self.id = id;
      if(_self.height === 0){
        _self.height = $(window).height() - 95;
      }
      //初始化宽度高度
      $('#' + id).css({width: $('.Z_map_outer').width(), height: _self.height});
      //画地图
      _self.center = new qq.maps.LatLng(mapdata.lat, mapdata.lng);
      _self.map = new qq.maps.Map(document.getElementById(id), {
        center: _self.center,
        zoom: 14
      });
      //加标记
      _self.marker = new qq.maps.Marker({
        position: _self.center,
        map: _self.map
      });
      //添加信息窗口
      _self.info_win = new qq.maps.InfoWindow({
        map: _self.map
      });
      _self.info_win.open();
      content = '<strong>' + mapdata.name + '</strong><br/>地址：' + mapdata.city.replace('/', '') + mapdata.address + '<br/>';
      content += '电话：' + mapdata.tel;
      _self.info_win.setContent(content);
      _self.info_win.setPosition(_self.map.getCenter());
      _self.hideTencentLogo();
      //检测是否含有街景
      href = window.location.href;
      if(href.indexOf('pano') === -1){
        var pano_url = href + (href.indexOf('?') > -1 ? '&' : '?') + 'pano=1&nav=0';
        _self.has_pano(function(result){
          $('<a></a>').attr('id', 'show-pano-a')
                  .attr('href', pano_url)
                  .addClass('iconfont icon-ioseyeoutline map-showpano').text('查看街景').appendTo('#' + _self.id);
        });
      }
    },
    //隐藏地图左下角logo
    hideTencentLogo: function(){
      var _self = this, e_intval;
      //隐藏左下角LOGO
      e_intval = setInterval(function(){
        if($('#' + _self.id + '>div>div').length > 2){
          $('#' + _self.id + '>div>div').eq(1).hide();
          $('#' + _self.id + '>div>div').eq(2).hide();
          clearInterval(e_intval);
        }
      }, 30);
    },
    //导航服务
    directionsService: function(){
      var _self = this, route_lines, route_steps, directions_routes;
      $.ui.loading('正在初始化导航…', 30);
      var directions_placemarks = route_lines = route_steps = [];
      _self.dire = new qq.maps.DrivingService({
        complete: function(response){
          var start = response.detail.start,
                  end = response.detail.end;
          var anchor = new qq.maps.Point(6, 6),
                  size = new qq.maps.Size(24, 36),
                  start_icon = new qq.maps.MarkerImage(C.IMG + '/hotel-map-busmarker.png', size, new qq.maps.Point(0, 0), anchor),
                  end_icon = new qq.maps.MarkerImage(C.IMG + '/hotel-map-busmarker.png', size, new qq.maps.Point(24, 0), anchor);

          sosomap.start_marker && sosomap.start_marker.setMap(null);
          sosomap.end_marker && sosomap.end_marker.setMap(null);
          //clearOverlay(route_lines);
          sosomap.start_marker = new qq.maps.Marker({
            icon: start_icon,
            position: start.latLng,
            map: _self.map,
            zIndex: 1
          });
          sosomap.end_marker = new qq.maps.Marker({
            icon: end_icon,
            position: end.latLng,
            map: _self.map,
            zIndex: 1
          });
          directions_routes = response.detail.routes;
          var routes_desc = [];
          //所有可选路线方案
          for(var i = 0; i < directions_routes.length; i++){
            var route = directions_routes[i],
                    legs = route;
            //调整地图窗口显示所有路线    
            _self.map.fitBounds(response.detail.bounds);

            //所有路程信息            
            var steps = legs.steps, polyline = '';
            route_steps = steps;
            polyline = new qq.maps.Polyline(
                    {
                      path: route.path,
                      strokeColor: '#3893F9',
                      strokeWeight: 6,
                      map: _self.map
                    }
            );
            route_lines.push(polyline);
          }
          //所有路段信息
          for(var k = 0; k < steps.length; k++){
            var step = steps[k];
            //路段途经地标
            directions_placemarks.push(step.placemarks);
            //转向
            var turning = step.turning, img_position, mg_position;
            ;
            switch(turning.text){
              case '左转':
                img_position = '0px 0px';
                break;
              case '右转':
                img_position = '-19px 0px';
                break;
              case '直行':
                img_position = '-38px 0px';
                break;
              case '偏左转':
              case '靠左':
                img_position = '-57px 0px';
                break;
              case '偏右转':
              case '靠右':
                img_position = '-76px 0px';
                break;
              case '左转调头':
                img_position = '-95px 0px';
                break;
              default:
                mg_position = '';
                break;
            }
            var turning_img = '&nbsp;&nbsp;<span' +
                    ' style="margin-bottom: -4px;' +
                    'display:inline-block;background:' +
                    'url(' + C.IMG + '/hotel-map-turning.png) no-repeat ' +
                    img_position + ';width:19px;height:' +
                    '19px"></span>&nbsp;';
            routes_desc.push('<div class="map-line item-text-wrap"><p><b>' + (k + 1) +
                    '.</b>' + turning_img + step.instructions + "</p></div>");
          }
          //方案文本描述
          var pos_start = '<div class="map-line map-divider"><p id="my-pos">正在获取...</p></div>';
          var pos_end = '<div class="map-line map-divider"><p id="to-pos">正在获取...</p></div>';
          $("#routes").html(pos_start + routes_desc.join('') + pos_end);
          _self.getAddress(start.latLng, $('#my-pos'), '<strong>您的位置：</strong>');
          _self.getAddress(end.latLng, $('#to-pos'), '<strong>目的地：</strong>', '（' + _self.mapdata.name + '）');
          //console.log($.ui.box);
          $.ui.message.close();
        }
      });
    },
    //导航
    nav: function(id, mapdata){
      var _self = this, start, end;
      _self.map = '';
      _self.center = '';
      $('#' + id).html('');
      _self.height = $(window).height() * 0.52;
      _self.init(id, mapdata);
      _self.info_win.close();
      _self.marker.setMap(null);
      //初始化导航服务
      _self.directionsService();
      _self.dire.setLocation("北京");
      //设置方案
      _self.dire.setPolicy(qq.maps.DrivingPolicy['LEAST_DISTANCE']);
      //导航
      start = new qq.maps.LatLng(mapdata._lat, mapdata._lng);
      end = new qq.maps.LatLng(mapdata.lat, mapdata.lng);
      _self.dire.search(start, end);
      //修改标题
      $('h1.title').text('地图导航');
    },
    //根据坐标获取地址
    getAddress: function(latlng, obj, preAdd, lastAdd){
      var geocoder, address;
      geocoder = new qq.maps.Geocoder({
        complete: function(result){
          address = result.detail.address.replace('中国', '');
          obj.html((preAdd ? preAdd : '') + address + (lastAdd ? lastAdd : ''));
        }
      });
      geocoder.getAddress(latlng);
    },
    //获取坐标位置
    getLatLng: function(fun, flush){
      var wechat = require('wechat'), latlng;
      latlng = sessionStorage.getItem('lat_lng');
      if(latlng === null || flush){
        //执行微信JS获取坐标
        wechat.getLatLng(fun);
      }else{
        _nowlat = latlng.split(',')[0];
        _nowlng = latlng.split(',')[1];
        if(typeof fun === 'function'){
          fun(_nowlat, _nowlng);
        }
      }
    },
    //检测是否含有街景
    has_pano: function(fun){
      var _self = this;
      var panoService = new qq.maps.PanoramaService();
      panoService.getPano(_self.center, 1000, function(result){
        if(result){

          if(typeof fun === 'function'){
            fun(result);
          }
        }else{
          $.ui.loading.tip('此处无街景');
        }
      });
    },
    //获取全景
    pano: function(){
      var _self = this;
      _self.has_pano(function(result){
        $('#' + _self.id).html('');
        $('#show-pano-a').remove();
        var pano = new qq.maps.Panorama(document.getElementById(_self.id), {
          pano: result.svid,
          disableMove: false,
          disableFullScreen: false,
          zoom: 1,
          pov: {
            heading: 20,
            pitch: 15
          }
        });
      });
    }
  };

  module.exports = sosomap; //导出模块
});
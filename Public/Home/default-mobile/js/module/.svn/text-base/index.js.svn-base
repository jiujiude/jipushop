define(function(require){

  'use strict';

  //require('slider');
  require('swipeSlide');
  
  $('#slider').swipeSlide({
      continuousScroll:true,
      speed : 3000,
      transitionType : 'cubic-bezier(0.22, 0.69, 0.72, 0.88)',
      firstCallback : function(i,sum,me){
          me.find('.dot').children().first().addClass('cur');
      },
      callback : function(i,sum,me){
          me.find('.dot').children().eq(i).addClass('cur').siblings().removeClass('cur');
      }
  });
  
  $('#small_slider').swipeSlide({
      continuousScroll:true,
      speed : 3000,
      transitionType : 'cubic-bezier(0.22, 0.69, 0.72, 0.88)',
      firstCallback : function(i,sum,me){
          me.find('.dot').children().first().addClass('cur');
      },
      callback : function(i,sum,me){
          me.find('.dot').children().eq(i).addClass('cur').siblings().removeClass('cur');
      }
  });
});
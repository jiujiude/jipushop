define('module/receiver', function (require, exports, module){

  'use strict';
  
  require('zepto.loadtype');

  var receiver = {
    areaType: function(aObject){
      $(aObject.obj).Loadtype({
        type: 'Area',
        name1: 'province',
        name2: 'district',
        name3: 'city',
        value1: aObject.value1,
        value2: aObject.value2,
        value3: aObject.value3
      });
    }
  };
  
  module.exports = receiver;
});
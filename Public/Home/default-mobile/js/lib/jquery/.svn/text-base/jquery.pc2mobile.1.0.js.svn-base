/**
* PC端无模板时调用手机端样式-js控制器
* @version 2015031910
* @author Max.Yu <max@winhu.com>
*/
window.onerror=function(){return true;};
$(function(){
  path = pathinit = window.location.href;
  if(window.parent !== window.self){
    return ;
  }
  //console.log(window.parent);
  if(path.indexOf('?')>-1){
    path += '&showmobile=1';
  }else{
    path += '?showmobile=1';
  }
  var margin_left = function(){
    return ($('body').width()-415)/2;
  };
  var iframeHtml = '<iframe src="'+ path +'" class="iframe_css">';
  $('body').html(iframeHtml);
  $('body').css({
    background:'#e7e8eb',
    position: 'relative',
    width: '100%'
  });
  $('.iframe_css').css({
    width: '415px', 
    height: '695px', 
    marginLeft: margin_left() +'px', 
    marginTop: '20px',
    border: '18px solid #F2F2F2',
    borderRadius: '20px', 
    boxShadow: '3px 0px 5px rgba(0,0,0,.2)',
    background: '#FFFFFF',
  });
  var qrHtml = '<div class="qrCode"></div>';
  $(qrHtml).appendTo('body');
  url = C.APP + '/Api/qrcode.html?data=' + encodeURIComponent(pathinit);

  $('.qrCode').html('<p><img src="' + url +'"></p><span>微信扫一扫<br>体验最佳效果</span>').css({
    display: 'inline-block',
    position: 'absolute',
    top: '20px',
    left: (margin_left() + 465) +'px',
    padding: '5px 8px 10px',
    background: '#FFFFFF',
    border: '1px solid #DDDDDD',
    borderRadius: '3px', 
  });
  $('.qrCode p').css({
    marginBottom: 0,
  });
  $('.qrCode span').css({
    textAlign: 'center',
    color: '#333333',
    display:'block',
  });
  $(window).resize(function(){
    $('.iframe_css').css('marginLeft', margin_left() +'px');
    $('.qrCode').css('left', (margin_left()+465) +'px');
  });
});
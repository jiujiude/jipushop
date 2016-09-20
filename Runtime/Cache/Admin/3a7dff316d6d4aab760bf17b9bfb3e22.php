<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo ($meta_title); ?>|Jipu管理平台</title>
  <meta property="formhash" content="<?php echo create_form_hash();?>">
  <link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
  <link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css?15031711" media="all">
  <link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css?15031711" media="all">
  <link rel="stylesheet" type="text/css" href="/Public/Admin/css/module.css?15031711">
  <link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css?15031711" media="all">
  <link rel="stylesheet" type="text/css" href="/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css?15031711" media="all">
  <!--[if lt IE 9]>
  <script type="text/javascript" src="/Public/Admin/js/jquery/jquery-1.10.2.min.js"></script>
  <![endif]--><!--[if gte IE 9]><!-->
  <script type="text/javascript" src="/Public/Admin/js/jquery/jquery-2.0.3.min.js"></script>
  <script type="text/javascript" src="/Public/Admin/js/jquery/jquery.mousewheel.js"></script>
  <!--<![endif]-->
  
</head>
<body>
  <!-- 头部 -->
  <div class="header">
    <!-- Logo -->
    <a href="<?php echo U('index');?>" class="logo"></a>
    <!-- /Logo -->

    <!-- 主导航 -->
    <ul class="main-nav">
      <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    <!-- /主导航 -->

    <!-- 用户栏 -->
    <div class="user-bar">
      <a href="javascript:;" class="user-entrance">&nbsp;</a>
      <ul class="nav-list user-menu hidden">
        <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em></li>
        <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
        <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
        <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
      </ul>
    </div>

    <!-- 导航扩展 -->
    <ul class="main-sub">
      <a href="<?php echo U('/');?>" target="_blank">返回前台</a>
    </ul>
  </div>
  <!-- /头部 -->

  <!-- 边栏 -->
  <div class="sidebar">
    <!-- 子导航 -->
    
      <div id="subnav" class="subnav">
        <?php if(!empty($_extra_menu)): ?>
          <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>

        <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
          <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
            <ul class="side-sub-menu">
              <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                  <a class="item" href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul><?php endif; ?>
          <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
      </div>
    
    <!-- /子导航 -->
  </div>
  <!-- /边栏 -->
  <!-- 内容区 -->
  <div id="main-content">
    <div id="top-alert" class="fixed alert alert-error" style="display: none;">
      <button class="close fixed" style="margin-top: 4px;">&times;</button>
      <div class="alert-content">这是内容</div>
    </div>
    <div id="main" class="main">
      
      <!-- nav -->
      <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
        <span>您的位置:</span>
        <?php $i = '1'; ?>
        <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
          <?php else: ?>
          <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
          <?php $i = $i+1; endforeach; endif; ?>
      </div><?php endif; ?>
      <!-- nav -->
      

      
  <!-- 标题 -->
  <div class="main-title">
    <h2>营销工具</h2>
  </div>

  <div class="promote-list">
    <ul>
       <li class="danger-dark">
        <a href="<?php echo U('RedPackage/index');?>">
          <i class="icon icon-redpacket"></i>
          <h4 class="title">红包</h4>
        </a>
      </li> 
      <li class="info">
        <a href="<?php echo U('Advertise/index');?>">
          <i class="icon icon-advertise"></i>
          <h4 class="title">广告</h4>
        </a>
      </li>
      <li class="warning">
        <a href="<?php echo U('Coupon/index');?>">
          <i class="icon icon-coupon"></i>
          <h4 class="title">优惠券</h4>
        </a>
      </li>
      <!-- <li class="success">
        <a href="<?php echo U('Card/index');?>">
          <i class="icon icon-vipcard"></i>
          <h4 class="title">礼品卡</h4>
        </a>
      </li>-->
      <li class="success">
        <a href="<?php echo U('Invite/index');?>">
          <i class="icon icon-invite"></i>
          <h4 class="title">邀请</h4>
        </a>
      </li>
      <li class="danger">
        <a href="<?php echo U('Activity/index');?>">
          <i class="icon icon-activity"></i>
          <h4 class="title">专题</h4>
        </a>
      </li>
      <li class="info">
        <a href="<?php echo U('Manjian/index');?>">
          <i class="icon icon-manjian"></i>
          <h4 class="title">满减</h4>
        </a>
      </li>
      <li class="success">
        <a href="<?php echo U('SecondPieces/index');?>">
          <i class="icon icon-banjia"></i>
          <h4 class="title">第二件折扣</h4>
        </a>
      </li>
      <li class="warning">
        <a href="<?php echo U('BuySend/index');?>">
          <i class="icon icon-zeng"></i>
          <h4 class="title">买送</h4>
        </a>
      </li>
      <li class="info">
        <a href="<?php echo U('Seckill/index');?>">
          <i class="icon icon-activity"></i>
          <h4 class="title">秒杀</h4>
        </a>
      </li>
      <li class="danger">
        <a href="<?php echo U('Fugou/index');?>">
          <i class="icon icon-manjian"></i>
          <h4 class="title">老客户立减</h4>
        </a>
      </li>
    </ul>
  </div>

    </div>
    <div class="cont-ft">
      <div class="copyright">
        <div class="fl">感谢您使用<a href="http://www.jipushop.com" target="_blank">JipuShop</a>商城系统</div>
        <div class="fr">V<?php echo (ONETHINK_VERSION); ?></div>
      </div>
    </div>
  </div>
  <!-- /内容区 -->
  <script type="text/javascript">
  (function(){
    var Core = window.Core = {
      'ROOT': '', // 当前网站地址
      'APP': '', // 当前项目地址
      'PUBLIC': '/Public', // 项目公共目录地址
      'IMG': '/Public/Admin/images', // 项目图片地址
      'DEEP': "<?php echo C('URL_PATHINFO_DEPR');?>", // PATHINFO分割符
      'MODEL': ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
      'VAR': ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
      'UID': '<?php echo is_login();?>'
    };
    var UI = window.UI = {};
  })();
  </script>
  <script type="text/javascript" src="/Public/Admin/js/core.js"></script>
  <script type="text/javascript" src="/Public/Admin/js/lib/ui.js"></script>
  <script type="text/javascript" src="/Public/Admin/js/lib/common.js"></script>
  <script type="text/javascript" src="/Public/Admin/js/lib/module.js"></script>
  <script type="text/javascript">
    +function(){
      var $window = $(window), $subnav = $("#subnav"), url;
      $window.resize(function(){
        $("#main").css("min-height", $window.height() - 130);
      }).resize();

      /* 左边菜单高亮 */
      url = window.location.pathname + window.location.search;
      url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
      $subnav.find("a[href='" + url + "']").parent().addClass("current");

      /* 左边菜单显示收起 */
      $("#subnav").on("click", "h3", function(){
        var $this = $(this);
        $this.find(".icon").toggleClass("icon-fold");
        $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
            prev("h3").find("i").addClass("icon-fold").end().end().hide();
      });

      $("#subnav h3 a").click(function(e){e.stopPropagation()});

      /* 头部管理员菜单 */
      $(".user-bar").mouseenter(function(){
        var userMenu = $(this).children(".user-menu ");
        userMenu.removeClass("hidden");
        clearTimeout(userMenu.data("timeout"));
      }).mouseleave(function(){
        var userMenu = $(this).children(".user-menu");
        userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
        userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
      });

      /* 表单获取焦点变色 */
      $("form").on("focus", "input", function(){
        $(this).addClass('focus');
      }).on("blur","input",function(){
            $(this).removeClass('focus');
          });
      $("form").on("focus", "textarea", function(){
        $(this).closest('label').addClass('focus');
      }).on("blur","textarea",function(){
        $(this).closest('label').removeClass('focus');
      });

      // 导航栏超出窗口高度后的模拟滚动条
      var sHeight = $(".sidebar").height();
      var subHeight  = $(".subnav").height();
      var diff = subHeight - sHeight; //250
      var sub = $(".subnav");
      if(diff > 0){
        $(window).mousewheel(function(event, delta){
          if(delta>0){
            if(parseInt(sub.css('marginTop'))>-10){
              sub.css('marginTop','0px');
            }else{
              sub.css('marginTop','+='+10);
            }
          }else{
            if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
              sub.css('marginTop','-'+(diff-10));
            }else{
              sub.css('marginTop','-='+10);
            }
          }
        });
      }
    }();
  </script>
  
  <script type="text/javascript">
    //导航高亮
    highlight_subnav("<?php echo U('Promote/index');?>");
  </script>

</body>
</html>
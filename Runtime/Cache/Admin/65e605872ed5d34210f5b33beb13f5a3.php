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
      

      
  <!-- 标题栏 -->
  <div class="main-title">
    <h2>用户列表(<?php echo ($_total); ?>)</h2>
  </div>
  <div class="cf">
    <div class="fl">
      <a class="btn" href="<?php echo U('User/add');?>">新 增</a>
      <button class="btn ajax-post" url="<?php echo U('User/changeStatus',array('method'=>'resumeUser'));?>" target-form="ids">启 用</button>
      <button class="btn ajax-post" url="<?php echo U('User/changeStatus',array('method'=>'forbidUser'));?>" target-form="ids">禁 用</button>
      <button class="btn ajax-post confirm" url="<?php echo U('User/changeStatus',array('method'=>'deleteUser'));?>" target-form="ids">删 除</button>
    </div>

    <!-- 高级搜索 -->
    <div class="search-form fr cf">
      <div class="sleft">
        <input type="text" name="keyword" class="search-input" value="<?php echo I('get.keyword');?>" placeholder="ID、用户名、昵称、手机">
        <a class="sch-btn" href="javascript:;" id="search" url="<?php echo U('index');?>"><i class="btn-search"></i></a>
      </div>
    </div>
  </div>
    <!-- 数据列表 -->
  <div class="data-table table-striped">
    <table>
      <thead>
        <tr>
          <th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
          <th><a href="<?php echo U('index?_field=uid&_order='.$_order);?>" title="按UID排序">UID<?php echo ($_order_icon["uid"]); echo ($_order_icon_show); ?></a></th>
          <!-- <th>用户名</th>
          <th>会员等级</th>-->
          <th width="40">头像</th>
          <th>昵称</th>
          <th>手机</th>
          <th><a href="<?php echo U('index?_field=finance&_order='.$_order);?>" title="按账户余额排序">账户余额<?php echo ($_order_icon["finance"]); echo ($_order_icon_show); ?></a></th>
          <th><a href="<?php echo U('index?_field=score&_order='.$_order);?>" title="按积分排序">积分<?php echo ($_order_icon["score"]); echo ($_order_icon_show); ?></a></th>
          <th>注册时间</th>
          <!-- <th>登录次数</th>
          <th><a href="<?php echo U('index?_field=last_login_time&_order='.$_order);?>" title="按最后登录时间排序">最后登录时间<?php echo ($_order_icon["last_login_time"]); echo ($_order_icon_show); ?></a></th>-->
          <!--<th>最后登录IP</th>-->
          <th>状态</th>
          <th width="180">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($_list)): if(is_array($_list)): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="status_trclass_<?php echo ($vo["status"]); ?>">
              <td><input class="ids" type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
              <td><?php echo ($vo["id"]); ?></td>
              <!-- <td>
                <a href="<?php echo U('User/view?id='.$vo['id']);?>"><?php echo ($vo["username"]); ?></a>
              </td>
              <td>
                <?php if($vo['group_id']): ?><a href="<?php echo U('UserGroup/edit?id='.$vo['group_id']);?>"><?php echo (get_group_name($vo["group_id"])); ?></a>
                <?php else: ?>
                  暂无会员等级<?php endif; ?>
              </td>-->
              <td>
                <?php if(!empty($vo["avatar"])): ?><img src="<?php echo ($vo["avatar"]); ?>" width="22" height="22" style="border-radius: 11px"><?php endif; ?>
              </td>
              <td>
                <a href="<?php echo U('User/view?id='.$vo['id']);?>" target="_blank"><?php echo (get_nickname($vo["id"])); ?></a>               
              </td>
              <td><a href="http://www.ip138.com:8080/search.asp?action=mobile&mobile=<?php echo ($vo["mobile"]); ?>" title="查看手机号码来源" target="_blank"><?php echo ($vo["mobile"]); ?></a></td>
              <td>
                <span class="text-danger"><?php echo ($vo["finance"]); ?></span> 元
                <a href="javascript:;" onclick="UI.load('<?php echo U('User/rechangeAdd?uid='.$vo['id']);?>');">[充/扣]</a>
                <!-- <?php if(($vo["finance"]) > "0"): ?><a href="<?php echo U('User/setValue?key=finance&value=0.00&id='.$vo['id']);?>" class="confirm ajax-get">[清零]</a><?php endif; ?>-->
              </td>
              <td>
                <span class="text-danger"><?php echo ($vo["score"]); ?></span> 积分
                <a href="javascript:;" onclick="UI.load('<?php echo U('User/scoreAdd?uid='.$vo['id']);?>');">[充/扣]</a>
                <!-- <?php if(($vo["score"]) > "0"): ?><a href="<?php echo U('User/setValue?key=score&value=0&id='.$vo['id']);?>" class="confirm ajax-get">[清零]</a><?php endif; ?>-->
              </td>
              <!-- <td><?php echo ($vo["login"]); ?></td>-->
              <!-- <td><span><?php echo (time_format($vo["last_login_time"])); ?></span></td>-->
              <!--<td><span><a href="http://www.ip138.com/ips138.asp?ip=<?php echo long2ip($vo['last_login_ip']);?>&action=2" title="查看IP地理位置" target="_blank"><?php echo long2ip($vo['last_login_ip']);?></a></span></td>-->
              <td><?php echo (time_format($vo["reg_time"])); ?></td>
             <td><?php echo ($vo["status_text"]); ?></td>
             <?php if($vo['id'] != 1){ ?>
              <td>
                <?php if(($vo["status"]) == "1"): ?><a href="<?php echo U('User/changeStatus?method=forbidUser&id='.$vo['id']);?>" class="ajax-get">[禁用]</a>
                <?php else: ?>
                  <a href="<?php echo U('User/changeStatus?method=resumeUser&id='.$vo['id']);?>" class="ajax-get">[启用]</a><?php endif; ?>
                <a href="javascript:;" onclick="UI.load('<?php echo U('User/edit?uid='.$vo['id']);?>');">[修改]</a>
                <a href="<?php echo U('AuthManager/group?uid='.$vo['id']);?>" class="authorize">[授权]</a>
                <a href="<?php echo U('User/changeStatus?method=deleteUser&id='.$vo['id']);?>" class="confirm ajax-get">[删除]</a>
              </td>
              <?php } ?>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        <?php else: ?>
          <tr><td colspan="12" class="text-center"> aOh! 暂时还没有内容! </td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="page">
    <?php echo ($_page); ?>
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
    //搜索功能
    $("#search").click(function(){
      var url = $(this).attr('url');
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if(url.indexOf('?')>0){
          url += '&' + query;
        }else{
          url += '?' + query;
        }
      window.location.href = url;
    });
    //回车搜索
    $(".search-input").keyup(function(e){
      if(e.keyCode === 13){
        $("#search").click();
        return false;
      }
    });
    //导航高亮
    highlight_subnav("<?php echo U('User/index');?>");
  </script>

</body>
</html>
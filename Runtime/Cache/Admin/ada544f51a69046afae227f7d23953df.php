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
    <h2>推广联盟管理</h2>
  </div>
  
  <div class="cf">
    <div class="fl">
     <!--  <a id="add-group" class="btn" href="<?php echo U('add');?>">新 增</a> -->
      <a url="<?php echo U('setStatus', array('status' => 1));?>" class="btn ajax-post" target-form="ids" >启 用</a>
      <a url="<?php echo U('setStatus', array('status' => 0));?>" class="btn ajax-post" target-form="ids" >禁 用</a>
      <button class="btn ajax-post confirm" target-form="ids" url="<?php echo U('setStatus', array('status' => -1));?>">删 除</button>
      <a class="btn" href="<?php echo U('userlist');?>">刷 新 <span style="font-size:12px;font-weight:normal">(清空搜索条件)</span></a>
    </div>
    
    <!-- 高级搜索 -->
    <form id="J_search_form" action="<?php echo U('userlist');?>" method="POST">
      <div class="search-form fr cf">
        <div class="fl">
         
          <div class="sleft">
           <!--  <input type="text" name="start_time" class="search-input w-100 date_select" value="<?php echo I('get.start_time');?>" placeholder="起始时间"/>
            <span class="fl line-h">至</span>-->
            <input type="text" name="keywords" class="search-input w-100 " value="" placeholder="用户名或联系电话"  style="width:150px;"/> 
            <a class="sch-btn" href="javascript:;" id="J_search"><i class="btn-search"></i></a>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- 数据列表 -->
  <div class="data-table table-striped">
    <table class="">
      <thead>
        <tr>
          <th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
          
          <th>所属用户</th>
          <!-- <th>名称</th> -->
    
          <th>联系方式</th>
          <th>一级代理</th>
          <th>二级代理</th>
          <th>三级代理</th>
          <th>订单总数</th>
          <th>分销金额</th>
          <th>待提取金额</th>
          <th>二维码</th>
          <th width="140" class="text-center">操作</th>
        </tr>
      </thead>
      <tbody>
      <?php if(!empty($lists)): if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="status_trclass_<?php echo ($vo["status"]); ?>">
            <td><input class="ids" type="checkbox" name="ids[]" value="<?php echo ($vo["id"]); ?>" /></td>
            
            <td>
              <a href="<?php echo U('User/view', array('id' => $vo['uid']));?>">
                <?php if(get_nickname($vo['uid']) != ''): echo (get_nickname($vo["uid"])); ?>
                <?php else: ?>
                  <?php echo (get_username($vo["uid"])); endif; ?>
              </a>
            </td>
            <!-- <td><a href="<?php echo U('edit?id='.$vo['id']);?>"><?php echo ($vo["link_name"]); ?></a> </td> -->
        
            <td><?php echo ($vo["link_mobile"]); ?></td>
            <td><a href="<?php echo U('agents?type=1&uid='.$vo['uid']);?>"><?php echo ($vo["one"]); ?></a></td>
            <td><a href="<?php echo U('agents?type=2&uid='.$vo['uid']);?>"><?php echo ($vo["two"]); ?></a></td>
            <td><a href="<?php echo U('agents?type=3&uid='.$vo['uid']);?>"><?php echo ($vo["three"]); ?></a></td>
             <td><?php echo ((isset($vo["orders"]) && ($vo["orders"] !== ""))?($vo["orders"]):0); ?></td>
             <td><?php echo ((isset($vo["money"]) && ($vo["money"] !== ""))?($vo["money"]):0); ?></td>
             <td><?php echo ((isset($vo["smoney"]) && ($vo["smoney"] !== ""))?($vo["smoney"]):0); ?></td>
            <td><a href="javascript:;" onclick="UI.load('<?php echo U('detail',array('qrcode_url'=>md5($vo['qrcode_url'])));?>','扫描二维码查看')">[查看]</a></td>
            <td class="text-center">
              <?php if(($vo["status"]) == "1"): ?><a href="<?php echo U('setStatus?status=0&ids='.$vo['id']);?>" class="ajax-get">[禁用]</a>
              <?php else: ?>
                <a href="<?php echo U('setStatus?status=1&ids='.$vo['id']);?>" class="ajax-get">[启用]</a><?php endif; ?>
             <!--  <a href="<?php echo U('edit?id='.$vo['id']);?>">[编辑]</a> -->
              <a href="<?php echo U('setStatus?status=-1&ids='.$vo['id']);?>" class="confirm ajax-get">[删除]</a>
            </td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        <?php else: ?>
        <td colspan="10" class="text-center"> aOh! 暂时还没有内容! </td><?php endif; ?>
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
  
  <script language="javascript" type="text/javascript" src="/Public/Admin/js/My97Date/WdatePicker.js"></script>
  <script type="text/javascript" charset="utf-8">
    //导航高亮
    highlight_subnav('<?php echo U('Union/userlist');?>');
    Core.setValue('type', '<?php echo I('get.type', '');?>');
    //时间选择
    $('.date_select').focus(function(){
      WdatePicker({skin: 'twoer', dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-%d'});
    });
  </script>

</body>
</html>
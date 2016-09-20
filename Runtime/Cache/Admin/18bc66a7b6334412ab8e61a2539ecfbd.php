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
  
  <link rel="stylesheet" type="text/css" href="/Public/Admin/css/order.css" media="all">

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
    <h2>
      <?php if(!empty($_GET['uid'])): echo (get_nickname($_GET['uid'])); ?>的<?php endif; ?>
      <?php if(!empty($sdp_uid)): echo (get_nickname($sdp_uid)); ?>的分销<?php endif; ?>
      订单列表(<?php echo ($_total); ?>)
    </h2>
  </div>
  <!-- 按钮工具栏 -->
  <div class="cf">
    <div class="fl">
      <button class="btn ajax-post confirm" target-form="ids" url="<?php echo U('Order/cancel');?>">取 消</button>
      <a href="javascript:order.bestmart();" class="btn">灵通打单</a>
      <a class="btn" href="<?php echo U('index');?>">刷 新</a>
    </div>
    <!-- 高级搜索 -->
    <form action="<?php echo U();?>" method="GET" id="J_search_form">
      <div class="search-form fr cf">
        <div class="fl">
          <?php if(C('SDP_IS_OPEN')): ?><select name="is_sdp">
              <option value="0">全部订单</option>
              <option value="1">分销订单</option>
            </select><?php endif; ?>
          <select name="order_type">
              <option value="0">所有订单</option>
              <option value="1">普通订单</option>
              <option value="2">团购订单</option>
            </select>
          <select id="o_status" name="o_status">
            <option value="-2">订单状态</option>
            <option value="0">待付款</option>
            <option value="-3">已付款</option>
            <option value="-1">交易取消</option>
            <optgroup label="已付款">
              <option value="200">待发货</option>
              <option value="201">待买家收货</option>
              <option value="202">交易成功</option>
            </optgroup>
            <optgroup label="退款">
              <option value="300">待处理退款申请</option>
              <option value="301">待买家退货</option>
              <option value="302">待退款</option>
              <option value="303">退款成功</option>
              <option value="404">系统取消订单</option>
              <option value="405">退款驳回</option>
            </optgroup>
          </select>
        </div>
        <div class="sleft">
          <select id="time_type" name="time_type" class="fl">
            <option value="create_time">下单时间</option>
            <option value="payment_time">支付时间</option>
          </select>
          <input type="text" name="start_time" class="search-input w-100 date_select" value="<?php echo I('get.start_time');?>" placeholder="开始"/>
          <span class="fl line-h">至</span>
          <input type="text" name="end_time" class="search-input w-100 date_select" value="<?php echo I('get.end_time');?>" placeholder="结束"/>
        </div>
        <div class="sleft">
          <!--<input type="text" name="keywords" class="search-input input-2x" value="<?php echo I('keywords');?>" placeholder="订单编号">-->
          <input type="text" name="ship" class="search-input input-2x" value="<?php echo I('ship');?>" placeholder="收货人姓名或收货人手机号">
          <a class="sch-btn" href="javascript:;" id="J_search"><i class="btn-search"></i></a>
        </div>
      </div>
      <?php if(!empty($_GET["uid"])): ?><input type="hidden" name="uid" value="<?php echo I('get.uid');?>" /><?php endif; ?>
      <?php if(!empty($sdp_uid)): ?><input type="hidden" name="sdp_uid" value="<?php echo ($sdp_uid); ?>" /><?php endif; ?>
    </form>
  </div>
  <!-- 数据表格 -->
  <table class="order-table-head">
    <tr>
      <th width="30">
         <input class="checkbox check-all" type="checkbox">
      </th>
      <th width="350">订单基本信息</th>
      <th width="110" class="text-right">订单总额</th>
      <th width="110" class="text-right">第三方支付金额</th>
      <th>&nbsp;</th>
      <th width="360">状态</th>
    </tr>
  </table>
  <?php if(!empty($list)): if(is_array($list)): $key = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><table class="order-table">
        <tr>
          <th colspan="8">
            <span class="text-cancel">下单时间：</span>
            <?php echo (time_format($vo["payment"]["create_time"])); ?>
            <?php if(current($vo['order'])['payment_time']): ?><span class="text-cancel">支付时间：</span>
              <?php echo time_format(current($vo['order'])['payment_time']); endif; ?>
            <?php if($vo["is_sdp"] == true): ?><span class="order-sdp">分销</span><?php endif; ?>
            <span class="split-tab"></span>
            <span class="text-cancel">下单用户：</span>
            <a href="<?php echo U('User/view?id='.$vo['payment']['uid']);?>"><?php echo (get_nickname($vo["payment"]["uid"])); ?></a>
            <span class="split-tab"></span>
            <span class="text-cancel">收货人：</span>
            <?php echo ($vo["ship"]["ship_uname"]); ?>
            <?php if(($vo["payment"]["payment_status"]) == "1"): ?><span class="fr">
                <span class="text-cancel">支付方式：</span>
                <?php if($vo['payment']['payment_type'] == '' OR ($vo['payment']['payment_amount'] == 0 AND $vo['payment']['finance_amount'] > 0)): ?>余额支付
                  <?php else: ?>
                  <?php echo get_payment_type_text($vo['payment']['payment_type']); endif; ?>
                <?php if(IS_SUPPLIER == false): ?><a href="javascript:;" onclick="UI.load('<?php echo U('Payment/preview?payment_id='.$vo['ship']['payment_id']);?>');">[详情]</a><?php endif; ?>
              </span><?php endif; ?>
            <?php if(($vo["invoice_need"]) == "1"): ?><span class="order-sdp">索要发票</span><?php endif; ?>
            <?php if(is_array($vo["order"])): $i = 0; $__LIST__ = $vo["order"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i; if($data["order_type"] != 1 ): ?><span class="order-sdp">
                <?php switch($data["order_type"]): case "2": ?>团购订单<?php break; endswitch;?>
               </span><?php endif; endforeach; endif; else: echo "" ;endif; ?>
          </th>
        </tr>
        <?php if(is_array($vo["order"])): $i = 0; $__LIST__ = $vo["order"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
          <td width="20" class="text-center<?php echo ($data['supplier_ids'] == 0 ? ' is-pai-order' :''); ?>">
            <input name="ids[]" class="ids" type="checkbox" value="<?php echo ($data['id']); ?>"> 
          </td>
          <td width="130" class="item-lists">
            <?php if(is_array($data["item_ids_arr"])): foreach($data["item_ids_arr"] as $key=>$item): ?><a href="<?php echo U('/Item/detail?id='.$item);?>" target="_blank"><img src="<?php echo get_image_thumb(get_cover(get_item_images($item), 'path'), 60, 60);?>" title="<?php echo (get_item_name($item)); ?>"></a><?php endforeach; endif; ?>
          </td>
          <td width="190">订单号：<a href="<?php echo U('view?id='.$data['id']);?>" target="_blank"><?php echo ($data["order_sn"]); ?></a></td>
          <td width="100" class="text-right"><?php echo sprintf('%.2f', $data['total_amount']+$data['finance_amount']);?> 元</td>
          <td width="100" class="text-right">
            <?php if(($data["o_status"]) == "0"): ?><input type="text" data-action="<?php echo U('Order/updateField');?>" name="total_amount" data-id="<?php echo ($data['id']); ?>" title="修改付款金额" class="text text-right input-mini J_ajax_updatefield" value="<?php echo ($data["total_amount"]); ?>">
            <?php else: ?>
            <span class="text-danger"><?php echo ($data["total_amount"]); ?></span><?php endif; ?> 元
          </td>
          <td class="text-right">
          <?php if($data["o_status"] == 405 && $data['shipping_time'] != 0): ?><span class="text-success">交易成功</span>
          <?php else: ?>
         <?php echo ($data["o_status_text"]); endif; ?>
            <span class="split-tab"></span>
            <?php switch($data["o_status"]): case "200": ?><a href="<?php echo U('Ship/add?order_id='.$data['id']);?>">[发货]</a><?php break;?>
              <?php case "405": if($vo['payment']['payment_status'] == 1 && $data['shipping_time'] == 0): ?><a href="<?php echo U('Ship/add?order_id='.$data['id']);?>">[发货]</a><?php endif; break;?> 
              <?php case "300": case "302": ?><a href="javascript:UI.load('<?php echo U('Order/refund',array('order_id' => $data['id']));?>','处理退款');">[处理退款]</a><?php break; endswitch;?>
          </td>
          <td width="80" class="text-center">
            <?php echo ($data["is_packed_text"]); ?>
          </td>
          <td width="140" class="text-center">
            <?php if(($vo["is_packed"]) == "1"): ?><a href="<?php echo U('updateField?is_packed=0&id='.$data['id']);?>" class="ajax-get">[未打包]</a>
              <?php else: ?>
                <a href="<?php echo U('updateField?is_packed=1&id='.$data['id']);?>" class="ajax-get">[打包]</a><?php endif; ?>
              <a href="<?php echo U('view?id='.$data['id']);?>" target="_blank">[查看]</a>
              <a href="<?php echo U('setStatus?status=-1&ids='.$data['id']);?>" class="confirm ajax-get">[删除]</a>
            </td>
          </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
      </table><?php endforeach; endif; else: echo "" ;endif; ?>
    <div class="page">
      <?php echo ($_page); ?>
    </div>
  <?php else: ?>
  <div class="list-empty">
    暂无订单！
  </div><?php endif; ?>

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
  
  <script src="/Public/Admin/js/My97Date/WdatePicker.js"></script>
  <script type="text/javascript">
    $(function(){
      //设置单选，复选，下拉菜单的值
      Core.setValue('is_sdp', '<?php echo I("get.is_sdp");?>');
      Core.setValue('o_status', '<?php echo ((isset($o_status) && ($o_status !== ""))?($o_status): ""); ?>');
      Core.setValue('order_type', '<?php echo ((isset($order_type) && ($order_type !== ""))?($order_type): ""); ?>');
      Core.setValue('time_type', '<?php echo ((isset($time_type) && ($time_type !== ""))?($time_type): ""); ?>');
      //时间选择
      $('.date_select').focus(function(){
        WdatePicker({skin: 'twoer', dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-%d'});
      });
      //导航高亮
      highlight_subnav("<?php echo U('index');?>");
    });
  </script>

</body>
</html>
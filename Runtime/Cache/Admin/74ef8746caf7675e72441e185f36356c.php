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
      

      
  <div class="main-title">
    <h2>查看订单</h2>
  </div>    

  <!-- 订单信息 -->
  <div class="order-box">
    <p>
      <strong>订单状态：</strong>
      <?php switch($info["o_status"]): case "-1": case "404": ?><span class="text-danger">交易取消</span><?php break;?>
        <?php case "0": ?><span class='text-danger'>待付款</span><?php break;?>
        <?php case "200": ?><span class="text-success">待发货</span><?php break;?>
        <?php case "201": ?><span class="text-success">待买家确认收货</span><?php break;?>
        <?php case "202": ?><span class="text-success">交易成功</span><?php break;?>
        <?php case "303": ?><span class="text-success">已退款</span><?php break;?>
        <?php case "300": case "301": case "302": ?><span class="text-danger">
            退款中
            <?php if($info['o_status'] == 301): ?>，待买家退货<?php endif; ?>
            <?php if($info['o_status'] == 302): ?>，买家已退货<?php endif; ?>
          </span>
          <a href="javascript:UI.load('<?php echo U('Order/refund',array('order_id' => $info['id']));?>','处理退款');">[处理退款]</a><?php break; endswitch;?>
      <?php if(($info["o_status"]) > "0"): ?><span class="sep"></span>
        <strong>付款状态：</strong><?php echo ($info['o_status']>0 ?'<span class="text-success">已付款</span>':'<span class="text-danger">未付款</span>'); ?>
        <span class="sep"></span>
        <strong>支付方式：</strong>
        <?php if(($info["o_status"]) > "0"): if($info["total_amount"] == 0): ?>余额支付
            <?php else: ?>
            <?php echo ($info["payment_type_text"]); endif; ?>
        <?php else: ?>
          -<?php endif; ?>
        <span class="sep"></span>
        <strong>发货状态：</strong><?php echo ($info['shipping_time']>0 ?'<span class="text-success">已发货</span>':'<span class="text-danger">未发货</span>'); endif; ?>
    </p>
    <p>
      <strong>订单编号：</strong><?php echo ($info["order_sn"]); ?> <?php if($info['payment_time'] > 0 AND $info['sdp_uid'] > 0): ?><span class="order-sdp">分销</span><?php endif; ?>
      <span class="sep"></span>
      <strong>订单总额：</strong><span class="text-danger"><?php echo sprintf('%.2f', $info['total_amount']+$info['finance_amount']);?> </span> 元
      <span class="sep"></span>
      <strong>下单用户：</strong>
      <?php if(IS_SUPPLIER): echo (get_nickname($info["uid"])); ?>
        <?php else: ?>
        <a href="<?php echo U('User/view?id='.$info['uid']);?>"><?php echo (get_nickname($info["uid"])); ?></a><?php endif; ?>
      
      <span class="sep"></span>
      <strong>下单时间：</strong><?php echo (time_format($info["create_time"])); ?>
      <span class="sep"></span>
      <?php if(($info["o_status"]) == "202"): if(($info["complete_time"]) > "0"): ?><strong>交易完成时间：</strong><?php echo (time_format($info["complete_time"])); endif; endif; ?>
    </p>
    <p>
      <strong>收货人姓名：</strong><?php echo ($info["ship"]["ship_uname"]); ?>
      <span class="sep"></span>
      <strong>手机：</strong><?php echo ($info["ship"]["ship_mobile"]); ?>
      <?php if(!empty($info["ship"]["ship_phone"])): ?><span class="sep"></span>
        <strong>座机：</strong><?php echo ((isset($info["ship"]["ship_phone"]) && ($info["ship"]["ship_phone"] !== ""))?($info["ship"]["ship_phone"]):'-'); endif; ?>
      <span class="sep"></span>
      <strong>地址：</strong><?php echo ($info["ship"]["ship_area"]); echo ($info["ship"]["ship_address"]); ?>
      <?php if(!empty($info["delivery_tpl"])): ?><span class="sep"></span>
        <strong>买家指定：</strong><span class="text-warning"><?php echo ($info["delivery_tpl"]); ?></span><?php endif; ?>
      <?php if(!empty($info["ship"]["ship_zipcode"])): ?><span class="sep"></span>
        <strong>邮编：</strong><?php echo ($info["ship"]["ship_zipcode"]); endif; ?>
    </p>
    <?php if(!empty($info['invoice_need'])){ ?>
    <p><strong>发票信息：</strong><span class="text-danger"><?php echo ($info["invoice_title"]); ?></span></p>
    <?php } ?>
    <p><strong>备注：</strong><span class="text-danger">
    <?php if(is_array($info["memo"])): $i = 0; $__LIST__ = $info["memo"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; echo ($key); ?> ---<?php echo ($vo); ?> &nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
    </span></p>
     <?php if(C('REAL_NAME') == 1): ?><p>
        <strong>真实姓名:</strong><?php echo ($info["realname"]); ?>
        <span class="sep"></span>
        <strong>身份证号:</strong><?php echo ($info["idcard"]); ?>
    </p><?php endif; ?>
  </div>

  <div class="order-item-table">
    <div class="sub-title">
      <h3>订单明细</h3>
    </div>
    <table class="dynamic-table">
      <thead>
        <tr>
          <th width="130">商品编号</th>
          <th>商品名称</th>
          <th>商品规格</th>
          <th width="100">商品价格</th>
          <th width="100">商品数量</th>
          <th width="100">小计</th>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($info["itemList"])): $i = 0; $__LIST__ = $info["itemList"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
          <td><a href="<?php echo U('/Item/detail?id='.$vo['item_id']);?>" target="_blank"><?php echo ($vo["number"]); ?></a></td>
          <td class="aleft">
            <a href="<?php echo U('/Item/detail', array('id' => $vo['item_id']));?>" target="_blank">
              <img src="<?php echo get_image_thumb(get_cover($vo['thumb'], 'path'), 100, 100);?>" class="goods-thumb" alt="<?php echo ($vo["name"]); ?>">
            </a>
            <a href="<?php echo U('/Item/detail?id='.$vo['item_id']);?>" target="_blank"><?php echo ($vo["name"]); ?></a>
            <?php if($vo[price] == 0): ?><span class="text-danger">（赠品）</span><?php endif; ?>
          </td>
          <td><?php echo ((isset($vo["spec"]) && ($vo["spec"] !== ""))?($vo["spec"]):'无'); ?></td>
          <td>
            &yen;<?php echo ($vo["price"]); ?>
            <?php if(($vo["fugou_dis_price"]) > "0"): ?><br /> <span class="text-danger">老客户优惠价</span><?php endif; ?>
          </td>
          <td><?php echo ($vo["quantity"]); ?></td>
          <td>&yen;<?php echo $vo['sub_total'] > 0 ? $vo['sub_total'] : $vo['subtotal'];?>
            <?php if($vo['memo']): ?><br /><span class="text-cancel">（<?php echo ($vo["memo"]); ?>）</span><?php endif; ?>
          </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
      </tbody>
    </table> 
  </div>

  <!-- 结算合计 -->
  <div class="cf order-detail-total clearfix mb-20">
    <dl class="total-list">
      <dt>商品共计：</dt><dd><?php echo ($info["itemCount"]["total_quantity"]); ?> 件</dd>
      <dt>商品总金额：</dt><dd>&yen; <?php echo ($info["total_price"]); ?></dd>
      <?php if(($info["is_use_coupon"]) == "1"): ?><dt>- 优惠券支付：</dt><dd>&yen; <?php echo ($info["coupon_amount"]); ?> 元</dd><?php endif; ?>
      <?php if(($info["is_use_card"]) == "1"): ?><dt>- 礼品卡支付：</dt><dd>&yen; <?php echo ($info["card_amount"]); ?> 元</dd><?php endif; ?>
      <?php if(($info["delivery_fee"]) > ""): ?><dt>+ 运费总额：</dt><dd>&yen; <?php echo ($info["delivery_fee"]); ?></dd><?php endif; ?>
      <?php if($info['discount_amount'] > 0): ?><dt>- 优惠金额：</dt><dd>&yen; <?php echo ($info["discount_amount"]); ?></dd><?php endif; ?>
      <?php $amount = $info['finance_amount'] + $info['total_amount']; ?>
      <dt><?php echo ($info['payment_time'] > 0 ? '实际支付' : '实际应付'); ?>：</dt><dd><b style="margin-right:0;">&yen; <?php echo sprintf('%.2f', $amount);?></b></dd>
      <?php if($info['payment_time'] > 0 AND $amount > 0): ?><p class="text-right text-cancel">
          （<?php if($info['finance_amount'] > 0): ?>余额：<?php echo ($info["finance_amount"]); ?> 元<?php endif; ?>
          <?php if($info['finance_amount'] > 0 AND $info['total_amount'] > 0): ?>+<?php endif; ?>
          <?php if(($info["total_amount"]) > "0"): if(!is_array($info['payment_type_text'])): echo ($info["payment_type_text"]); else: ?>第三方<?php endif; ?>：<?php echo ($info["total_amount"]); ?></b> 元<?php endif; ?>
          ）
        </p><?php endif; ?>
    </dl>
  </div>
  
  <!-- 分销 -->
  <?php if($info["sdp_shop"] and get_cashback_amount($info['id'])): ?><div class="sub-title">
      <h3>分销信息</h3>
    </div>
    <div class="order-box">
      <p>
        <strong>店铺ID：</strong>
        <?php echo ($info["sdp_shop"]["uid"]); ?>
        
        <span class="sep"></span>
        <strong>店铺名称：</strong>
        <a href="<?php echo U('Shop/index', array('uid' => $info['sdp_shop']['uid']));?>"><?php echo ((isset($info["sdp_shop"]["name"]) && ($info["sdp_shop"]["name"] !== ""))?($info["sdp_shop"]["name"]):"尚未设置店名"); ?></a>
        
        <span class="sep"></span>
        <strong>店家：</strong>
        <a href="<?php echo U('User/view?id='.$info['sdp_shop']['uid']);?>"><?php echo (get_nickname($info["sdp_shop"]["uid"])); ?></a>
      </p>
      <p>
        <strong>返现金额：</strong>
        <span class="text-danger"><?php echo (get_cashback_amount($info["id"])); ?></span> 元
      </p>
    </div><?php endif; ?>
  
  <!-- 发货信息 -->
  <?php if(($info['delivery']) > "0"): ?><div class="sub-title">
      <h3>发货信息</h3>
    </div>
    <div class="order-box">
      <p>
        <strong>物流公司：</strong><?php echo ($info["delivery"]["delivery_name"]); ?>
        <span class="sep"></span>
        <strong>物流单号：</strong><?php echo ($info["delivery"]["delivery_sn"]); ?>
        <a href="https://www.baidu.com/s?wd=<?php echo ($info["delivery"]["delivery_name"]); ?>%20<?php echo ($info["delivery"]["delivery_sn"]); ?>" target="_blank">[追踪]</a>
        <span class="sep"></span>
        <strong>发货日期：</strong><?php echo (time_format($ship["create_time"])); ?>
      </p>
      <?php if(!empty($ship["memo"])): ?><p>
        <strong>备注信息：</strong><?php echo ($ship["memo"]); ?>
      </p><?php endif; ?>
    </div><?php endif; ?>
  
  <div>
    <?php if(($info['o_status']) == "200"): ?><a href="<?php echo U('Ship/add?order_id='.$info['id']);?>" class="btn submit-btn">发 货
        <?php if(!empty($info["delivery_type"])): ?>（<?php echo ($info["delivery_type"]); ?>）<?php endif; ?>
        </a><?php endif; ?>
    <a href="<?php echo (cookie('__forward__')); ?>" class="btn btn-return">返 回</a>
    <a href="<?php echo U('Order/printItem?id='.$info['id']);?>" target="_blank" class="btn btn-block fr btn-default btn-xlarge">打印商品清单</a>
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
    highlight_subnav("<?php echo U('index');?>");
  </script>

</body>
</html>
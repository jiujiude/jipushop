<?php if (!defined('THINK_PATH')) exit();?><div class="container-span top-columns cf">
  <?php if(!IS_SUPPLIER): ?><dl class="show-num-mod">
    <dt><a href="<?php echo U('Admin/Item/index');?>" class="icon-bg-warning"><i class="icon icon-goods icon-count"></i></a></dt>
    <dd>
      <strong><a href="<?php echo U('Admin/Item/index');?>"><?php echo ($data["info"]["item"]); ?></a></strong>
      <p>
        <span>商品数</span>
        <a href="<?php echo U('Admin/Item/add');?>" class="fr btn">+添加商品</a>
      </p>
    </dd>
  </dl><?php endif; ?>
  <dl class="show-num-mod">
    <dt><a href="<?php echo U('Admin/Order/index');?>" class="icon-bg-danger"><i class="icon icon-edit icon-count"></i></a></dt>
    <dd>
      <strong><a href="<?php echo U('Admin/Order/index');?>"><?php echo ($data["info"]["order"]); ?></a></strong>
      <p>
        <span>订单数</span>
        <a href="<?php echo U('Admin/Order/index');?>" class="fr btn">管理订单</a>
      </p>
    </dd>
  </dl>
  <?php if(!IS_SUPPLIER): ?><dl class="show-num-mod">
    <dt><a href="<?php echo U('Admin/User/index');?>" class="icon-bg-success"><i class="icon icon-friend icon-count"></i></a></dt>
    <dd>
      <strong><a href="<?php echo U('Admin/User/index');?>"><?php echo ($data["info"]["user"]); ?></a></strong>
      <p>
        <span>用户数</span>
        <a href="<?php echo U('Admin/User/index');?>" class="fr btn">管理用户</a>
      </p>
    </dd>
  </dl>
  <dl class="show-num-mod">
    <dt><a href="<?php echo U('Shop/index');?>" class="icon-bg-info"><i class="icon icon-24 icon-count"></i></a></dt>
    <dd>
      <strong><a href="<?php echo U('Shop/index');?>"><?php echo ($data["info"]["shop"]); ?></a></strong>
      <p>
        <span>分销店铺</span>
        <a href="<?php echo U('Shop/index');?>" class="fr btn">查看列表</a>
      </p>
    </dd>
  </dl>
<!--  <dl class="show-num-mod">
    <dt><a href="<?php echo U('Admin/WechatUser/index');?>" class="icon-bg-info"><i class="icon icon-wechat icon-count"></i></a></dt>
    <dd>
      <strong><a href="<?php echo U('Admin/WechatUser/index');?>"><?php echo ($data["info"]["wechat_user"]); ?></a></strong>
      <p>
        <span>微信粉丝数</span>
        <a href="<?php echo U('Admin/WechatUser/index');?>" class="fr btn">查看粉丝</a>
      </p>
    </dd>
  </dl>--><?php endif; ?>
</div>
<!-- 显示待办事项 -->
<div class="span4">
  <div class="columns-mod">
    <div class="hd cf">
      <h5>待办事项</h5>
      <div class="title-opt">
      </div>
    </div>
    <div class="bd">
      <div class="todolist">
        <?php if(!empty($data["todolist"])): ?><ul>
            <?php if(is_array($data["todolist"])): $i = 0; $__LIST__ = $data["todolist"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
              <i class="icon icon-success icon-check"></i>您有
                <?php switch($key): case "ship": ?><strong class="text-danger"><?php echo ($vo); ?></strong> 笔订单待发货。<a href="<?php echo U('Order/index', array('o_status' => 200));?>" class="btn">立即发货<i class="icon icon-arrow-right"></i></a><?php break;?>
                  <?php case "refund": ?><strong class="text-danger"><?php echo ($vo); ?></strong> 笔退款待处理。<a href="<?php echo U('Refund/index');?>" class="btn">立即处理<i class="icon icon-arrow-right"></i></a><?php break;?>
                  <?php case "item": ?><strong class="text-danger"><?php echo ($vo); ?></strong> 个商品库存不足。<a href="<?php echo U('Item/index', array('stock' => 0, 'status' => 1));?>" class="btn">立即补货<i class="icon icon-arrow-right"></i></a><?php break;?>
                  <?php case "unrefund": ?><strong class="text-danger"><?php echo ($vo); ?></strong> 笔待处理退款申请。<a href="<?php echo U('Order/index',array('o_status'=>300));?>" class="btn">立即处理<i class="icon icon-arrow-right"></i></a><?php break;?>
                  <?php case "withdraw": ?><strong class="text-danger"><?php echo ($vo); ?></strong> 笔待处理提现申请。<a href="<?php echo U('Withdraw/index',array('status'=>100));?>" class="btn">立即处理<i class="icon icon-arrow-right"></i></a><?php break;?>
                  <?php case "shop": ?><strong class="text-danger"><?php echo ($vo); ?></strong> 个待审核的开店请求。<a href="<?php echo U('Shop/index',array('status'=>0));?>" class="btn">立即处理<i class="icon icon-arrow-right"></i></a><?php break; endswitch;?>
              </li><?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
        <?php else: ?>
          <p class="text-left text-cancel text-empty">暂无待办事项！</p><?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php
/**
 * 后台礼品卡控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;
use Think\Page;

class CardController extends AdminController {

  /**
   * 礼品卡列表
   * @author Max.Yu <max@jipu.com>
   */
  public function index($is_bind = null, $is_use = null, $is_expire = null, $keywords = null){
    //实例化礼品卡模型
    $Card = D('Card');

    //更新过期状态值
    $Card->updateExpireStatus();

    //定义查询条件
    $where = array();

    //查询条件：绑定状态
    if(isset($is_bind)){
      $where['is_bind'] = $is_bind;
    }

    //查询条件：绑定状态
    if(isset($is_use)){
      $where['is_use'] = $is_use;
    }

    //查询条件：过期状态
    if(isset($is_expire)){
      $where['is_expire'] = $is_expire;
    }

    //查询关键词
    if(isset($keywords)){
      $where['_string'] = '(name like "%'.$keywords.'%")  OR (number like "%'.$keywords.'")';
    }

    //按条件查询结果并分页
    $list = $this->lists($Card, $where, 'id desc');
    $intToStringMap = array(
      'is_bind'=>array(1=>'<font color="#86DB00">已绑定</font>',0=>'<font color="#cccccc">未绑定</font>'),
      'is_use'=>array(1=>'<font color="#cccccc">已使用</font>',0=>'<font color="#0000ff">未使用</font>'),
      'is_expire'=>array(1=>'<font color="#cccccc">已过期</font>',0=>'<font color="#0000ff">未过期</font>'),
    );
    int_to_string($list,$intToStringMap);

    // 获取已绑定礼品卡的用户信息
    $card_user = D('CardUser')->lists();
    if($card_user){
      foreach($card_user as $key => $value){
        $card_user_list[$value['card_id']] = $value;
      }
    }
    if($list){
      foreach($list as $key => &$value) {
        if($value['is_bind'] == 1){
          $value['card_user'] = $card_user_list[$value['id']];
        }
      }
    }

    //记录当前列表页的Cookie
    Cookie('__forward__', $_SERVER['REQUEST_URI']);

    //模板输出变量赋值
    $this->assign('list', $list);
    $this->assign('is_bind', $is_bind);
    $this->assign('is_use', $is_bind);
    $this->assign('is_expire', $is_expire);
    $this->assign('keywords', $keywords);
    $this->meta_title = '礼品卡列表';
    $this->display();
  }

  /**
   * 新增礼品卡
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    if(IS_POST){
      // 实例化礼品卡模型
      $Card = D('Card');

      // 触发自动验证
      $data = $Card->create();

      if($data){
        /* 获取表单提交值 */
        $name = I('post.name');         //卡名称
        $quantity = I('post.quantity');     //卡数量
        $amount = I('post.amount');       //卡面值
        $length = I('post.length');       //卡号长度
        $expire_time = I('post.expire_time'); //有效日期

        // 开卡日期
        $create_time = NOW_TIME;

        // 批量生成卡
        for($i=1; $i<=$quantity; $i++){
          $cardItem[] = array(
            'number'    => build_card_num($length, $i),
            'password'    => build_card_pwd(6, $i),
            'name'      => $name,
            'amount'    => $amount,
            'balance'   => $amount,
            'create_time' => $create_time,
            'expire_time' => strtotime($expire_time),
            'is_bind'   => 0,
            'is_expire'   => 0,
          );
        }

        // 批量写入卡数据
        $result = $Card->addAll($cardItem);

        if($result){
          //记录行为
          action_log('update_card', 'card', 'all', UID);
          $jump_url = I('post.jump_url');
          if(empty($jump_url)){
            $this->success('新增成功', Cookie('__forward__'));
          }else{
            $this->success('新增成功', $jump_url);
          }
        }else{
          $this->error('新增失败');
        }
      }else{
        $this->error($Card->getError());
      }
    }else{
      $this->meta_title = '新增礼品卡';
      $this->display();
    }
  }

  /**
   * 编辑礼品卡
   * @author Max.Yu <max@jipu.com>
   */
  public function edit($id = 0){
    if(IS_POST){
      $Card = D('Card');
      $data = $Card->create();
      if($data){
        if($Card->save()!== false){
          //记录行为
          action_log('update_card', 'card', $data['id'], UID);
          $this->success('更新成功', Cookie('__forward__'));
        }else{
          $this->error('更新失败');
        }
      }else{
        $this->error($Card->getError());
      }
    }else{
      $info = array();

      /* 获取数据 */
      $info = M('Card')->find($id);

      if(false === $info){
        $this->error('获取礼品卡信息错误');
      }
      $this->assign('info', $info);
      $this->meta_title = '编辑礼品卡';
      $this->display();
    }
  }

  /**
   * 查看礼品卡
   * @author Max.Yu <max@jipu.com>
   */
  public function view($id = 0){
    $info = array();
    $cardlog = array();

    /* 获取数据 */
    $info = M('Card')->find($id);

    if(false === $info){
      $this->error('获取礼品卡信息错误');
    }else{
      $map['card_id'] = $id;
      $cardlog = M('CardLog')->where($map)->order('id DESC')->select();
    }

    $this->assign('info', $info);
    $this->assign('cardlog', $cardlog);
    $this->meta_title = '查看礼品卡信息';
    $this->display();
  }

  /**
   * 删除礼品卡（物理删除）
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    $ids = I('request.ids');

    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }

    $map['id'] = array('in', $ids);
    if(M('Card')->where($map)->delete()){
      //记录行为
      action_log('update_card', 'Card', $ids, UID);
      $this->success('删除成功！');
    }else{
      $this->error('删除失败！');
    }
  }

  /**
   * 导出礼品卡到excel文件
   * @author Max.Yu <max@jipu.com>
   */
  public function export($ids = null, $is_bind = null, $is_expire = null, $keywords = null){
    // 实例化礼品卡模型
    $Card = M('Card');

    //定义返回或者操作的字段
    $field = 'id, number, password, name, amount, balance, FROM_UNIXTIME(create_time, "%Y-%m-%d"), FROM_UNIXTIME(expire_time, "%Y-%m-%d")';

    //定义查询条件
    $where = array();

    //查询条件：用户所选记录ID
    if(isset($ids)){
      $where['id'] = array('in', $ids);
    }else{
      //查询条件：绑定状态
      if(isset($is_bind)){
        $where['is_bind'] = $is_bind;
      }

      //查询条件：过期状态
      if(isset($is_expire)){
        $where['is_expire'] = $is_expire;
      }

      //查询关键词
      if(isset($keywords)){
        $where['_string'] = '(name like "%'.$keywords.'%")  OR (number like "%'.$keywords.'")';
      }
    }

    //获取礼品卡数据
    $data = $Card->where($where)->order('id asc')->field($field)->select();

    //文件名
    $filename = "礼品卡汇总表";

    //表头
    $headArr = array('编号', '卡号', '密码', '名称', '面值', '余额', '开卡日期', '失效日期');

    //调用Excel文件生成并导出函数
    createExcel($filename, $headArr, $data);
  }
}

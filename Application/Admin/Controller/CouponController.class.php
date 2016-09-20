<?php
/**
 * 后台优惠券控制器
 * @author Max.Yu <max@jipu.com>
 */

namespace Admin\Controller;

use Admin\Model\AuthGroupModel;
use Think\Page;

class CouponController extends AdminController {

  public function index() {
    //实例化礼品卡模型
    $Coupon = D('Coupon');

    //更新过期状态值
    $Coupon->updateExpireStatus();

    //记录当前列表页的Cookie
    Cookie('__forward__',$_SERVER['REQUEST_URI']);

    // 获取优惠券列表
    $list = $this->lists($Coupon, $where, '`id` DESC', null, $field);

    $intToStringMap = array(
      'is_expire' => array(1 => '<font color="#cccccc">已过期</font>', 0 => ''),
      'status' => array(1 => '禁用', 0 => '启用')
    );
    int_to_string($list,$intToStringMap);
    
    // 转化items为JSON格式
    /*foreach($list as $key => &$value){
     if($value['items']){
     $item_ids = explode(',', $value['items']);
     if(is_array($item_ids)){
     foreach($item_ids as $item_id){
     $item_array['item_ids'][$item_id] = $item_id;
     }
     $value['item_count'] = $item_array['item_ids'] ? count($item_array['item_ids']) : 0;
     $value['item_json'] = json_encode($item_array['item_ids']);
     }else{
     $value['item_count'] = 0;
     }
     }
     }*/

    //模板输出变量赋值
    $this->assign('list', $list);
    $this->meta_title = '优惠券管理';
    $this->display();
  }

  /**
   * 添加优惠券
   * @author Max.Yu <max@jipu.com>
   */
  public function add() {
    /*生成商品编号*/
    $coupon['number'] = create_uniqid_sn(4);

    /*模板输出变量赋*/
    $this->assign('coupon', $coupon);
    $this->meta_title = '添加优惠券';
    $this->display();
  }

  /**
   * 编辑优惠券
   * @author Max.Yu <max@jipu.com>
   */
  public function edit($id){
    if(empty($id)){
      $this->error('参数不能为空！');
    }

    /*获取一条记录的详细数据*/
    $Coupon = D('Coupon');
    $map['id'] = $id;
    $data = $Coupon->detail($map);
    if(!$data){
      $this->error($Coupon->getError());
    }

    $this->assign('data', $data);
    $this->meta_title = '编辑优惠券';
    $this->display();
  }

    /**
     *生成卡券编码
     */
    public function creatCouponNum(){
        $Coupon = D('Coupon');
        $Coupon->createCouponNum(1,2);
    }

    /**
     * 导出卡券编码
     */
    public function exportCouponNum(){

    }

  /**
   * 设置优惠券适用的商品
   * @author Max.Yu <max@jipu.com>
   */
  public function setItems($coupon_id = null, $items = null){
    if($coupon_id && $items){
      $model_coupon = M('Coupon');
      $result = $model_coupon->where('id='.$coupon_id)->setField('items', $items);
      if(!$result){
        $this->error($model_coupon->getError());
      }else{
        $this->success('更新成功', Cookie('__forward__'));
      }
    }else{
      $this->error('参数错误');
    }

  }

  /**
   * 删除优惠券
   * @author Max.Yu <max@jipu.com>
   */
  public function del(){
    $ids = I('request.ids');
    if(empty($ids)){
      $this->error('请选择要操作的数据!');
    }

    $map['id'] = array('in', $ids);
    if(M('Coupon')->where($map)->delete()){
      //记录行为
      action_log('update_coupon', 'coupon', $ids, UID);
      $this->success('删除成功！');
    }else{
      $this->error('删除失败！');
    }
  }
  
  
  /**
   * 优惠券编码列表
   * @author ezhu <ezhu@jipukeji.com>
   */
    public function export(){
        $id=I('get.id');
        if(empty($id)){
            $this->error('无效的优惠券ID');
        }
        $headArr = '券号;是否领取';
        $data = M('CouponNum')->where(array('cn_coupon_id'=>$id))->field('coupon_num,is_get')->select();
        $coupon=M('Coupon')->find($id);
        $filename = $coupon['name'].'券号表';
        //调用Excel文件生成并导出函数
        createExcel($filename, explode(';', $headArr), $data);
    }
}

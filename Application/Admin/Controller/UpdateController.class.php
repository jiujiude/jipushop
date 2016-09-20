<?php
/**
* 更新数据库
* @version 2015073015
* @author Justin
*/

namespace Admin\Controller;

use Think\Controller;

class UpdateController extends Controller{
  
  /**
  * 去掉重复记录
  * @param string $table 表名
  * @param string $unique 唯一字段（用于group by）
  * @version 2015073015
  * @author Justin
  */
  function removeDuplicate($table, $unique){
    //思路：找出最小id放入临时表，删除id不在临时表里面的数据
    $sql = 'create table `tmp_table` (id int unsigned not null primary key);'
         . 'insert into `tmp_table` (select min(id) from '.$table.' group by '.$unique.');'
         . 'delete from '.$table.' where id not in (select * from tmp_table);'
         . 'drop table `tmp_table`;';
    
    $m = new \Think\Model();
    $m->execute($sql);
    return true;
  }
  
   /**
   * 订单状态合并程序
   * @author Max.Yu <max@jipu.com>
   * http://www.shop.com/Admin/Update/mergeOrderStatus
   * @version 2015022810
   */
  public function mergeOrderStatus(){
    exit;
    //获取订单列表
    $order_list = M('Order')->field('*')->order('id desc')->select();
    foreach($order_list as $order){
      $o_status = -1000;
      if(isset($order['order_status'])){
        if($order['order_status'] == -1){
          if($order['payment_status'] == 1){
            $o_status = 404; //系统取消订单（已支付取消）
          }else{
            $o_status = -1; //已取消
          }
        }elseif($order['order_status'] == 1){
          $o_status = 202; //已确认收货（交易完成）
        }elseif($order['order_status'] == 0){
          if($order['payment_status'] == 0){
            $o_status = 0; //待付款
          }elseif($order['payment_status'] == 1){
            if($order['shipping_status'] == 0){
              $o_status = 200; //已付款，待发货
            }elseif($order['shipping_status'] == 1){
              if($order['receive_status'] == 0){
                $o_status = 201; //已发货，待确认收货
              }elseif($order['receive_status'] == 1){
                $o_status = 202; //已确认收货（交易完成）
              }
            }
          }
        }
      }
      //更新合并后的状态
      if($o_status != -1000){
        M('Order')->where(array('id' => $order['id']))->save(array('o_status' => $o_status));
      }
    }
    dump('Merge Success!');
  }
  
  function article(){
    exit;
    $lists = M('Document')->select();
    
    foreach($lists as &$v){
      $v['content'] = M('DocumentArticle')->getFieldById($v['id'], 'content');
      $v['cid'] = $v['category_id'];
    }
    p($lists);
    
    M('Article')->addall($lists);
  }
  
}
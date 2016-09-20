<?php
/**
 * 计划任务控制器
 *
 * 1.订单自动确认收货 AutoConfirmReceipt()
 * 2.自动下载远程头像 DownLoadAvatar()
 * 3.行为日志转存SQL actionLogRestore()
 * 
 * @version 2015012712
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

use Think\Controller;

class CrontabController extends Controller{

  public function __construct(){
    //关闭代码超时
    set_time_limit(3600);
    //读取站点配置
    $config = api('Config/lists');
    C($config);
  }

  /**
   * 执行计划任务
   */
  public function init(){
    //订单自动确认收货
    $this->AutoConfirmReceipt();
    //自动下载远程头像
    $this->DownLoadAvatar();
    //行为日志转存sql
    $this->actionLogRestore();
    
    // 微信
    $this->weixintask(); 
    // 代理自动返现
    $this->distributeTast(); 
    //记录日志
    
    F('crontab/'.date('Y_m_d_H_i_s'), NOW_TIME);
  }
  /**
   * 微信
   */
  public function weixintask(){
    S('accessToken_client' ,null);
  }
  /**
   * 代理自动返现
   * 
   */
  public function distributeTast(){
    $max_day = intval( C('DIS_TIME') );
    empty($max_day) && $max_day =15 ;
    $where = array(
      'status'      => 0 ,
      'flow'        => 'in',
      'type'        => 'union_order',
      'create_time' => array('lt' , (time() - C('DIS_TIME')*3600*24 ))
    );
    $data = M('Finance')->where($where)->field('id,uid,amount')->select();
    
    if($data){
      foreach($data as $k => $v){
        $count[$v['uid']] +=$v['amount'] ;
        $ids[] = $v['id'];
      }
      foreach($count as $k => $v){
        M('Member')->where('uid ='.$k)->setInc('finance',$v); 
      }
      M('Finance')->where(array('in' ,$ids))->setField('status',1);
    }
  }

  /**
   * 订单自动确认收货
   */
  public function AutoConfirmReceipt(){
    //限制天数
    $max_day = intval(C('MAX_CONFIRM_RECEIPT_DAY'));
    $max_day == 0 && $max_day = 7;
    if($max_day > 0){
      $map = array(
        'o_status' => 201, //待确认收货
        'shipping_time' => array('between', array(strtotime('2014-11-11 00:00:00'), time() - $max_day * 86400)), //限制时间内
      );
      $save_data = array(
        'o_status' => 202,
        'complete_time' => NOW_TIME
      );
      M('Order')->where($map)->save($save_data);
    }
  }

  /**
   * 自动下载非本地头像
   */
  public function DownLoadAvatar(){
    $where = array(
      'avatar' => array('LIKE', 'http%'),
    );
    $list = M('Member')->field('uid, avatar,reg_time')->where($where)->select();
    foreach($list as $line){
      if(@fopen($line['avatar'], 'r')){
        $info = read_file($line['avatar']);
        $file = '/Uploads/Avatar/'.date('Y/m/d', $line['reg_time']).'/'.$line['uid'].'.png';
        mkdir('.'.dirname($file), 0777, true);
        if(strlen($info) > 100){
          if(file_put_contents('.'.$file, $info)){
            M('Member')->where('uid='.$line['uid'])->save(array('avatar' => $file));
          }
        }
      }
    }
  }
    
  /**
   * 行为日志转存SQL
   * @author Justin <justin@jipu.com> 2015.5.13
   */
  public function actionLogRestore(){
    $table = 'jipu_action_log';
    $start = 0;
    $db = M('');
    //数据总数
    $result = $db->query("SELECT COUNT(*) AS count FROM `{$table}`");
    $count  = $result['0']['count'];
    if($count <= 50000){
      return;
    }
    while($count > $start){
      $this->_actionLog2sql($start);
      $start += 10000;
    }
    $sql = "TRUNCATE TABLE `{$table}`";
    $db->execute($sql);
    $db->execute("alter table `{$table}` AUTO_INCREMENT=".($count+1).";");
  }
  
  private function _actionLog2sql($start = 0){
    $path = C('DATA_BACKUP_PATH');
    if(!is_dir($path)){
      mkdir($path, 0755, true);
    }
    //读取备份配置
    $config = array(
      'path'     => realpath($path) . DIRECTORY_SEPARATOR,
    );
    $table = 'jipu_action_log';
    $db = M('');
    $result = $db->query("SELECT * FROM `{$table}` LIMIT {$start}, 1000");
    foreach ($result as $row) {
        $row = array_map('addslashes', $row);
        $sql = "INSERT INTO `{$table}` VALUES ('" . str_replace(array("\r","\n"),array('\r','\n'),implode("', '", $row)) . "');\n";
        file_put_contents($config['path'].'./actionLog-'.date('Ymd-His', NOW_TIME).'.sql', $sql,FILE_APPEND);
    }
  }
  
}

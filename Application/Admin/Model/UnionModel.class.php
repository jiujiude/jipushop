<?php
/**
 * 推广联盟模型
 * @version 2015091511
 * @author Justin <justin@jipu.com>
 */

namespace Admin\Model;

class UnionModel extends AdminModel{
  
  /**
   * 自动验证规则
   * @var array
   * @author Justin <justin@jipu.com>
   */
  protected $_validate = array(
    array('uid', 'require', '请选择绑定用户', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('uid', '_checkUid', '该用户已被绑定！', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   * @author Justin <justin@jipu.com>
   */
  protected $_auto = array(
    array('create_time', NOW_TIME, self::MODEL_INSERT),
  );
  
  protected function _checkUid(){
    $uid = I('post.uid', '');
    $id = I('post.id', 0);
    $count = $this->where(array('status' => array('gt', -1), 'uid' => $uid, 'id' => array('neq', $id)))->count();

    return $count == 0;
  }
  
  /**
  * 获取微信带参数二维码URL
  */
  public function _after_insert($data, $options){
    $scene_id = $data['id'];
    $wechat = new \Org\Wechat\WechatAuth(C('WECHAT_APPID'), C('WECHAT_SECRET'));
    $res = $wechat->getQrcodeTicket($scene_id);
    $res_arr = json_decode($res, true);

    $this->where('id='.$scene_id)->setField('qrcode_url', $res_arr['url']);
    $this->_create_qrcode($res_arr['url']);
  }
  
  /**
  * 生成带参数的二维码图片
  */
  private function _create_qrcode($url){
    get_qrcode($url, 0, 10, 1, 2);
  }
  
}

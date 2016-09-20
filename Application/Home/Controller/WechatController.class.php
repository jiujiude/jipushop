<?php
/**
 * 微信消息接口入口
 * 所有发送到微信的消息都会推送到该操作
 * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
 * @version 2014091618
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Controller;

use Org\Wechat\Wechat;

class WechatController extends HomeController{

  public function index(){
    
    $token = C('WECHAT_TOKEN'); //微信后台填写的TOKEN
    //加载微信SDK
    $wechat = new Wechat($token);
    //获取请求信息
    $data = $wechat->request();
  // set_log('./psd' ,$data);
    if($data && is_array($data)){
      /**
       * 你可以在这里分析数据，决定要返回给用户什么样的信息
       * 接受到的信息类型有9种，分别使用下面九个常量标识
       * Wechat::MSG_TYPE_TEXT       //文本消息
       * Wechat::MSG_TYPE_IMAGE      //图片消息
       * Wechat::MSG_TYPE_VOICE      //音频消息
       * Wechat::MSG_TYPE_VIDEO      //视频消息
       * Wechat::MSG_TYPE_MUSIC      //音乐消息
       * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
       * Wechat::MSG_TYPE_LOCATION   //位置消息
       * Wechat::MSG_TYPE_LINK       //链接消息
       * Wechat::MSG_TYPE_EVENT      //事件消息
       * Wechat::MSG_TYPE_TCS        //转多客服
       *
       * 事件消息又分为下面五种
       * Wechat::MSG_EVENT_SUBSCRIBE          //订阅
       * Wechat::MSG_EVENT_SCAN               //二维码扫描
       * Wechat::MSG_EVENT_LOCATION           //报告位置
       * Wechat::MSG_EVENT_CLICK              //菜单点击
       * Wechat::MSG_EVENT_MASSSENDJOBFINISH  //群发消息成功
       */

      /*回复内容，回复不同类型消息，内容的格式有所不同*/
      /*响应用户关注消息*/

      if($data['MsgType'] == Wechat::MSG_TYPE_TEXT){ //文本

        $where['event'] = 'normal';
        $where['keyword'] = array('LIKE', '%'.$data['Content'].'%');
        $msg = D('WechatMsg')->detail($where);
        if(empty($msg)){
          $msg = array('type' =>Wechat::MSG_TYPE_TEXT,'content'=>'亲，欢迎回来 ~');
        }
      }elseif($data['MsgType'] == Wechat::MSG_TYPE_LOCATION){
        //收到位置信息
        $msg = array('type' => 'text', 'content' => '位置信息我已收到');
        
      }else if($data['MsgType'] == Wechat::MSG_TYPE_EVENT){ //事件
        
        if($data['Event'] == Wechat::MSG_EVENT_SUBSCRIBE){ //订阅
          //判断是否通过扫描带参数的二维码关注
          $data['EventKey'] && D('WechatQrcodeLog')->update($data);
          
          $where['event'] = Wechat::MSG_EVENT_SUBSCRIBE;
          $where['mid'] = $this->mid;
          $msg = D('WechatMsg')->detail($where);
        }else if($data['Event'] == Wechat::MSG_EVENT_CLICK){ //菜单点击
          $where_event['key|keyword'] = $data['EventKey'];
          //$e_data = M('WechatEvent')->getByKey($data['EventKey']);
          $e_data = M('WechatEvent')->where($where_event)->find();
          if($e_data){
            //关联自定义回复
            if($e_data['keyword']){
              $where['event'] = 'normal';
              $where['keyword'] = array('LIKE', '%'.$e_data['keyword'].'%');
              $msg = D('WechatMsg')->detail($where);
            }else{
              //推送图文信息
              $ed = json_decode($e_data['data'], true);
              if(!empty($ed)){
                $itm = get_itmlist($ed);
                $msg = array('type' => 'image_text_msg','content' => array());
                foreach($itm as $v){
                  $msg['content'][] = array(
                    $v['title'], $v['description'], $v['path'], $v['img']
                  );
                }
              }
            } 
          }
        }else{
          //$msg = array('type' => 'text', 'content' => '亲，欢迎回来 ~');
          $msg = array('type' =>Wechat::MSG_TYPE_TCS);//转为客服处理
        }
        
      }else{
        $msg = array('type' =>Wechat::MSG_TYPE_TCS);//转为客服处理
      }
      //处理单、多图文消息
      if($msg['type'] == 'text'){
        $content = $msg['content'];
        //定义SUB_DOMAIN_NAME，拼接手机版二级域名
        $sreach = array('[bind]', '[website]');
        $replace = array(U('/Member/index@'.SUB_DOMAIN_NAME), U('/Index/index@'.SUB_DOMAIN_NAME));
        $msg['content'] = str_replace($sreach, $replace, $msg['content']);
        $content = $msg['content'];
        $type = $msg['type'];
      }else if($msg['type'] == 'news'){  
        //推送图文信息
        $ed = json_decode($msg['content'], true);
        if(!empty($ed)){
          $itm = get_itmlist($ed);
          $msg = array('type' => 'image_text_msg','content' => array());
          foreach($itm as $v){
            $msg['content'][] = array(
              $v['title'], $v['description'], $v['path'], $v['img']
            );
          }
        }
        $content = $msg['content'];
        $type = 'news';
      //自定义图文消息
      }else if($msg['type'] == 'image_text_msg'){
        $content = $msg['content'];
        $type = 'news';
      }else{
        $type=$msg['type'];
      }

      //if($data['MsgType'] == Wechat::MSG_TYPE_EVENT){
        //if($data['Event'] == Wechat::MSG_EVENT_SUBSCRIBE){
          //$content = array(
          //  array("关注订阅立刻回复图文信息","关注订阅立刻回复图文信息", "http://www.thinkphp.cn", "http://www.thinkphp.cn/Uploads/da/2014-08-25/53faf9f0a5c0a.jpg";),
          //  array("关注订阅立刻回复图文信息","关注订阅立刻回复图文信息", "http://www.thinkphp.cn", "http://www.thinkphp.cn/Uploads/da/2014-08-25/53faf9f0a5c0a.jpg";),
          //  array("关注订阅立刻回复图文信息","关注订阅立刻回复图文信息", "http://www.thinkphp.cn", "http://www.thinkphp.cn/Uploads/da/2014-08-25/53faf9f0a5c0a.jpg";),
          //);
          //$content = ''; //回复内容，回复不同类型消息，内容的格式有所不同
          //$type = 'news'; //回复消息的类型
          //$wechat->response($content, $type);
        //}
      //}
      /*响应当前请求(自动回复)*/
      $wechat->response($content, $type);

      /**
       * 响应当前请求还有以下方法可以只使用
       * 具体参数格式说明请参考文档
       * 
       * $wechat->replyText($text); //回复文本消息
       * $wechat->replyImage($media_id); //回复图片消息
       * $wechat->replyVoice($media_id); //回复音频消息
       * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
       * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
       * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
       * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
       * 
       */
    }
  }
}

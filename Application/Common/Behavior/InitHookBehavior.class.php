<?php
/**
 * 钩子插件行为
 * @version 2014051512
 */

namespace Common\Behavior;

use Think\Behavior;
use Think\Hook;

defined('THINK_PATH') or exit();

class InitHookBehavior extends Behavior {

  //行为扩展的执行入口必须是run
  public function run(&$content){
    if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;
    
    $data = S('hooks');
    if(!$data){
      $hooks = M('Hooks')->getField('name,addons');
      foreach ($hooks as $key => $value) {
        if($value){
          $map['status'] = 1;
          $names = explode(',',$value);
          $map['name'] = array('IN',$names);
          $data = M('Addons')->where($map)->getField('id,name');
          if($data){
            $addons = array_intersect($names, $data);
            Hook::add($key,array_map('get_addon_class',$addons));
          }
        }
      }
      S('hooks',Hook::get());
    }else{
      Hook::import($data,false);
    }
  }

}

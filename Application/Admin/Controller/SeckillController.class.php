<?php
/**
 * 秒杀控制器
 * @version 2015101415
 * @author Justin <justin@jipu.com> & <ezhu@jipukeji.com>
 */

namespace Admin\Controller;
use Think\Cache;

class SeckillController extends AdminController{
    
    
    function _before_index_display(&$lists){
        foreach($lists as &$vo){
            $item_ids_arr = str2arr($vo['item_ids']);
            foreach($item_ids_arr as $item){
                $vo['item_info'][] = get_item_info($item);
            }
        }
    }
    
    
    /**
     * 加入redis缓存
     */
    public function setRedisData($data){
        $redis = Cache::getInstance('Redis');
        $redis->set('invoice_'.$data['id'],$data);
    }
    
    
    
}


<?php
/**
 * 拼团活动
 * @author  ezhu <ezhu@jipukeji.com>
 */

namespace Admin\Controller;

class JoinController extends AdminController{
    
    
    /**
     * 拼团活动列表
     * @see \Admin\Controller\AdminController::index()
     */
    function _before_index_display(&$lists){
        foreach($lists as &$vo){
            $item_ids_arr = str2arr($vo['item_ids']);
            foreach($item_ids_arr as $item){
                $vo['item_info'][] = get_item_info($item);
            }
            if($vo['limit']){
                $vo['limit'] = getFormat($vo['limit']);
            }
        }
    }
    /**
     * 拼团订单展示
     * @return [type] [description]
     */
    function orderList(){
      $prefix  = C('DB_PREFIX');
      $model    = M()->table( $prefix.'join_list a ' )->join ('INNER JOIN '. $prefix.'item b on a.item_id=b.id ' );
      $order  = 'a.id desc' ;
      $fields = 'a.* , b.thumb,b.number ,b.name';
      $lists  = A('Home/Page', 'Event')->lists($model, null, $order, $limit ,NULL,$fields);
      $this->assign('list' ,$lists);
      $this->display();
    }
    /**
     * 拼团订单详情
     * @return [type] [description]
     */
    function view(){
      $main = M('Join_list')->where('id='.I('id'))->find();
      $this->main = $main ;
      $data = M('Join_order')->alias('a')->join('__PAYMENT__ b on a.payment_id=b.id')->where('a.join_id='.I('id'))->field('a.* , b.payment_status ,b.payment_type')->select();
      foreach($data as $k => $v){
        $data[$k]['spec'] = unserialize($v['spec']);
      }
      $this->count = M('Join_order')->where('join_id='.I('id'))->count();
      $this->data  = $data ;
      // var_dump($data[0]['spec']);die;
      $this->display();
    }
  /**
   * 编辑拼团
   * @param string $model 模型名字
   * @author ezhu <ezhu@jipushop.com>
   */
  function edit(){
      $id = I('request.id');
      if(empty($id)){
          $this->error('参数不能为空！');
      }
      $this->data = D('Join')->detail($id);
      $itemIds = explode(',',$this->data['item_ids']);
      $this->meta_title = '编辑'.$this->meta[CONTROLLER_NAME];
      $this->display();
  }
  
  
  /**
   * 更新方法
   * @param string $model 模型名字
   * @author Justin <justin@jipu.com>
   */
  function update(){
      if(IS_POST){
          $model = D('Join');
          $data = $model->create();
          $model->startTrans();
          if($data && false !== $rst = $model->update($data)){
              //操作活动产品表
              $itemIds = getUniqure(explode(',',$data['item_ids']));
              $newArr = array();
              if($data['id']){
                  $itemData = M('JoinItem')->where(array('join_id'=>$data['id'],'status'=>1))->field('item_id,join_id')->select();
                  $oldIds = array_column($itemData, 'item_id');
                  $i = 0;
                  foreach ($itemIds as $key=>$val){
                      if(!in_array($val,$oldIds)){
                          $newArr[$i]['item_id'] = $val;
                          $newArr[$i]['join_id'] = $data['id'];
                          $newArr[$i]['stime'] = $data['stime'];
                          $newArr[$i]['etime'] = $data['etime'];
                          $i++;
                      }
                  }
                  $delMap['join_id'] = array('eq',$data['id']);
                  $delMap['item_id'] = array('not in',$itemIds);
                  M('JoinItem')->where($delMap)->delete();
                  //更新拼团商品
                  $saveData['stime'] = $data['stime'];
                  $saveData['etime'] = $data['etime'];
                  M('JoinItem')->where(array('join_id'=>$data['id']))->save($saveData);
              }else{
                  foreach ($itemIds as $key=>$val){
                      $newArr[$key]['item_id'] = $val;
                      $newArr[$key]['join_id'] = $rst;
                      $newArr[$key]['stime'] = $data['stime'];
                      $newArr[$key]['etime'] = $data['etime'];
                  }
              }
              
              if(count($newArr)){
                  $resAdd = M('JoinItem')->addAll($newArr);
                  if(!$resAdd){
                      $model->rollback();
                      $this->error('添加产品失败');
                  }
              }
              $model->commit();
              $join_id = $data['id'] ? : $rst;
              $this->success('操作成功！', U('Join/itemList',array('id'=>$join_id)));
          }else{
              $error = $model->getError();
              $model->rollback();
              $this->error(empty($error) ? '系统错误！' : $error);
          }
      }else{
          $this->redirect('index');
      }
  }
  
  
    public function itemList(){
        $joinId = I('id');
        $keyword = I('keywords');
        $joinId || $this->error('活动id为空');
        $joinData = D('Join')->detail($joinId);
        //根据商品名称
        if($keyword){
          if(strpos($keyword, '=') === false){
            $map['m.name|m.number'] = array('LIKE', '%'.$keyword.'%');
          }
        }
        $map['m.status'] = array('eq', 1);
        $map['j.status'] = array('egt', 0);
        $map['j.join_id'] = array('eq', $joinId);
        $prefix = C('DB_PREFIX');
        $l_table = $prefix.'join_item';
        $r_table = $prefix.'item';
        $model = M()->table($l_table.' j')->join($r_table.' m ON j.item_id = m.id');
        $field = 'm.images,m.supplier_id,m.name,m.number,j.item_id as id,j.price,j.stock,j.status,j.join_num,j.first_price,j.join_price';
        $order = 'j.item_id DESC';
        $limit = 10;
        $list = $this->lists($model, $map, $order, $field, $limit, null);
        int_to_string($list);
        //记录当前列表页的Cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign('data', $joinData);
        $this->assign('list', $list);
        $this->meta_title = '拼团商品列表';
        $this->display();
    }
    
    
    /**
     * 编辑活动产品
     */
    public function editItem(){
        $id = I('id');
        $joinId = I('join_id');
        if(empty($id) || empty($joinId)) $this->error('参数异常');
        $prefix = C('DB_PREFIX');
        $joinItemMode = $prefix.'join_item';
        $joinItemSpecMode = $prefix.'join_item_spec';
        $itemModel = $prefix.'item';
        $model = M('join j')->join(' INNER JOIN '.$joinItemMode.' m ON j.id = m.join_id ')
                            ->join(' LEFT JOIN '.$joinItemSpecMode.' s ON s.item_id = m.item_id ')
                            ->join(' LEFT JOIN '.$itemModel.' i ON i.id=m.item_id');
        $map['m.item_id'] = array('eq',$id);
        $map['m.join_id'] = array('eq',$joinId);
        $map['j.status'] = array('eq',1);
        $field = 'j.id as join_id,m.prop_info,m.item_id,m.price as oprice,m.stock as ostock,m.join_num,m.first_price ofprice,m.join_price as ojprice,s.spc_code,i.cid_1,i.cid_2,i.cid_3,i.name';
        $data = $model->where($map)->field($field)->find();
        $data['prop_info'] = json_decode($data['prop_info'],true);
        $this->data = $data;
        $this->display();
    }
    
    
    /**
     * 编辑活动产品
     */
    public function editItemHandle(){
        $Model = D('JoinItem');
        $item_id = I('item_id');
        $Model->startTrans();
        $res = $Model->update();
        if($res !== false){
            $rst = $this->saveSpecifiction($item_id);
            if($rst !== false){
                $Model->commit();
                $this->success('更新成功' , Cookie('__forward__'));
            }else{
                $Model->rollback();
                $this->error('更新失败');
            }
        }else{
            $Model->rollback();
            $this->error($Model->getError());
        }
    }
    
    
    /**
     * 保存商品规格组合对应的价格与数量
     * @param string $item_id   商品id
     * @return boolean
     */
    public function saveSpecifiction($item_id = null){
        if(empty($item_id)){
            return false;
        }
        //实例化商品规格模型
        $ItemSpecifiction = M('JoinItemSpec');
        //获取库存数量数组
        $spc_quantity = I('spc_quantity');
        
        //获取价格数组
        $first_price = I('first_pri');
        //获取库存数量数组
        $join_price = I('join_pri');
        //生成规格组合值数组
        $total_quantity = 0;
        foreach($spc_quantity as $key => $val){
            if(!empty($val)){
                $spcItem[] = array(
                        'item_id' => $item_id,
                        'spc_code'  => $key,
                        'quantity'  => $val,
                        'first_price'  => $first_price[$key],
                        'join_price'  => $join_price[$key],
                );
                //计算总库存
                $total_quantity += $spc_quantity[$key];
            }
        }
        //清空旧的规格数据
        $result = $ItemSpecifiction->where(array('item_id'=>$item_id))->delete();
        if($result !== false){
            //规格组合信息批量写入
            if(!empty($spcItem)){
                if($result = $ItemSpecifiction->addAll($spcItem)){
                    //更新总库存
                    $result = M('JoinItem')->where(array('item_id'=>$item_id))->setField('stock', $total_quantity);
                }
            }
        }
        return $result;
        
    }
    
    
    /**
     * 修改商品参数
     */
    public function setFieldValue(){
        $id = I('post.id', '');
        $field = I('post.field', '');
        $value = I('post.value', '');
        if(empty($id) || empty($field)){
            $this->error('参数不完整！');
        }
        if(!check_form_hash()){
            $this->error('非法数据提交！');
        }
        $res = M('JoinItem')->where(array('item_id'=>$id))->setField($field, $value);
        $res ? $this->success('更新成功！') : $this->error('更新失败！');
    }
    
    
    /**
     * 
     * @param string $Model
     */
    public function setItemStatus(){
        $ids = (array)I('request.ids');
        $joinId = I('request.join_id');
        $status = I('request.status');
        if(empty($ids) || empty($joinId)){
            $this->error('请选择要操作的数据');
        }
        $joinData = M('Join')->where(array('id'=>$joinId))->find();
        $joinItemIds = explode(',',$joinData['item_ids']);
        $model = M('JoinItem');
        $map['item_id'] = array('in',$ids);
        $map['join_id'] = array('eq',$joinId);
        $model->startTrans();
        switch ($status){
            case -1 ://删除
                $rst = $model->where($map)->delete();
                if($rst !== false){
                    $rst = $this->delItemIds($joinId,$joinItemIds,$ids);
                    if($rst !== false){
                        $model->commit();
                        $this->success('删除成功','',IS_AJAX);
                    }else{
                        $model->rollback();
                        $this->error('删除失败','',IS_AJAX);
                    }
                }else{
                    $model->rollback();
                    $this->error('删除失败','',IS_AJAX);
                }
            break;
            case 0  :  //禁用
                $rst = $model->where($map)->save(array('status'=>$status));
                if($rst !== false){
                    $model->commit();
                    $this->success('禁用成功','',IS_AJAX);
                }else{
                    $model->rollback();
                    $this->error('禁用失败','',IS_AJAX);
                }
            break;
            case 1  :  //上架
                $rst = $model->where($map)->save(array('status'=>$status));
                if($rst !== false){
                    $model->commit();
                    $this->success('启用成功','',IS_AJAX);
                }else{
                    $model->rollback();
                    $this->error('启用失败','',IS_AJAX);
                }
            break;
            default:
                $model->rollback();
                $this->error('系统参数错误');
            break;
            
        }
    }
    
    
    /**
     * 添加或者删除拼团产品
     * @param int   $id         活动id
     * @param array $itemIds    原来的活动商品ids
     * @param array $newIds     要删除的ids
     * @param bool  $isAdd      是否是添加商品
     * @return Ambigous <boolean>
     */
    public function delItemIds($id,$itemIds,$newIds){
        $newArr = array();
        foreach ($itemIds as $key=>$val){
            if(!in_array($val,$newIds)){
                array_push($newArr,$val);
            }
        }
        $Ids = implode(',',$newArr);
        $rst = M('Join')->where(array('id'=>$id))->save(array('item_ids'=>$Ids));
        return $rst;
    }
    
    
    /**
     * 输出商品属性，供前端AJAX调用
     * @param string $item_id
     * @param string $type
     * @param string $cid_1
     * @param string $cid_2
     * @param string $cid_3
     */
    public function ajaxProp($item_id = null, $type = null, $cid_1 = null, $cid_2 = null, $cid_3 = null){
        $where['type'] = $type;
        if(!empty($cid_1)){
            $cid[0] = $cid_1;
        }
    
        if(!empty($cid_2)){
            $cid[1] = $cid_2;
        }
    
        if(!empty($cid_3)){
            $cid[2] = $cid_3;
        }
    
        $where['cid'] = array('in',$cid);
        $where['a.item_id'] = array('eq',$item_id);
        $where['b.type'] = array('eq','specification');
        
        //获取该商品的属性值和关联的属性项信息（关联模型不能用从表的字段排序，用原生sql代替）
        $prefix = C('DB_PREFIX');
        $l_table = $prefix.'item_extend';
        $r_table = $prefix.'item_property';
        $field = 'b.type, b.cname, b.ename, b.displayorder, b.formtype, a.*';
        $order = 'b.displayorder asc, b.id asc';
        $model = M()->table($l_table.' a')->join($r_table.' b ON a.prp_id = b.id');
        $property_list = $model->where($where)->field($field)->order($order)->select();
//         $sql = 'select '.$field .' from '.$l_table.' a, '.$r_table.' b where a.item_id = '.$item_id.' AND a.prp_id = b.id '.$order;
//         $property = M()->query($sql);
        
        
        
    
        //获取规格组合数据
        $spc_data = null;
        $propVal = array();
        if(!empty($item_id)){
            $field = 'spc_code,quantity,first_price,join_price';
            $spc_info = M('join_item_spec')->where('item_id = '.$item_id)->field($field)->select();
            if(!empty($spc_info)){
                $spc_data = json_encode($spc_info);
                foreach ($spc_info as $key=>$val){
                    $propVal = array_merge($propVal,explode('-',$val['spc_code']));
                }
            }
        }
        
        //拼接属性名称，属性选项配置，属性值数组
        if($property_list){
            foreach($property_list as $list) {
                $property_all[$list['id']]['property'] = $list;
                $property_all[$list['id']]['option'] = D('Join')->getOptionValue(unserialize($list['info']),$list['type']);
                $property_all[$list['id']]['value'] = $propVal;
                //$property_all[$list['id']]['pic'] = $this->getValue($item_id, $list['id'], 'pic', $type, $list['formtype']);
            }
        }
    
        
        $this->assign('item_id', $item_id);
        $this->assign('type', $type);
        $this->assign('property_all', $property_all);
        $this->assign('spc_data', $spc_data);
        $this->display();
    }
    
    
}



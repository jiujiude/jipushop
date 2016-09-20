<?php
/**
 * 发票控制器
 * ezhu <ezhu@jipukeji.com>
 */
namespace Home\Controller;

class InvoiceController extends HomeController{
    
    public function _initialize(){
        //记录当前页URL地址Cookie，点击我的登录完成后跳转至个人中心
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        parent::_initialize();
        parent::login();
    }
    
    /**
     * 保存个人发票信息
     */
    public function save(){
        $type = I('type');
        //接受参数
        $data = $this->checkData($type);
        $data['uid'] = UID;
        
        $html = $data['_string'];
        unset($data['_string']);
        $rtn = $data['rtn'];
        unset($data['rtn']);
        
        $model = M('Invoice');
        $map['uid'] = UID;
        $isExist = $model->where($map)->find();
        $rst = $isExist ? $model->where($map)->save($data) : $model->add($data);
        if($rst !== false){
            $this->ajaxReturn(array(
                    'status' => 1,
                    'html' => $html,
                    'rtn' => $rtn,
                    'info' => '操作成功！'
            ));
        }else{
            $this->error('操作失败！');
        }
        
    }
    
    
    /**
     * 根据发票类型接受参数
     */
    public function checkData($type){
        $data = array();
        $data['type'] = intval($type);
        $invoiceTypes = C('INVOICE_TYPE');
        $content = C('INVOICE_CONTENT');
        switch ($type){
            case 1:
                $data['normal_title'] = I('normal_title');
                $data['normal_content'] = I('normal_content');
                $typeName = $invoiceTypes[$type];
                $conName = $content[$data['normal_content']];
                
                if(empty($data['normal_title']) || empty($data['normal_content'])){
                    $this->error('请填写普通发票信息');
                }
                $data['_string'] = ' <span>'.$typeName.'&nbsp</span> <span>'
                                  .$data['normal_title'].'&nbsp</span> <span>'
                                  .$conName.'&nbsp</span>';
                $data['rtn'] = $typeName.'&nbsp'.$data['normal_title'].'&nbsp'.$conName;
            break;
            case 2 :
                $data['ele_title'] = I('ele_title');
                $data['ele_content'] = I('ele_content');
                $typeName = $invoiceTypes[$type];
                $conName = $content[$data['ele_content']];
                
                if(empty($data['ele_content']) || empty($data['ele_title'])){
                    $this->error('请填写电子发票信息');
                }
                $data['_string'] = ' <span>'.$typeName.'&nbsp</span> <span>'
                                  .$data['ele_title'].'&nbsp</span> <span>'
                                  .$conName.'&nbsp</span>';
                $data['rtn'] = $typeName.'&nbsp'.$data['ele_title'].'&nbsp'.$conName;
            break;
            case 3:
                $data['unit'] = I('unit');
                $data['code'] = I('code');
                $data['address'] = I('address');
                $data['tel'] = I('tel');
                $data['bank'] = I('bank');
                $data['account'] = I('account');
                $data['inc_content'] = I('inc_content');
                
                $typeName = $invoiceTypes[$type];
                $conName = $content[$data['inc_content']];
                
                if(empty($data['unit']) || empty($data['code']) || empty($data['address']) || empty($data['tel']) || empty($data['bank']) || empty($data['account']) || empty($data['inc_content'])){
                    $this->error('请填写完整的增值税发票信息');
                }
                $data['_string'] = '<span>'.$typeName.'&nbsp</span> <span>'
                                  .$data['unit'].'&nbsp</span> <span>'
                                  .$conName.'&nbsp</span>';
                $data['rtn'] = $typeName.'&nbsp'.$data['unit'].'&nbsp'.$conName.'&nbsp'.$data['code'].'&nbsp'.$data['address'].'&nbsp'.$data['tel'].'&nbsp'.$data['bank'].'&nbsp'.$data['account'];
            break;
        }
        return $data;
    }
    
    
}
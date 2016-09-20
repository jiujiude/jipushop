<?php
/**
 * 后台公共的输出控件类
 * ezhu <ezhu@jipukeji.com>
 */
namespace Admin\Widget;

use Think\Controller;
class EditorWidget extends Controller {
    /**
     * 默认配置
     * @var unknown
     */
    protected $config = array(
        'editor'=>array(
            'resize_type' => 1,        //是否允许拖拉编辑器，1允许，2不允许
            'height'      => '500px'   //编辑器高度
        )
    );
    
    
    /**
     * 时间控件
     * @param array $data
     */
    public function dateTime($data=array()){
        $this->assign('time',$data);
        $this->display('Editor/dateTime');
    }
    
    
    /**
     * 富文本编辑器
     */
    public function editor($data=array()){
        $data = array_merge($this->config['editor'],$data);
        $this->assign('editor',$data);
        $this->display('Editor/editor');
    }
    
}
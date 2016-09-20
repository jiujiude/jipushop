<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.jipushop.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ezhu <ezhu@jipukeji.com>
// +----------------------------------------------------------------------

	return array(
		'notice_type'=>array(
			'title'=>'提醒类型:',
			'type'=>'radio',
			'options'=>array(
				'1'=>'头部提醒',
				'2'=>'确认框提醒',
				'3'=>'弹框提醒',
				'4'=>'自定义标题提醒'
			),
			'value'=>'1',
		),
		'notice_status'=>array(
			'title'=>'提醒状态:',
			'type'=>'radio',
			'options'=>array(
				'1'=>'开启',
				'0'=>'关闭',
			),
			'value'=>0
		),
		'notice_text'=>array(
			'title'=>'提醒内容:',
			'type'=>'text',
			'value'=>'测试数据，请勿购买！'
		),
		'notice_title'=>array(
				'title'=>'提醒标题:',
				'type'=>'text',
				'value'=>'提示信息',
				'tip'=>'自定义标题时起作用'
		),
		'time'=>array(
				'title'=>'提醒时间:',
				'type'=>'text',
				'value'=>'2000',
				'tip'=>'以毫秒为单位（如：2000，代表2秒，也就是1000*2）'
		)
	);

<?php if (!defined('THINK_PATH')) exit(); $rand_key = time() . mt_rand(10000,99999); ?>
<input type="file" id="upload_picture_<?php echo ($rand_key); ?>" class="hide">
<input name="item_id" id="item_id_<?php echo ($rand_key); ?>" type="hidden" value="<?php echo ($param["item_id"]); ?>">
<div class="upload-img-box dragsort J-uib-<?php echo ($rand_key); ?>">
  <div style="display: none;"></div><!-- 新增一个隐藏层，解决无图片的时候初始化图片拖拽排序插件失败的问题 -->
  <input type="hidden" name="<?php echo ($param["name"]); ?>" value="<?php echo ($param['images'][0][id]); ?>" class="icon" />
  <?php if(!empty($param["images"])): if(is_array($param["images"])): $i = 0; $__LIST__ = $param["images"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="upload-pre-item J_item_<?php echo ($rand_key); ?>" id="item_<?php echo ($vo['id']); ?>_<?php echo ($rand_key); ?>">
        <img src="<?php echo ($vo['path']); ?>" data-id="<?php echo ($vo['id']); ?>" title="点击显示大图" />
        <span class='btn-close delPic' title='删除图片' data-rand='<?php echo ($rand_key); ?>'></span>
        <label class="set" for="default_<?php echo ($vo['id']); ?>"><input type="radio" name="thumb" class="thumb" value="<?php echo ($vo['id']); ?>" id="default_<?php echo ($vo['id']); ?>" <?php if(!empty($param["thumb"])): if(($vo["id"]) == $param["thumb"]): ?>checked="checked"<?php endif; else: if(($key) == "0"): ?>checked="checked"<?php endif; endif; ?>> 设为封面</label>
      </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
</div>
<script type="text/javascript">
  //限制传单张
  var uploadify_<?php echo ($rand_key); ?> = function(){
    /* 初始化上传插件 */
    $("#upload_picture_<?php echo ($rand_key); ?>").uploadify({
      "height": 36,
      "swf": "/Public/Admin/js/uploadify/uploadify.swf?ver=" + Math.random(),
      "fileObjName": "download",
      "buttonText": "上传图片",
      "uploader": "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
      "width": 80,
      'removeTimeout': 1,
      'fileTypeExts': '*.jpg; *.png; *.gif;',
      "onUploadSuccess": uploadPicture<?php echo ($rand_key); ?>,
      "simUploadLimit": 1,
      "multi": false,
      'onFallback': function(){
        alert('未检测到兼容版本的Flash');
      }
    });
    if($(".J_item_<?php echo ($rand_key); ?>").size() > 0){
      $("#upload_picture_<?php echo ($rand_key); ?>").hide();
    }
  }
  
  //
  if(typeof(swf_upload_i) === 'undefined'){ swf_upload_i = 0;}
  if(typeof SWFUpload === 'undefined' && swf_upload_i == 0){
    swf_upload_i = 1;
    $.getScript("/Public/Admin/js/uploadify/jquery.uploadify.min.js");
  }
  var upload_init_<?php echo ($rand_key); ?> = function(){
    if(typeof SWFUpload !== 'undefined'){
      window.clearInterval(upload_timer<?php echo ($rand_key); ?>);
      uploadify_<?php echo ($rand_key); ?>();
    }
  }
  upload_timer<?php echo ($rand_key); ?> = window.setInterval(upload_init_<?php echo ($rand_key); ?>,1e2);
  upload_init_<?php echo ($rand_key); ?>();
  
  function uploadPicture<?php echo ($rand_key); ?>(file, data){
    
    var data = $.parseJSON(data);
    var src = '';
    if(data.status){
      //创建<div class="upload-pre-item">
      var upload_item = $("<div class='upload-pre-item' id='item_" + data.id + "_<?php echo ($rand_key); ?>'></div>");
      //创建img input close-btn
      $(".J-uib-<?php echo ($rand_key); ?> input[name='<?php echo ($param["name"]); ?>']").val(data.id);
      src = data.url || '' + data.path;
      var upload_img = $("<img src=" + src + " title='点击显示大图，拖动图片可以对图片进行排序。' data-id=" + data.id + ">");
      var img_del = $("<span class='btn-close delPic' title='删除图片' data-rand='<?php echo ($rand_key); ?>'></span>");
      var set_thumb = $('<label class="set" for="default_' + data.id + '"><input type="radio" name="thumb" class="thumb" value="' + data.id + '" id="default_' + data.id + '"> 设为封面</label>');

      //加入到upload-img-box
      upload_item.append(upload_img);
      upload_item.append(img_del);
      upload_item.append(set_thumb);
      $('.J-uib-<?php echo ($rand_key); ?>').append(upload_item);

  
      // 设置默认封面图片
      var obj_thumb = $('input.thumb:checked');
      if(obj_thumb.length == 0){
        $(".upload-img-box > div:eq(1) > label > input.thumb").attr("checked", true)
      }
      $("#upload_picture_<?php echo ($rand_key); ?>").hide();
      $('.uploadify-queue-item').slideUp();
    }else{
      updateAlert(data.info);
      setTimeout(function(){
        $('#top-alert').find('button').click();
        $(that).removeClass('disabled').prop('disabled', false);
      }, 1500);
    }
    
  }
  
  // 删除图片
  $(document).on("click",".delPic",function(){
    $("#upload_picture_"+ $(this).data('rand')).show();
    $(".J-uib-"+ $(this).data('rand') +" input[name='<?php echo ($param["name"]); ?>']").val('');
    $(this).parent().remove();
  });
</script>
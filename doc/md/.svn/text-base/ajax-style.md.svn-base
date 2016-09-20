## Ajax请求编码风格约定
统一采用$.ajax()方法实现Ajax请求，该方法在jQuery和Zepto框架中通用。具体写法如下：

    $.ajax({
      type: 'POST',
      url: $.U(),
      data: $('form').serialize(),  //或对象的形式，如：{name: name, type: type, id: id}
      dataType: 'json',
      success: function(res){ //统一期间，这里约定结果变量名称为：res
        if(res.status == 1){
          console.log(res.data)  
        }else{

        }
      }
    })

## 服务端Ajax请求数据返回
服务端返回数据统一采用TP $this->ajaxReturn($data)方法，返回标准json数据，下面是一个简单的例子：
    
    $user_model = M('User'); //实例化User对象
    $result = $user_model->add($data);
    if($result){
      //成功后返回客户端新增的用户ID，并返回提示信息和操作状态
      $this->ajaxReturn($result, '新增成功！', 1);
     }else{
      //错误后返回错误的操作状态和提示信息
      $this->ajaxReturn(0, '新增错误！', 0);
    }

ajaxReturn()的返回参数约定为以下形式：  
status：操作状态，一般为1 | 0，也可为y | n(jquery.validate中返回y | n) ，或其他约定的数字；
info：提示信息；
data：返回数据； 
url：跳转地址或回调地址。



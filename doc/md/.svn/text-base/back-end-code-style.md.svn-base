## 后端开发中的编码风格约定

### MVC分层
为了保证更好的扩展性，减少后期维护重构工作，根据目前的业务需求，我们在MVC的基础上增加了Event层，主要用于实现Controller层的业务逻辑处理，数据格式化等工作。详细MVC分层说明如下：
##### 1.Controller
Controller主要用于负责外部交互响应，通过URL请求响应，主要完成：接收客户端数据、路由、模板数据绑定等功能。  
避免在Controller中出现业务逻辑处理、数据存取等操作，相应的处理应交由Model和Event来负责。
##### 2.Model
Model主要用于完成数据的：自动验证、自动完成和数据存取接口。
##### 3.Event
Event主要负责内部的事件响应，完成：业务逻辑处理、数据存取等操作。Event并且只能在内部调用，不对客户端提供接口。
##### 4.View
View层目前采用在HomeController中更改DEFAULT_THEME来切换模板。

### 变量获取
所有的客户端数据获取，统一使用I函数进行变量获取和过滤。具体参照：<http://document.thinkphp.cn/manual_3_2.html#input_var>    
相应的方法过滤也采用TP框架推荐的：

    I('get.name', '', 'htmlspecialchars');
    I('post.name', '', 'htmlspecialchars'); //采用htmlspecialchars方法对$_POST['name'] 进行过滤，如果不存在则返回空字符串
    I('session.user_id', 0); //获取$_SESSION['user_id'] 如果不存在则默认为0
    I('cookie.'); //获取整个 $_COOKIE 数组
    I('server.REQUEST_METHOD'); //获取 $_SERVER['REQUEST_METHOD'] 

### Ajax返回
Ajax返回采用TP框架自带的：ajaxReturn()方法，具体参照<http://document.thinkphp.cn/manual_3_2.html#ajax_return>  
    
    $this->ajaxReturn($data);

ajaxReturn()的返回参数约定为以下形式：
status：操作状态，一般为1 | 0，也可为y | n(jquery.validate中返回y | n) ，或其他约定的数字；
info：提示信息；
data：返回数据； 
url：跳转地址或回调地址。

### 模板标签
##### 1.模板标签写法
模板中的标签在可能的情况下，统一使用以下写法：

    $data.username
不推荐的写法：
    
    $data['username']

##### 2.模板标签赋值命名约定
模板标签赋值时，列表数据赋值统一使用：$lists，详情页面统一使用：$data，多个变量时采用如：$lists_item，$data_profile这样的命名方式。

##### 3.模板标签赋值写法约定
$this->data = $data;  和  $this->assign('data', $data); 都是推荐的赋值写法，注意，$this->assign('data', $data);若为多个变量赋值时，请采用以下数组的写法：

    $this->assign(array(
      'data' => $data,
      'lists' => $lists
    ));




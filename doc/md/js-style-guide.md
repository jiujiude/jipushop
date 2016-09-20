## JavaScript编码规范
### 命名规范
#### 通用命名规则

+ 所有变量必须是有意义的英文，严厉禁止拼音；  
+ 变量命名统一采用小写，单词直接以下划线分割；  
+ 变量允许使用公认英文缩写，例如`nav`；  
+ 常量必须所有单词大写，并且每个单词间加下划线；    
+ "on"只能用作事件的命名；  
+ 所有全局变量必须初始化；
+ 保留字以及特有的dom属性不能作为变量名。  

#### 变量命名规范
* 全部采用小写字母，以下划线`_`分割。  
* 特殊简写：小范围作用域临时变量，如函数内部的局部变量或参数：o(Object)、e(Element)、evt(event)、err(error)、res(result)等；  
* 循环变量：i、j、k以此类推；

#### 模块对象命名规范
模块对象即模块中声明的顶级对象名称。
采用小驼峰：即第一个单词首字母小写其他单词首字母大写，例如：

    var wechatMap = {
      init: function(){
        
      }
    }

#### 函数命名规范
* 独立函数（公共函数）：全部采用【小写字母 + 下划线`_`】方式命名；
* 模块内部函数：使用【动词+名词】小驼峰法，如： `getClientIp`、`getList`、`getVersion`；  
* 涉及逻辑返回值的函数：`is`、`has`、`can`，如：`isAdmin`、`hasChild`；  

### 书写规范
#### 对齐和缩进

* 必须使用空格键进行代码缩进，建议设置编辑器的Tab为2个空格的宽度；
* 所有语句结束后，必须使用 ; 号结束；
* 大括号前面不能换行；
* 操作符必须使用空格隔开；

#### 语法结构

普通代码段应该如下：

    while(!is_done){
      doSomething();
      is_done = moreTodo();
    }

变量定义方法如下：

    var a = null;

函数定义方法如下：

    var func_a = function(){
      var a = 0;
      ...
    }

if 语句应该像这样：

    if(some_condition){
      statements;
    }else if(some_other_condition){
      statements;
    }else{
      statements;
    }

for 语句应该像这样：

    for(initialization; condition; update){
      statements;
    }

while 语句应该像这样：

    while(!is_done){
      doSomething();
      is_done = moreTodo();
    }

do ... while 语句应该像这样：

    do{
      statements;
    }while(condition);

switch 语句应该像这样： 

    switch(condition){
      case "A": 
          statements;
          break;
      case "B": 
         statements;
          break;
      default:
          statements;
          break;
    }

try ... catch 语句应该像这样：

    try{
      statements;
    }catch(ex){
      statements;
    }finally{
      statements;
  }

单行的 if - else，while 或者 for 语句也必须加入括号：

    if(condition){
      statement;
    }
  
    while(condition){
      statement;
    }

    for(intialization; condition; update){
      statement;
    }

### Javascript编写风格
#### 单引号和双引号的问题
Javascript中单引号和双引号没有太大区别，我们和PHP规范统一，即：可以使用单引号的地方，全部使用单引号。  

#### 多变量定义规范
多个变量定义时，只声明一次var，采用【逗号】分割：

    var _self = this,
        count = 0,
        sum = 0,
        total = 0;

#### 对象定义规范
在Javascript中，所有类型都是对象，包括string类型。函数的定义，统一使用以下方式：

    var a = function(){...};

最后要加上 “;”号，不要再使用以下方式：

    function a(){}

对象定义规范：

* string类型定义：`var str = 'xxxxxx';`
* function类型定义：`var func = function(){...};`
* array类型定义：`var arr = new Array();`
* object类型定义：`var obj = {'name': 'xxxxxx', 'age': '10'}`

#### 选择器命名方式
为了避免class或id选择器与CSS造成的紧耦合问题，html中定义中的选择器名称统一以大写`J_`或`Z_`开头，后面以小写字母+下划线的方式表示。  
* 基于jQuery的项目中采用大写`J_`开头，主要用于PC端；  
* 基于Zepto的项目中采用大写`Z_`开头，主要用于移动端。   

另外，选择器class或id置于CSS类名之后，如：

    <div class="item-list J_item_list">
        
    </div>
    

#### 基于业务模块的对象封装
为了提高javascript代码的可维护性，降低耦合，在javascript代码编写中，我们采用基于业务模块的对象封装方式，即将同一业务模块，如系统通用、购物车、商品、用户等定义为对象，对象内定义模块使用的各类方法。例如：

    var item = {
      init: function(){
          …… 
      },
      getItemDetail: function(){
          ……
      }     
    }

* 其中对象的init方法中主要用于执行模块初始化的内容，如参数传入、事件绑定、初始化方法等。  
* 业务模块对象内函数的命名采用小驼峰法，即每个单词首字母大写并且第一个单词首字母小写，与PHP命名统一。




## CSS编码规范

### 命名规范

1.尽量使用英文命名原则，严格禁止拼音、数字。参照文章末尾的常用命名规则；  
2.尽量不加缩写，除非已约定的缩写方式，如：nav；  
3.id和class全部小写，采用该版块的英文单词或组合命名，以中杠“-”分割，如：nav-tabs(导航标签/nav+tabs)；

### 书写规范

1.必须使用2个空格进行代码缩进，建议设置编辑器Tab为2个空格的宽度；  
2.属性与“{}”之间，属性名和值之间必须有空格，属性必须换行，“{}”必须换行显示(即使只有一个属性)，如：  

    .nav-tabs {
      border-bottom: 1px solid #ddd;
    }

3.属性值后面必须加分号“;”，即使只有一个；  
4.使用CSS缩写属性，如：padding,margin,font等属性，使用缩写形式。

    .list-box {
      padding: 10px 5px;
      font: 180%/0.6 serif;
    }
5.RGB颜色值有字母的必须采用小写，可缩写的尽量使用缩写，如：#ffcccc缩写为#fcc；  
6.针对特殊浏览器的属性，应写在标准属性之前，左侧对齐，如：

    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;

7.按照元素模型由外及内，由整体到细节的书写顺序，属性值大致分为以下五组：
位置：position,top,right,bottom,left,float
盒模型属性：display,margin,padding,width,height
边框与背景：border,background
段落与文本：line-height,text-indent,font,color,text-decoration,text-align…
其他属性：overflow,cursor,visibility,…

标准的元素属性值顺序如：

    .nav-tabs {
      position: absolute;
      top: 40%;
      left: 15px;
      float: left;
      display: inline-block;
      margin-top: 20px;
      padding: 5px;
      width: 40px;
      height: 40px;
      border: 3px solid #fff;
      -webkit-border-radius: 23px;
      -moz-border-radius: 23px;
      border-radius: 23px;
      background: #222;
      line-height: 30px;
      font-size: 60px;
      font-weight: 100;
      color: #fff;
      text-align: center;
      opacity: 0.5;
      filter: alpha(opacity=50);
    }

8.class和id连写必须换行，如：

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active,
    .btn-primary.active,
    .btn-primary.disabled {
      color: #fff;
    }

9.请勿使用冗余低效的CSS写法，如：

    ul li a span {
      
    }

10.0后面不跟单位，去掉小数点前的0

    margin: 0px auto; //踩
    margin: 0 auto //赞

    opacity: 0.8 //踩
    opacity: .8  //赞

11.url()中不使用引号  


### 注释规范
#### 文件头部注释
文件头部注释主要用来阐述此文件的名称，版本，作者。按照以下形式书写：

    /*!
     * order.css
     * Version 2015042614
     * Max.Yu <max@winhu.com>
     */

其中版本号的格式为：yyyyMMDDHH

#### 行内注释
行内注释主要说明本段代码所在的模块，如：

    /*订单模块*/

### 常用命名推荐
头：header  
内容：content/container  
尾：footer  
导航：nav  
侧栏：sidebar  
栏目：column  
页面外围控制整体布局宽度：wrapper  
左右中：left right center  
登录条：loginbar  
标志：logo  
广告：banner  
页面主体：main  
热点：hot  
新闻：news  
下载：download  
子导航：subnav  
菜单：menu  
子菜单：submenu  
搜索：search  
友情链接：friendlink  
页脚：footer  
版权：copyright  
滚动：scroll  
内容：content  
标签：tags  
列表：list / item-list  
列表条目：item  
提示信息：msg  
小技巧：tips  
栏目标题：title  
加入：joinus  
指南：guide  
服务：service  
注册：regsiter  
状态：status  
投票：vote  
合作伙伴：partner  



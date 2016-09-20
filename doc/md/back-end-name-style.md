## 后端开发中的命名约定
### 方法命名
方法命名遵循：【动作+操作名称】的命名约定，基本的CURD操作（创建、更新、读取和删除）直接使用动作名称。
##### 1.Controller & Model方法命名
根据后端开发约定，控制器中的方法在项目中主要实现路由功能，因此应带有指向性和行动性。
* 首页：index
* 列表：lists
* 列表片段：listsItem （主要用于移动端ajax分页中）
* 详情：detail
* 数据添加：add
* 数据修改：edit
* 数据更新：update
* 数据删除：remove
* 获取数据：getActionName
* 设置/重置：setActionName / resetActionName
* 判断/检测：checkActionName


##### 2.Event方法命名
* 添加数据：addActionName
* 编辑数据：editActionName
* 更新数据：updateActionName
* 删除数据：removeActionName
* 获取数据：getActionName
* 设置数据：setActionName
* 格式化数据：formatActionName
* 判断/检测数据：checkActionName
* 处理数据：dealActionName


总之，命名的原则就是：【能（shuo）看（ren）懂（hua）】  
下面附上著名博客软件wordpress部分函数的命名：
##### 1.基本条件判断Tag  
is_home() : 是否为主页  
is_single() : 是否为内容页(Post)  
is_page() : 是否为内容页(Page)  
is_category() : 是否为Category/Archive页  
is_tag() : 是否为Tag存档页  
is_date() : 是否为指定日期存档页  
is_year() : 是否为指定年份存档页  
is_month() : 是否为指定月份存档页  
is_day() : 是否为指定日存档页  
is_time() : 是否为指定时间存档页  
is_archive() : 是否为存档页  
is_search() : 是否为搜索结果页  
is_404() : 是否为 “HTTP 404: Not Found” 错误页  
is_paged() : 主页/Category/Archive页是否以多页显示  
##### 2.模板常用的PHP函数及命令
<?php get_header(); ?> : 调用Header模板  
<?php get_sidebar(); ?> : 调用Sidebar模板  
<?php get_footer(); ?> : 调用Footer模板  
<?php the_content(); ?> : 显示内容(Post/Page)  
<?php if(have_posts()) : ?> : 检查是否存在Post/Page  
<?php the_title(); ?> : 内容页(Post/Page)标题  
<?php the_permalink() ?> : 内容页(Post/Page) Url  
<?php the_category(’, ‘) ?> : 特定内容页(Post/Page)所属Category  
<?php the_author(); ?> : 作者  
<?php edit_post_link(); ?> : 如果用户已登录并具有权限，显示编辑链接  
<?php get_links_list(); ?> : 显示Blogroll中的链接  
<?php wp_list_pages(); ?> : 显示Page列表  
<?php wp_list_categories(); ?> : 显示Categories列表  
<?php next_post_link(’ %link ‘); ?> : 下一篇文章链接  
<?php prev_post_link(’%link’); ?> : 上一篇文章链接  
<?php get_calendar(); ?> : 日历  
<?php wp_register(); ?> : 显示注册链接  
<?php wp_loginout(); ?> : 显示登录/注销链接  
<?php timer_stop(1); ?> : 网页加载时间(秒)  




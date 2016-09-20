<?php

namespace Think\Pay;

abstract class Pay{

  protected $config;
  protected $info;

  public function __construct($config){
    $this->config = array_merge($this->config, $config);
  }

  /**
   * 配置检查
   * @return boolean
   */
  public function check(){
    return true;
  }

  /**
   * 验证通过后获取订单信息
   * @return type
   */
  public function getInfo(){
    return $this->info;
  }

  /**
   * 建立提交表单
   */
  abstract public function buildRequestForm($data);

  /**  默认
   * 建立请求，以表单HTML形式构造（默认）
   * @param $para_temp 请求参数数组
   * @param $method 提交方式。两个值可选：post、get
   * @param $button_name 确认按钮显示文字
   * @return 提交表单HTML文本
   */
  protected function _buildForm($params, $gateway, $method, $charset = 'utf-8'){
    if(is_weixin() && strpos($gateway, 'alipay') !== false){
      return $this->_buildFormByIFrame($params, $gateway, $method, $charset);
    }
    //待请求参数数组
    $params = $this->buildRequestParam($params);
    header("Content-type:text/html;charset={$charset}");
    $sHtml = "<form id='paysubmit' name='paysubmit' action='{$gateway}' method='{$method}'>\n";
    foreach ($params as $k => $v) {
      $sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
    }
    $sHtml = $sHtml . "</form>\n";
    $sHtml = $sHtml . "Loading......\n";
    $sHtml = $sHtml . "<script>document.forms['paysubmit'].submit();</script>";
    return $sHtml;
  }

  /**  为解决支付宝在微信内无法支付的问题
   * 建立请求，以表单HTML形式构造（默认）
   * @param $para_temp 请求参数数组
   * @param $method 提交方式。两个值可选：post、get
   * @param $button_name 确认按钮显示文字
   * @return 提交表单HTML文本
   */
  protected function _buildFormByIFrame($params, $gateway, $method, $charset = 'utf-8'){
    //待请求参数数组
    $params = $this->buildRequestParam($params);
    header("Content-type:text/html;charset={$charset}");
    $jquery = SITE_URL.'/Public/Admin/js/jquery/jquery-2.0.3.min.js';
    ob_start();
    echo <<<EOO
    <!DOCTYPE html>
    <html>
      <head>
        <title>支付收银台</title>
        <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=0">
        <script src="$jquery"></script>
        <style>
          html,body{margin:0;padding:0;overflow:hidden;background:#FFFFFF;}
          .div_tip{text-align:center;margin-top:15%;}
          .iframe_d{background:#FFFFFF;overflow:hidden;border:none;width:100%;margin:0;opacity:0;left:0;top:0;position:absolute;}
        </style>
      </head>
      <body>
      <div class="div_tip">正在请求支付系统...</div>
      <iframe name="form_to" id="form_to" class="iframe_d">正在请求支付...</iframe>
      
EOO;
    $sHtml = "<form id='paysubmit' target='form_to' name='paysubmit' action='{$gateway}' method='{$method}'>\n";
    foreach($params as $k => $v){
      $sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
    }
    $sHtml = $sHtml."</form>\n";
    echo $sHtml;
    echo <<<EFO
      <script>
        $(function(){
          document.forms['paysubmit'].submit();
          ww = $(window).width();
          wh = $(window).height() - 4;
          $('.iframe_d').css({height:wh,width:ww});
          window.setTimeout(function(){
            $('.div_tip').animate({opacity:0}, 300);
            $('.iframe_d').animate({opacity:1}, 300);
          },1000);
        });
      </script>
    </body>
    </html>
EFO;
    $content = ob_get_contents();
    ob_clean();
    return $content;
  }

  /**
   * 建立请求，以模拟远程HTTP的POST请求方式构造并获取支付宝的处理结果
   * @param $params 请求参数数组
   * @return 支付宝处理结果
   */
  protected function _buildHttp($params, $gateway, $method = 'post', $charset = 'utf-8'){
    //待请求参数数组字符串
    $request_data = $this->buildRequestParam($params);
    //远程获取数据
    $sResult = $this->getHttpResponsePOST($gateway, $this->config['cacert'], $request_data, $charset);
    return $sResult;
  }

  /**
   * 生成要请求给支付宝的参数数组
   * @param $para_temp 请求前的参数数组
   * @return 要请求的参数数组
   */
  private function buildRequestParam($param){
    ksort($param);
    reset($param);
    $arg = '';

    foreach($param as $key => $value){
      if($value){
        $arg .= "$key=$value&";
      }
    }
    $param['sign'] = md5(substr($arg, 0, -1).$this->config['key']);
    if($param['service'] != 'alipay.wap.trade.create.direct' && $param['service'] != 'alipay.wap.auth.authAndExecute'){
      $para_sort['sign_type'] = strtoupper('MD5');
    }
    return $param;
  }

  /**
   * 远程获取数据，POST模式
   * 注意：
   * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
   * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
   * @param $url 指定URL完整路径地址
   * @param $cacert_url 指定当前工作目录绝对路径
   * @param $para 请求的数据
   * @param $input_charset 编码格式。默认值：空值
   * return 远程输出的数据
   */
  private function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = ''){
    if(trim($input_charset) != ''){
      $url = $url."_input_charset=".$input_charset;
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
    curl_setopt($curl, CURLOPT_CAINFO, $cacert_url); //证书地址
    curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
    curl_setopt($curl, CURLOPT_POST, true); // post传输数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $para); // post传输数据
    $responseText = curl_exec($curl);
    //如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);
    return $responseText;
  }

  /**
   * 远程获取数据，GET模式
   * 注意：
   * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
   * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
   * @param $url 指定URL完整路径地址
   * @param $cacert_url 指定当前工作目录绝对路径
   * return 远程输出的数据
   */
  private function getHttpResponseGET($url, $cacert_url){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
    curl_setopt($curl, CURLOPT_CAINFO, $cacert_url); //证书地址
    $responseText = curl_exec($curl);
    //var_dump( curl_error($curl) );
    //如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);
    return $responseText;
  }

  /**
   * 支付通知验证
   */
  abstract public function verifyNotify($notify);

  /**
   * 异步通知验证成功返回信息
   */
  public function notifySuccess(){
    echo "success";
  }

  final protected function fsockOpen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE, $encodetype = 'URLENCODE', $allowcurl = TRUE, $position = 0, $files = array()){
    $return = '';
    $matches = parse_url($url);
    $scheme = $matches['scheme'];
    $host = $matches['host'];
    $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
    $port = !empty($matches['port']) ? $matches['port'] : ($scheme == 'http' ? '80' : '');
    $boundary = $encodetype == 'URLENCODE' ? '' : random(40);

    if($post){
      if(!is_array($post)){
        parse_str($post, $post);
      }
      $this->formatPostkey($post, $postnew);
      $post = $postnew;
    }
    if(function_exists('curl_init') && function_exists('curl_exec') && $allowcurl){
      $ch = curl_init();
      $httpheader = array();
      if($ip){
        $httpheader[] = "Host: ".$host;
      }
      if($httpheader){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
      }
      curl_setopt($ch, CURLOPT_URL, $scheme.'://'.($ip ? $ip : $host).($port ? ':'.$port : '').$path);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      if($post){
        curl_setopt($ch, CURLOPT_POST, 1);
        if($encodetype == 'URLENCODE'){
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }else{
          foreach($post as $k => $v){
            if(isset($files[$k])){
              $post[$k] = '@'.$files[$k];
            }
          }
          foreach($files as $k => $file){
            if(!isset($post[$k]) && file_exists($file)){
              $post[$k] = '@'.$file;
            }
          }
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
      }
      if($cookie){
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
      }
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
      $data = curl_exec($ch);
      $status = curl_getinfo($ch);
      $errno = curl_errno($ch);
      curl_close($ch);
      if($errno || $status['http_code'] != 200){
        return;
      }else{
        $GLOBALS['filesockheader'] = substr($data, 0, $status['header_size']);
        $data = substr($data, $status['header_size']);
        return !$limit ? $data : substr($data, 0, $limit);
      }
    }

    if($post){
      if($encodetype == 'URLENCODE'){
        $data = http_build_query($post);
      }else{
        $data = '';
        foreach($post as $k => $v){
          $data .= "--$boundary\r\n";
          $data .= 'Content-Disposition: form-data; name="'.$k.'"'.(isset($files[$k]) ? '; filename="'.basename($files[$k]).'"; Content-Type: application/octet-stream' : '')."\r\n\r\n";
          $data .= $v."\r\n";
        }
        foreach($files as $k => $file){
          if(!isset($post[$k]) && file_exists($file)){
            if($fp = @fopen($file, 'r')){
              $v = fread($fp, filesize($file));
              fclose($fp);
              $data .= "--$boundary\r\n";
              $data .= 'Content-Disposition: form-data; name="'.$k.'"; filename="'.basename($file).'"; Content-Type: application/octet-stream'."\r\n\r\n";
              $data .= $v."\r\n";
            }
          }
        }
        $data .= "--$boundary\r\n";
      }
      $out = "POST $path HTTP/1.0\r\n";
      $header = "Accept: */*\r\n";
      $header .= "Accept-Language: zh-cn\r\n";
      $header .= $encodetype == 'URLENCODE' ? "Content-Type: application/x-www-form-urlencoded\r\n" : "Content-Type: multipart/form-data; boundary=$boundary\r\n";
      $header .= 'Content-Length: '.strlen($data)."\r\n";
      $header .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
      $header .= "Host: $host:$port\r\n";
      $header .= "Connection: Close\r\n";
      $header .= "Cache-Control: no-cache\r\n";
      $header .= "Cookie: $cookie\r\n\r\n";
      $out .= $header;
      $out .= $data;
    }else{
      $out = "GET $path HTTP/1.0\r\n";
      $header = "Accept: */*\r\n";
      $header .= "Accept-Language: zh-cn\r\n";
      $header .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
      $header .= "Host: $host:$port\r\n";
      $header .= "Connection: Close\r\n";
      $header .= "Cookie: $cookie\r\n\r\n";
      $out .= $header;
    }

    $fpflag = 0;
    if(!$fp = @fsocketopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout)){
      $context = array(
        'http' => array(
          'method' => $post ? 'POST' : 'GET',
          'header' => $header,
          'content' => $post,
          'timeout' => $timeout,
        ),
      );
      $context = stream_context_create($context);
      $fp = @fopen($scheme.'://'.($ip ? $ip : $host).':'.$port.$path, 'b', false, $context);
      $fpflag = 1;
    }

    if(!$fp){
      return '';
    }else{
      stream_set_blocking($fp, $block);
      stream_set_timeout($fp, $timeout);
      @fwrite($fp, $out);
      $status = stream_get_meta_data($fp);
      if(!$status['timed_out']){
        while(!feof($fp) && !$fpflag){
          $header = @fgets($fp);
          $headers .= $header;
          if($header && ($header == "\r\n" || $header == "\n")){
            break;
          }
        }
        $GLOBALS['filesockheader'] = $headers;

        if($position){
          for($i = 0; $i < $position; $i++){
            $char = fgetc($fp);
            if($char == "\n" && $oldchar != "\r"){
              $i++;
            }
            $oldchar = $char;
          }
        }

        if($limit){
          $return = stream_get_contents($fp, $limit);
        }else{
          $return = stream_get_contents($fp);
        }
      }
      @fclose($fp);
      return $return;
    }
  }

  final protected function formatPostkey($post, &$result, $key = ''){
    foreach($post as $k => $v){
      $_k = $key ? $key.'['.$k.']' : $k;
      if(is_array($v)){
        $this->formatPostkey($v, $result, $_k);
      }else{
        $result[$_k] = $v;
      }
    }
  }

}

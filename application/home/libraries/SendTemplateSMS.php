<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */



header("Content-Type: text/html;charset=utf-8");
/**
  * 发送模板短信
  * @param to 手机号码集合,用英文逗号分开
  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
  * @param $tempId 模板Id
  */       
function sendTemplateSMS($to,$datas,$tempId)
{
     
     include_once("./application/home/libraries/CCPRestSDK.php");

     //主帐号
     $accountSid= '8a216da85adaebff015aeb9b4c36040e';

     //主帐号Token
     $accountToken= 'a6ea1e91205a43ea8f09df59fb88ffab';

     //应用Id
     $appId='8a216da85b22910c015b2834b2a702b7';

     //请求地址，格式如下，不需要写https://
     $serverIP='app.cloopen.com';

     //请求端口 
     $serverPort='8883';

     //REST版本号
     $softVersion='2013-12-26';
     
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
    
     // 发送模板短信
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     if($result == NULL ) {
         echo "result error!";
         exit;
     }
     
       return $result->statusCode;
    
}

?>

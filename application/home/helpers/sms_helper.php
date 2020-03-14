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
require_once('submail/SUBMAILAutoload.php');

/**
 * 发送模板短信
 * @param $mobile 手机号码集合 单个
 * @param $params 内容数据 格式为数组 例如：array('code' => '343438', 'minute' => 10)
 * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
 */
function sendTemplatesubmail($mobile, $params, $tempId)
{
    $message_configs = [];
    // SMS 应用ID
    $message_configs['appid'] = '36659';
    // SMS 应用密匙
    $message_configs['appkey'] = 'b2f18b6e58532e9550eefa7f790da277';
    // SMS 验证模式     1、md5=md5 签名验证模式（推荐） 2、sha1=sha1 签名验证模式（推荐）  3、normal=密匙明文验证
    $message_configs['sign_type'] = 'md5';
    // Default API Domain 默认 API 服务域名
    $message_configs['server'] = 'https://api.mysubmail.com/';

    $submail = new MESSAGEXsend($message_configs);
    $submail->setTo($mobile);                               // 设置短信接收的11位手机号码
    $submail->SetProject($tempId);                          // 设置短信模板ID
    foreach ($params as $key => $param) {
        $submail->AddVar($key, $param);
    }

    // 调用 xsend 方法发送短信
    $xsend = $submail->xsend();

    return $xsend;
}
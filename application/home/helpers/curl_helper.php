<?php

/**
 * CURL以POST方式抓取数据
 * @param unknown $url
 * @param unknown $param
 */
function curl_post($url, $param = array())
{
    $oCurl = curl_init();
    if (stripos($url, "https://") !== FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    }
    if (is_string($param)) {
        $strPOST = $param;
    } else {
        $aPOST = array();
        foreach ($param as $key => $val) {
            $aPOST[] = $key . "=" . urlencode($val);
        }
        $strPOST = join("&", $aPOST);
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POST, true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);

    if (intval($aStatus["http_code"]) == 200) {
        return $sContent;
    } else {
        return false;
    }
}

/**
 * CURL以GET方式抓取数据
 */
function curl_get($url, $encoding = 'UTF-8')
{
    $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);  //0表示不输出Header，1表示输出
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    $contents = curl_exec($curl);
    curl_close($curl);              // 关闭cURL资源，并且释放系统资源

    if ($encoding == 'GBK') {
        return mb_convert_encoding($contents, 'UTF-8', 'GBK');
    } else {
        return mb_convert_encoding($contents, 'UTF-8', 'UTF-8');
    }
}


/* 调用新浪接口将长链接转为短链接
 * @param  string        $source    申请应用的AppKey
 * @param  array|string  $url_long  长链接，支持多个转换（需要先执行urlencode)
 * @return array
 */
function getSinaShortUrl($url_long){
    $source= "4230483069"; //AppKey
    // 参数检查
    if(!$url_long){
        return false;
    }
    // 参数处理，字符串转为数组
    if(!is_array($url_long)){
        $url_long = array($url_long);
    }
    // 拼接url_long参数请求格式
    $url_param = array_map(function($value){return '&url_long='.urlencode($value);}, $url_long);
    $url_param = implode('', $url_param);
    // 新浪生成短链接接口
    $api = 'http://api.t.sina.com.cn/short_url/shorten.json';    // 请求url
    $request_url = sprintf($api.'?source=%s%s', $source, $url_param);
    $result = array();
    // 执行请求
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $request_url);
    $data = curl_exec($ch);
    if($error=curl_errno($ch)) {
        return false;
    }
    curl_close($ch);
    $result = json_decode($data, true);
    if ($result)
    {
        if (!isset($result["error_code"])) //如果错误码不存在
        {
            $url_short=$result[0]["url_short"];
            if ($url_short)
            {
                return $url_short;
            }
        }
    }
    return false;
}

function getShortUrl($url_long)
{
    $url = 'https://ctsign.cn/Web/open/generateShortUrl';
    $param['secretKey'] = '7554nbyd864tb4a26vaaf34cba9cqe28e';
    $param['url'] = $url_long;
    $result = curl_post($url, $param);
    $result = json_decode($result, true);
    if ($result['success']) {
        $shot_url = $result['data']['shortUrl'];
        if ($shot_url) {
            return $shot_url;
        } else {
            return false;
        }
    } else {
        return false;
    }
}








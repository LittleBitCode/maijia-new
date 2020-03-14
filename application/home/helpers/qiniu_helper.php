<?php

/**
 * 七牛文件上传
 * @author hu
 */

require __DIR__.'/qiniu-sdk/autoload.php';

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;

function qiniu_upload ($path) {

	// Access Key
	$accessKey = 'o5JJtjZq826Jkl_-aQ06Fh5P9jN1hVtGwDLpq_7q';
	// Secret Key
	$secretKey = 'vSsHPfSMN_cxp7i9vPC8f-gkiufKIy1k6PgFdmEo';

	// 构建鉴权对象
	$auth = new Auth($accessKey, $secretKey);

	// 要上传的空间
	$bucket = 'utahfti6zddceh82mqds';

	// 生成上传 Token
	$token = $auth->uploadToken($bucket);

	// 要上传文件的本地路径
	$filePath = $path;

	// 上传到七牛后保存的文件名
	$key = $path;

	// 初始化
	$uploadMgr = new UploadManager();

	// 文件上传
	$result = $uploadMgr->putFile($token, $key, $filePath);

	return $result;
}

/** 生成文件上传token */
function qiniu_token()
{
    // Access Key
    $accessKey = 'o5JJtjZq826Jkl_-aQ06Fh5P9jN1hVtGwDLpq_7q';
    // Secret Key
    $secretKey = 'vSsHPfSMN_cxp7i9vPC8f-gkiufKIy1k6PgFdmEo';
    // 构建鉴权对象
    $auth = new Auth($accessKey, $secretKey);
    // 要上传的空间
    $bucket = 'utahfti6zddceh82mqds';
    // 生成上传 Token
    return $auth->uploadToken($bucket);
}
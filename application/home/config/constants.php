<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
define('IS_GET', REQUEST_METHOD == 'GET' ? true : false);
define('IS_POST', REQUEST_METHOD == 'POST' ? true : false);
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');


/******************************************************
 * 项目配置相关常量
 ******************************************************/
// 网站名称
define('PROJECT_NAME', '多赢符');

// 网站域名
define('DOMAIN_URL', 'http://'. $_SERVER['HTTP_HOST']);

// COOKIE NAME
define('COOKIE_NAME', 'BUS_ZHULI_TOKEN');

// COOKIE过期时间
define('COOKIE_EXPIRE_TIME', 604800);

// CDN加速域名
define('CDN_URL', 'http://cdn.0718go.com');

// STATIC域名
define('STATIC_URL', 'http://shangjia.renqizhuli.com');

// STATIC域名
define('MD5_SALT', 'LOP5^$.%90+*%%$@rqf');

define('VERSION_TXT', '1.0.0.3');

// 帮助中心
define('HELP_CENTER_URL', 'http://zhidao.vytao.com');
// 快递服务
define('POST_SERVICE_URL', 'http://vip.taoyoui.com');

/******************************************************
 * 会员相关常量
 ******************************************************/
// 商家每年会员费价格
define('BUSINESS_PRICE', 3688);

// 绑定店铺单价
define('BIND_SHOP_PRICE', 1288);

// 绑定店铺数量
define('MAX_BIND_SHOP_CNT', 10);

// 商家开通会员赠送月数
define('BUSINESS_GIVE_MONTH', 6);


/******************************************************
 * 任务相关常量
 ******************************************************/
// 任务保证金比例
define('TRADE_PAYMENT_PERCENT', 0.0);

// 不包邮邮费
define('POST_FEE', 10);

// 手机端订单分布价格
define('ORDER_DIS_PRICE', 0.5);

// 超级浏览任务
define('SUPER_SCAN_PRICE', 0.88);

// 手机端单个浏览任务（超级浏览任务）
define('PHONE_SCAN_REWARD', 0.3);

// 手机端赏金
define('PHONE_REWARD', 0.3);

// 默认支付id(7:快钱)
define('DEFAULT_PAY_ID', 7);

// 较之前系统减少商家收费
define('SUB_SELLER_PRICE', 2);

// 较之前系统减少买家佣金
define('SUB_BUYER_PRICE', 0.7);

/******************************************************
 * 增值服务相关常量
 ******************************************************/
// 商家返款手续费比例
define('BUS_REFUND_PERCENT', 0.002);

// 优先审核价格
define('FIRST_CHECK_PRICE', 5);

// 定时发布价格
define('SET_TIME_PRICE', 1);
// 活动定时结束
define('SET_OVER_TIME_PRICE', 1);
// 自定义发布时间
define('CUSTOM_TIME_PRICE', 3);
// 间隔发布价格
define('SET_INTERVAL_PRICE', 0);

// 加赏金币百分比
define('ADD_REWARD_POINT_PERCENT', 0.5);

/** 人气权重优化 **/
// 浏览商品
define('VIEW_GOODS_PRICE', 0.5);
// 收藏商品
define('COLLECT_GOODS_PRICE', 0.7);
// 加购物车
define('ADD_TO_CART_PRICE', 1);
// 收藏商品
define('COLLECT_SHOP_PRICE', 0.7);
// 申请优惠券
define('GET_COUPON_PRICE', 0.6);
// 浏览评价
define('ITEM_EVALUATE_PRICE', 0.4);

// 自定义包裹重量
define('SET_WEIGHT_PRICE', 0);

// 默认好评
define('DEFAULT_EVAL_PRICE', 0);

// 关键词好评
define('KWD_EVAL_PRICE', 1);

// 关键词好评
define('SETTING_EVAL_PRICE', 2);

// 延长买家购物周期(2个月)
define('EXTEND_CYCLE1_PRICE', 1);

// 延长买家购物周期(3个月)
define('EXTEND_CYCLE2_PRICE', 1.5);

// 限制买号重复进店下单
define('SHOPPING_END_BOX', 3);

// 地域限制
define('AREA_LIMIT', 0.5);
// 性别限制
define('SEX_LIMIT', 0.5);
// 限钻级别的买号
define('REPUTATION_LIMIT', 1);
// 限淘气值1000以上买号
define('TAOQI_LIMIT', 1);

/******************************************************
 * 提现相关常量
 ******************************************************/
// 商家提现手续费
define('BUS_WITH_PERCENT', 0.003);

// 商家单笔提现限制金额
define('BUS_WITH_LIMIT', 5);

/******************************************************
 * 目录常量
 ******************************************************/
// 上传目录
define('UPLOAD_DIR', 'upload/');
// 用户头像
define('UPLOAD_USER_FACE_DIR', UPLOAD_DIR . 'face/');
// 店铺LOGO
define('UPLOAD_SHOP_LOGO_DIR', UPLOAD_DIR . 'logo/');
// 个人信息截图
define('UPLOAD_USER_INFO_DIR', UPLOAD_DIR . 'user/');
// 商品主图,二维码,搜索图
define('UPLOAD_TRADE_INFO_DIR', UPLOAD_DIR . 'trade/');
// 做任务截图
define('UPLOAD_ORDER_INFO_DIR', UPLOAD_DIR . 'order/');
// 支付截图
define('UPLOAD_PAY_INFO_DIR', UPLOAD_DIR . 'pay/');
// 售后上传截图
define('UPLOAD_SERVICE_DIR', UPLOAD_DIR . 'service/');

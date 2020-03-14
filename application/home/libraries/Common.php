<?php
// +----------------------------------------------------------------------
// | 公共函数类库
// +----------------------------------------------------------------------
// | Copyright (c) 2017年 unknown. All rights reserved.
// +----------------------------------------------------------------------
// | File:      Common.php
// |
// | Author:    zhichao_hu <1036898351@qq.com>
// | Created:   2017-06-24
// +----------------------------------------------------------------------
defined('BASEPATH') OR exit('No direct script access allowed');

class Common
{
    /**
     * Base64编码加密，可用于地址栏中传递
     * @param  $data string 需要加密的字符串
     */
    static function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /** **/
    static function check_phone($mobile = '')
    {
        return preg_match("/^(?:13\d|14\d|15\d|16\d|17\d|19\d|18[0123456789])-?\d{5}(\d{3}|\*{3})$/", $mobile);
    }

    /**
     * 判断路径是否存在，不存在，将循环创建目录，并且权限是0766，有读写无执行权限，Window中无效
     * @param  $path string 路径
     */
    static function create_file_dir($path)
    {
        if (!file_exists($path)) {
            Common::create_file_dir(dirname($path));
            mkdir($path);
            chmod($path, 0766);    // 正式使用这个
            //chmod($path, 0777);  // 测试用这个
        }
    }


    /**
     * 获取客户端IP地址
     */
    static public function get_client_address()
    {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_REMOTEIP"])) {
            $ip = $_SERVER["HTTP_REMOTEIP"];
        } else {
            $ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '';
        }
        return $ip;
    }

    /**
     * 隐藏用户名等字符截取拼接方法
     */
    static public function hidecard($cardnum, $type = 1, $default = "")
    {
        if (empty($cardnum)) return $default;
        if ($type == 1) $cardnum = substr($cardnum, 0, 3) . str_repeat("*", 12) . substr($cardnum, strlen($cardnum) - 4);   //身份证
        elseif ($type == 2) $cardnum = substr($cardnum, 0, 3) . str_repeat("*", 5) . substr($cardnum, strlen($cardnum) - 4);    //手机号
        elseif ($type == 3) $cardnum = str_repeat("*", strlen($cardnum) - 4) . substr($cardnum, strlen($cardnum) - 4);    //银行卡
        elseif ($type == 4) {
            $mb_str = mb_strlen($cardnum, 'UTF-8');
            if ($mb_str <= 7) {
                $suffix = mb_substr($cardnum, $mb_str - 1, 1, 'UTF-8');
                $cardnum = mb_substr($cardnum, 0, 1, 'UTF-8') . str_repeat("*", 3) . $suffix;    //新用户名,无乱码截取
            } else {
                $suffix = mb_substr($cardnum, $mb_str - 4, 4, 'UTF-8');
                $cardnum = mb_substr($cardnum, 0, 3, 'UTF-8') . str_repeat("*", 3) . $suffix;    //新用户名,无乱码截取
            }
        } elseif ($type == 5) {
            $str = explode("@", $cardnum);
            $cardnum = substr($str[0], 0, 2) . str_repeat("*", strlen($str[0]) - 2) . "@" . $str[1];  //邮箱
        } elseif ($type == 6) $cardnum = mb_substr($cardnum, 0, 1, 'utf-8') . str_repeat("*", 3);    //真实姓名隐藏
        elseif ($type == 7) $cardnum = substr($cardnum, 6, 4) . "-" . substr($cardnum, 10, 2) . "-" . substr($cardnum, 12, 2);    //出生日期
        elseif ($type == 8) {
            if (empty($cardnum)) {
                $cardnum = "";
            } else $cardnum = date('Y', time()) - substr($cardnum, 6, 4) . "岁";    //通过身份证号码获取用户年龄
        } elseif ($type == 9) $cardnum = str_repeat("*", (strlen($cardnum) - 1) / 3) . mb_substr($cardnum, -1, 1, 'utf-8');    //紧急联系人姓名
        elseif ($type == 10) { //通过身份证号码获取用户性别
            $num = substr($cardnum, -2, 1);
            if ($num % 2 == 0) {
                $cardnum = "女";
            } else {
                $cardnum = "男";
            }
        } elseif ($type == 11) {
            $cardnum = mb_substr($cardnum, 0, 1, 'utf-8') . str_repeat("", 3);    //真实姓名
        } elseif ($type == 12) {
            $match = preg_match("/^(((14[0-9]{1})|(17[0-9]{1})|(13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\*\*\*\*+\d{4})$/", $cardnum);
            if (strlen((trim($cardnum))) == 11 && $match) {
                $cardnum = $cardnum;
            } else {
                $mb_str = mb_strlen($cardnum, 'UTF-8');
                $suffix = mb_substr($cardnum, $mb_str - 1, 1, 'UTF-8');
                $cardnum = mb_substr($cardnum, 0, 1, 'UTF-8') . str_repeat("*", 3) . $suffix;
            }
        }
        return $cardnum;
    }

    /*** 解决  com_create_guid  函数兼容问题 */
    static public function com_create_guid()
    {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);
        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);
        self::ensure_length($dec_hex, 5);
        self::ensure_length($sec_hex, 6);
        $guid = "";
        $guid .= $dec_hex;
        $guid .= self::create_guid_section(3);
        $guid .= '-';
        $guid .= self::create_guid_section(4);
        $guid .= '-';
        $guid .= self::create_guid_section(4);
        $guid .= '-';
        $guid .= self::create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= self::create_guid_section(6);
        return $guid;
    }

    public function get_rand_str($length)
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 61)};    //生成php随机数
        }
        return $key;
    }

    private function ensure_length(&$string, $length)
    {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, "0");
        } else if ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }

    private function create_guid_section($characters)
    {
        $return = "";
        for ($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0, 15));
        }
        return $return;
    }

    /**
     * 返回操作成功的JSON数据
     * @param string $msg 操作成功提示信息
     * @param array $data 返回的结果数据
     * @param string $id 返回的相关编号
     * @return
     */
    static function success($msg, $data = array(), $id = null)
    {
        $result = array('success' => true, 'msg' => $msg);
        if (!empty($data))
            $result['data'] = $data;
        if (!empty($id))
            $result['id'] = $id;

        self::json($result, 200);
    }

    /**
     * 返回操作失败的JSON数据
     * @param string $msg 操作成功提示信息
     * @param array $data 返回的结果数据
     * @param string $id 返回的相关编号
     * @param int $code HTTP Status Code
     * @return void
     */
    static function failure($msg, $code = 400, $data = array(), $id = null)
    {
        $result = array('success' => false, 'msg' => $msg);
        if (!empty($data))
            $result['data'] = $data;
        if (!empty($id))
            $result['id'] = $id;

        self::json($result, $code);
    }

    /**
     * 返回操作失败的JSON数据
     * @param $data 需要格式化的数据
     * @return void
     */
    static function json($data, $status_code = 200)
    {
        exit(json_encode($data));
    }


}

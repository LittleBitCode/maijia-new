<?php

/**
 * 获取当前版本
 */
function ver()
{
    $version = file_get_contents('version/home.txt');
    return '?v=' . $version;
}

/**
 * 错误提示
 */
function error_back($info)
{
    header('Content-Type:text/html;charset=utf-8');
    echo "<script type='text/javascript'>alert('$info');history.back();</script>";
}

/**
 * 分表后缀
 */
function suffix($user_id)
{
    return $user_id % 10;
}

/**
 * 订单后缀
 */
function order_suffix($order_sn)
{
    $tmp = explode('-', $order_sn);
    return $tmp[1]{0};
}

/**
 * 搜索路径
 */
function search_url($base, $k, $v, $a = [])
{
    if ($v === '') {
        unset($base[$k]);
    } else {
        $base[$k] = $v;
    }
    if (!empty($a)) {
        foreach ($a as $i) {
            unset($base[$i]);
        }
    }

    return '?' . http_build_query($base);
}

/**
 * 16-19 位卡号校验位采用 Luhm 校验方法计算：
 * 1，将未带校验位的 15 位卡号从右依次编号 1 到 15，位于奇数位号上的数字乘以 2
 * 2，将奇位乘积的个十位全部相加，再加上所有偶数位上的数字
 * 3，将加法和加上校验位能被 10 整除。
 ** */
function luhm($s)
{
    $arr_no = str_split($s);
    $last_n = $arr_no[count($arr_no) - 1];
    krsort($arr_no);
    $i = 1;
    $total = 0;
    foreach ($arr_no as $n) {
        if ($i % 2 == 0) {
            $ix = $n * 2;
            if ($ix >= 10) {
                $nx = 1 + ($ix % 10);
                $total += $nx;
            } else {
                $total += $ix;
            }
        } else {
            $total += $n;
        }
        $i++;
    }
    $total -= $last_n;
    $total *= 9;
    if ($last_n == ($total % 10)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 小于等于800 由之前的0.6%下调至0.5%
 * 大于800元的由之前0.6%下调至0.4%
 */
function fun_plat_refund_percent($total_amount)
{
    if (floatval($total_amount) > 800) {
        return 0.004;
    } else {
        return 0.005;
    }
}
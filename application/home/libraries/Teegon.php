<?php
/**
 * 天工支付
 */
class Teegon {

	// 账户
	private $client_id = "ci8c1yg8qhhjmz75eye0nvoc";
	// 密码
	private $client_secret = "4eqbb2n4pgpi0fuovre5bu0tg89e6ufm";
	// 签名
	private $sign;
	// 客户端ip
	private $client_ip;

	// 订单号
	private $order_no;
	// 渠道
	private $channel;
	// 金额
	private $amount;
	// 标题
	private $subject;
	// 元数据
	private $metadata;
	// 同步回调url
	private $return_url;
	// 异步回调url
	private $notify_url;

	/**
	 * construct
	 */
	public function __construct ($params = []) {

		$this->client_ip = $_SERVER["REMOTE_ADDR"];

		foreach ($params as $k=>$v) {
			$this->$k = $v;
		}

		$para = $params;
		$para['client_id'] = $this->client_id;
		$para['client_ip'] = $this->client_ip;

		$this->sign = $this->get_sign($para, $this->client_secret);
	}

	/**
	 * 提交数据
	 */
	public function send () {

		$request = [
			'order_no'=>$this->order_no,
			'channel'=>$this->channel,
			'amount'=>$this->amount,
			'subject'=>$this->subject,
			'metadata'=>$this->metadata,
			'client_ip'=>$this->client_ip,
			'return_url'=>$this->return_url,
			'notify_url'=>$this->notify_url,
			'client_id'=>$this->client_id,
			'sign'=>$this->sign
		];

		$url = "https://api.teegon.com/v1/charge";

		$result = $this->do_curl($url, $request);

		$data = [];

		if ($result) {
			$params = explode(';', $result['result']['action']['params']);

			if ($this->channel == 'alipay')
				$imgs = explode('"', $params[1]);
			else
				$imgs = explode('"', $params[3]);

			$data['amount'] = $result['result']['amount'];
			$data['img'] = $imgs[1];
		}

		return $data;
	}

	/**
	 * 生成签名
	 */
	private function get_sign ($para_temp, $client_secret){

		$para_filter = $this->para_filter($para_temp);

		$para_sort = $this->arg_sort($para_filter);

		$prestr = $this->create_string($para_sort);

		$prestr = $client_secret . $prestr . $client_secret;

		return strtoupper(md5($prestr));
	}

	/**
	 * 除去待签名参数数组中空值和签名参数
	 */
	private function para_filter ($para) {
		$para_filter = array();

		while (list ($key, $val) = each ($para)) {
			if ($key == "sign")
				continue;
			else
				$para_filter[$key] = $para[$key];
		}

		return $para_filter;
	}

	/**
	 * 对待签名参数数组进行排序并重新定位
	 */
	private function arg_sort ($para) {
		ksort($para);
		reset($para);
		return $para;
	}

	/**
	 * 首尾相椄成连续字符串并去除转义部分
	 */
	private function create_string ($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg .= $key . $val;
		}

		// 如果存在转义字符，那么去掉转义部分的
		if (get_magic_quotes_gpc()) {
			$arg = stripslashes($arg);
		}

		return $arg;
	}

    /**
     * 执行curl接口
     */
    private function do_curl ($url, $params) {
        header('Content-type:text/html; charset=utf-8');

        // cURL会话相关设置
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        $result = curl_exec($curl);

        // 查看返回的Curl状态码是否存在异常
        if ($result === FALSE) {
            $curlerror = 'Curl error: '. curl_error($curl);
        } else {
            // echo 'Curl请求操作完成，未发现相关异常。';
        }

        // 查看返回的Http状态码是否存在异常
        $curlinfo = curl_getinfo($curl);
        if ($curlinfo['http_code'] != 200) {
            if (!empty($curlinfo['redirect_url'])) {
                $redirect_url = $curlinfo['redirect_url'];
            } else {
                $httperror = 'Http error: '. $curlinfo['http_code'];
            }
        }

        curl_close($curl);

        // 抛出Curl请求异常信息
        if (isset($curlerror)) {
            return $curlerror;
        } elseif (isset($redirect_url)) {
            redirect($redirect_url);
        } elseif (isset($httperror)) {
            return $httperror;
        }

        // 将得到的字符串，通过进一步过滤，处理成json字符串
        $new_str = strstr($result, '{');
        $new_res = substr($new_str, 0, strripos($new_str, '}') + 1);
        $object = json_decode($new_res, TRUE);

        // 判断返回值的类型
        if (!is_null($object)) {
        // 1.返回值是一JSON字符串
            return $object;
        } else {
        // 2.返回值是接口结果信息
            return $result;
        }
    }
}
?>
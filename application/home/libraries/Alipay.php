<?php
/**
 * 支付宝支付
 */
class Alipay {

	// 支付类型
	private $payment_type = "1";

	// 商户订单号
	private $out_trade_no = '';

	// 订单名称
	private $subject = '';

	// 付款金额
	private $total_fee = '';

	// 同步回调url
	private $return_url = "http://www.linlangxiu.com/pay_check/alipay_callback_return";

	// 异步回调url
	private $notify_url = "http://www.linlangxiu.com/pay_check/alipay_callback_notify";

	/**
	 * construct
	 */
	public function __construct ($params = []) {

		foreach ($params as $k=>$v) {
			$this->$k = $v;
		}
	}

	/**
	 * 提交数据
	 */
	public function send () {

		require_once (APPPATH."/config/alipay.config.php");
		require_once (APPPATH."/libraries/alipay_submit.class.php");

		$alipaySubmit = new AlipaySubmit($alipay_config);

		$params = [
			"service" => "create_direct_pay_by_user",
			"partner" => trim($alipay_config['partner']),
			"seller_email" => trim($alipay_config['seller_email']),
			"payment_type" => $this->payment_type,
			"notify_url" => $this->notify_url,
			"return_url" => $this->return_url,
			"out_trade_no" => $this->out_trade_no,
			"subject" => $this->subject,
			"total_fee" => $this->total_fee,
			"body" => "",
			"show_url" => "",
			"anti_phishing_key" => "",
			"exter_invoke_ip" => "",
			"_input_charset" => trim(strtolower($alipay_config['input_charset']))
		];

		echo $alipaySubmit->buildRequestForm($params, "get", "确定");
	}
}
?>
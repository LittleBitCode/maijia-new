<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Kuaiqian {
    
	//人民币网关账号，该账号为11位人民币网关商户编号+01,该参数必填。
	public $merchantAcctId = 1021048707401;
    
	//编码方式，1代表 UTF-8; 2 代表 GBK; 3代表 GB2312 默认为1,该参数必填。
	public $inputCharset = 1;
    
	//接收支付结果的页面地址，该参数一般置为空即可。
	public $pageUrl = '';
    
	//服务器接收支付结果的后台地址，该参数务必填写，不能为空。
	public $bgUrl = DOMAIN_URL . '/pay_check/kq_callback';
    
	//网关版本，固定值：v2.0,该参数必填。
	public $version =  'v2.0';
    
	//语言种类，1代表中文显示，2代表英文显示。默认为1,该参数必填。
	public $language =  1;
    
	//签名类型,该值为4，代表PKI加密方式,该参数必填。
	public $signType =  4;
    
	//支付人姓名,可以为空。
	public $payerName= ''; 
    
	//支付人联系类型，1 代表电子邮件方式；2 代表手机联系方式。可以为空。
	public $payerContactType =  1;
    
	//支付人联系方式，与payerContactType设置对应，payerContactType为1，则填写邮箱地址；payerContactType为2，则填写手机号码。可以为空。
	public $payerContact =  "";
    
	//商户订单号，以下采用时间来定义订单号，商户可以根据自己订单号的定义规则来定义该值，不能为空。
	public $orderId = ''; //外传
    
	//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试。该参数必填。
	public $orderAmount = 1000;
    
	//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101，不能为空。
	public $orderTime = '';
    
	//商品名称，可以为空。
	public $productName= "苹果";
     
	//商品数量，可以为空。
	public $productNum = 5;
    
	//商品代码，可以为空。
	public $productId = '55558888';
    
	//商品描述，可以为空。
	public $productDesc = '';
    
	//扩展字段1，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
	public $ext1 = '';
    
	//扩展自段2，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
	public $ext2 = '';
    
	//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10，必填。
	public $payType = '10-1,10-2';
    
	//银行代码，如果payType为00，该值可以为空；如果payType为10，该值必须填写，具体请参考银行列表。
	public $bankId = 'CMB,SRCB,ICBC,NJCB,ABC,CEB,CCB,HZB,BOC,PAB,SPDB,SHB,BCOM,PSBC,CMBC,CIB,GDB,CITIC,HXB';
    
	//同一订单禁止重复提交标志，实物购物车填1，虚拟产品用0。1代表只能提交一次，0代表在支付不成功情况下可以再提交。可为空。
	public $redoFlag = '';
    
	//快钱合作伙伴的帐户号，即商户编号，可为空。
	public $pid = '';
    
    //签名
    public $signMsg = ''; 
    
    /**
     * 构造函数
     */ 
	public function __construct($params = array())
	{
        $this->orderTime = date("YmdHis"); //订单提交时间
		foreach($params as $key => $val)
		{
			if (property_exists($this, $key))
			{
				$this->$key = $val;
			}
		}
	}

    /**
     * signMsg 签名字符串 不可空，生成加密签名串
     */ 
	private function _kq_ck_null($kq_va,$kq_na){
        if($kq_va == ""){
            $kq_va="";
        }else{
            return $kq_va=$kq_na.'='.$kq_va.'&';
        }
    }

    /**
     * 生成签名
     */ 
    private function _create_sign()
    {
    	$kq_all_para =$this->_kq_ck_null($this->inputCharset,'inputCharset');
    	$kq_all_para.=$this->_kq_ck_null($this->pageUrl,"pageUrl");
    	$kq_all_para.=$this->_kq_ck_null($this->bgUrl,'bgUrl');
    	$kq_all_para.=$this->_kq_ck_null($this->version,'version');
    	$kq_all_para.=$this->_kq_ck_null($this->language,'language');
    	$kq_all_para.=$this->_kq_ck_null($this->signType,'signType');
    	$kq_all_para.=$this->_kq_ck_null($this->merchantAcctId,'merchantAcctId');
    	$kq_all_para.=$this->_kq_ck_null($this->payerName,'payerName');
    	$kq_all_para.=$this->_kq_ck_null($this->payerContactType,'payerContactType');
    	$kq_all_para.=$this->_kq_ck_null($this->payerContact,'payerContact');
    	$kq_all_para.=$this->_kq_ck_null($this->orderId,'orderId');
    	$kq_all_para.=$this->_kq_ck_null($this->orderAmount,'orderAmount');
    	$kq_all_para.=$this->_kq_ck_null($this->orderTime,'orderTime');
    	$kq_all_para.=$this->_kq_ck_null($this->productName,'productName');
    	$kq_all_para.=$this->_kq_ck_null($this->productNum,'productNum');
    	$kq_all_para.=$this->_kq_ck_null($this->productId,'productId');
    	$kq_all_para.=$this->_kq_ck_null($this->productDesc,'productDesc');
    	$kq_all_para.=$this->_kq_ck_null($this->ext1,'ext1');
    	$kq_all_para.=$this->_kq_ck_null($this->ext2,'ext2');
    	$kq_all_para.=$this->_kq_ck_null($this->payType,'payType');
    	$kq_all_para.=$this->_kq_ck_null($this->bankId,'bankId');
    	$kq_all_para.=$this->_kq_ck_null($this->redoFlag,'redoFlag');
    	$kq_all_para.=$this->_kq_ck_null($this->pid,'pid');

    	$kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);
        
    	//RSA 签名计算开始
    	$fp = fopen("./public_cert/ytu/99bill-rsa.pem", "r");
    	$priv_key = fread($fp, 123456);
    	fclose($fp);
    	$pkeyid = openssl_get_privatekey($priv_key);
    
    	//compute signature
    	openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);
    
    	//free the key from memory
    	openssl_free_key($pkeyid);
    
        $signMsg = base64_encode($signMsg);
    	//RSA 签名计算结束
        $this->signMsg = $signMsg;
    }
    
    /**
     * 发送数据到快钱
     */ 
    public function send()
    {
        $this->_create_sign();
		$kq_url  = '<form name="kqPay" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm" method="post">';
		$kq_url .= '<input type="hidden" name="inputCharset" value="'.$this->inputCharset.'" />';
		$kq_url .= '<input type="hidden" name="pageUrl" value="'.$this->pageUrl.'" />';
		$kq_url .= '<input type="hidden" name="bgUrl" value="'.$this->bgUrl.'" />';
		$kq_url .= '<input type="hidden" name="version" value="'.$this->version.'" />';
		$kq_url .= '<input type="hidden" name="language" value="'.$this->language.'" />';
		$kq_url .= '<input type="hidden" name="signType" value="'.$this->signType.'" />';
		$kq_url .= '<input type="hidden" name="signMsg" value="'.$this->signMsg.'" />';
		$kq_url .= '<input type="hidden" name="merchantAcctId" value="'.$this->merchantAcctId.'" />';
		$kq_url .= '<input type="hidden" name="payerName" value="'.$this->payerName.'" />';
		$kq_url .= '<input type="hidden" name="payerContactType" value="'.$this->payerContactType.'" />';
		$kq_url .= '<input type="hidden" name="payerContact" value="'.$this->payerContact.'" />';
		$kq_url .= '<input type="hidden" name="orderId" value="'.$this->orderId.'" />';
		$kq_url .= '<input type="hidden" name="orderAmount" value="'.$this->orderAmount.'" />';
		$kq_url .= '<input type="hidden" name="orderTime" value="'.$this->orderTime.'" />';
		$kq_url .= '<input type="hidden" name="productName" value="'.$this->productName.'" />';
		$kq_url .= '<input type="hidden" name="productNum" value="'.$this->productNum.'" />';
		$kq_url .= '<input type="hidden" name="productId" value="'.$this->productId.'" />';
		$kq_url .= '<input type="hidden" name="productDesc" value="'.$this->productDesc.'" />';
		$kq_url .= '<input type="hidden" name="ext1" value="'.$this->ext1.'" />';
		$kq_url .= '<input type="hidden" name="ext2" value="'.$this->ext2.'" />';
		$kq_url .= '<input type="hidden" name="payType" value="'.$this->payType.'" />';
		$kq_url .= '<input type="hidden" name="bankId" value="'.$this->bankId.'" />';
		$kq_url .= '<input type="hidden" name="redoFlag" value="'.$this->redoFlag.'" />';
		$kq_url .= '<input type="hidden" name="pid" value="'.$this->pid.'" />';
        $kq_url .= '</form><script>document.forms["kqPay"].submit();</script>';
        echo $kq_url;
    }
}
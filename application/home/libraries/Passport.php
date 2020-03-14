<?php
class Passport
{
	private $_key; //_key *密*钥
        public function __construct() {
            $this->_key = "#uthor_by_bill.chen#_>>&&(U(*";
        }

	public function encrypt($txt){
		//srand((double)microtime() * 1000000);
		//$encrypt_key = md5(rand(0, 32000));
        $encrypt_key = md5($this->_key);
		$ctr = 0;
		$tmp = '';
		for($i = 0;$i < strlen($txt); $i++) {
		   $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		   $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
		}
		return base64_encode($this -> passport_key($tmp));
	}
	
	public function decrypt($txt){
		$txt = $this -> passport_key(base64_decode($txt));
		$tmp = '';
		for($i = 0;$i < strlen($txt); $i++) {
		   $md5 = $txt[$i];
		   $tmp .= $txt[++$i] ^ $md5;
		}
		return $tmp;
	}
	
	private function passport_key($txt) {
		$encrypt_key = md5($this -> _key);
		$ctr = 0;
		$tmp = '';
		for($i = 0; $i < strlen($txt); $i++) {
		   $ctr = $ctr == strlen($this -> _key) ? 0 : $ctr;
		   $tmp .= $txt[$i] ^ $this -> _key[$ctr++];
		}
		return $tmp;
	}
        
    /**
     * 手*机*后*面5位*移*位，原*号*码*混*淆
     */
    public function emb($mobile){
        if ( $mobile == '' )
            return '';
        $pre = substr($mobile, 0, 6);
        $next = str_replace($pre, '', $mobile);
        $last = '';

        for($i=0; $i<strlen($next); $i++){
            $last .= $this->replace_digit( $next{$i} );
        }
        return $pre . $last;
    }

    /**
     * 加2 移位
     * @param int $n, $n > 0 and $n < 10
     * @return int
     */
    private function replace_digit( $n ){
        if (!in_array($n, range(0,9)))
            $n = 0;
        $n += 2;
        $n >= 10 && $n -=10;
        return $n;
    }

    /**
     * 原*手*机*号*码*还*原
     */
    public function dmb($mobile){
        if ( $mobile == '' )
            return '';
        $pre = substr($mobile, 0, 6);
        $next = str_replace($pre, '', $mobile);
        $last = '';

        for($i=0; $i<strlen($next); $i++){
            $last .= $this->restore_digit( $next{$i} );
        }
        return $pre . $last;
    }

    private function restore_digit($n){
        if (!in_array($n, range(0,9)))
            $n = 0;
        $n -= 2;
        if ( $n == -1 ) 
            $n = 9;
        else if ( $n == -2 ) 
            $n = 8;
        return $n;
    }
}
?>
<?php

/**
 * 加密函数
 */

function encrypt_bank_card($card_no) {

	$len = strlen($card_no);

	$arr = [];

	for ($i = 0; $i < $len; $i+=4) {
		$arr[] = substr($card_no, $i, 4);
	}

	foreach ($arr as $k=>$v) {

		if ($k == 0) continue;

		if ($k == (count($arr)-1)) continue;

		$arr[$k] = '****';
	}

	return implode(' ', $arr);
}

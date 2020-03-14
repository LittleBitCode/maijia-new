<?php
/**
 * 公用模型
 * @author hu
 */

class Base64_Model extends CI_Model {

	/**
	 * 转换图片
	 */
	public function to_img ($base64, $path) {

		$save_path = $path . date('Ymd') . '/';

		if (!is_dir($save_path)) {
			@mkdir($save_path, 0777, true);
		}

		$data = base64_decode(substr($base64,22));

		$img = imagecreatefromstring($data);

		$filename = md5(uniqid()) . '.jpg';

		if ($img !== false) {
			imagejpeg($img, $save_path . $filename);
		} else {
			return '';
		}

		imagedestroy($img);

		return '/' . $save_path . $filename;
	}

}

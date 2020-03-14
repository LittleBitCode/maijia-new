<?php
/**
 * Banner模型
 * @author hu
 */

class Banner_Model extends CI_Model {

	/**
	 * 获取banner
	 */
	public function get_banner ($position_id,$limit=10) {

		$sql = "SELECT img, url, is_members_visible
	            FROM rqf_banner
	            WHERE show_position = {$position_id} AND show_status = 1 AND is_delete = 0
	            AND updated_time > ?
	            ORDER BY created_time DESC LIMIT {$limit}";
        $banner_list = $this->db->query($sql, array(time()))->result();

		return $banner_list;
	}
}

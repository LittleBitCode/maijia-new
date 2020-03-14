<?php
/**
 * Cancel模型
 * 
 */

class Cancel_Model extends CI_Model {

	/**
	 * 获取当日取消次数
	 */
	public function cancel_times($user_id = 0) {

		if (empty($user_id)) {
			$user_id = $this->session->userdata('user_id');
		}

		$d = date('Ymd');

		$sql = "select sum(cancel_times) cnt from rqf_cancel_record
				where user_id = {$user_id}
				and cancel_date = {$d}";

		$row = $this->db->query($sql)->row();

		if (empty($row)) {
			return 0;
		}

		return $row->cnt;
	}

	/**
	 * 添加取消次数
	 */
	public function add_times($user_id, $plat_id, $account_id) {

		if ($plat_id == '2') $plat_id = '1';

		$d = date('Ymd');

		$this->write_db = $this->load->database('write', true);

		$sql = "update rqf_cancel_record
				set cancel_times = cancel_times + 1
				where cancel_date = {$d}
				and user_id = {$user_id}
				and plat_id = {$plat_id}
				and account_id = {$account_id}";

		$this->write_db->query($sql);

		if (!$this->write_db->affected_rows()) {

			$cancel_record_ins = [
				'cancel_date'=>$d,
				'user_id'=>$user_id,
				'plat_id'=>$plat_id,
				'account_id'=>$account_id,
				'cancel_times'=>1
			];

			$this->write_db->delete('rqf_cancel_record', ['cancel_date'=>$d, 'user_id'=>$user_id, 'plat_id'=>$plat_id, 'account_id'=>$account_id]);

			$this->write_db->insert('rqf_cancel_record', $cancel_record_ins);
		}

		$this->write_db->close();
	}
}

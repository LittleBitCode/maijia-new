<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function redirectmessage($uri = '', $message = '', $refresh_txt = '个人中心', $refresh_time = 5)
	{
		$CI =& get_instance();
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = $CI->config->site_url($uri);
		}
		
		$data = array('uri'=>$uri, 'message'=>$message, 'refresh_txt'=>$refresh_txt, 'refresh_time'=>$refresh_time);
		$data['html_title'] = $message;
		$CI->load->view('common/redirect', $data);
	}
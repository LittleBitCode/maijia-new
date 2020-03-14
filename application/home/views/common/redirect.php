<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $message; ?>-<?php echo PROJECT_NAME; ?></title>
<link rel="shortcut icon" href="/static/imgs/favicon.ico"/>
<link rel="stylesheet" href="/static/css/common.css"/>
<?php if ($refresh_time == 0): ?><meta http-equiv="refresh" content="0;url=<?php echo $uri; ?>"><?php endif; ?>
</head>
<body>
<?php
$data['left_index'] = '';
$this->load->model('User_Model', 'user');
$this->load->model('Review_Model', 'review');
$user_id = $this->session->userdata('user_id');
$data['user_info'] = $this->user->get_user_info($user_id);
$data['review_plat_cnts'] = $this->review->review_plat_cnts();
$data['is_vip'] = '';
$this->load->view("common/top", $data);
?>
<div style="height: 500px;">
    <div style="width: 1000px;margin: 0 auto;margin-top: 20px;border: 1px #ddd solid;border-radius: 6px;background: #fff;text-align: center;line-height: 140px;font-size: 20px;color: #666;">
        <?php echo $message; ?>，
        <span class="J_box_timeout"><?php echo $refresh_time; ?></span>s后返回&nbsp;<a style="color: #00A3EF;;" href="<?php echo $uri; ?>"><?php echo $refresh_txt; ?> &gt;</a>
    </div>
</div>
<?php $this->load->view("common/footer"); ?>
<script>
    $(function () {
        _timeout_limit = <?php echo $refresh_time;?>;
        $('.J_box_timeout').html(_timeout_limit);
        var _timeout = setInterval(function () {
            _timeout_limit--;
            if (_timeout_limit <= 0) {
                location.href = "<?php echo $uri;?>";
                clearInterval(_timeout);
            }
            $('.J_box_timeout').text(_timeout_limit);
        }, 1 * 1000);
    });
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/css/common.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/center.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/css/business_center.css?v=<?= VERSION_TXT ?>" />
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<title>已完成的活动-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'manage']); ?>
    <div class="center_box">
        <div class="business_center">
            <!-- 待处理活动start -->
            <div class="pending_tasks">
                <iframe src="/frame/trade_list_frame/3" id="trade_list" frameborder="0" scrolling="no" width="100%" height="1000"></iframe>
            </div>
        </div>
    </div>
    <?php $this->load->view("/common/footer"); ?>
    <div id="iframe_popup"></div>
</body>
</html>
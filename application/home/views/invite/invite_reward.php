<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/css/common.css">
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/invite.css">
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<title>邀请会员-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
    <?php $this->load->view("/common/top1", ['site' => 'invite']); ?>
        <div style="width: 1170px;margin: auto;">
    <?php $this->load->view("/common/test", ['site' => 'recode']); ?>
	<div class="content flex" style="width: 947px;float: left;">
        <div class="right_wrap">
            <div class="right_content">
                <p class="title2">商家发布活动完成的奖励</p>
                <table class="invite_list">
                    <thead>
                        <th width="18%">日期</th>
                        <th width="16%">接单人数</th>
                        <th width="16%">接单数量</th>
                        <th width="16%">完成单数</th>
                        <th width="18%">奖励金币</th>
                        <th width="16%">操作</th>
                    </thead>
                    <tbody>
                    <?php  if(!empty($result['data'])): ?>
                    <?php foreach ($result['data'] as $key => $value):  ?>
                        <tr>
                            <th width="18%"><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
                            <th width="16%"><?php echo $value->total_order_pnums ?>人</th>
                            <th width="16%"><?php echo $value->total_order_nums ?>人</th>
                            <th width="16%"><?php echo $value->total_finish_nums ?>人</th>
                            <th width="18%"><span><?php echo $value->total_reward_points ?></span>金币</th>
                            <th width="16%"><a href="/invite/invite_reward_detaile/<?php echo $value->record_date  ?>">查看详情&gt;</a></th>
                        </tr>
                     <?php endforeach; ?>
                     <?php endif; ?>
                    </tbody>
                </table>
             <!-- 分页 begin -->
            <div class="pager"><?php echo $result['pagination']; ?></div>
            <!-- 分页 end -->

            </div>
        </div>
    </div>
    </div>
    <!-- 示例截图 -->
    <?php $this->load->view("/common/footer"); ?>
</body>
</html>
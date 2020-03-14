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
				<p class="title2">邀请会员记录</p>
                <table class="invite_list">
                    <thead>
	                    <th width="13%">日期</th>
	                    <th width="13%">邀请人数</th>

	                    <th width="13%">还未购买</th>
	                    <th width="16%">已购买\续费</th>
	                   
	                    <th width="13%">奖励金币</th>
	                    <th width="15%">操作</th>
                    </thead>
					<tbody>
                    <?php  if(!empty($result['data'])): ?>
                    <?php foreach ($result['data'] as $key => $value):  ?>		
                        <tr>
						<th width="13%"><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
	                    <th width="13%"><?php echo $value->total_invite_nums ?>人</th>
	                    
	                    <th width="13%"><?php echo $value->total_invite_nums - $value->total_vip_nums < 0 ? 0 : $value->total_invite_nums - $value->total_vip_nums ?>人</th>
	                    <th width="16%"><?php echo $value->total_vip_nums ?>人</th>
	                  
	                    <th width="13%"><span><?php echo $value->total_reward_points ?></span>金币</th>
	                    <th width="15%"><a href="/invite/invite_record_detail/<?php echo $value->record_date  ?>">查看详情&gt;</a></th>
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
	
	
	<script type="text/javascript">
		$(function(){
			
		})

	</script>
</body>
</html>
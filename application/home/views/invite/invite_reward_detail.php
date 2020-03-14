<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/common.css">
<link rel="stylesheet" href="/static/css/invite.css">
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<title>邀请会员-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'invite']); ?>
	<div class="content flex" >
		<div class="right_wrap">
			<div class="right_content">
				<p class="title2">买手完成活动的奖励</p>
                <table class="invite_list invite_buy_list">
                    <thead>
	                    <th width="20%">日期</th>
	                    <th width="20%">接单人数</th>
	                    <th width="20%">接单数量</th>
	                    <th width="20%">完成单数</th>
	                    <th width="20%">奖励金币</th>
                    </thead>
                    <?php  if($result['reward']): ?>
					<tbody>
						<tr style="border-bottom: 1px #ddd solid;">
							<th width="20%"><?php echo date('Y-m-d', strtotime($result['reward']->record_date)) ?></th>
		                    <th width="20%"><?php echo $result['reward']->total_order_pnums ?>人</th>
		                    <th width="20%"><?php echo $result['reward']->total_order_nums ?></th>
		                    <th width="20%"><?php echo $result['reward']->total_finish_nums ?></th>
		                    <th width="20%"><span><?php echo $result['reward']->total_reward_points ?></span>金币</th>
						</tr>
					</tbody>
					<?php endif; ?>
                </table>

                <?php  if($result['lists']): ?>
                <table class="invite_list invite_buy_list">
                    <thead>
	                    <th width="18%">排名</th>
	                    <th width="16%">会员ID</th>
	                    <th width="16%">接手单数</th>
	                    <th width="16%">完成单数</th>
	                    <th width="18%">奖励金币</th>
	                    <th width="16%">操作</th>
                    </thead>
					<tbody>
						<?php foreach ($result['lists'] as $key => &$value):  ?>
						<tr>
							<th width="18%"><?php  echo $key; ?></th>
		                    <th width="16%"><?php  echo $value->user_id; ?></th>
		                    <th width="16%"><?php  echo $value->count; ?></th>
		                    <th width="16%"><?php  echo $value->count; ?></th>
		                    <th width="18%"><span><?php  echo $value->p_reward_points+$value->pp_reward_points; ?></span>金币</th>
		                    <th width="16%"><a class="see_details" href="javascript:;">查看明细</a></th>
						</tr>
                        <?php if($result['lists'][$key]->lists): ?>
						<tr style="display: none;border-bottom: none;">
							<th colspan="6">
								<div class="invite_buy_task">
									<span class="arrow_top_span"></span>
									<table class="invite_list">
										<tbody>
                       					   <?php foreach ($result['lists'][$key]->lists as $k => &$v):  ?>
											<tr>
												<th width="40%">活动单号：<?php  echo $v->order_sn; ?></th>
							                    <th width="30%">活动金额：<?php  echo $v->order_money; ?></th>
							                    <th width="30%">奖励：<span class="color_red"><?php  echo $v->p_reward_points+$v->pp_reward_points; ?></span>金币</th>
											</tr>
											<?php endforeach; ?>
										</tbody>
					                </table>
								</div>
							</th>
						</tr>
						<?php endif; ?>
					<?php endforeach; ?>
					</tbody>
                </table>
                <?php  endif; ?>
                <div class="pager">&nbsp;</div>
			</div>
		</div>
	</div>

	<?php $this->load->view("/common/footer"); ?>

	<script type="text/javascript">
		$(function(){
			$(".see_details").click(function(event) {
				if($(this).parents('tr').hasClass('open')){
					$(this).parents('tr').removeClass('open').next('tr').hide();
				}else{
					$(this).parents('tr').addClass('open').next('tr').show();
				}
			});
		});

	</script>
</body>
</html>
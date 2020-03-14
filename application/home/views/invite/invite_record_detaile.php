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
<link rel="stylesheet" href="/static/css/invite.css">
<title>邀请会员-互利符</title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'invite']); ?>
	<div class="wrap">
		<p class="where_page"><a href="/">首页</a>&gt;<a href="javascript:;">邀请会员</a></p>
	</div>

	<div class="content flex" >
	   	<?php $this->load->view("/common/left"); ?>
		<div class="right_wrap">
			<div class="right_content">
				<p class="title2">邀请会员记录详情</p>
                <table class="invite_list">
                    <thead>
	                    <th width="15%">日期</th>
	                    <th width="15%">邀请人数</th>

	                    <th width="13%">还未购买</th>
	                    <th width="16%">已购买\已续费</th>
	                 
	                    <th width="15%">奖励金币</th>
                    </thead>


                    <?php  if($result['record']): ?>
					<tbody>
						<tr>
							<th width="15%"><?php echo date('Y-m-d', strtotime($result['record']->record_date)) ?></th>
		                    <th width="15%"><?php echo $result['record']->total_invite_nums ?>人</th>

		                    <th width="13%"><?php echo $result['record']->total_invite_nums - $result['record']->total_vip_nums < 0 ? 0 : $result['record']->total_invite_nums - $result['record']->total_vip_nums ?>人</th>
		                    <th width="16%"><?php echo $result['record']->total_vip_nums ?>人</th>
		               
		                    <th width="15%"><span><?php echo $result['record']->total_reward_points ?></span>金币</th>
						</tr>
					</tbody>

                </table>
                <div class="invite_record_detaile">
                	<span class="arrow_top_span"></span>
					<div class="invite_record_detaile_nav">
						<a class="active invite_record_detaile_item" href="javascript:;">所有记录</a>
						<a class="invite_record_detaile_item" href="javascript:;">已邀请注册（<?php echo $result['record']->total_invite_nums ?>）</a>
						<a class="invite_record_detaile_item" href="javascript:;">已购买会员（<?php echo $result['record']->total_vip_nums ?>）</a>
						<a class="invite_record_detaile_item" href="javascript:;">已续费会员（<?php echo $result['record']->total_renew_vip_nums ?>）</a>
					</div>
					<?php  endif; ?>


					<table class="invite_list">
	                    <thead>
		                    <th width="13%">会员ID</th>
		                    <th width="13%">会员类型</th>
		                    <th width="13%">注册时间</th>
		                    <th width="13%">状态</th>
		                    <th width="16%">购买时长</th>
		                    <th width="13%">联系方式</th>
		                    <th width="15%">奖励金币</th>

	                    </thead>

						<tbody class="invite_record_detaile1">
						<?php  if(!empty($result['lists'])): ?>
                   	    <?php foreach ($result['lists'] as $key => $value):  ?>		
							<tr>
								<th width="13%"><?php echo $value->user_name ?></th>
			                    <th width="10%"><?php echo $value->type == 1 ? '商家' : '买手'  ?></th>
			                    <th width="13%"><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
			                    <th width="13%"><?php echo $value->is_reward == 0 ? '未奖励' : '已奖励' ?></th>
			                    <th width="16%"><?php echo $value->pay_month.'个月' ?></th>
			                    <th width="13%"><?php echo $value->user_name ?></th>
			                    <th width="15%"><span><?php echo $value->reward_points ?></span>金币</th>
							</tr>
					    <?php endforeach; ?>
                        <?php endif; ?>
						</tbody>


						<tbody class="invite_record_detaile2" style="display:none;">
						<?php  if(!empty($result['lists'])): ?>
                   	    <?php foreach ($result['lists'] as $key => $value):  ?>		
                   	    	<?php  if($value->record_flag == 0): ?>
							<tr>
								<th width="13%"><?php echo $value->user_name ?></th>
			                    <th width="10%"><?php echo $value->type == 1 ? '商家' : '买手'  ?></th>
			                    <th width="13%"><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
			                    <th width="13%"><?php echo $value->is_reward == 0 ? '未奖励' : '已奖励' ?></th>
			                    <th width="16%"><?php echo $value->pay_month.'个月' ?></th>
			                    <th width="13%"><?php echo $value->user_name ?></th>
			                    <th width="15%"><span><?php echo $value->reward_points ?></span>金币</th>
							</tr>
							<?php endif; ?>
						<?php endforeach; ?>
                        <?php endif; ?>
						</tbody>

						<tbody class="invite_record_detaile3" style="display:none;">
						<?php  if(!empty($result['lists'])): ?>
                   	    <?php foreach ($result['lists'] as $key => $value):  ?>		
                   	    	<?php  if($value->record_flag == 1): ?>
							<tr>
								<th width="13%"><?php echo $value->user_name ?></th>
			                    <th width="10%"><?php echo $value->type == 1 ? '商家' : '买手'  ?></th>
			                    <th width="13%"><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
			                    <th width="13%"><?php echo $value->is_reward == 0 ? '未奖励' : '已奖励' ?></th>
			                    <th width="16%"><?php echo $value->pay_month.'个月' ?></th>
			                    <th width="13%"><?php echo $value->user_name ?></th>
			                    <th width="15%"><span><?php echo $value->reward_points ?></span>金币</th>
							</tr>
							<?php endif; ?>
						<?php endforeach; ?>
                        <?php endif; ?>
						</tbody>
						
						<tbody class="invite_record_detaile4" style="display:none;">
						<?php  if(!empty($result['lists'])): ?>
                   	    <?php foreach ($result['lists'] as $key => $value):  ?>		
                   	    	<?php  if($value->record_flag == 2): ?>
							<tr>
								<th width="13%"><?php echo $value->user_name ?></th>
			                    <th width="10%"><?php echo $value->type == 1 ? '商家' : '买手'  ?></th>
			                    <th width="13%"><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
			                    <th width="13%"><?php echo $value->is_reward == 0 ? '未奖励' : '已奖励' ?></th>
			                    <th width="16%"><?php echo $value->pay_month.'个月' ?></th>
			                    <th width="13%"><?php echo $value->user_name ?></th>
			                    <th width="15%"><span><?php echo $value->reward_points ?></span>金币</th>
							</tr>
							<?php endif; ?>
						<?php endforeach; ?>
                        <?php endif; ?>
						</tbody>
	                </table>
                </div>
			</div>
		</div>
	</div>

	
	<!-- 示例截图 -->
	<?php $this->load->view("/common/footer"); ?>
	
	
	<script type="text/javascript">
		$(function(){
			$(".invite_record_detaile_item").click(function(){
				$(this).addClass("active").siblings(".invite_record_detaile_item").removeClass("active");
				var index = $(this).index()+1;
				$(".invite_record_detaile"+ index).show().siblings("tbody").hide();
			})
		})

	</script>
</body>
</html>
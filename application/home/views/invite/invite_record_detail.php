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
<link rel="stylesheet" href="/static/css/flat-ui.css" />
<link rel="stylesheet" href="/static/css/layout.css" />
<link rel="stylesheet" href="/static/css/non-responsive.css" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<title>邀请会员-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'invite']); ?>
	<div class="content flex" >
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
					<tbody>
						<tr>
							<th width="15%"><?php echo date('Y-m-d', strtotime($result['record']['record_date'])) ?></th>
		                    <th width="15%"><?php echo $result['record']['total_invite_nums'] ?>人</th>
		                    <th width="13%"><?php echo $result['record']['total_invite_nums'] - $result['record']['total_vip_nums'] < 0 ? 0 : $result['record']['total_invite_nums'] - $result['record']['total_vip_nums'] ?>人</th>
		                    <th width="16%"><?php echo $result['record']['total_vip_nums'] ?>人</th>
		                    <th width="15%"><span><?php echo $result['record']['total_reward_points'] ?></span>金币</th>
						</tr>
					</tbody>
                </table>
                <div class="invite_record_detaile">
                	<span class="arrow_top_span"></span>
                    <div class="tab">
                        <ul class="nav nav-tabs" id="myTab_two">
                            <li class="active" style="text-align:center;">
                                <a href="#invite_record_detaile1" data-toggle="tab"><i class="fa fa-user"></i>所有记录</a>
                            </li>
                            <li style="text-align:center;">
                                <a href="#invite_record_detaile2" data-toggle="tab"><i class="fa fa-envelope"></i>已邀请注册（<?php echo $result['record']['total_invite_nums'] ?>）</a>
                            </li>
                            <li style="text-align:center;">
                                <a href="#invite_record_detaile3" data-toggle="tab"><i class="fa fa-envelope"></i>已购买会员（<?php echo $result['record']['total_vip_nums'] ?>）</a>
                            </li>
                            <li style="text-align:center;">
                                <a href="#invite_record_detaile4" data-toggle="tab"><i class="fa fa-envelope"></i>已续费会员（<?php echo $result['record']['total_renew_vip_nums'] ?>）</a>
                            </li>
                        </ul>
                    </div>
                    <div class="invite_list tab-content">
                        <div id="invite_record_detaile1" class="tab-pane fade in active">
                            <table class="invite_list">
                                <thead>
                                <th>会员ID</th>
                                <th>会员类型</th>
                                <th>注册时间</th>
                                <th>状态</th>
                                <th>购买时长</th>
                                <th>联系方式</th>
                                <th>奖励金币</th>
                                </thead>
                                <tbody>
                                <?php foreach ($result['lists'] as $key => $value): ?>
                                    <tr>
                                        <th><?php echo $value->user_name ?></th>
                                        <th><?php echo $value->type == 1 ? '商家' : '买手' ?></th>
                                        <th><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
                                        <th><?php echo $value->is_reward == 0 ? '未奖励' : '已奖励' ?></th>
                                        <th><?php echo $value->pay_month . '个月' ?></th>
                                        <th><?php echo $value->qq ?></th>
                                        <th><span><?php echo $value->reward_points ?></span>金币</th>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="invite_record_detaile2" class="tab-pane fade in">
                            <table class="invite_list">
                                <thead>
                                <th>会员ID</th>
                                <th>会员类型</th>
                                <th>注册时间</th>
                                <th>状态</th>
                                <th>购买时长</th>
                                <th>联系方式</th>
                                <th>奖励金币</th>
                                </thead>
                                <tbody>
                                <?php foreach ($result['lists'] as $key => $value):  ?>
                                    <?php  if($value->record_flag == 0): ?>
                                        <tr>
                                            <th><?php echo $value->user_name ?></th>
                                            <th><?php echo $value->type == 1 ? '商家' : '买手'  ?></th>
                                            <th><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
                                            <th><?php echo $value->is_reward == 0 ? '未奖励' : '已奖励' ?></th>
                                            <th><?php echo $value->pay_month.'个月' ?></th>
                                            <th><?php echo $value->qq ?></th>
                                            <th><span><?php echo $value->reward_points ?></span>金币</th>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="invite_record_detaile3" class="tab-pane fade in">
                            <table class="invite_list">
                                <thead>
                                <th>会员ID</th>
                                <th>会员类型</th>
                                <th>注册时间</th>
                                <th>状态</th>
                                <th>购买时长</th>
                                <th>联系方式</th>
                                <th>奖励金币</th>
                                </thead>
                                <tbody>
                                <?php foreach ($result['lists'] as $key => $value):  ?>
                                    <?php  if($value->record_flag == 1): ?>
                                        <tr>
                                            <th><?php echo $value->user_name ?></th>
                                            <th><?php echo $value->type == 1 ? '商家' : '买手'  ?></th>
                                            <th><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
                                            <th><?php echo $value->is_reward == 0 ? '未奖励' : '已奖励' ?></th>
                                            <th><?php echo $value->pay_month.'个月' ?></th>
                                            <th><?php echo $value->qq ?></th>
                                            <th><span><?php echo $value->reward_points ?></span>金币</th>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="invite_record_detaile4" class="tab-pane fade in">
                            <table class="invite_list">
                                <thead>
                                <th>会员ID</th>
                                <th>会员类型</th>
                                <th>注册时间</th>
                                <th>状态</th>
                                <th>购买时长</th>
                                <th>联系方式</th>
                                <th>奖励金币</th>
                                </thead>
                                <tbody>
                                <?php foreach ($result['lists'] as $key => $value):  ?>
                                    <?php  if($value->record_flag == 2): ?>
                                        <tr>
                                            <th><?php echo $value->user_name ?></th>
                                            <th><?php echo $value->type == 1 ? '商家' : '买手'  ?></th>
                                            <th><?php echo date('Y-m-d', strtotime($value->record_date)) ?></th>
                                            <th><?php echo $value->is_reward == 0 ? '未奖励' : '已奖励' ?></th>
                                            <th><?php echo $value->pay_month.'个月' ?></th>
                                            <th><?php echo $value->qq ?></th>
                                            <th><span><?php echo $value->reward_points ?></span>金币</th>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
	<!-- 示例截图 -->
	<?php $this->load->view("/common/footer"); ?>
</body>
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js"></script>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
</html>
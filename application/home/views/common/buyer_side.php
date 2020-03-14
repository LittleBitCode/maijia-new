<!-- 在需要的地方引用 -->
	<!-- 个人中心左侧 侧边 -->
		<div class="left_side">
			<div class="side_nav">
				<dl style="border-top:none;">
					<dt>活动管理</dt>
					<?php foreach ($left_plat_cnt_list as $k=>$v): ?>
					<dd <?php if ($left_index == "order_manage_{$k}"): ?>class="click_active"<?php endif; ?>>
						<a href="/center/order_manage?plat_id=<?php echo $k; ?>"><?php echo $v['pname']; ?><span>(<?php echo $v['cnt']; ?>)</span></a>
					</dd>
					<?php endforeach; ?>
				</dl>
				<a href="/center/bind" target="_self" class="buy_add">+添加买号</a>
			    <dl>
					<dt>邀请会员</dt>
					<dd <?php if ($left_index == 'invite_url'): ?>class="click_active"<?php endif; ?>>
						<a target="_self" href="/invite/invite_url">邀请会员</a>
					</dd>
					<dd <?php if ($left_index == 'invite_record'): ?>class="click_active"<?php endif; ?>>
						<a target="_self" href="/invite/invite_record">邀请会员记录</a>
					</dd>
					<dd <?php if ($left_index == 'invite_reward'): ?>class="click_active"<?php endif; ?>>
						<a target="_self" href="/invite/invite_reward">活动奖励记录</a>
					</dd>
					<dd <?php if ($left_index == 'failure_reward'): ?>class="click_active"<?php endif; ?>>
						<a target="_self" href="/invite/failure_reward">失效的奖励</a>
					</dd>
				</dl>
				<dl>
					<dt>资金记录</dt>
					<dd <?php if ($left_index == 'deposit_with_record'): ?>class="click_active"<?php endif; ?>>
						<a target="_self" href="/center/record_list">本金提现记录</a>
						<em><a href="/withdrawal/deposit">提现</a></em>
					</dd>
					<dd <?php if ($left_index == 'point_with_record'): ?>class="click_active"<?php endif; ?>>
						<a target="_self" href="/center/record_list/2">金币提现记录</a><em>
						<a href="/withdrawal/point">提现</a></em>
					</dd>
					<dd <?php if ($left_index == 'snatch_record'): ?>class="click_active"<?php endif; ?>>
						<a target="_self" href="/center/record_list/6">夺宝币记录</a>
					</dd>
			        <dd <?php if ($left_index == 'point_record'): ?>class="click_active"<?php endif; ?>>
			        	<a target="_self" href="/center/record_list/3">金币记录</a>
			        </dd>
			        <dd <?php if ($left_index == 'reward_record'): ?>class="click_active"<?php endif; ?>>
			        	<a target="_self" href="/center/record_list/4">佣金记录</a>
			        </dd>
			        <dd <?php if ($left_index == 'group_record'): ?>class="click_active"<?php endif; ?>>
			        	<a target="_self" href="/center/record_list/5">会员记录</a>
			        </dd>
				</dl>
				<dl>
					<dt>账号信息</dt>
					<dd <?php if ($left_index == 'user_info'): ?>class="click_active"<?php endif; ?>><a target="_self" href="/center/user_info" >基本信息</a></dd>
				</dl>
			    <a href="/center/withdrawal_info?left_list_id=2" target="_self" class="buy_add">+提现账号设置</a>
				<!-- <dl>
					<dd><a target="_self" href="">申述中心</a></dd>
				</dl> -->
			</div>
			<div class="side_nav">
				<img src="/static/imgs/qr_code/ewm0718.png" width="150" />
			</div>
			<img src="/static/imgs/qr_code/yyg_ewm_0726.png" width="150" />
		</div>

<script>
	$("dd").hover(function() {
		$(this).addClass('active');
	}, function() {
		
		$(this).removeClass('active');
		
	});

</script>
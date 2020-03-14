<link rel="stylesheet" href="/static/css/left.css" />
<div class="left">
	<dl style="border-top:none;">
		<dt>活动管理</dt>
        <dd <?php if ($left_index == 'trade_finished'): ?>class="left_list_dd"<?php endif; ?>>
        	<a href="/center/trade_finished">已完成的活动</a>
        </dd>
        
        <?php foreach ($review_plat_cnts as $k=>$v): ?>
		<dd <?php if ($left_index == "trade_manage_{$k}"): ?>class="left_list_dd"<?php endif; ?>>
			<a href="/center/trade_manage?plat_id=<?php echo $k; ?>"><?php echo $v['pname']; ?><span>(<?php echo $v['cnt']; ?>)</span></a>
		</dd>
        <?php endforeach; ?>
	</dl>
	<a href="/center/bind" class="buy_add">+绑定店铺</a>
    <dl>
		<dt>邀请会员</dt>
		<dd <?php if ($left_index == 'invite_url'): ?>class="left_list_dd"<?php endif; ?>><a href="/invite/invite_url">邀请会员</a></dd>
		<dd <?php if ($left_index == 'invite_record'): ?>class="left_list_dd"<?php endif; ?>><a href="/invite/invite_record">邀请会员记录</a></dd>
		<dd <?php if ($left_index == 'invite_reward'): ?>class="left_list_dd"<?php endif; ?>><a href="/invite/invite_reward">活动奖励记录</a></dd> 
		<dd <?php if ($left_index == 'failure_reward'): ?>class="left_list_dd"<?php endif; ?>><a href="/invite/failure_reward">失效的奖励</a></dd>
	</dl>
	<dl>
		<dt>资金记录</dt>
		<dd <?php if ($left_index == 'deposit_record'): ?>class="left_list_dd"<?php endif; ?>><a href="/center/record_list">押金记录</a><em><a href="/withdrawal/deposit">提现</a></em></dd>
		<dd <?php if ($left_index == 'point_record'): ?>class="left_list_dd"<?php endif; ?>><a href="/center/record_list/3">金币记录</a></dd>
	</dl>
	<dl>
		<dt>账号信息</dt>
		<dd <?php if ($left_index == 'user_info'): ?>class="left_list_dd"<?php endif; ?>><a href="/center/user_info">基本信息</a></dd>
		<dd <?php if(intval($this->input->get('left_list_id')) == 2){ echo 'class="left_list_dd"'; } ?> ><a href="/center/withdrawal_info?left_list_id=2" target="_self">提现账号管理</a></dd>
		<div style="text-align: left;margin-left: 12px;">(银行卡|支付宝)</div>
	</dl>
	<!-- <dl>
		<dd><a href="javascript:;">申述中心</a></dd>
	</dl> -->
</div>
<script>
	$('.left dd').hover(function() {
		$(this).addClass('active');
	}, function() {
		$(this).removeClass('active');
	});
</script>
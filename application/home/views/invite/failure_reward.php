<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="x">
    <meta name="keywords" content="x">
    <link rel="shortcut icon" href="/static/imgs/favicon.ico"/>
    <link rel="stylesheet" href="/static/css/common.css">
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css"/>
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
            <p class="title2">失效的奖励</p>
            <div class="tab" style="margin-top: -2px;">
                <ul class="nav nav-tabs" id="myTab_two">
                    <li role="presentation" class="<?= ($type == 'all') ? 'active' : ''; ?>" style="text-align:center;">
                        <a href="/invite/failure_reward/all"><i class="fa fa-user"></i>所有会员</a>
                    </li>
                    <li role="presentation" class="<?= ($type == 'novip') ? 'active' : ''; ?>" style="text-align:center;">
                        <a href="/invite/failure_reward/novip"><i class="fa fa-envelope"></i>还没有购买VIP的会员</a>
                    </li>
                    <li role="presentation" class="<?= ($type == 'expired') ? 'active' : ''; ?>" style="text-align:center;">
                        <a href="/invite/failure_reward/expired"><i class="fa fa-envelope"></i>已过期的会员</a>
                    </li>
                    <li role="presentation" class="<?= ($type == 'days_30') ? 'active' : ''; ?>" style="text-align:center;">
                        <a href="/invite/failure_reward/days_30"><i class="fa fa-envelope"></i>超过30天没有报名活动</a>
                    </li>
                </ul>
            </div>

            <!-- 所有会员 -->
            <table class="invite_list" style="<?= ($type == 'all') ? '' : 'display:none'; ?>">
                <thead>
                <th width="18%"><b>会员ID</b></th>
                <th width="16%"><b>会员类型</b></th>
                <th width="16%"><b>注册日期</b></th>
                <th width="20%"><b>状态</b></th>
                <th width="30%"><b>联系方式</b></th>
                </thead>
                <tbody>
                <tr><th colspan="5"><p class="color_red">以下会员您已邀请，但是目前您未能获得相应奖励，请联系一下会员购买VIP或继续做活动，以便获得奖励</p></th></tr>
                <?php if ($all): ?>
                    <?php foreach ($all as $key => $value): ?>
                        <tr>
                            <td><?php echo $value->nickname; ?></td>
                            <td><?php echo $value->user_type == 1 ? '商家' : '买手'; ?></th>
                            <td><?php echo date('Y-m-d', $value->reg_time); ?></th>
                            <td><?php echo $value->title; ?></th>
                            <td><?php echo $value->qq_decode; ?></th>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <?php if (!$all): ?><p class="invlte_empty" style="<?php if ($type == 'all') echo 'display:block'; else echo 'display:none'; ?>">当前没有失效的记录</p><?php endif; ?>

            <!-- 还没有购买VIP的会员 -->
            <table class="invite_list" style="<?= ($type == 'novip') ? '' : 'display:none'; ?>">
                <thead>
                <th width="18%"><b>会员ID</b></th>
                <th width="16%"><b>会员类型</b></th>
                <th width="16%"><b>注册日期</b></th>
                <th width="20%"><b>状态</b></th>
                <th width="30%"><b>联系方式</b></th>
                </thead>
                <tbody>
                <tr><th colspan="5"><p class="color_red">以下会员您已邀请，但是目前您未能获得相应奖励，请联系一下会员购买VIP或继续做活动，以便获得奖励</p></th></tr>
                <?php if ($novip): ?>
                    <?php foreach ($novip as $key => $value): ?>
                        <tr>
                            <td><?php echo $value->nickname; ?></th>
                            <td><?php echo $value->user_type == 1 ? '商家' : '买手'; ?></th>
                            <td><?php echo date('Y-m-d', $value->reg_time); ?></th>
                            <td><?php echo $value->title; ?></th>
                            <td><?php echo $value->qq_decode; ?></th>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <?php if (!$novip): ?><p class="invlte_empty" style="<?php if ($type == 'novip') echo 'display:block'; else echo 'display:none'; ?>">当前没有失效的记录</p><?php endif; ?>

            <!-- 已过期的会员 -->
            <table class="invite_list" style="<?= ($type == 'expired') ? '' : 'display:none'; ?>">
                <thead>
                <th width="18%"><b>会员ID</b></th>
                <th width="16%"><b>会员类型</b></th>
                <th width="16%"><b>注册日期</b></th>
                <th width="20%"><b>状态</b></th>
                <th width="30%"><b>联系方式</b></th>
                </thead>
                <tbody>
                <tr><th colspan="5"><p class="color_red">以下会员已过期，您可以通过QQ联系邀请的会员进行续费，待续费成功后即可获得奖励</p></th></tr>
                <?php if ($expired): ?>
                    <?php foreach ($expired as $key => $value): ?>
                        <tr>
                            <td><?php echo $value->nickname; ?></th>
                            <td><?php echo $value->user_type == 1 ? '商家' : '买手'; ?></th>
                            <td><?php echo date('Y-m-d', $value->reg_time); ?></th>
                            <td><?php echo $value->title; ?></th>
                            <td><?php echo $value->qq_decode; ?></th>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <?php if (!$expired): ?><p class="invlte_empty" style="<?php if ($type == 'expired') echo 'display:block'; else echo 'display:none'; ?>">当前没有失效的记录</p><?php endif; ?>

            <!-- 超过30天没有接手活动 -->
            <table class="invite_list" style="<?= ($type == 'days_30') ? '' : 'display:none'; ?>">
                <thead>
                <th width="18%"><b>会员ID</b></th>
                <th width="16%"><b>会员类型</b></th>
                <th width="16%"><b>注册日期</b></th>
                <th width="20%"><b>状态</b></th>
                <th width="30%"><b>联系方式</b></th>
                </thead>
                <tbody>
                <tr><th colspan="5"><p class="color_red">以下会员已接受您成功邀请，目前超过30天没有接手活动，您可以通过QQ联系会员，待完成活动后即可获得奖励</p></th></tr>
                <?php if ($days_30): ?>
                    <?php foreach ($days_30 as $key => $value): ?>
                        <tr>
                            <td><?php echo $value->nickname; ?></th>
                            <td><?php echo $value->user_type == 1 ? '商家' : '买手'; ?></th>
                            <td><?php echo date('Y-m-d', $value->reg_time); ?></th>
                            <td><?php echo $value->title; ?></th>
                            <td><?php echo $value->qq_decode; ?></th>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <?php if (!$days_30): ?><p class="invlte_empty" style="<?php if ($type == 'days_30') echo 'display:block'; else echo 'display:none'; ?>">当前没有失效的记录</p><?php endif; ?>
            <!-- 分页 begin -->
            <div class="pager"><?php echo $pagination; ?></div>
            <!-- 分页 end -->
        </div>
    </div>
</div>
</div>
<!-- 示例截图 -->
<?php $this->load->view("/common/footer"); ?>
</body>
</html>
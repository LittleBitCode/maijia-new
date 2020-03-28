<link rel="stylesheet" href="/static/css/flat-ui.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/css/layout.css?v=<?= VERSION_TXT ?>" />
<header class="nav_head">
    <div class="nav_head_t">
        <div class="contain">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/" style="padding: 12px 15px;"><img src="/static/imgs/logo.png" alt="logo" /></a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/recharge/deposit">押金：<?= $user_info->user_deposit*1; ?></a></li>
                    <li><a href="/recharge/point">金币：<?= $user_info->user_point*1; ?></a></li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><?= $this->session->userdata('mobile'); ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/center/user_info">账号设置</a></li>
                            <li><a href="/center/withdrawal_info">提现账号管理</a></li>
                        </ul>
                    </li>
                    <li><a href="/user/logout">退出</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="nav_head_b contain">
        <nav class="navbar navbar-default navbar-lg" role="navigation">
            <ul class="nav navbar-nav navbar-left">
                <li class="<?= ($site=='index') ? 'active': '' ?>"><a href="/">首页</a></li>
                <li class="<?= ($site=='trade') ? 'active': '' ?>"><a href="/trade/step">报名活动</a></li>
                <li class="dropdown <?= ($site=='manage') ? 'active': '' ?>">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">活动管理<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/trade/step">报名活动</a></li>
                        <li><a href="/center/trade_finished">已完成的活动</a></li>
                        <?php foreach ($review_plat_cnts as $k=>$v): ?>
                        <li><a href="/center/trade_manage?plat_id=<?= $k; ?>"><?= $v['pname']; ?>待处理的 (<?= $v['cnt']; ?>)</a></li>
                        <?php endforeach; ?>
                        <li><a href="/review/traffic_list">流量订单</a></li>
                        <li><a href="/center/bind">+绑定店铺</a></li>
                    </ul>
                </li>
                <li class="dropdown <?= ($site=='recode') ? 'active': '' ?>">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">资金记录<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/recharge/deposit">充值押金</a></li>
                        <li><a href="/recharge/point">充值金币</a></li>
                        <li><a href="/group/pay_group"><?= $is_vip ? '续费会员':'开通会员'; ?></a></li>
                        <li><a href="/center/record_list">押金记录</a></li>
                        <li><a href="/center/record_list/3">金币记录</a></li>
                        <li><a href="/center/record_list/2">提现记录</a></li>
                        <li><a href="/withdrawal/deposit">提现</a></li>
                    </ul>
                </li>
                <li class="dropdown <?= ($site=='invite') ? 'active': '' ?>">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">邀请会员<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/invite/invite_url" target="_blank">邀请会员</a></li>
                        <li><a href="/invite/invite_record">邀请会员记录</a></li>
                        <li><a href="/invite/invite_reward">活动奖励记录</a></li>
                        <li><a href="/invite/failure_reward">失效的奖励</a></li>
                    </ul>
                </li>
                <li class="<?= ($site=='member') ? 'active': '' ?>">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">个人信息<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="/center/user_info">账号设置</a></li>
                        <li><a href="/center/withdrawal_info">提现账号管理</a></li>
                    </ul>
                </li>
                <li class="<?= ($site=='finance') ? 'active': '' ?>"><a href="/finance/summary">账房</a></li>
                <li class="<?= ($site=='traffic') ? 'active': '' ?>"><a href="/review/traffic_list">流量订单</a></li>
            </ul>
        </nav>
    </div>
</header>
<!-- 右则通知、消息 -->
<div class="right">
    <ul>
        <li><img src="/static/imgs/icon/notice.png"/></li>
<!--        <li><p>平台</p><p>公告</p></li>-->
<!--        <li><p>常见</p><p>问题</p></li>-->
        <li><a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzgwMDgzMDEyM180ODM4NDBfODAwODMwMTIzXzJf" target="_blank"><p>联系</p><p>客服</p></a></li>
        <li class="bdqrcode"><a href="javascript:;" target="_blank"><p>联系</p><p>运营</p></a><img src="/static/imgs/bd_qrcode.jpg" alt="联系运营" /></li>
        <li class="top"><span class="glyphicon glyphicon-chevron-up"></span></li>
        <div>
            <div class="questions1 questions"></div>
            <div class="questions1 questions">
                <?php foreach ($bus_notice_list as $key => $item): ?>
                    <p class="ellipsis"><a href="<?= $item->url ?>" target="_blank"><?= ($key + 1) . '、' . $item->contents; ?></a></p>
                <?php endforeach; ?>
            </div>
            <div class="questions2 questions">
                <?php foreach ($bus_question_list as $key => $item): ?>
                    <p class="ellipsis"><a href="<?= $item->url ?>" target="_blank"><?= ($key + 1) . '、' . $item->contents; ?></a></p>
                <?php endforeach; ?>
            </div>
        </div>
    </ul>
</div>
<script type="text/javascript" src="/static/js/jquery-1.12.4.min.js?v=<?= VERSION_TXT ?>"></script>
<script type="text/javascript">
    $(function () {
        // 右则导航
        $('.right ul li').hover(function(){
            var index=$(this).index();
            $($('.questions')[index]).fadeIn(300).siblings().fadeOut();
        });
        $('.questions').mouseleave(function(){
            $(this).fadeOut(300);
        });
        $('.right .bdqrcode').mouseenter(function(){
            $(this).find('img').show();
        });
        $('.right .bdqrcode').mouseleave(function () {
            $(this).find('img').hide();
        });
        //回到顶部
        $(window).scroll(function() {
            var t = $(this).scrollTop();
            if (t > 300) {
                $(".top").stop().css('visibility','visible');
            } else {
                $(".top").stop().css('visibility','hidden');
            }
        });
        $(".right .top").click(function() {
            $("body,html").stop().animate({
                scrollTop: 0
            }, 300);
        });
    });
</script>

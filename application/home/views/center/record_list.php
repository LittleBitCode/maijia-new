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
<link rel="stylesheet" href="/static/css/deposit_list.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/toast/toastr.min.css" />
<title>资金记录-<?php echo PROJECT_NAME; ?></title>
<style>
    .form-control{padding: 0 8px !important;height: 34px !important;}
    .deposit_list_box .time_start, .deposit_list_box .time_end {display: inline-block;width: 128px;margin: 0 5px;padding-left: 5px;}
    .search_sub {display: inline-block;text-align: center;background-color: #ff6b71;border: 1px solid #ff6b71;border-radius: 5px;color: #fff;margin-left: 30px;padding:6px 16px !important}
</style>
</head>
<body>
	<?php $this->load->view("/common/top1", ['site' => 'recode']); ?>
    <div style="width: 1170px;margin: auto;">
    <?php $this->load->view("/common/test", ['site' => 'recode']); ?>
    <div class="center_box" style="width: 947px;float: left;">
        <div class="deposit_box">
            <h1>资金记录</h1>
            <div class="tab">
                <ul class="nav nav-tabs" id="myTab_two">
                    <li role="presentation" class="<?= ($t == 1) ? 'active':''; ?>" style="text-align:center;">
                        <a href="/center/record_list"><i class="fa fa-user"></i>押金记录</a>
                    </li>
                    <li role="presentation" class="<?= ($t == 2) ? 'active':''; ?>" style="text-align:center;">
                        <a href="/center/record_list/2"><i class="fa fa-envelope"></i>提现记录</a>
                    </li>
                    <li role="presentation" class="<?= ($t == 3) ? 'active':''; ?>" style="text-align:center;">
                        <a href="/center/record_list/3"><i class="fa fa-envelope"></i>金币记录</a>
                    </li>
                    <li role="presentation" class="<?= ($t == 4) ? 'active':''; ?>" style="text-align:center;">
                        <a href="/center/record_list/4"><i class="fa fa-envelope"></i>会员记录</a>
                    </li>
                    <li role="presentation" class="<?= ($t == 5) ? 'active':''; ?>" style="text-align:center;">
                        <a href="/center/record_list/5"><i class="fa fa-envelope"></i>充值记录</a>
                    </li>
                </ul>
            </div>

            <div class="deposit_list_box">
                <!-- 押金记录start -->
                <?php if ($t == 1): ?>
                <div class="deposit_list">
                    <form action="/center/record_list" method="get">
                        <div style="padding: 16px 0 16px 32px;">
                            <span>平台：</span>
                            <select name="plat" class="form-control" style="width: 108px;">
                                <option <?= ($params['plat']==0) ? 'selected':''; ?> value="0">全部</option>
                                <?php foreach ($plat_type_list as $key => $item): ?>
                                    <option <?= ($params['plat']==$key) ? 'selected':''; ?> value="<?= $key ?>"><?= $item['pname'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span style="margin-left:16px;">店铺：</span>
                            <select name="shop" class="form-control" style="width: 256px;">
                                <option <?= ($params['shop']==0) ? 'selected':''; ?> value="0">全部</option>
                                <?php foreach ($shop_list as $item): ?>
                                    <option <?= ($params['shop']==$item['id']) ? 'selected':''; ?> value="<?= $item['id'] ?>"><?= $item['shop_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span style="margin-left:16px;">起止时间：</span>
                            <input name="st" type="text" class="form-control time_start" onClick="WdatePicker()" value="<?= $params['start_time'] ?>" /><span>-</span>
                            <input name="et" type="text" class="form-control time_end" onClick="WdatePicker()" value="<?= $params['end_time'] ?>" />
                            <br /><br/>
                            <span>任务编号：</span>
                            <input name="sn" type="text" class="form-control" value="<?= $params['sn'] ?>" style="display: inline-block;width: 256px;"/>
                            <input type="submit" class="btn search_sub" style='background-color: #ff6b71;border: 1px solid #ff6b71;' value="查&nbsp;询" />
                            <input type="button" class="btn btn-default j-export-data" value="导出报表" data-type="deposit" style="margin-left:32px;" />
                        </div>
                    </form>
                    <?php if ($deposit_res): ?>
                    <table class="table table-hover">
                        <thead><tr style="white-space:nowrap"><th>店铺</th><th>收入（元）</th><th>支出（元）</th><th>冻结（元）</th><th>结余（元）</th><th>时间</th><th>活动编号</th><th>备注</th></tr></thead>
                        <tbody>
                        <?php foreach ($deposit_res as $v): ?>
                        <tr style="white-space:nowrap">
                            <td><?= $v->shop_name; ?><?= empty($v->plat_id) ? '--' : ' / '. $v->plat_id ?></td>
                            <?php if ($v->score_nums > 0): ?>
                            <td><span><?php echo $v->score_nums; ?></span></td>
                            <td><span></span></td>
                            <?php else: ?>
                            <td><span></span></td>
                            <td><span><?php echo $v->score_nums; ?></span></td>
                            <?php endif; ?>
                            <td><span><?= number_format($v->frozen_score_nums, 2) ?></span></td>
                            <td><span><?php echo $v->last_score; ?></span></td>
                            <td><?php echo date('Y-m-d H:i', $v->action_time); ?></td>
                            <td><?php echo $v->sn; ?></td>
                            <td><p><?php echo $v->type_name; ?></p></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pager"><?php echo $pagination; ?></div>
                    <?php else: ?>
                    <p class="noRecord">没有记录</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <!-- 押金记录end -->

                <!-- 提现记录start -->
                <?php if ($t == 2): ?>
                <div class="deposit_list">
                    <form action="/center/record_list/2" method="get">
                    <div style="padding: 16px 32px;">
                        <span>起止时间：</span>
                        <input name="st" type="text" class="form-control time_start" onClick="WdatePicker()" value="<?= $params['start_time'] ?>" /><span>-</span>
                        <input name="et" type="text" class="form-control time_end" onClick="WdatePicker()" value="<?= $params['end_time'] ?>" />
                        <span style="margin-left:16px;">提现状态：</span>
                        <select name="status" class="form-control" style="width: 108px;">
                            <option <?= ($params['status']==99) ? 'selected':''; ?> value="99">全部</option>
                            <?php foreach ($status_list as $key => $item): ?>
                            <option <?= ($params['status']==$key) ? 'selected':''; ?> value="<?= $key ?>"><?= $item ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="submit" class="btn search_sub" value="查&nbsp;询" />
                    </div>
                    </form>
                    <?php if ($withdrawal_res): ?>
                    <table class="table table-hover">
                        <thead><tr style="white-space: nowrap;"><th width="15%">提现流水号</th><th width="15%">提现时间</th><th width="12%">金额（元）</th><th width="18%">状态</th><th width="40%">备注</th><th width="15%">操作</th></tr></thead>
                        <tbody>
                        <?php foreach ($withdrawal_res as $v): ?>
                        <tr>
                            <td><?php echo $v->withdrawal_sn; ?></td>
                            <td><?php echo date('Y-m-d H:s', $v->add_time); ?></td>
                            <td><span><?php echo $v->user_amount; ?></span></td>
                            <td><?php echo $v->status_text; ?></td>
                            <td>银行到账预计需要1-3个工作日，请耐心等待到账</td>
                            <td>
                                <?php if ($v->withdrawal_status == '0'): ?>
                                <a href="/withdrawal/cancel/<?php echo $v->id; ?>" onclick="return confirm('确认撤销该笔提现吗？');">撤销提现</a>
                                <?php else: ?>
                                --
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pager"><?php echo $pagination; ?></div>
                    <?php else: ?>
                    <p class="noRecord">没有记录</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <!-- 提现记录end -->

                <!-- 金币记录start -->
                <?php if ($t == 3): ?>
                <div class="deposit_list">
                    <form action="/center/record_list/3" method="get">
                        <div style="padding: 16px 0 16px 32px;">
                            <span>平台：</span>
                            <select name="plat" class="form-control" style="width: 108px;">
                                <option <?= ($params['plat']==0) ? 'selected':''; ?> value="0">全部</option>
                                <?php foreach ($plat_type_list as $key => $item): ?>
                                    <option <?= ($params['plat']==$key) ? 'selected':''; ?> value="<?= $key ?>"><?= $item['pname'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span style="margin-left:16px;">店铺：</span>
                            <select name="shop" class="form-control" style="width: 256px;">
                                <option <?= ($params['shop']==0) ? 'selected':''; ?> value="0">全部</option>
                                <?php foreach ($shop_list as $item): ?>
                                    <option <?= ($params['shop']==$item['id']) ? 'selected':''; ?> value="<?= $item['id'] ?>"><?= $item['shop_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span style="margin-left:16px;">起止时间：</span>
                            <input name="st" type="text" class="form-control time_start" onClick="WdatePicker()" value="<?= $params['start_time'] ?>" /><span>-</span>
                            <input name="et" type="text" class="form-control time_end" onClick="WdatePicker()" value="<?= $params['end_time'] ?>" />
                            <br /><br/>
                            <span>任务编号：</span>
                            <input name="sn" type="text" class="form-control" value="<?= $params['sn'] ?>" style="display: inline-block;width: 256px;"/>
                            <input type="submit" class="btn search_sub" value="查&nbsp;询" />
                            <input type="button" class="btn btn-default j-export-data" data-type="points" value="导出报表" style="margin-left:32px;" />
                        </div>
                    </form>
                    <?php if ($point_res): ?>
                    <table class="table table-hover">
                        <thead><tr style="white-space:nowrap"><th>店铺</th><th>收入（个）</th><th>支出（个）</th><th>冻结（个）</th><th>结余（个）</th><th>时间</th><th>活动编号</th><th>备注</th></tr></thead>
                        <tbody>
                        <?php foreach ($point_res as $v): ?>
                        <tr style="white-space:nowrap">
                            <td><?= $v->shop_name; ?><?= empty($v->plat_id) ? '--' : ' / '. $v->plat_id ?></td>
                            <?php if ($v->score_nums > 0): ?>
                            <td><span><?php echo number_format($v->score_nums, 2); ?></span></td>
                            <td><span></span></td>
                            <?php else: ?>
                            <td><span></span></td>
                            <td><span><?php echo $v->score_nums; ?></span></td>
                            <?php endif; ?>
                            <td><span><?php echo number_format($v->frozen_score_nums, 2); ?></span></td>
                            <td><span><?php echo $v->last_score; ?></span></td>
                            <td><?php echo date('Y-m-d H:i', $v->action_time); ?></td>
                            <td><?php echo $v->sn; ?></td>
                            <td><p><?php echo $v->type_name; ?></p></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pager"><?php echo $pagination; ?></div>
                    <?php else: ?>
                    <p class="noRecord">没有记录</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <!-- 金币记录end -->

                <!-- 会员记录start -->
                <?php if ($t == 4): ?>
                <div class="deposit_list">
                    <form action="/center/record_list/4" method="get">
                        <div style="padding: 16px 32px;">
                            <span>起止时间：</span>
                            <input name="st" type="text" class="form-control time_start" onClick="WdatePicker()" value="<?= $params['start_time'] ?>" /><span>-</span>
                            <input name="et" type="text" class="form-control time_end" onClick="WdatePicker()" value="<?= $params['end_time'] ?>" />
                            <input type="submit" class="btn search_sub" value="查&nbsp;询" />
                        </div>
                    </form>
                    <?php if ($group_res): ?>
                    <table class="table table-hover">
                        <thead><tr><th>购买时间</th><th width="15%">金额（元）</th><th width="20%"><?php echo PROJECT_NAME; ?>（金币）</th><th width="15%">类型</th><th width="50%">备注</th></tr></thead>
                        <tbody>
                        <?php foreach ($group_res as $v): ?>
                        <tr>
                            <td style="white-space: nowrap"><?= date('Y-m-d H:i', $v->add_time); ?></td>
                            <td><span><?php echo $v->pay_deposit; ?></span></td>
                            <td><span><?php echo $v->pay_point; ?></span></td>
                            <td>开通</td>
                            <td><?php echo $v->comments; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pager"><?php echo $pagination; ?></div>
                    <?php else: ?>
                    <p class="noRecord">没有记录</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <!-- 会员记录end -->

                <!-- 充值记录start -->
                <?php if ($t == 5): ?>
                <div class="deposit_list">
                    <?php $pay_status_list = ['99' => '全部', '0'=>'审核中','1'=>'充值成功','2'=>'充值失败']; ?>
                    <form action="/center/record_list/5" method="get">
                        <div style="padding: 16px 32px;">
                            <span>起止时间：</span>
                            <input name="st" type="text" class="form-control time_start" onClick="WdatePicker()" value="<?= $params['start_time'] ?>" /><span>-</span>
                            <input name="et" type="text" class="form-control time_end" onClick="WdatePicker()" value="<?= $params['end_time'] ?>" />
                            <span style="margin-left:16px;">充值状态：</span>
                            <select name="status" class="form-control" style="width: 108px;">
                                <?php foreach ($pay_status_list as $key => $item): ?>
                                    <option <?= ($params['status']==$key) ? 'selected':''; ?> value="<?= $key ?>"><?= $item ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="submit" class="btn search_sub" value="查&nbsp;询" />
                        </div>
                    </form>
                    <?php if ($deposit_res): ?>
                    <table class="table table-hover">
                        <thead><tr>
                            <th width="18%">交易编号</th>
                            <th width="18%">充值时间</th>
                            <th width="15%">充值金额（元）</th>
                            <th width="15%">充值状态</th>
                            <th width="10%">转账截图</th>
                            <th width="">备注</th>
                        </tr></thead>
                        <tbody>
                        <?php foreach ($deposit_res as $v): ?>
                        <tr>
                            <td><span><?php echo $v->pay_sn; ?></span></td>
                            <td><span><?php echo date('Y-m-d H:i',$v->add_time); ?></span></td>
                            <td><span><?php echo $v->pay_third; ?></span></td>
                            <td><?php echo $pay_status_list[$v->pay_status]; ?></td>
                            <td></td>
                            <td><?php echo $v->comment; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pager"><?php echo $pagination; ?></div>
                    <?php else: ?>
                    <p class="noRecord">没有记录</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <!-- 充值记录end -->
            </div>
        </div>
    </div>
    </div>
</body>
<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/My97DatePicker/WdatePicker.js"></script>
<script language="Javascript" src="/static/toast/toastr.min.js"></script>
<script type="text/javascript">
$(function (e) {
    $('.j-export-data').click(function (e) {
        var $this = $(this), _form = $this.parents('form');
        var _start_time = _form.find('input[name="st"]').val(),
            _end_time = _form.find('input[name="et"]').val(),
            _type = $this.data('type');
        if (_start_time == ''){
            toastr.warning('请选择报表导出的起始时间');
            return false;
        }
        if (_end_time == ''){
            toastr.warning('请选择报表导出的结束时间');
            return false;
        }
        if (_type == 'deposit') {
            location.href = '/export/user_deposit_list?st=' + _start_time + '&et=' + _end_time;
        } else {
            location.href = '/export/user_points_list?st=' + _start_time + '&et=' + _end_time;
        }
    })
})
</script>
</html>
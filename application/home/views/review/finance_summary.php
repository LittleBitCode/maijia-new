<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<link rel="shortcut icon" href="/static/imgs/favicon.ico" />
<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="/static/css/common.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/css/center.css?v=<?= VERSION_TXT ?>" />
<link rel="stylesheet" href="/static/css/handle_order.css?v=<?= VERSION_TXT ?>" />
<style>.form-control{padding: 0 !important;height:32px !important;}.summary-table th, .summary-table td{text-align: center!important;height:48px;}</style>
<title>待处理订单-<?php echo PROJECT_NAME; ?></title>
</head>
<body>
	<?php $this->load->view("/common/top", ['site' => 'finance']); ?>
    <div class="center_box">
        <div class="handle_wrap">
            <div style="border-bottom: 1px solid #E0E0E0;padding: 12px 0;font-size: 16px;">账务明细</div>
            <form id="order_form" action="/finance/summary">
                <div class="search_order">
                    <span style="margin-left:16px;">店铺：</span>
                    <select name="shop_id" class="form-control task_type_select" style="padding-left: 4px;">
                        <option value="0">全部</option>
                        <?php foreach ($shop_list as $v): ?>
                        <option value="<?php echo $v['id']; ?>" <?php if ($v['id'] == $params['shop_id']): ?>selected<?php endif; ?>><?php echo $v['shop_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span style="margin-left:24px;">筛选时间：</span>
                    <input name="st" type="text" class="form-control time_start" onClick="WdatePicker()" value="<?= $params['start_time'] ?>" style="margin-left:0" autocomplete="off"/><span>－</span>
                    <input name="et" type="text" class="form-control time_end" onClick="WdatePicker()" value="<?= $params['end_time'] ?>" style="margin-left:0" autocomplete="off" />
                    <a class="search_btn" onclick="javascript:$('#order_form').submit();" style="width: 86px;height:34px;line-height:32px;">查&nbsp;&nbsp;询</a>
                    <a class="export_btn" style="float:inherit;width: 86px;height:34px;line-height:32px;" href="javascript:;">导出详细记录</a>
                </div>
            </form>
            <table class="table task_table summary-table" style="border-bottom:1px solid #ddd">
                <tr><th>发布任务总数</th><th>总订单数</th><th>总花费</th></tr>
                <tr><td><?= intval($summary->cnts) ?></td><td><?= intval($summary->total_num) ?> 单</td><td>押金：<span class="red"><?= number_format($summary->trade_deposit, 2) ?></span> 元&nbsp;&nbsp;&nbsp;金币：<span class="red"><?= number_format($summary->trade_point, 2) ?></span> 元</td></tr>
            </table>
            <div class="chart">
                <label class="col-xs-12">押金、金币分布图<!-- <a class="mychart" data-status="1" style="float:right">隐藏</a> --></label>
                <canvas id="myChart" width="1000" height="400" ></canvas>
            </div>
            <label style="margin-top:32px;">商品信息</label>
            <table class="table task_table">
                <thead>
                <tr><th class="col-xs-3">商品</th><th class="col-xs-2 text-center">发布单数</th><th class="col-xs-2 text-center">已接手单数</th><th class="col-xs-2 text-center">撤销单数</th><th class="col-xs-3">花费</th></tr>
                </thead>
                <tbody>
                <?php foreach ($trade_list as $item): ?>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-xs-4"><img src="<?= $item->goods_img. '?imageView/3/w/128/h/128'; ?>"></div>
                            <div class="col-xs-8 overfloat-hidden-3">
                                <span class="text-nowrap">任务编号：<a href="/detail/trade/<?= $item->id; ?>" target="_blank"><?= $item->trade_sn ?></a></span>
                                <?= $item->goods_name ?>
                            </div>
                        </div>
                    </td>
                    <td class="text-center"><?= $item->total_num ?> 单</td>
                    <td class="text-center"><?= $item->apply_num ?> 单</td>
                    <td class="text-center"><?= $item->cancel_nums ?> 单</td>
                    <td>押金：<span class="red"><?= number_format(abs($item->trade_deposit), 2) ?></span> 元&nbsp;&nbsp;&nbsp;金币：<span class="red"><?= number_format(abs($item->trade_point), 2) ?></span> 元</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="pager"><?php echo $pagination; ?></div>
        </div>
    </div>
</body>
<?php $this->load->view("/common/footer"); ?>
<script language="javascript" src="/static/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" src="/static/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" src="/static/bootstrap/js/chart.min.js"></script>
<script type="text/javascript">
$(function(){
    $('.export_btn').click(function (e) {
        var _shop_id = $('.task_type_select').val(),
            _start_time = $('input[name="st"]').val(),
            _end_time = $('input[name="et"]').val();
        if (_start_time == '' || _end_time == ''){
            alert('请确定导出报表的时间范围。');
            return false;
        }
        location.href = '/export/capital_recode?shop=' + _shop_id + '&st=' + _start_time + '&et=' + _end_time;
    });

    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["<?= implode('","', $line_summary['labels']); ?>"],
            datasets: [{
                label: '押金',
                data: [<?= implode(',', $line_summary['deposit']); ?>],
                backgroundColor: ['rgba(255, 159, 64, 0.1)'],
                borderColor: ['rgba(255, 159, 64, 1)'],
                borderWidth: 1
            },{
                label: '金币',
                data: [<?= implode(',', $line_summary['points']); ?>],
                backgroundColor: ['rgba(98, 10, 255, 0.1)'],
                borderColor: ['rgba(98, 10, 255, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
});
</script>
</html>
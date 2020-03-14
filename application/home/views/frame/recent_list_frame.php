<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>商家报名活动-<?php echo PROJECT_NAME; ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="renderer" content="webkit">
<link rel="stylesheet" href="/static/css/common.css" />
<link rel="stylesheet" href="/static/css/trade.css" />
</head>
<body>
    <div class="trade_box" style="border: none;">

        <div class="step_task_box">
        	<h3>你已选择店铺：<span><?php echo $bind_shop->shop_name; ?></span>，最近发布的活动单有<span><?php echo count($res); ?></span>单</h3>
            
            <?php if ($res): ?>
            <div class="step_task_box_list">
            
                <?php foreach ($res as $k=>$v): ?>
            	<div class="step_task_list" <?php if ($k > 4): ?>style="display:none;"<?php endif; ?>>
                	<div class="task_list_title">
                        <span><i class="plat_icon plat_<?php echo $bind_shop->plat_name; ?>"></i><?php echo $bind_shop->shop_name; ?></span>
                        <span>活动编号：<?php echo $v->trade_sn; ?>[<a href="/detail/trade/<?php echo $v->id; ?>" target="_blank">详情</a>]</span>
                        <p><span>活动单数：<em><?php echo $v->total_num; ?></em>单</span><span>活动押金<em><?php echo $v->trade_deposit; ?></em>元</span><span><?php echo PROJECT_NAME; ?>：<em><?php echo $v->trade_point; ?></em>点</span></p>
                    </div>
                    <div class="task_list_info">
                        <div class="task_info">
                            <img src="<?= $v->trade_item->goods_img; ?>">
                            <p><?php echo $v->trade_item->goods_name; ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="javascript:;">展开更多活动<i></i></a>
            <?php endif; ?>
        </div>

    </div>

    <script src="//cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
    <script type="text/javascript">
        $(window.parent.document).find("#recent_list").css("height",$("body").height());

        //活动查看更多
        $(".step_task_box>a").click(function(){
            if( $(this).hasClass("task_more") ){
                $(".step_task_list:gt(4)").hide();
                $(this).removeClass("task_more").html("展开更多活动<i></i>");
            }else{
                $(".step_task_list").show();
                $(this).addClass("task_more").html("收起更多活动<i></i>");
            }
        })
    </script>
</body>
</html>
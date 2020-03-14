<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $html_title; ?>-58人气符</title>
<link rel="shortcut icon" href="/static/images/favicon.ico" />
<script language="javascript" src="/static/js/jquery-1.12.4.min.js"></script>
</head>

<body>
        <div style="width:1000px;margin:auto;color:#333; font-family:'微软雅黑';">
            <h3 style="margin:auto;margin:20px;color:#333;"><?php echo $html_title;?><span style="color:red;font-size:14px;padding-left:20px;"><?php echo $action_info;?></span></h3>

            <form method="post" action="" class="snform" enctype="multipart/form-data">
                <span style="color:red;font-size:14px;padding-left:20px;font-weight:bold"><?php echo $action_info3;?></span>
                <p style="margin:10px 20px;color:#333;">1.请选择充值类型：<?php foreach ($pay_type as $key => $value):?>
                    <label><input type="radio" class="text" value="<?php echo $key;?>"  name="pay_type" /><?php echo $value;?></label>
                <?php endforeach;?></p>
                <?php if($action_info2):?>
                    <span style="color:red;font-size:14px;padding-left:20px;font-weight:bold"><?php echo $action_info2;?></span>
                <?php else:?>
                    <br/>
                <?php endif;?>
                <!--<p style="margin:10px 20px;color:#333;">2.上传支付宝转账交易截图：<input type="file" class="text"  name="upload_img" /></p><br/>-->
                <p style="margin:10px 20px;color:#333;">2.填写支付宝转账交易流水号：<input type="text" class="text" id="sn"  name="sn" style="border:1px solid #C5C4B7; width:237px; height:28px; line-height:28px; border-radius:3px;" /><a style="margin-left:10px;margin-right:10px; color:#1F9CD8; text-decoration:none;" target="_blank" href="/static/imgs/how_alipay.jpg">如何查看交易流水号</a></p><br/>
                <p style="margin:0px 0px 10px 20px;color:#333;">3.点击 <input type="button" class="course_button pay" value="充值"  style="font-size:20px;"/> ，<span style="color:red;">请静等几秒</span>，继而完成充值</p>
                <span style="color:red;"><?php echo $info;?></span><br/>
                <p style="margin:0px 0px 10px 20px;color:red;font-size:14px;font-weight:bold;">请注意：支付宝转账以后请等待 2-3 分钟在进行操作充值；如果 5 分钟之后不能进行充值，则联系客服进行充值。</p>
            </form>

        </div>

<script type="text/javascript">
    $(function(){
        $('.pay').click(function() {
            $sn = $('#sn').val();
            $pay_type = $("input[name='pay_type']:checked").val();
            if(!$pay_type){
                alert('请选择充值类型');
                return false;
            }
            if($sn == ''){
                alert('交易流水号不能为空');
                return false;
            }

            $.post('/alipay/alipay_submit',{sn:$sn,pay_type:$pay_type}, function(data) {
                if(data.error == 0){
                    alert(data.message);
                }else{
                    alert(data.message);
                    location.href="/";
                }


            },"json");

        });

    });

</script>

</body>
</html>

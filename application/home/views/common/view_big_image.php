<!-- 大图 -->
<div class="modal fade big_popup_wrap" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="" class="big_see_img" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {
    // 查看和关闭示例截图
    $(".see_img").click(function (event) {
        var _img_frame = $('.big_popup_wrap');
        _img_frame.find(".big_see_img").attr("src", $(this).data("src"));
        _img_frame.modal();
    });
})
</script>
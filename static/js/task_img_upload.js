// 图片上传函数
function setImagePreview(obj) {
    var $this = $(obj);
    var _file = obj.files[0];
    if (_file.size >= 1024 * 1024 * 2) {
        toastr.warning("图片不能大于2M");
        return false;
    }
    var type = "";
    if ($this.val() != '') {
        type = $this.val().match(/^(.*)(\.)(.{1,8})$/)[3];
        type = type.toUpperCase();
    }
    if (type != "JPEG" && type != "PNG" && type != "JPG" && type != "GIF") {
        toastr.warning("图片格式必须是gif、jpg、png中的一种");
        return false;
    }
    if ($this.parents('li').siblings().length > 5) {
        return false;
    }else if ($this.parents('li').siblings().length >= 4) {
        $this.parents(".up_load_list").addClass('img_5');
        // 当上传第五张图片的时候，后续需加一个隐藏域，方便检查图片是否上传成功
        var up_load_5 = '<li style="display: none">' +
            '<div class="uploaded_img_preview">' +
            '<i style="display: none;" class="remove_upload_img"></i>' +
            '<img class="uploaded_goods_img" src="/static/imgs/icon/set_img.png" style="width:128px;height:128px;" />' +
            '<input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val hidden" onChange="javascript:setImagePreview(this);" uploaded="" base64="" path="" />' +
            '</div></li>';
        $this.parents(".up_load_list").append(up_load_5);
    } else {
        var up_load = '<li>' +
            '<div class="uploaded_img_preview">' +
            '<i style="display: none;" class="remove_upload_img"></i>' +
            '<img class="uploaded_goods_img" src="/static/imgs/icon/set_img.png" style="width:128px;height:128px;" />' +
            '<input type="file" name="upload_img[]" accept="image/*" class="uploaded_goods_img_val hidden" onChange="javascript:setImagePreview(this);" uploaded="" base64="" path="" />' +
            '</div></li>';
        $this.parents(".up_load_list").append(up_load);
    }

    $this.siblings('.uploaded_goods_img').prop("src", '/static/imgs/jiazaizhong.gif').addClass('uploaded_goods_imgs').removeClass('uploaded_goods_img').siblings('.remove_upload_img').show();
    $.ajax({
        type: "POST",
        url: "/ajax/get_img_upload",
        dataType: "json",
        success: function (res) {
            var _token = res.code;
            var reader = new FileReader();
            reader.readAsDataURL(_file);
            reader.onload = function (e) {
                var _base64 = e.target.result;
                var type = _base64.substring(0, _base64.indexOf(';')).replace('data:', '');
                var pic = _base64.substring(_base64.indexOf(',') + 1);
                var url = "http://up-z1.qiniup.com/putb64/-1/";
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        var _res_json = eval('(' + xhr.responseText + ')');
                        if (_res_json.key) {
                            var _img_url = 'http://cdn.0718go.com/' + _res_json.key;

                            $this.attr('uploaded', 1).attr('path', _img_url);
                            $this.parent().find('.uploaded_goods_imgs').attr('src', _img_url);
                        } else {
                            toastr.error('图片上传失败，请重新上传');
                            $this.parent().find('.uploaded_goods_imgs').attr('src', '/static/imgs/icon/set_img.png');
                        }
                    }
                };
                xhr.open("POST", url, true);
                xhr.setRequestHeader("Content-Type", type);
                xhr.setRequestHeader("Authorization", "UpToken " + _token);
                xhr.send(pic);
            };
        }
    });

    $this.parents('.up_load_list').siblings("p").html("");
}


// 对象转数组
function rtn_array(obj) {
    var tmpArr = new Array();
    obj.each(function () {
        tmpArr.push($(this).val());
    });

    return tmpArr;
}

// 返回图片路径数组
function rtn_imgs(_box) {
    var tmpArr = new Array();
    var img_upload_flag = true;
    _box.find('ul.up_load_list').each(function () {
        var tmpArr2 = new Array();
        var _upload_fail = [];
        $(this).find('.uploaded_goods_img_val').each(function () {
            if ($(this).attr('path') != '' && $(this).attr('uploaded') == '1') {
                tmpArr2.push($(this).attr('path'));
            } else {
                _upload_fail.push(1);
            }
        });
        tmpArr.push(tmpArr2);
        if (_upload_fail.length >= 2) {
            img_upload_flag = false;
        }
    });

    if (img_upload_flag == false){
        toastr.warning('请稍等检查上传的图片，有部分图片正在上传、或者上传失败了');
        return 0 ;
    } else {
        return tmpArr;
    }
}
var _video_duration = 0;
// 视频上传函数
function setVideoPreview(obj) {
    _video_duration = 0;
    var $this = $(obj);
    var _file = obj.files[0];
    if (_file.size > 1024 * 1024 * 100) {
        toastr.warning("视频大小在100Mb以内");
        return false;
    }
    var type = "";
    if ($this.val() != '') {
        type = $this.val().match(/^(.*)(\.)(.{1,8})$/)[3];
        type = type.toUpperCase();
    }
    if (type != "MP4") {
        toastr.warning("视频仅支持mp4格式");
        obj.parentNode.querySelector('.uploaded_goods_img').src = '/static/imgs/icon/set_video.png';
        return false;
    }
    // 检查视频时长
    var url = URL.createObjectURL(_file);
    obj.parentNode.querySelector("video").src = url;
    var _interval = setInterval(function () {
        if (_video_duration > 0) {
            clearInterval(_interval);
            if (_video_duration > 15) {
                toastr.warning("视频时间控制在15秒以内");
                obj.parentNode.querySelector('.uploaded_goods_img').src = '/static/imgs/icon/set_video.png';
                return false;
            }

            // Video upload
            $this.attr('uploaded', 0).attr('path', '');
            $this.parent().find('.uploaded_goods_img').attr('src', '/static/imgs/jiazaizhong.gif');
            $.ajax({
                type: "POST",
                url: "/ajax/get_img_upload",
                dataType: "json",
                success: function (res) {
                    var _token = res.code;
                    var reader = new FileReader();
                    reader.readAsDataURL(_file);
                    reader.onload = function (e) {
                        var _base64 = e.target.result;
                        var type = _base64.substring(0, _base64.indexOf(';')).replace('data:', '');
                        var video_data = _base64.substring(_base64.indexOf(',') + 1);
                        var url = "http://up-z1.qiniup.com/putb64/-1/";
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 4) {
                                var _res_json = eval('(' + xhr.responseText + ')');
                                if (_res_json.key) {
                                    var _video_url = 'http://cdn.0718go.com/' + _res_json.key;
                                    // 赋值
                                    $this.attr('uploaded', 1).attr('path', _video_url);
                                    $this.parent().find('.uploaded_goods_img').attr('src', _video_url + '?vframe/jpg/offset/0/w/128/h/128');
                                } else {
                                    toastr.error('视频上传失败，请重新上传');
                                    $this.parent().find('.uploaded_goods_img').attr('src', _video_url + '/static/imgs/icon/set_video.png');
                                }
                            }
                        };
                        xhr.open("POST", url, true);
                        xhr.setRequestHeader("Content-Type", type);
                        xhr.setRequestHeader("Authorization", "UpToken " + _token);
                        xhr.send(video_data);
                    };
                }
            });
        }
    }, 100);
}
// 获取视频时长
function myFunction(ele) {
    _video_duration = Math.floor(ele.duration);
}
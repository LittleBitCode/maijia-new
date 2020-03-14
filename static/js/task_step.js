// 传入图片路径，返回base64
function getBase64(img) {
    function getBase64Image(img) {
        var canvas = document.createElement("canvas");
        canvas.width = img.width;
        canvas.height = img.height;

        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        var ext = img.src.substring(img.src.lastIndexOf(".") + 1).toLowerCase();
        var dataURL = canvas.toDataURL("image/" + ext);
        return dataURL;
    }

    var image = new Image();
    image.crossOrigin = '';
    image.src = img;
    var deferred = $.Deferred();
    if (img) {
        image.onload = function () {
            // 将base64传给done上传处理
            deferred.resolve(getBase64Image(image));
        }
        // 问题要让onload完成后再return sessionStorage['imgTest']
        return deferred.promise();
    }
}

// 检查商品url
function check_goods_url(goods_url, plat_id) {
    var result = new Map();
    if (goods_url == '' || (goods_url.indexOf(".com") <= 0 && goods_url.indexOf("tmall.hk") <= 0)) {
        result.set('error', 1);
        result.set('message', '填写的商品链接不正确，请确认');
        return result;
    }

    if (plat_id == '1') {
        if (goods_url.indexOf("tmall.com") > 0) {
            result.set('error', 1);
            result.set('message', '如果发布天猫商品链接，请选择对应的天猫店铺');
            return result;
        }
        if (goods_url.indexOf("taobao.com") <= 0 && goods_url.indexOf("fliggy.com") <= 0 && goods_url.indexOf("alitrip.com") <= 0) {
            result.set('error', 1);
            result.set('message', '请填写正确的淘宝商品、或飞猪商品链接');
            return result;
        }
        if (goods_url.indexOf("id") <= 0) {
            result.set('error', 1);
            result.set('message', '您填写的淘宝商品链接地址不完整，请确认');
            return result;
        }
    } else if (plat_id == '2') {
        if (goods_url.indexOf("tmall.com") <= 0 && goods_url.indexOf("yao.95095.com") <= 0 && goods_url.indexOf("tmall.hk") <= 0 && goods_url.indexOf("ju.taobao.com") <= 0) {
            result.set('error', 1);
            result.set('message', '请填写正确的天猫、或天猫国际商品链接');
            return result;
        }
        if (goods_url.indexOf("id") <= 0) {
            result.set('error', 1);
            result.set('message', '您填写的天猫商品链接地址不完整，请确认');
            return result;
        }
    } else if (plat_id == '4') {
        if (goods_url.indexOf("jd.com") <= 0 || goods_url.indexOf("item") <= 0) {
            result.set('error', 1);
            result.set('message', '请填写正确的京东商品链接');
            return result;
        }
    } else if(plat_id == '14'){
        if (goods_url.indexOf("yangkeduo.com") <= 0 || goods_url.indexOf("goods_id") <= 0) {
            result.set('error', 1);
            result.set('message', '请填写正确的拼多多商品链接');
            return result;
        }
    }

    result.set('error', 0);
    return result;
}
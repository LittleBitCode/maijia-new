$(function () {
    var _total_nums = parseInt($('.people_weight_info').data('nums'));
    /** 人气权重优化 **/
    $('.people_weight_info').on('click', 'label', function (e) {
        var $this = $(this);
        var _ipt = $this.parent().find('input[type="text"]');
        if ($this.find('input').is(":checked")) {
            if (isNaN(parseInt(_ipt.val()))) {
                _ipt.focus();
            } else {
                fun_summation();
            }
        } else {
            _ipt.val('');
            var _div_box = $this.parent();
            _div_box.find('.span-num').text('--');
            _div_box.find('.color_red').text('0.0');
            // 去掉所有的勾选
            var _selected_name = $this.find('input').attr('name');
            if ('normal_price' == _selected_name) {
                $this.parents('.people_weight_info').find('label>input:checked').each(function (e) {
                    var _s_this = $(this);
                    var _div_box = _s_this.parent().parent();
                    _s_this.attr('checked', false);
                    _div_box.find('input[type="text"]').val('');
                    _div_box.find('.span-num').text('--');
                    _div_box.find('.color_red').text('0.0');
                });
            }
            fun_summation();
        }
    }).on('change', 'input[type="text"]', function (e) {
        var $this = $(this), _input_num = parseInt($this.val()), _price = parseFloat($this.data('price'));
        var _parent_box = $('.people_weight_info'),  _max_reference = parseInt(_parent_box.find('input[name="normal_price"]').parent().parent().find('input[type="text"]').val());
        var _div_box = $this.parent().parent();
        if (!_div_box.find('label>input').is(":checked")) {
            _div_box.find('label>input').prop('checked', 'checked');
        }
        // 参照值
        if (_max_reference < _input_num) {
            _input_num = _max_reference;
            $this.val(_max_reference);
        }
        var _other_max_num = 0;
        _parent_box.find('label>input:checked').each(function (e) {
            if ('normal_price' != $(this).attr('name')) {
                var _num = parseInt($(this).parent().parent().find('input[type="text"]').val());
                if (_num > _other_max_num) {
                    _other_max_num = _num;
                }
            }
        });
        if (_other_max_num > _max_reference){
            toastr.warning("浏览商品至少需要" + _other_max_num + '访客/单');
        }

        var _amount = Math.round(_input_num * _price * _total_nums * 100) / 100;
        _div_box.find('.span-num').text(_input_num);
        _div_box.find('.color_red').text(_amount.toFixed(2));

        fun_summation();
    });

    /** 人气权重优化合计 **/
    var fun_summation = function () {
        var _total_points = 0, _parent_box = $('.people_weight_info');
        _parent_box.find('label>input:checked').each(function (e) {
            var _amount = parseFloat($(this).parent().parent().find('.color_red').text());
            _total_points += _amount;
        });
        _parent_box.parent().find('.total .color_red').text((Math.round(_total_points * 100) / 100).toFixed(2));
        _parent_box.parent().parent().find('input[name="traffic"]').val(_total_points).trigger('click');
    }
});
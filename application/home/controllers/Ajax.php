<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名称:活动控制器
 * 担当:
 */
class Ajax extends Ext_Controller {

    /**
     * __construct
     */
    private  $user_id = 0 ;

    public function __construct() {
        parent::__construct();

        $this->user_id = intval($this->data['user_info']->id);
    }

    /** 根据商品链接获取商品名称、图片 **/
    public function get_goods_info()
    {
        $goods_url = $this->input->post('url');
        $plat_id = intval($this->input->post('plat'));
        if (!in_array($plat_id, [1, 2, 4, 14])) {
            exit(json_encode(['error' => 1, 'message' => '非法参数']));
        }

        $this->load->model('Trade_Model', 'trade');
        $item_id = $this->trade->get_item_id($goods_url, $plat_id);
        if (empty($item_id)) {
            exit(json_encode(['error' => 1, 'message' => '请检查您录入商品链接，不是合法的商品链接']));
        }

        $title = '';
        $img_url = '';
        $this->load->helper('curl_helper');
        $path_info = parse_url($goods_url);
        if (strpos($path_info['host'], 'taobao.com')) {
            if (strpos($path_info['host'], 'ju.taobao.com')) {
                // 聚划算链接
                $contents = curl_get($goods_url, 'GBK');
                $result = preg_match_all('/normal-pic-wrapper.*?<a.*?href=\"(.*?)\".*?piclink/ism', $contents, $match_title, PREG_PATTERN_ORDER);
                if ($result && isset($match_title[1][0])) {
                    $url = trim($match_title[1][0]);
                    if ($url) {
                        $path_info = parse_url($url);
                        if (!$path_info['scheme']) {
                            $url = 'http:' . $url;
                        }
                        if (strpos($path_info['host'], 'tmall.com')) {
                            // 天猫URL
                            $contents = curl_get($url, 'GBK');
                            $result = preg_match_all('/<img id=\"J_ImgBooth\" alt=\"(.*?)\" src=\"(.*?)\".*?>/ism', $contents, $match_list, PREG_PATTERN_ORDER);
                            if ($result) {
                                $title = trim($match_list[1][0]);
                                //$img_url = trim($match_list[2][0]);
                            }

                            $result2 = preg_match_all('/<li class=.*?<a href="#".*?<img src="(.*?)".*?<\/a>/ism', $contents, $match_list2, PREG_PATTERN_ORDER);

                            if ($match_list2) {
                                $img_url = 'https:'.str_replace('60x60q90','430x430q90',$match_list2[1][0]);
                            }
                            exit(json_encode(['error' => 0, 'info' => ['title'  => $title, 'img' => $img_url]]));
                        } else if (strpos($path_info['host'], 'taobao.com')) {
                            $contents = curl_get($url, 'GBK');
                            $result = preg_match_all('/<h3 class=\"tb-main-title\".*?>(.*?)<\/h3>/ism', $contents, $match_title, PREG_PATTERN_ORDER);
                            if ($result && isset($match_title[1][0])) {
                                $title = trim($match_title[1][0]);
                            }

                            $result = preg_match_all('/<img id=\"J_ImgBooth\" src=\"(.*?)\".*?>/ism', $contents, $match_img, PREG_PATTERN_ORDER);
                            if ($result && isset($match_img[1][0])) {
                                $img_url = trim($match_img[1][0]);
                            }

                            exit(json_encode(['error' => 0, 'info' => ['title'  => $title, 'img' => $img_url]]));
                        }
                    }
                }
            } else {
                // 淘宝URL
                $contents = curl_get($goods_url, 'GBK');
                $result = preg_match_all('/<h3 class=\"tb-main-title\".*?>(.*?)<\/h3>/ism', $contents, $match_title, PREG_PATTERN_ORDER);
                if ($result && isset($match_title[0][0])) {
                    $title = trim($match_title[0][0]);
                    preg_match("/data-title\=[\"|\'](.*)[\"|\'].*>/i",$title,$out);
                    $title=$out[1];
                }

                $result = preg_match_all('/<img id=\"J_ImgBooth\" src=\"(.*?)\".*?>/ism', $contents, $match_img, PREG_PATTERN_ORDER);
                if ($result && isset($match_img[1][0])) {
                    $img_url = trim($match_img[1][0]);
                }

                exit(json_encode(['error' => 0, 'info' => ['title'  => $title, 'img' => $img_url]]));
            }
        } elseif (strpos($path_info['host'], 'fliggy.com') || strpos($path_info['host'], 'alitrip.com')) {
            // 飞猪网
            $contents = curl_get($goods_url, 'UTF-8');
            $result = preg_match_all('/class=\"title-txt\".*?>.*?<span.*?>(.*?)<\/span>/ism', $contents, $match_title, PREG_PATTERN_ORDER);
            if ($result) {
                $title = trim($match_title[1][0]);
            }

            $result = preg_match_all('/class=\"item-gallery-top__img\".*?src=\"(.*?)\".*?>/ism', $contents, $match_img, PREG_PATTERN_ORDER);
            if ($result) {
                $img_url = trim($match_img[1][0]);
            }

            exit(json_encode(['error' => 0, 'info' => ['title'  => $title, 'img' => $img_url]]));
        } elseif (strpos($path_info['host'], 'tmall.com')) {
            // 天猫URL
            $contents = curl_get($goods_url, 'GBK');
            $result = preg_match_all('/<img id=\"J_ImgBooth\" alt=\"(.*?)\" src=\"(.*?)\".*?>/ism', $contents, $match_list, PREG_PATTERN_ORDER);
            if ($result) {
                $title = trim($match_list[1][0]);
                // $img_url = trim($match_list[2][0]);
            }

            $result2 = preg_match_all('/<li class=.*?<a href="#".*?<img src="(.*?)".*?<\/a>/ism', $contents, $match_list2, PREG_PATTERN_ORDER);

            if ($match_list2) {
                $img_url = 'https:'.str_replace('60x60q90','430x430q90',$match_list2[1][0]);
            }

            exit(json_encode(['error' => 0, 'info' => ['title'  => $title, 'img' => $img_url]]));
        } elseif (strpos($path_info['host'], 'jd.com')) {
            // 京东URL
            $contents = curl_get($goods_url, 'GBK');
            $result = preg_match_all('/<img id=\"spec-img\".*?data-origin=\"(.*?)\".*?alt=\"(.*?)\">/ism', $contents, $match_list, PREG_PATTERN_ORDER);
            if ($result) {
                $img_url = trim($match_list[1][0]);
                $title = trim($match_list[0][0]);
                preg_match("/alt\=[\"|\'](.*)[\"|\'].*>/i",$title,$out);
                $title=$out[1];
            }
            exit(json_encode(['error' => 0, 'info' => ['title'  => $title, 'img' => $img_url]]));
        } elseif (strpos($path_info['host'], 'tmall.hk')) {
            // 天猫国际
            $contents = curl_get($goods_url, 'GBK');
            $result = preg_match_all('/id="J_ImgBooth".*?alt="(.*?)".*?src="(.*?)".*?>/ism', $contents, $match_list, PREG_PATTERN_ORDER);
            if ($result) {
                $title = trim($match_list[1][0]);
                $img_url = trim($match_list[2][0]);
            }

            exit(json_encode(['error' => 0, 'info' => ['title'  => $title, 'img' => $img_url]]));
        } elseif (strpos($path_info['host'], 'yao.95095.com')) {
            // 95095医药
            $contents = curl_get($goods_url, 'GBK');
            $result = preg_match_all('/id="J_ImgBooth".*?alt="(.*?)".*?src="(.*?)".*?>/ism', $contents, $match_list, PREG_PATTERN_ORDER);
            if ($result) {
                $title = trim($match_list[1][0]);
                $img_url = trim($match_list[2][0]);
            }

            exit(json_encode(['error' => 0, 'info' => ['title'  => $title, 'img' => $img_url]]));
        } elseif (strpos($path_info['host'], 'yangkeduo.com')!==false) {
            //拼多多链接
            $contents = curl_get($goods_url);
            $result = preg_match_all('/window.rawData=(.*?);/ism', $contents, $match_list, PREG_PATTERN_ORDER);
            if ($result) {
                $goods_info = json_decode(trim($match_list[1][0]), true);
                $title = $goods_info["store"]["initDataObj"]['goods']['goodsName'];
                $img_url = $goods_info["store"]["initDataObj"]['goods']['hdThumbUrl'];
            }

            exit(json_encode(['error' => 0, 'info' => ['title'  => $title, 'img' => $img_url]]));
        }
    }

    public function get_shop_name()
    {
        $goods_url = $this->input->post('url');
        $this->load->helper('curl_helper');
        $path_info = parse_url($goods_url);
        if (strpos($path_info['host'], 'taobao.com')) {
            if (strpos($path_info['host'], 'ju.taobao.com')) {
                // 聚划算链接
                if (strpos($path_info['host'], 'tmall.com')) {
                    // 天猫URL
                    $contents = curl_get($goods_url, 'GBK');
                    $result = preg_match_all('/<a class="slogo-shopname" .*?>(.*?)<\/a>/ism', $contents, $shop_list, PREG_PATTERN_ORDER);
                    if ($result) {
                        $shop_name = trim(strip_tags($shop_list[1][0]));
                    }
                    exit(json_encode(['error' => 0, 'shop_name' => $shop_name]));
                } else if (strpos($path_info['host'], 'taobao.com')) {
                    $contents = curl_get($goods_url, 'GBK');
                    $result = preg_match_all('/<a class="sellername" .*?>(.*?)<\/a>/ism', $contents, $shop_list, PREG_PATTERN_ORDER);
                    if ($result) {
                        $shop_name = trim(strip_tags($shop_list[1][0]));
                    }
                    exit(json_encode(['error' => 0, 'shop_name' => $shop_name]));
                }
            } else {
                // 淘宝URL
                $contents = curl_get($goods_url, 'GBK');
                $result = preg_match_all('/<div class="tb-shop-name">(.*?)<\/div>/ism', $contents, $shop_list, PREG_PATTERN_ORDER);
                if ($result) {
                    $shop_name = trim(strip_tags($shop_list[1][0]));
                }
                exit(json_encode(['error' => 0, 'shop_name' => $shop_name]));
            }
        } elseif (strpos($path_info['host'], 'fliggy.com') || strpos($path_info['host'], 'alitrip.com')) {
            // 飞猪网
            $contents = curl_get($goods_url, 'UTF-8');
            $result = preg_match_all('/<a class="c-shop-logo-name" .*?>(.*?)<\/a>/ism', $contents, $shop_list, PREG_PATTERN_ORDER);
            if ($result) {
                $shop_name = trim(strip_tags($shop_list[1][0]));
            }
            exit(json_encode(['error' => 0, 'shop_name' => $shop_name]));
        } elseif (strpos($path_info['host'], 'tmall.hk')) {
            // 天猫国际
            $contents = curl_get($goods_url, 'GBK');
            $result = preg_match_all('/<a class="shopLink" .*?>(.*?)<\/a>/ism', $contents, $shop_list, PREG_PATTERN_ORDER);
            if ($result) {
                $shop_name = trim(strip_tags($shop_list[1][0]));
            }
            exit(json_encode(['error' => 0, 'shop_name' => $shop_name]));
        } else {
            exit(json_encode(['error' => 1, 'message' => '暂不支持其他平台商品']));
        }
    }

    /** 获取七牛上传token值 */
    function get_img_upload()
    {
        $this->load->helper('qiniu_helper');
        $token = qiniu_token();
        exit(json_encode(['code' => $token]));
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: reliy
 * Date: 2018/6/3
 * Time: 上午12:38
 */

namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class SlugTranslateHandler
{
    public function translate ( $text )
    {
        // 实例化 HTTP 客户端
        $http = new Client;

        // 初始化配置信息
        $api    = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $app_id = config('services.baidu_translate.appid');
        $key    = config('services.baidu_translate.key');
        $salt   = time();

        if ( empty($app_id) || empty($key) ) {
            return $this->pinyin($text);
        }

        $sign = md5($app_id . $text . $salt . $key);

        $query = http_build_query([
            "q" => $text,
            "from" => "zh",
            "to" => "en",
            "appid" => $app_id,
            "salt" => $salt,
            "sign" => $sign,
        ]);


        $response = $http->get($api . $query);
        $result   = json_decode($response->getBody(), true);


        if ( isset($result['trans_result'][0]['dst']) ) {
            return str_slug($result['trans_result'][0]['dst']);
        } else {
            return $this->pinyin($text);
        }
    }

    public function pinyin ( $text )
    {
        return str_slug(app(Pinyin::class)->permalink($text));
    }
}
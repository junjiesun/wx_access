<?php
namespace App\Core\Services;

use \Kerisy\Database\DB;
use Kerisy\Log\Logger;

class WxSDKService
{
    private $logService;
    private $appId = '';
    private $appSecret = '';

    public function __construct($appId = null, $appSecret = null, Logger $logger)
    {
        $this->logService = $logger;

        $data = request()->all();


        if( empty( $data['callback_url']) )
        {
            return false;
        }
        $wx_account_id = $data['wx_account_id'];
        $config = $this->getAppidAppSecret($wx_account_id);

        $this->wx_account_id = $config->wx_account_id;
        $this->callback_url = $data['callback_url'];
        $this->appId = $config->appId;
        $this->appSecret = $config->appSecret;
        $this->type = $config->type;
        $this->authorization = $config->authorization;
    }

    public function getSignPackage($wx_account_id, $url)
    {
        $jsapiTicket = $this->getJsApiTicket($wx_account_id);
        $config = $this->getAppidAppSecret($wx_account_id);
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        $url = urldecode($url);

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $config->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );

        return $signPackage;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket($wx_account_id)
    {

        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例

        $sql = "select * from js_api_ticket where wx_account_id = ?";

        $jsApiTicket = DB::select($sql,[$wx_account_id]);

        if ( count( $jsApiTicket ) > 0 )
        {
            $data = $jsApiTicket[0];

            if ( $data->expire_time < time() )
            {
                $accessToken = $this->getAccessToken($wx_account_id);
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                $res = json_decode($this->httpGet($url));
                $ticket = $res->ticket;

                if ($ticket)
                {
                    $expireTime = time() + 7000;
                    DB::update('update js_api_ticket set expire_time = ?, jsapi_ticket = ? where wx_account_id = ?', [$expireTime, $ticket, $wx_account_id]);
                }
            }else{
                $ticket = $data->jsapi_ticket;
            }
        }
        else
        {
            $accessToken = $this->getAccessToken($wx_account_id);
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;

            if ($ticket)
            {
                $expireTime = time() + 7000;
                DB::insert('insert into js_api_ticket (wx_account_id, expire_time, jsapi_ticket) values (?, ?, ?)', [$wx_account_id, $expireTime, $ticket]);
            }
        }
        return $ticket;

    }

    public function getAccessToken( $wx_account_id )
    {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $sql = "select * from access_token where wx_account_id = ? ";
        $accessToken = DB::select($sql,[$wx_account_id]);
        if ( count( $accessToken ) > 0 )
        {
            $data = $accessToken[0];
            if ( $data->expire_time < time() )
            {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
                $res = json_decode($this->httpGet($url));
                $access_token = $res->access_token;
                if ($access_token)
                {
                    $expireTime = time() + 7000;
                    DB::update('update access_token set expire_time = ?, access_token = ? where wx_account_id = ? ', [$expireTime,$access_token,$wx_account_id]);
                }
            }else{
                $access_token = $data->access_token;
            }
        }
        else
        {
            $config = $this->getAppidAppSecret($wx_account_id);
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$config->appId&secret=$config->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;

            if ($access_token)
            {
                $expireTime = time() + 7000;
                DB::insert('insert into access_token (wx_account_id, expire_time, access_token) values (?, ?, ?)', [$wx_account_id, $expireTime,$access_token]);
            }
        }

        return $access_token;

    }

    /**
     * wechat Authorize
     * Author:Comver
     * 注意！在控制器中应该判断是否已经授权！本方法直接跳转到授权页面！
     *
     * @param  string $redirect_uri 授权成功后回跳页面
     * @return string wechat auth url
     */
    public function wechatAuthorize($redirect_uri = '', $scope = 'snsapi_userinfo')
    {
        if($redirect_uri == '') return false;
        $redirect_uri = 'http://'.$redirect_uri;
        $redirect_uri = urlencode($redirect_uri);

        $state = $this->wx_account_id;

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appId&redirect_uri=$redirect_uri&response_type=code&scope=$scope&state=$state#wechat_redirect";

//        var_dump($url);
//        exit;
        return $url;
    }

    /**
     * wechat auth callback after user confirm the request
     * Author:Comver
     *
     * @param  string $code 微信授权传回的code 用于取access_token
     * @return array  $res  access_token等信息
     */
    public function wechatCallback($wx_account_id, $code = '')
    {
        $sql = "select * from wx_account where wx_account_id = ?";
        $config = DB::select($sql,[$wx_account_id]);

        if( count($config) > 0 )
        {
            $config = $config[0];
        }else{
            return false;
        }
        $this->wx_account_id = $config->wx_account_id;
        $appId = $config->appId;
        $appSecret = $config->appSecret;

        if($code == '') return false;

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appId&secret=$appSecret&code=$code&grant_type=authorization_code";
        $res = json_decode($this->httpGet($url), true);
        return $res;
    }

    /**
     * wechat get user info
     * Author: Comver
     *
     * @param  string $access_token 上一步获得到的access_token，非全局access_token，无需缓存！
     * @param  string $openId 		用户的openId
     * @return array  $res 			用户信息数组
     */
    public function wechatUserinfo($access_token = '',$openId = '')
    {
        if($access_token == '' || $openId == '') return false;
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openId";
        $res = json_decode($this->httpGet($url));
        return $res;
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    public function getAppidAppSecret( $wx_account_id )
    {

        if( empty($wx_account_id) )
        {
            return false;
        }
        $sql = "select * from wx_account where wx_account_id = ?";
        $config = DB::select($sql,[$wx_account_id]);


        if( count($config) > 0 )
        {
            $config = $config[0];
        }else{
            return false;
        }

        return $config;

    }



}

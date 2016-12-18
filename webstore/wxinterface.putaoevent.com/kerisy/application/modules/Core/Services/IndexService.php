<?php
namespace App\Core\Services;

use \Kerisy\Database\DB;
use Kerisy\Log\Logger;
// use Lib\Support\User;

class IndexService
{
	private $logService;

	public function __construct(
		Logger $logger
	)
	{
		$this->logService = $logger;
	}

	public function signin(Array $parameters)
	{
		$returnData = array(
			'isSuccess' => false,
			'message'   => '用户名或密码错误',
			'url'       => '/'
		);

		$loginRet = $this->checkSysLogin($parameters);

		if($loginRet['isSuccess'] === true)
		{
			$returnData['isSuccess'] = true;
		}
		return $returnData;
	}

	public function checkSysLogin(Array $parameters)
	{

		$user['username'] = $parameters['username'];
		$user['password'] = $parameters['password'];

		$sessionId = sha1(json_encode($user) . time());

		$returnData = array(
			'isSuccess'  => false,
			'user'       => []
		);

		if( $user['username'] === "admin@putao.com" && $user['password'] === "hello123")
		{
			$returnData['isSuccess'] = true;
			$returnData['user']      = $user;
		}

		session()->set($sessionId, $user);
		response()->setCookie('sid', $sessionId, 0, '/', '');

		return $returnData;
	}

	public function loginout()
    {
        $cookies = request()->cookie;

        if ( array_key_exists("sid", $cookies ) )
        {
            $key = $cookies['sid'];
            session()->destroy($key);
            response()->setCookie( 'sid', '', time() - 3600 );
                    
            if( !empty( session()->get($key) ) )
            {
                return false;
            }
        }
        
        return true;
    }

    public function getUser($users_id)
    {

        $sql = "select openid, nickname, sex, province, country, headimgurl, unionid
             from users where users_id = ? ";

        $user = DB::selectOne($sql,[$users_id]);
        return $user;
    }


    public function getAccessToken( $wx_account_id )
    {
        $config = $this->getAppidAppSecret($wx_account_id);

        $appId = $config->appId;
        $appSecret = $config->appSecret;

        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $sql = "select * from access_token where wx_account_id = ? order by expire_time desc limit 1";
        $accessToken = DB::select($sql,[$wx_account_id]);

        if ( count( $accessToken ) > 0 )
        {
            $data = $accessToken[0];

            if ( $data->expire_time < time() )
            {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
                $res = json_decode($this->httpGet($url));
                $access_token = $res->access_token;
                if ($access_token)
                {
                    $expireTime = time() + 7000;
                    DB::update('update access_token set expire_time = ?, access_token = ? where wx_account_id = ? order by expire_time desc limit 1', [$expireTime,$access_token,$wx_account_id]);
                }
            }
            else if (false)
            {

            }
            else
            {
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
}

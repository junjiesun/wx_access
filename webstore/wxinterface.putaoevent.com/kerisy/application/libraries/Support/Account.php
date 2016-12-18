<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei<haoyf@putao.com>
 * Date: 2015/12/2
 * Time: 14:51
 */

namespace Lib\Support;

use Kerisy\Http\Client;

/**
 * @describe only support api
 * Class Account
 * @package Kerisy\Support
 */
trait Account
{
    protected $client;

    /**
     * @describe 验证通过
     * client----uid,token,appid,sign
     * @auth haoyanfei<haoyf@putao.com>
     * @param $credentials [serverid,appid,token]
     * @return $result ['uid','token','nickname']
     */
    public function authorizable($credentials = [])
    {
        $this->client = new Client;
        $account = config('config')->get('account');
        $credentials['serverid'] = $account['serverid'];
        $credentials['sign'] = makeVerify($credentials, $account['secret_key']);
        $result = $this->client->post($account['checkToken'], $credentials);
        if (isset($result['error_code']) && $result['error_code'] === 0) {
            return true;
        }
        return false;
    }

    public function getNickName($credentials = [])
    {
        $this->client = new Client;
        $account = config('config')->get('account');
        $credentials['serverid'] = $account['serverid'];
        $credentials['type'] = 'getNickName';
        $credentials['sign'] = makeVerify($credentials, $account['secret_key']);
        $result = $this->client->post($account['getNickName'], $credentials);
        if (isset($result['error_code']) && $result['error_code'] === 0) {
            return $result['msg'];
        }
        return false;
    }

    public function getAvatar($credentials = [])
    {
        $this->client = new Client;
        $account = config('config')->get('account');
        $credentials['serverid'] = $account['serverid'];
        $credentials['isFullPath'] = 'true';
        $credentials['sign'] = makeVerify($credentials, $account['secret_key']);

        $result = $this->client->post($account['getAvatar'], $credentials);
        if (isset($result['error_code']) && $result['error_code'] === 0) {
            return $result['avatar'];
        }
        return false;
    }
	
	 public function loginAuth($user = [])
    {
        return $this->getAccountByType('check_login',$user);

    }


    public function getBaseInfo($arg){
        return $this->getAccountByType('base_info',$arg);
    }

    public function getAccountByType($type,$arg){

        $this->client = new Client;
        //登录结束之后第一次验证处理
        $passport = config('config')->get('passport');
		$sign = null;
		
		if ( empty($arg) )
		{
			return false;
		}
		
		if ( array_key_exists("uid", $arg ) && $arg['uid'] != '' && array_key_exists("token", $arg) && $arg['token'] != '' )
		{
			$sign = strtoupper(md5($arg['uid'] . $arg['token'] . $passport['secret_key']));
		}
		
		if ( !array_key_exists("sign", $arg ) || $arg['sign'] == '' )
		{
			return false;
		}
		else if ( array_key_exists("sign", $arg ) && $sign != strtoupper($arg['sign']) )
        {
            return false;
        }
        //第二次检验数据的真实性

        $result = $this->client->post($passport["{$type}_url"], $arg);

        if (!empty($result['error_code'])) {
            return false;
        }
        if($type == 'check_login') {
            //参数不全 退出认证
            if ((!$uid = array_get($result, 'uid')) || (!$token = array_get(
                    $result, 'token')) || (!$sign = array_get($result, 'sign')) || (!$nickname = array_get($result, 'nickname'))
            ) {
                return false;
            }
            $sign = strtoupper(md5($result['uid'] . $result['token'] . $passport['secret_key']));
            if ($sign != strtoupper($arg['sign'])) {
                return false;
            }
        }

        return $result;
    }
} 
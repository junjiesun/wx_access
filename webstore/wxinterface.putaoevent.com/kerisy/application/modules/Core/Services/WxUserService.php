<?php
namespace App\Core\Services;

use \Kerisy\Database\DB;
use Kerisy\Log\Logger;

class WxUserService
{
    private $logService;
    private $wxSDKService;

    public function __construct(WxSDKService $wxSDK, Logger $logger)
    {
        $this->logService = $logger;
        $this->wxSDKService = $wxSDK;
    }

    public function setWxSession($user = array())
    {
        $sessionId = sha1(json_encode($user) . time());

        $userInfo = array(
            'users_id'      	=> $user['users_id'],
//            'openid'          	=> $user['openid'],
            'wx_account_id'     => $user['wx_account_id'],
//            'nickname'		    => $user['nickname']
        );

        session()->set($sessionId, $userInfo);
        response()->setCookie('uid', $sessionId, 0, '/', '');
        return true;
    }

    public function getWxSession()
    {
        $cookie = request()->cookie;
        $user = array();

        if ( array_key_exists("uid", $cookie) )
        {
            $user = session()->get($cookie['uid']);
            if(empty($user))
            {
                $key = $cookie['uid'];
                session()->destroy($key);
                response()->setCookie( 'uid', '', time() - 3600 );

                if( !empty( session()->get($key) ) )
                {
                    return false;
                }
            }
        }
        return $user;
    }

    public function clearWxSession()
    {
        $cookies = request()->cookie;

        if ( array_key_exists("uid", $cookies ) )
        {
            $key = $cookies['uid'];
            session()->destroy($key);
            response()->setCookie( 'uid', '', time() - 3600 );

            if( !empty( session()->get($key) ) )
            {
                return false;
            }
        }
        return true;
    }

    public function getLogin(){
        $wxSession = $this->getWxSession();
//        var_dump($wxSession);
        if($wxSession){
            return $wxSession;
        }else{
            return false;
        }
    }

    public function createUser($userInfo, $wx_account_id)
    {
        $time = time();
        if( $userInfo )
        {
            $this->logService->info("------------ openid ------------" .  json_encode($userInfo));
            $this->logService->info("------------ openid ------------" .  $userInfo->openid);
            $this->logService->info("------------ nickname ------------" .$userInfo->nickname.'===');
            $this->logService->info("------------ headimgurl ------------" .  $userInfo->headimgurl);

            $sql = "select * from users where openid = ? and wx_account_id = ?";

            $user = DB::select($sql,[$userInfo->openid, $wx_account_id]);
//            $user = DB::table('users')->where('openid',$userInfo->openid)->where('wx_account_id',$userInfo->$wx_account_id)->first();

//            var_dump($user);exit;

            $Data = array(
                'nickname' => $userInfo->nickname,
                'sex' => $userInfo->sex,
                'province' => $userInfo->province,
                'city' => $userInfo->city,
                'country' => $userInfo->country,
                'headimgurl' => $userInfo->headimgurl,
                'modified_time' => time()
            );

            if( !empty($user) )
            {
                $user = $user[0];

                //更新资料
                DB::table('users')->where(['openid'=>$userInfo->openid, 'wx_account_id'=>$wx_account_id])->update( $Data );
//                var_dump($user);
                $userId = $user->users_id;
            }
            else
            {
                $Data['wx_account_id'] = $wx_account_id;
                $Data['openid'] = $userInfo->openid;
                $Data['create_time'] = $time;

                if( !empty($userInfo->unionid) )
                {
                    $Data['unionid'] = $userInfo->unionid;
                }

                $userId = DB::table('users')->insertGetId($Data);
            }

            $setUser = array(
                'users_id'      	=> $userId,
                'wx_account_id'      	=> $wx_account_id,
                'openid'        => $userInfo->openid,
                'nickname'		=> $userInfo->nickname
            );

            if( !empty($userInfo->unionid) )
            {
                $setUser['unionid'] = $userInfo->unionid;
            }

            $this->setWxSession($setUser);
            $this->logService->info('session_setUser:===='.json_encode($setUser));

            return $userId;
        }
        else
        {
            $this->logService->info('<<<<<<<<<++++++++>>>>>>>'.json_encode($userInfo));
            return false;
        }
    }
}

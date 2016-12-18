<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei<haoyf@putao.com>
 * Date: 2015/12/1
 * Time: 10:35
 */

namespace Lib\Controller;

use Kerisy\Http\Controller;
use Lib\Middleware\Controller\Auth;

class FrontController extends Controller
{
    public function __construct()
    {
        $this->middleware = ['Lib\Middleware\Controller\WxToken'];
    }

    public function showError($error = 'System Error', $code = 10000)
    {
        response()->json(errorFormat($error, $code));
        request()->abort = true;
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

    public function userId(){
        $user_info = $this->userInfo();
        return $user_info['uid'];
    }

    public  function userInfo(){

        $cookies =  request()->cookie;
        $user_info = array();
	 
        if ( array_key_exists("uid", $cookies ) && array_key_exists("token", $cookies) )
        {
            $key  = $cookies['uid'].$cookies['token'];
            $user_info = session()->get($key);
        }
	
	return $user_info;
    }

    public function showSuccess(array $data, $code = 200)
    {
        response()->json(successFormat($data, $code));
        request()->abort = true;
    }

    public function guestActions()
    {
        return ['lists','detail'];
    }
    /*
     *
    public function guestActions()
    {
        return ['orderNotify']; //orderNotify Action 不需要登录可以访问
    }
     */
}

<?php

namespace Lib\Middleware\Controller;

use Kerisy\Core\MiddlewareContract;
use Kerisy\Database\DB;
use Kerisy\Http\Controller;
use App\Core\Services\WxSDKService as wx_sdk;
use App\Core\Services\WxUserService as wxUser;
/**
 * BasicAccess middleware.
 *
 * @package Kerisy\Auth\Middleware
 */
class WxToken implements MiddlewareContract
{
    /**
     * The user identity name that used to authenticate.
     *
     * @var string
     */
    public $identity = 'id';
    
    public $wxSDK;
    public $wxUser;

    public function __construct(wx_sdk $wxSDK,wxUser $wxUser) {

        $this->wxSDK  = $wxSDK;
        $this->wxUser = $wxUser;
//        var_dump($this->wxUser);
//        exit;
    }
    /**
     * 
     * @param Request $request
     */
    public function handle($controller)
    {
        $getPost = request()->all();



        $code = request()->input("code","");

        if(in_array(request()->getRoute()->getAction(), $controller->guestDenyActions())){
            $return = $this->wxUser->getLogin();

            //没有查到用户信息
            if (!$return)
            {
                if(!$code){
                    $wx_account_id = $getPost['wx_account_id'];
                    $isSuccess = $this->callbackurl($wx_account_id,$getPost['callback_url']);

                    $requestHeader = request()->getHeaders();
                    $host = $requestHeader->first('host');

                    if( $this->wxSDK->authorization == 2 )
                    {
                        $authUrl=$this->wxSDK->wechatAuthorize($host,'snsapi_userinfo');
                    }else{
                        $authUrl=$this->wxSDK->wechatAuthorize($host,'snsapi_base');
                    }

                    response()->redirect($authUrl);
                    request()->abort = true;
                    return null;
                }else{

                    $wx_account_id =  $getPost['state'];
                    if( empty($wx_account_id) )
                    {
                        return null;
                    }
                    $callBack  = $this->wxSDK->wechatCallback($wx_account_id, $code);
                    if( $callBack !== false && isset($callBack['access_token']) )
                    {
                        $userInfo = $this->wxSDK->wechatUserinfo( $callBack['access_token'], $callBack['openid'] );

                        $users_id = $this->wxUser->createUser($userInfo, $this->wxSDK->wx_account_id);
                    }

                    $url = $this->url($wx_account_id);
                    $callBack_url = $url->callback_url.'?users_id='.$users_id;

                    if(isset($getPost['active_topic_id']))
                    {
                        $callBack_url = $url->callback_url.'?users_id='.$users_id.'&active_topic_id='.$getPost['active_topic_id'];
                    }

                    response()->redirect($callBack_url);
                    request()->abort = true;
                    return null;
                }
            }else{
                $users_id = $return['users_id'];
                $wx_account_id = $return['wx_account_id'];

                $url = $this->url($wx_account_id);
                $callBack_url = $url->callback_url.'?users_id='.$users_id;

                if(isset($getPost['active_topic_id']))
                {
                    $callBack_url = $url->callback_url.'?users_id='.$users_id.'&active_topic_id='.$getPost['active_topic_id'];
                }

                response()->redirect($callBack_url);
                request()->abort = true;
                return null;
            }
        }
    }


    public function callbackurl($wx_account_id, $callback_url)
    {
        $isSuccess = false;
        if( empty($wx_account_id) || empty($callback_url))
        {
            return $isSuccess;
        }

        $sql = "select * from callback_url where wx_account_id = ?";
        $callback_info = DB::selectOne($sql,[$wx_account_id]);

        if( count($callback_info) < 1 )
        {
            //插入回调地址
           $callback = array(
               'wx_account_id' => $wx_account_id,
               'callback_url' => $callback_url
           );
            $isSuccess = DB::table('callback_url')->insert($callback);
        }else{
            //更新回调地址
            $sql = "update callback_url set callback_url = ? where wx_account_id = ?";
            $isSuccess = DB::update($sql,[$callback_url, $wx_account_id]);
        }

        return $isSuccess;
    }

    public function url($wx_account_id)
    {
        $sql = "select * from callback_url where wx_account_id = ?";
        $callback_info = DB::selectOne($sql,[$wx_account_id]);
        return $callback_info;
    }
}

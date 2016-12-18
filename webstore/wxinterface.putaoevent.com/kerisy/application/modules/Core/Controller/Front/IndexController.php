<?php

namespace App\Core\Controller\Front;

use Kerisy\Http\Controller;
use Kerisy\Http\Request;
use Kerisy\Http\Response;
use \Kerisy\Database\DB;

use App\Core\Services\WxSDKService;
use Kerisy\Log\Logger;
use Lib\Controller\FrontController;
use App\Core\Services\IndexService;

class IndexController extends FrontController
{
	private $logService;
    private $indexService;
	private $wxSDKService;

    public function __construct(
		Logger $logger,
        IndexService $indexService,
		WxSDKService $wxSDKService
	)
    {
		parent::__construct();
		$this->logService = $logger;
		$this->wxSDKService = $wxSDKService;
        $this->indexService = $indexService;
    }

	public function guestDenyActions()
	{
		return ['index','wechat','test'];
	}

	public function index(Request $request, Response $response)
	{
        $users_id = $request->input('users_id');
        return array(
            'http_code' => 200,
            'users_id' => $users_id
        );
	}

    public function test(Request $request, Response $response)
    {
        $code = $request->input('code');
        $state = $request->input('state');
        $state = json_decode($state);
        return array(
            'http_code' => 201,
            'code' => $code,
            'wx_account_id' => $state['wx_account_id'],
            'callback_url' => $state['callback_url']
        );
    }

    public function getUser(Request $request, Response $response)
    {
        $users_id = $request->input('users_id');
        $user_info = $this->indexService->getUser($users_id);
        return array(
            'http_code' => 200,
            'user_info' => $user_info
        );
    }

    public function jssdk(Request $request, Response $response)
    {
        $wx_account_id = $request->input('wx_account_id');
        $url = $request->input('url');
        $parameters['signPackage'] = $this->wxSDKService->getSignPackage($wx_account_id, $url);
        return json_encode($parameters['signPackage'] );
    }

    public function token(Request $request, Response $response)
    {
        $wx_account_id = $request->input('wx_account_id');

        $accessToken = $this->indexService->getAccessToken($wx_account_id);
        return array(
            'http_code' => 200,
            'access_token' => $accessToken
        );
    }

}

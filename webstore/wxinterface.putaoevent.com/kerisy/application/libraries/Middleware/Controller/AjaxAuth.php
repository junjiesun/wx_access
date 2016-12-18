<?php
namespace Lib\Middleware\Controller;

use Kerisy\Core\MiddlewareContract;


/**
 * BasicAccess middleware.
 *
 * @package Kerisy\Auth\Middleware
 */
class AjaxAuth implements MiddlewareContract
{
    /**
     *
     * @param Request $request
     */
    public function handle($controller)
    {
		
		$user = $controller->getUser();
		
		if ( empty( $user ) && in_array( request()->getRoute()->getAction(), $controller->ajaxGuestDenyActions() ) )
		{
			request()->abort = true;
 
			return response()->json([
				'httpStatusCode' => 403,
				'message' => "用户信息错误, 请重新登入后在尝试"
			]);
		}

    }
	
}
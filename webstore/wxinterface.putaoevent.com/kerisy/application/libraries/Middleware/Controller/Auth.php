<?php
namespace Lib\Middleware\Controller;

use Kerisy\Core\MiddlewareContract;
use Kerisy\Log\Logger;


/**
 * BasicAccess middleware.
 *
 * @package Kerisy\Auth\Middleware
 */
class Auth implements MiddlewareContract
{
    /**
     *
     * @param Request $request
     */
    public function handle($controller)
    {
        $user      = $controller->getUser();
        $currentCM = $controller->initCA();
		if (in_array(request()->getRoute()->getAction(), $controller->allowActions()))
		{
            request()->abort = false;
			return true;
		}
        else
        {
            if(empty($user))
            {
                response()->redirect('/signin');
                request()->abort = true;
                return false;
            }
        }
        response()->assign('user',$user);
        response()->assign('currentCM',$currentCM);
    }
}
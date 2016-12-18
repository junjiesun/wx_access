<?php
namespace Lib\Middleware\Controller;

use Kerisy\Core\MiddlewareContract;


/**
 * BasicAccess middleware.
 *
 * @package Kerisy\Auth\Middleware
 */
class SigninAuth implements MiddlewareContract
{
    /**
     *
     * @param Request $request
     */
    public function handle($controller)
    {
		
		$user = $controller->getUser();
		
		if ( empty( $user ) && in_array( request()->getRoute()->getAction(), $controller->guestDenyActions() ) )
		{
			response()->redirect('/signin');
			request()->abort = true;
			
			return false;
		}

    }
	
}
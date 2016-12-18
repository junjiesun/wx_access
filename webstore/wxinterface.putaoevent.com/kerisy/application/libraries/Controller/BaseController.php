<?php
namespace Lib\Controller;

use Kerisy\Http\Request;
use Kerisy\Http\Controller;
use Kerisy\Log\Logger;

class BaseController extends Controller
{
	private $allowActions = ['signin', 'signinPage', 'logout'];
	
    public function __construct()
    {
        $this->middleware = ['Lib\Middleware\Controller\Auth'];
    }

    public function getUser()
    {
        $cookie = request()->cookie;
        $user = array();
        
        if ( array_key_exists("sid", $cookie) )
        {
            $user = session()->get($cookie['sid']);
            if(empty($user))
            {
                $key = $cookie['sid'];
                session()->destroy($key);
                response()->setCookie( 'sid', '', time() - 3600 );
                        
                if( !empty( session()->get($key) ) )
                {
                    return false;
                }
            }
        }
        return $user;
    }

    public function allowActions()
    {
        return $this->allowActions;
    }
	
	public function setAllowActions( Array $arr )
    {
        $this->allowActions = array_unique(array_merge($this->allowActions, $arr));
    }
	
	public function setCacheData( $key, $values, $expiration = null )
	{
		if ( empty($expiration) )
		{
			$expiration = time() + 3600 * 2;
		}
			
		return cache()->set($key, $values, $expiration);
	}
	
	public function getCacheData( $key )
	{
		return cache()->get($key);
	}
	
	public function deleteCacheData( $key )
	{
		return cache()->destroy($key);
	}

    public function menu()
    {
        $user    = $this->getUser();
        $menuKey = sha1('menu'.$user['userId'].$user['name']);

        $allowMenu = array();
        if($menuList = cache()->get($menuKey))
        {
            foreach ($menuList as $menu)
            {
                if(intval($menu['parent_menu_id']) === 0)
                {
                    $allowMenu[$menu['menu_id']] = $menu;
                }else
                {
                    if(isset($allowMenu[$menu['parent_menu_id']]))
                        $allowMenu[$menu['parent_menu_id']]['submenu'][] = $menu;
                }
            }
        }

        return $allowMenu;
    }

    public function getUserPermission()
    {
        $user          = $this->getUser();

        $permissionKey = sha1('user_permission'.$user['userId'].$user['name']);

        $allowPermission = array();
        if($permissionList = cache()->get($permissionKey))
        {
            foreach ($permissionList as $permission) 
            {
                if($permission->method)
                {
                    array_push($allowPermission, strtolower($permission->controller.'_'.$permission->method));    
                }
            }
        }
        array_push($allowPermission,'index_index');
        return $allowPermission;
    }

    public function initCA()
    {
        return strtolower(request()->getRoute()->getController() . '_' . request()->getRoute()->getAction());
    }
}
?>
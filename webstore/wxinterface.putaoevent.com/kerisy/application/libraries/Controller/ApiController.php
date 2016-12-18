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

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware = ['Lib\Middleware\Controller\Auth'];
    }

    public function showError($error = 'System Error', $code = 10000)
    {
        response()->json(errorFormat($error, $code));
        request()->abort = true;
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

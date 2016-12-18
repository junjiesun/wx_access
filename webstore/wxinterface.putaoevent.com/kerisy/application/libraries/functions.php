<?php
/**
 * Created by PhpStorm.
 * User: haoyanfei<haoyf@putao.com>
 * Date: 2015/12/1
 * Time: 10:54
 */

if (!function_exists('cloud_file')) {
    function cloud_file($filename)
    {
        $url = config('config')->get('file_cloud')['read'];
        return trim($url, '/') . DIRECTORY_SEPARATOR . $filename;
    }
}
if (!function_exists('makeVerify')) {
    function makeVerify($data, $signKey)
    {
        ksort($data);
        $phone = $data['mobile'];
        $password = $data['passwd'];
        $code = 'appid=1108&client_type=web&device_id=dev&mobile='. $phone .'&passwd='. $password .'&platform_id=1&version=1.0.0';

        if($data['type'] == 'AVATAR'){
            $token = $data['token'];
            $uid = $data['uid'];
            $serverid = $data['serverid'];
            $code = 'appid=1108&uid='. $uid .'&token='. $token .'&serverid=' . $serverid;
        }

        return md5($code . $signKey);
    }
}

function getFilePath ( $fileName, $w='', $h='' )
{
    if( empty($fileName) )
    {
        return '';
    }
	
    $path = config('config')->get('file_cloud')['read'] . $fileName; 
    return $path;   
}
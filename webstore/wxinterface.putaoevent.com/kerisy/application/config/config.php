<?php
$account = 'https://account-api.putao.com';
$passport = 'https://account.putao.com';

//$account = 'https://account.store.com';
return [
    'file_cloud'=> [
        'appid' => 2001,
        'read'  => 'http://bbs.file.putaocdn.com/file/',
        'write' => 'http://upload.putaocloud.com/upload',
        'check' => 'http://upload.putaocloud.com/fileinfo',
        'key' => [
            'access_key' => '7b34bd2a9d0f4181adabbead19a02b82',
            'secret' => 'e4189370a2504c0abf8bd16458339166'
        ]
    ],
    /** passport 通行证参数 **/
    'passport'=>[
        'site_url' => $passport,
        'login_url' => $passport . '/login',
        'register_url' => $passport . '/register',
        'logout_url' => $passport . '/logout',
        'check_login_url' => $passport . '/api/user/checktoken',
        'secret_key' => '40f76636eea970fb91c4bec27368d921',
        'base_info_url'=> $passport . '/api/user/basicinfo'
    ],
    /** api 通行证参数**/
    'account' => [
        'site_url' => $account,
        'serverid' => '1113',
        'login' => $account . '/api/login',
        'readAvatar'=>'http://account.file.putaocdn.com/file',
        'checkToken' => $account . '/api/checkToken',
        'getNickName' => $account . '/api/getNickName',
        'getAvatar' => $account . '/api/getUserAvatar',
        'secret_key' => 'd9f8a7ae956244818d4565761649a636',
        'sync_secret_key' => '262381a3dc3444189f3363eb460d81df',//同步信息的key
    ],
    'threads_url'=> 'http://bbs.putao.com/thread/',
//    'wx_account'=>[
    //        'appId' => 'wx92c54af03f85d146',
    //        'appSecret' => '780d1ce733e8d7c7f5f09dcf183836f3'
    //    ],
    //    'wx_account'=>[
    //        'appId' => 'wx36d0f49943b88762',
    //        'appSecret' => '89c24f1a4d775feb85593ad09118f21d'
    //    ],
    'wx_account'=>[
        'appId' => 'wx489a5077d182e494',
        'appSecret' => '461ce11ab879b936a290d35faa202f65'
    ],
];
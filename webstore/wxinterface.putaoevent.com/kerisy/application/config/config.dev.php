<?php
$account = 'https://account-api-dev.putao.com';
$passport = 'https://account.putao.com';

//$account = 'https://account.store.com';
return [
    'file_cloud'=> [
        'appid' => 2001,
        'read' => 'http://bbs.file.dev.putaocloud.com/file/',
        'write' => 'http://upload.dev.putaocloud.com/upload',
        'check' => 'http://upload.dev.putaocloud.com/fileinfo',
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
        'readAvatar'=>'http://account.file.dev.putaocloud.com/file',
        'checkToken' => $account . '/api/checkToken',
        'getNickName' => $account . '/api/getNickName',
        'getAvatar' => $account . '/api/getUserAvatar',
        'secret_key' => 'd9f8a7ae956244818d4565761649a636',
        'sync_secret_key' => '262381a3dc3444189f3363eb460d81df',//同步信息的key
    ],
    'url'    => 'http://api.weidu.start.wang/message/callback',
    'signKey'=> 'a4d1eb96ds424771f3p62537fd51fc30',
    'threads_url'=> 'http://ldev.community.putao.com/thread/',
//    'wx_account'=>[
//        'appId' => 'wx2684071a10856ff8',
//        'appSecret' => 'f13db91ac1fe44b3691b489dbdf40096'
//    ],
    //自用测试号
    'wx_account'=>[
        'appId' => 'wxe1dcbec64decc586',
        'appSecret' => '1885a29d89e368ec7b88ec13d5fcb0b3'
    ],
//    'wx_account'=>[
//        'appId' => 'wxb78d0b4ad3e07834',
//        'appSecret' => '219b2264f3d44c700389ae0c2e9f6eec'
//    ],
//    //发布会参数
//    'conference_account'=>[
//        'appId' => 'wx36d0f49943b88762',
//        'appSecret' => 'a51574a677a6fe509b0d2a09fc0ee6ee',
//    ],
];
<?php
return [
    'request' => [
        'class' => \Kerisy\Http\Request::class,
        'middleware' => [],
    ],
    'response' => [
        'class' => \Kerisy\Http\Response::class,
        'middleware' => [],
    ],
    'cache' => [
        'class' => 'Kerisy\Cache\Manager',
        'expires' => 3600 * 24,
        'engine' => [
            'class' => 'Kerisy\Cache\Engine\MemcachedEngine',
            'host' => '127.0.0.1',
            'port' => 11211,
            'prefix' => 'kerisy_'
            ]
    ],
    'session' => [
        'class' => 'Kerisy\Session\Manager',
        'expires' => 3600 * 24 * 1,
// Use Redis Storage
/*
        'storage' => [
            'class' => 'Kerisy\Session\RedisStorage',
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => '',
            'prefix' => 'session_'
        ]*/
// Use Memcache Storage
        'storage' => [
            'class' => 'Kerisy\Session\MemcachedStorage',
            'host' => '127.0.0.1',
            // 'host' => 'memcache1.putao.io',
            'port' => '11211',
            'prefix' => 'session_'
        ]
// Use FileSystem Storage
//        'storage' => [
//            'class' => 'Kerisy\Session\FileStorage',
//            'path' => __DIR__ . '/../../runtime/sessions'
//        ]
    ],
    'auth' => [
        'class' => 'Kerisy\Auth\Auth',
        'model' => 'App\User\Model\User',
    ],
    'log' => [
        'class' => 'Kerisy\Log\Logger',
        'targets' => [
            'file' => [
                'class' => 'Kerisy\Log\StreamTarget',
                'enabled' => true,
                'stream' => 'php://stderr',
                'level' => 'info',
            ]
        ],
    ],
];

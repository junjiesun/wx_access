<?php
return [
    'class' => '\Kerisy\Server\Swoole',
    'bootstrap' => require __DIR__ . '/../bootstrap.php',
    'host' => '0.0.0.0',
    'port' => 7456,
    'numWorkers' => 1
];

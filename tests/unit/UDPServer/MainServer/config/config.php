<?php

return [
    'configs'    =>    [
    ],
    // bean扫描目录
    'beanScan'    =>    [
        'Imi\Test\UDPServer\MainServer\Controller',
        'Imi\Test\UDPServer\MainServer\Listener',
    ],
    'beans'    =>    [
        'UdpDispatcher'    =>    [
            'middlewares'    =>    [
                \Imi\Server\UdpServer\Middleware\RouteMiddleware::class,
                \Imi\Test\UDPServer\MainServer\Middleware\Test::class,
            ],
        ],
        'ConnectContextStore'   =>  [
            'handlerClass'  =>  \Imi\Server\ConnectContext\StoreHandler\MemoryTable::class,
        ],
        'ConnectContextMemoryTable' =>  [
            'tableName' =>  'connectContext',
        ],
        'ServerGroup'   =>  [
            'status'    =>  false,
        ],
    ],
];
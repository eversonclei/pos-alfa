<?php

return [
    'service_manager' => [
        'factories' => [
            Application\Model\BeerTableGateway::class =>  Application\Factory\BeerTableGateway::class,
            Application\Factory\DbAdapter::class => Application\Factory\DbAdapter::class,
            Application\Service\Auth::class => Application\Factory\ServiceAuth::class,          
        ],
    ],
    'db' => [
        'driver' => 'Pdo_Sqlite',
        'database' => 'data/beers.db',
    ],
];
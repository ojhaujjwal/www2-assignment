<?php
return [
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'App\Entity' => 'App_Entity',
                ],
            ],
            'App_Entity' => [
                'class' => \Doctrine\ORM\Mapping\Driver\XmlDriver::class,
                'cache' => 'array',
                'paths' => __DIR__ . '/../doctrine',
            ],
        ],
    ],
];

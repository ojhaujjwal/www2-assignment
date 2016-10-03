<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
            App\Action\PingAction::class => App\Action\PingAction::class
        ],
        'factories' => [
            App\Action\HomePageAction::class => App\Action\HomePageFactory::class,
            Api\TvShow\TvShowResource::class      => Api\TvShow\TvShowResourceFactory::class,
            Api\TvShow\ReviewResource::class      => Api\TvShow\ReviewResourceFactory::class,
        ],
    ],

    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'middleware' => App\Action\HomePageAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'api.tv-shows',
            'path' => '/api/tv-shows[/{id:\d+}]',
            'middleware' => Api\TvShow\TvShowResource::class,
            'allowed_methods' => ['GET', 'POST', 'PUT'],
        ],
        [
            'name' => 'api.tv-show.reviews',
            'path' => '/api/tv-shows/{tvShowId:\d+}/reviews',
            'middleware' => Api\TvShow\ReviewResource::class,
            'allowed_methods' => ['GET', 'POST'],
        ],
        [
            'name' => 'api.reviews',
            'path' => '/api/reviews/{id:\d+}',
            'middleware' => Api\TvShow\ReviewResource::class,
            'allowed_methods' => ['PUT', 'DELETE'],
        ],
    ],
];

<?php
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;
use App\InputFilter\TvShowInputFilter;
use App\InputFilter\ReviewInputFilter;

return [

    'dependencies' => [
        'invokables' => [
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
            TvShowInputFilter::class => TvShowInputFilter::class,
            ReviewInputFilter::class => ReviewInputFilter::class
        ],

        'factories' => [
            Application::class => ApplicationFactory::class,
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,

        ],
        'aliases' => [
            'doctrine.entity_manager.orm_default' => 'doctrine.entitymanager.orm_default'
        ]
    ],
];

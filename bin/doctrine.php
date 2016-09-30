<?php
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

return new \Symfony\Component\Console\Helper\HelperSet([
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper(
        $container->get('doctrine.entity_manager.orm_default')
    ),
]);

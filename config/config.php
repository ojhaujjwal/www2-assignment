<?php

/**
 * Configuration files are loaded in a specific order. First ``global.php``, then ``*.global.php``.
 * then ``local.php`` and finally ``*.local.php``. This way local settings overwrite global settings.
 *
 * The configuration can be cached. This can be done by setting ``config_cache_enabled`` to ``true``.
 *
 * Obviously, if you use closures in your config you can't cache it.
 */

use Zend\Expressive\ConfigManager\ConfigManager;
use Zend\Expressive\ConfigManager\PhpFileProvider;
use App\ConfigManager\Zend2ModuleProvider;

$configManager = new ConfigManager(
    [
        new Zend2ModuleProvider(new DoctrineModule\Module()),
        new Zend2ModuleProvider(new DoctrineORMModule\Module()),
        new PhpFileProvider('config/autoload/{{,*.}global,{,*.}local}.php'),
    ],
    'data/cache/app_config.php'
);

return $configManager->getMergedConfig();

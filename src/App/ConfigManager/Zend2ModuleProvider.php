<?php

namespace App\ConfigManager;

use Zend\ModuleManager\Feature\ConfigProviderInterface;


class Zend2ModuleProvider
{
    /**
     * @var ConfigProviderInterface
     */
    private $module;

    /**
     * Zend2ModuleProvider constructor.
     * @param ConfigProviderInterface $module
     */
    public function __construct(ConfigProviderInterface $module)
    {
        $this->module = $module;
    }

    public function __invoke()
    {
        return $this->module->getConfig();
    }
}
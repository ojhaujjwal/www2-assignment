<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class TvShowCreateActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new TvShowCreateAction($container->get(TemplateRendererInterface::class));
    }
}

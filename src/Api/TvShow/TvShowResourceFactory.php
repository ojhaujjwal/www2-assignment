<?php
namespace Api\TvShow;

use App\InputFilter\TvShowInputFilter;
use Interop\Container\ContainerInterface;

class TvShowResourceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new TvShowResource(
            $container->get('doctrine.entitymanager.orm_default'),
            $container->get(TvShowInputFilter::class)
        );
    }
}
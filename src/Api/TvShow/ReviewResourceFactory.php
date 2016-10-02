<?php
namespace Api\TvShow;

use App\InputFilter\ReviewInputFilter;
use Interop\Container\ContainerInterface;

class ReviewResourceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ReviewResource(
            $container->get('doctrine.entitymanager.orm_default'),
            $container->get(ReviewInputFilter::class)
        );
    }
}

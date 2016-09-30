<?php
namespace Api\TvShow;

use Api\AbstractResource;
use Zend\Diactoros\Response\JsonResponse;

class TvShowResource extends AbstractResource
{
    public function fetch($id)
    {
        return [];
    }

    public function fetchAll(array $params = [])
    {
        return [];
    }

    public function create(array $data = [])
    {
        return new JsonResponse([], 201);
    }
}

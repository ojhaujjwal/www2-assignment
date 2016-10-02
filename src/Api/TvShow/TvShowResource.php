<?php
namespace Api\TvShow;

use Api\AbstractResource;
use App\Entity\TvShow;

class TvShowResource extends AbstractResource
{
    const ENTITY_CLASS = TvShow::class;


    public function create(TvShow $tvShow)
    {
        $this->entityManager->persist($tvShow);
        $this->entityManager->flush();

        return $tvShow;
    }

    protected function fetch($id)
    {
        $repository = $this->entityManager->getRepository(static::ENTITY_CLASS);
        return $repository->find($id);
    }

    protected function fetchAll($data = [])
    {
        $repository = $this->entityManager->getRepository(static::ENTITY_CLASS);
        return $repository->findBy($data);
    }
}

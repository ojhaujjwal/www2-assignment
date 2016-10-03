<?php
namespace Api\TvShow;

use Api\AbstractResource;
use App\Entity\TvShow;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Diactoros\Response\JsonResponse;

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

        $tvShow = $repository->find($id);
        if (!$tvShow) {
            return new JsonResponse(['message' => 'Not found'], 404);
        }

        return $tvShow;
    }

    protected function fetchAll($data = [])
    {
        $repository = $this->entityManager->getRepository(static::ENTITY_CLASS);
        return new ArrayCollection($repository->findBy([], [$data['sort'] ?? null => 'ASC']));
    }
}

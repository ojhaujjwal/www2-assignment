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
            return new JsonResponse(['message' => 'Not found'], 404, [], JSON_PRETTY_PRINT);
        }

        return $tvShow;
    }

    protected function fetchAll($data = [])
    {
        $repository = $this->entityManager->getRepository(static::ENTITY_CLASS);
        $sort = [];
        if (isset($data['sort'])) {
            $sort[$data['sort']] = 'ASC';
        }
        return new ArrayCollection($repository->findBy([], $sort));
    }
}

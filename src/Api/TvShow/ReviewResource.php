<?php
namespace Api\TvShow;

use Api\AbstractResource;
use App\Entity\Review;
use App\Entity\TvShow;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ReviewResource extends AbstractResource
{
    const ENTITY_CLASS = Review::class;

    public function create(Review $review, ServerRequestInterface $request)
    {
        /** @var TvShow $tvShow */
        $tvShow = $this->entityManager
            ->getRepository(TvShow::class)
            ->find($request->getAttribute('tvShowId'));
        $tvShow->addReview($review);

        $this->entityManager->persist($review);
        $this->entityManager->flush();

        return $review;
    }

    public function fetchAll(array $query, ServerRequestInterface $request)
    {
        /** @var TvShow $tvShow */
        $tvShow = $this->entityManager
            ->getRepository(TvShow::class)
            ->find($request->getAttribute('tvShowId'));
        return $tvShow->getReviews();
    }

    public function update(Review $review)
    {
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        return $review;
    }

    public function delete(Review $review)
    {
        $this->entityManager->remove($review);
        $this->entityManager->flush();
        return new JsonResponse(null, 204);
    }
}

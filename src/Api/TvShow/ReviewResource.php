<?php
namespace Api\TvShow;

use Api\AbstractResource;
use App\Entity\Review;
use App\Entity\TvShow;
use Psr\Http\Message\ServerRequestInterface;

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
}

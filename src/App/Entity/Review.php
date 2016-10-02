<?php
namespace App\Entity;


class Review
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $review;

    /**
     * @var int
     */
    protected $rating;

    /**
     * @var string
     */
    protected $reviewerName;

    /**
     * @var TvShow
     */
    protected $tvShow;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getReview(): string
    {
        return $this->review;
    }

    /**
     * @param string $review
     */
    public function setReview(string $review)
    {
        $this->review = $review;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return string
     */
    public function getReviewerName(): string
    {
        return $this->reviewerName;
    }

    /**
     * @param string $reviewerName
     */
    public function setReviewerName(string $reviewerName)
    {
        $this->reviewerName = $reviewerName;
    }

    /**
     * @param TvShow $tvShow
     */
    public function setTvShow(TvShow $tvShow)
    {
        $this->tvShow = $tvShow;
    }
}

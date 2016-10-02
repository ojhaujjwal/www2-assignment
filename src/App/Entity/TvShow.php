<?php
namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class TvShow
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $imageUrl;

    /**
     * @var Collection
     */
    protected $reviews;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return Collection
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    /**
     * @param Review $review
     */
    public function addReview(Review $review)
    {
        $this->reviews->add($review);
        $review->setTvShow($this);
    }
}

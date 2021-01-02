<?php

namespace App\Entity;

use App\Repository\UrlsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UrlsRepository::class)
 */
class Urls
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortUrl;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastVisited;

    /**
     * @ORM\Column(type="integer")
     */
    private $visitCount;

    /**
     * Urls constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW');
        $this->visitCount = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalUrl(): ?string
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl(string $originalUrl): self
    {
        $this->originalUrl = $originalUrl;

        return $this;
    }

    public function getShortUrl(): ?string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $short_url): self
    {
        $this->shortUrl = $short_url;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->createdAt = $created_at;

        return $this;
    }

    public function getLastVisited(): ?\DateTimeInterface
    {
        return $this->lastVisited;
    }

    public function setLastVisited(?\DateTimeInterface $last_visited): self
    {
        $this->lastVisited = $last_visited;

        return $this;
    }

    public function getVisitCount(): ?int
    {
        return $this->visitCount;
    }

    public function setVisitCount(int $visit_count): self
    {
        $this->visitCount = $visit_count;

        return $this;
    }
}

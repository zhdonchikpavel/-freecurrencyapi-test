<?php

namespace App\Entity\Common;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait Timestamps
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private $updatedAt;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $timestamp): self
    {
        $this->createdAt = $timestamp;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $timestamp): self
    {
        $this->updatedAt = $timestamp;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtAutomatically()
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime());
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtAutomatically()
    {
        $this->setUpdatedAt(new DateTime());
    }
}

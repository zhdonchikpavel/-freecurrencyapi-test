<?php

namespace App\Entity;

use App\Entity\Common\Timestamps;
use App\Repository\RateRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: RateRepository::class)]
#[HasLifecycleCallbacks]
class Rate
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $sourceCurrency = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Currency $targetCurrency;

    #[ORM\Column]
    private float $rate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceCurrency(): ?Currency
    {
        return $this->sourceCurrency;
    }

    public function setSourceCurrency(?Currency $currency): static
    {
        $this->sourceCurrency = $currency;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getTargetCurrency(): ?Currency
    {
        return $this->targetCurrency;
    }

    public function setTargetCurrency(Currency $targetCurrency): static
    {
        $this->targetCurrency = $targetCurrency;

        return $this;
    }
}

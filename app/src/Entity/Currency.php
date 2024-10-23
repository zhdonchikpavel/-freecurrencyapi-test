<?php

namespace App\Entity;

use App\Entity\Common\Timestamps;
use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[HasLifecycleCallbacks]
class Currency
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 3, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $decimalDigits = null;

    /**
     * @var Collection<int, Rate>
     */
    #[ORM\OneToMany(targetEntity: Rate::class, mappedBy: 'sourceCurrency', orphanRemoval: true)]
    private Collection $rates;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isActive = false;

    public function __construct()
    {
        $this->rates = new ArrayCollection();
        $this->isActive = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDecimalDigits(): ?int
    {
        return $this->decimalDigits;
    }

    public function setDecimalDigits(int $decimalDigits): static
    {
        $this->decimalDigits = $decimalDigits;

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): static
    {
        if (!$this->rates->contains($rate)) {
            $this->rates->add($rate);
            $rate->setSourceCurrency($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): static
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getSourceCurrency() === $this) {
                $rate->getSourceCurrency(null);
            }
        }

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive = false): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}

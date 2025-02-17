<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, Quality>
     */
    #[ORM\OneToMany(targetEntity: Quality::class, mappedBy: 'ingrdient', orphanRemoval: true)]
    private Collection $qualities;

    public function __construct()
    {
        $this->qualities = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Quality>
     */
    public function getQualities(): Collection
    {
        return $this->qualities;
    }

    public function addQuality(Quality $quality): static
    {
        if (!$this->qualities->contains($quality)) {
            $this->qualities->add($quality);
            $quality->setIngrdient($this);
        }

        return $this;
    }

    public function removeQuality(Quality $quality): static
    {
        if ($this->qualities->removeElement($quality)) {
            // set the owning side to null (unless already changed)
            if ($quality->getIngrdient() === $this) {
                $quality->setIngrdient(null);
            }
        }

        return $this;
    }
}

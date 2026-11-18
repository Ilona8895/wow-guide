<?php

namespace App\Entity;

use App\Enum\Faction;
use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: Faction::class)]
    private ?Faction $faction = null;

    /**
     * @var Collection<int, CharacterClass>
     */
    #[ORM\ManyToMany(targetEntity: CharacterClass::class, inversedBy: 'races')]
    private Collection $allowedClasses;

    public function __construct()
    {
        $this->allowedClasses = new ArrayCollection();
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

    public function getFaction(): ?Faction
    {
        return $this->faction;
    }

    public function setFaction(Faction $faction): static
    {
        $this->faction = $faction;

        return $this;
    }

    /**
     * @return Collection<int, CharacterClass>
     */
    public function getAllowedClasses(): Collection
    {
        return $this->allowedClasses;
    }

    public function addAllowedClass(CharacterClass $allowedClass): static
    {
        if (!$this->allowedClasses->contains($allowedClass)) {
            $this->allowedClasses->add($allowedClass);
        }

        return $this;
    }

    public function removeAllowedClass(CharacterClass $allowedClass): static
    {
        $this->allowedClasses->removeElement($allowedClass);

        return $this;
    }
}

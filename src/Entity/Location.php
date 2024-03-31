<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $location_id = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $name;

    public function getId(): ?int
    {
        return $this->location_id;
    }

    public function setLocationId(int $location_id): static
    {
        $this->location_id = $location_id;

        return $this;
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
}

<?php

namespace App\Entity;

use App\Repository\AuditorRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AuditorRepository::class)]
class Auditor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $auditor_id = null;

    #[Assert\NotBlank]
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $name;

    #[Assert\NotBlank]
    #[Assert\Type(Location::class)]
    #[ORM\ManyToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(name: "location_id", referencedColumnName: "location_id", nullable: false)]
    private Location $location;

    public function getId(): ?int
    {
        return $this->auditor_id;
    }

    public function setAuditorId(int $auditor_id): static
    {
        $this->auditor_id = $auditor_id;

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

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): static
    {
        $this->location = $location;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $job_id = null;

    #[Groups(["default", "create"])]
    #[Assert\NotBlank(groups: ["default", "create"])]
    #[Assert\NotBlank]
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $description;

    public function getId(): ?int
    {
        return $this->job_id;
    }

    public function setJobId(int $job_id): static
    {
        $this->job_id = $job_id;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}

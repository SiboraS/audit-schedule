<?php

namespace App\Entity;

use App\Repository\AssignedJobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssignedJobRepository::class)]
class AssignedJob
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $assignment_id = null;

    #[Assert\NotBlank]
    #[Assert\Type(Job::class)]
    #[ORM\OneToOne(targetEntity: Job::class)]
    #[ORM\JoinColumn(name: "job_id", referencedColumnName: "job_id", nullable: false)]
    private Job $job;

    #[Assert\NotBlank]
    #[Assert\Type(Auditor::class)]
    #[ORM\ManyToOne(targetEntity: Auditor::class)]
    #[ORM\JoinColumn(name: "auditor_id", referencedColumnName: "auditor_id", nullable: false)]
    private Auditor $auditor;

    #[Assert\NotBlank]
    #[Assert\Type("int")]
    #[ORM\Column]
    private ?int $completion_status = 0;

    #[Assert\Type("string")]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $assessment = null;

    #[Assert\Type(\DateTimeInterface::class)]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->assignment_id;
    }

    public function setAssignmentId(int $assignment_id): static
    {
        $this->assignment_id = $assignment_id;

        return $this;
    }

    public function getJobId(): ?Job
    {
        return $this->job;
    }

    public function setJob(Job $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getAuditor(): ?Auditor
    {
        return $this->auditor;
    }

    public function setAuditor(Auditor $auditor): static
    {
        $this->auditor = $auditor;

        return $this;
    }

    public function getCompletionStatus(): ?int
    {
        return $this->completion_status;
    }

    public function setCompletionStatus(int $completion_status): static
    {
        $this->completion_status = $completion_status;

        return $this;
    }

    public function getAssessment(): ?string
    {
        return $this->assessment;
    }

    public function setAssessment(string $assessment): static
    {
        $this->assessment = $assessment;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}

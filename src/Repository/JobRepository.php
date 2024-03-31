<?php

namespace App\Repository;

use App\Entity\AssignedJob;
use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct($registry, Job::class);
    }

    /**
     * Find available jobs that are not assigned to anyone
     *
     * @return mixed
     */
    public function getUnassignedJobs()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        return $queryBuilder
            ->select('j')
            ->from(Job::class, 'j')
            ->leftJoin(AssignedJob::class, 'aj', 'WITH', 'j.job_id = aj.job')
            ->where('aj.job IS NULL')
            ->getQuery()
            ->getResult();
    }

}

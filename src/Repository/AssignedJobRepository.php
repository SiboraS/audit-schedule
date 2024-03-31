<?php

namespace App\Repository;

use App\Entity\AssignedJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssignedJob>
 *
 * @method AssignedJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssignedJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssignedJob[]    findAll()
 * @method AssignedJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssignedJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssignedJob::class);
    }
}

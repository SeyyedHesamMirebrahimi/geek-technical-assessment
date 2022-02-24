<?php

namespace App\Repository;

use App\Entity\SubStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubStep[]    findAll()
 * @method SubStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubStepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubStep::class);
    }

    // /**
    //  * @return SubStep[] Returns an array of SubStep objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubStep
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function getInspectionSubSteps()
    {
        return $this->createQueryBuilder('s')
            ->select('s.id , s.name , inspection.id as inspectionID , inspection.heading')
            ->innerJoin('s.inspection' , 'inspection')



            ->addGroupBy('inspection.id')

            ->getQuery()
            ->getArrayResult();
    }
}

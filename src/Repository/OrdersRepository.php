<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Entity\Clients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    public function findAll()
    {
        $fields = [
            'o.id',
            'o.confirmationDate',
            'o.deliveryDate',
            'o.deliveryTime',
            'o.status',
            'o.paymentStatus',
            'client.id clientId',
            'client.firstName',
            'client.lastName',
        ];
        return $this->createQueryBuilder('o')
            ->select($fields)
            ->innerJoin('o.client', 'client')
            ->getQuery()
            ->getResult();
    }

    public function findByClient(Clients $client)
    {
        $fields = [
            'o.id',
            'o.confirmationDate',
            'o.deliveryDate',
            'o.deliveryTime',
            'o.status',
            'o.paymentStatus',
            'client.id clientId',
            'client.firstName',
            'client.lastName',
        ];
        return $this->createQueryBuilder('o')
            ->select($fields)
            ->innerJoin('o.client', 'client')
            ->andWhere('client.id = :client')
            ->setParameter('client', $client)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Orders[] Returns an array of Orders objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Orders
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

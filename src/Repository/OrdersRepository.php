<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Entity\Clients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function findAllPaginated(
        int $page = 0,
        int $limit = 0,
        array $sort = [],
        string $groupBy = ''
    ) {
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
            'client.city',
        ];
        $query = $this->createQueryBuilder('o')
            ->select($fields)
            ->innerJoin('o.client', 'client');
        if (!empty($sort)) {
            $query->addOrderBy($sort['criteria'], 'desc');
        }
        if (!empty($groupBy)) {
            $query->addGroupBy($groupBy);
        }
        $query->getQuery();

        $paginator = $this->paginate($query, $page, $limit);
        // WHY THIS ???
        $paginator->setUseOutputWalkers(false);
        return $paginator;
    }

    public function paginate($query, $page = 1, $limit = 5)
    {
        $paginator = new Paginator($query);

        $paginator
            ->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit);

        return $paginator;
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
            'client.city',
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

    public function findByStatus(
        string $status,
        int $page,
        int $limit,
        array $sort,
        string $groupBy
    ) {
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
            'client.city',
        ];
        $query = $this->createQueryBuilder('o')
            ->select($fields)
            ->innerJoin('o.client', 'client')
            ->andWhere('o.status = :status')
            ->setParameter('status', $status);
        if (!empty($sort)) {
            $query->addOrderBy($sort['criteria'], 'desc');
        }
        $query->getQuery();

        $paginator = $this->paginate($query, $page, $limit);
        $paginator->setUseOutputWalkers(false);
        return $paginator;
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
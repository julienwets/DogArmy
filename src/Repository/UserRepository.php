<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\SearchUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $params
     * @return Query
     */
    public function findFilter(SearchUser $params): Query
    {
        $query = $this->createQueryBuilder('u');

        if (!empty($params->getEmail())) {
            $query->andWhere('u.email = :email')
                ->setParameter('email', $params->getEmail());
        }

        if (!empty($params->getHomeType())) {
            $query->andWhere('u.homeType = :homeType')
                ->setParameter('homeType', $params->getHomeType());
        }

        if (!empty($params->getHomeDetails())) {
            $arr = [];
            foreach ($params->getHomeDetails() as $item => $key) {
                $arr[$key] = $key;
            }

            $query->andWhere('u.homeDetails like :homeDetails')
                // ->setParameter('homeDetails', '%'.$arr[$key].'%');
                ->setParameter('homeDetails', '%' . $arr[$key] . '%');
        }

        if (!empty($params->getDuringWork())) {
            $query->andWhere('u.duringWork = :duringWork')
                ->setParameter('duringWork', $params->getDuringWork());
        }

        // if (!empty($params->getDuringWork())) {

        //     $query->andWhere('u.duringWork Like :duringWork')
        //         ->setParameter('duringWork', '%'.implode('; ', $params->getDuringWork()).'%');
        // }

        // dump($params->getDuringWork());


        $query->orderBy('u.email', 'ASC');
        $resultat = $query
            ->getQuery();

        return $resultat;
    }

    public function findSql($params)
    {

        $conn = $this->getEntityManager()->getConnection();

        $meta = $this->getEntityManager()->getClassMetadata(User::class);
        $tableUser = $meta->getTableUser();

        $sql = "SELECT * FROM $tableUser ";

        if (isset($params['email']) and !empty($params['email'])) {
            $sql .= ' u.email =' . $params['email'];
        }

        if (isset($params['homeType']) and !empty($params['homeType'])) {
            $sql .= ' u.disponibility =' . $params['disponibility'];
        }

        // if (isset($params['homeDetails']) and !empty($params['homeDetails'])) {
        //     $sql .= ' u.homeDetails ='.$params['homeDetails'] ;
        // }
        if (isset($params['duringWork']) and !empty($params['duringWork'])) {
            $sql .= ' u.duringWork =' . $params['duringWork'];
        }

        // if (isset($params['duringWork']) and !empty($params['duringWork'])) {
        //     $sql .= ' u.duringWork ='.$params->getDuringWork() ;
        // }

        $statement = $conn->executeQuery($sql);
        $fetchedIds = $statement->fetchAll();

        return $fetchedIds;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

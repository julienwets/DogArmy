<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Search;
use App\Entity\Sitting;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $tokenStorage;

    public function __construct(RegistryInterface $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, User::class);
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param $params
     * @return Query
     */
    public function findFilter(Search $params): Query
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
        
        if (!empty($params->getNeedsHelp())) {
            $query->andWhere('u.needsHelp = :needsHelp')
                ->setParameter('needsHelp', $params->getNeedsHelp());
            }

        //Hide current user from member list
        $user = $this->tokenStorage->getToken()->getUser();
        $query->andWhere('u.id != :id')
            ->setParameter('id', $user->getId());

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

        if (isset($params['duringWork']) and !empty($params['duringWork'])) {
            $sql .= ' u.duringWork =' . $params['duringWork'];
        }

        
        if (isset($params['needsHelp']) and !empty($params['needsHelp'])) {
            $sql .= ' u.needsHelp =' . $params['needsHelp'];
        }

        $statement = $conn->executeQuery($sql);
        $fetchedIds = $statement->fetchAll();

        return $fetchedIds;
    }
}

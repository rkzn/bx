<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ClientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientRepository extends EntityRepository
{
    public function findByUsername($username)
    {
        $queryBuilder = $this->createQueryBuilder('cl')
            ->where('cl.username = :username')
            ->setParameter('username', $username);

        $queryBuilder->setMaxResults(1);
        $result = $queryBuilder->getQuery()->getOneOrNullResult();

        return $result;
    }

}

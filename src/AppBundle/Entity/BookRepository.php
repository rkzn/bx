<?php

namespace AppBundle\Entity;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAuthors($offset, $limit)
    {
        $data = $this->createQueryBuilder('b')
            ->select('b.author', 'COUNT(b.ISBN) as cnt')
                ->groupBy('b.author')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()->getResult();

        return $data;
    }

}

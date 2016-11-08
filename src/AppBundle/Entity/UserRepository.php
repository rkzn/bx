<?php

namespace AppBundle\Entity;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findCountryList()
    {
        $sql = <<<sql
SELECT TRIM(SUBSTRING_INDEX(bu.`Location`, ',', -1)) Country, COUNT(bu.UserId) Users
FROM `BookUser` bu
WHERE bu.`UserId` IN (
	SELECT DISTINCT br.UserId
	FROM `BookRating` br
	WHERE 1
	AND br.`Rating` > 0
)
GROUP BY TRIM(SUBSTRING_INDEX(bu.`Location`, ',', -1))
HAVING Users > 6 AND LENGTH(Country) > 2;
sql;
        $conn = $this->getEntityManager()->getConnection();
        $data = $conn->executeQuery($sql);

        return $data->fetchAll();
    }
}

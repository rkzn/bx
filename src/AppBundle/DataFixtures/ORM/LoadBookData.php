<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Ddeboer\DataImport\Reader\CsvReader;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use VIPSoft\Unzip\Unzip;

class LoadBookData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $resources = [
        [
            'file' => 'BX-Books.csv',
            'entity' => 'AppBundle:Book',
            'columns' => [
                'ISBN' => 'ISBN',
                'Title' => 'Book-Title',
                'Author' => 'Book-Author',
                'YearOfPublication' => 'Year-Of-Publication',
                'Publisher' => 'Publisher',
                'ImageUrlS' => 'Image-URL-S',
                'ImageUrlM' => 'Image-URL-M',
                'ImageUrlL' => 'Image-URL-L',
            ],
            'onDublicate' => 'ISBN=VALUES(ISBN)'
        ],
        [
            'file' => 'BX-Users.csv',
            'entity' => 'AppBundle:User',
            'columns' => [
                'UserId' => 'User-ID',
                'Location' => 'Location',
                'Age' => 'Age',
            ],
            'onDublicate' => 'UserId=VALUES(UserId)'
        ],
        [
            'file' => 'BX-Book-Ratings.csv',
            'entity' => 'AppBundle:Rating',
            'columns' => [
                'UserId' => 'User-ID',
                'ISBN' => 'ISBN',
                'Rating' => 'Book-Rating',
            ],
            'onDublicate' => 'UserId=VALUES(UserId),ISBN=VALUES(ISBN)'
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $batchSize = 100;

        $zipFile = 'BX-CSV-Dump.zip';
        $rootPath = $this->container->get('kernel')->getRootDir();
        $dataPath =  $rootPath . '/../src/AppBundle/Resources/data/';

        $fs = new Filesystem();
        if ($fs->exists($dataPath.$zipFile)) {
            $unzip = new Unzip();
            $unzip->extract($dataPath.$zipFile, $dataPath);
        } else {
            throw new FileNotFoundException(null, 0, null, $dataPath.$zipFile);
        }

        $connection = $manager->getConnection();

        foreach ($this->resources as $resource) {
            $tableName = $manager->getClassMetadata($resource['entity'])->getTableName();
            $filePath = $dataPath.$resource['file'];

            if ($fs->exists($filePath) == false) {
                throw new FileNotFoundException(null, 0, null, $filePath);
            }

            $file = new \SplFileObject($filePath);
            $reader = new CsvReader($file, ";");
            $reader->setHeaderRowNumber(0);

            $header = sprintf("INSERT INTO %s (%s) VALUES ", $tableName, implode(',', array_keys($resource['columns'])));
            $footer = sprintf(" ON DUPLICATE KEY UPDATE %s", $resource['onDublicate']);
            $values = [];

            $stmt = $connection->prepare(sprintf('TRUNCATE TABLE %s', $tableName));
            $stmt->execute();

            $i = 1;
            foreach ($reader as $row) {
                $values[] = $this->generateInsertValues($manager, $row, $resource['columns']);
                if (($i++ % $batchSize) === 0) {
                    $sql = sprintf("%s\n%s\n%s;", $header, implode(",\n", $values), $footer);
                    $stmt = $connection->prepare($sql);
                    $stmt->execute();
                    $values = [];
                }
            }

            if (!empty($values)) {
                $sql = sprintf("%s\n%s\n%s;", $header, implode(",\n", $values), $footer);
                $stmt = $connection->prepare($sql);
                $stmt->execute();
            }
        }
    }

    protected function generateInsertValues(ObjectManager $manager, array $item, array $columns)
    {
        $result = [];
        foreach ($columns as $columnDbName => $columnFileName) {
            $value = array_key_exists($columnFileName, $item) ? $item[$columnFileName] : null;
            $result[] = is_null($value) ? 'NULL' : $manager->getConnection()->quote($value, \PDO::PARAM_STR);
        }

        return sprintf('(%s)', implode(', ', $result));
    }


    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10;
    }
}

<?php
namespace AppBundle;

use AppBundle\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class BooksManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var  EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getBooks(array $isbnList)
    {
        return $this->entityManager->getRepository('AppBundle:Book')->findBy(['ISBN' => $isbnList]);
    }

    public function getBook($isbn)
    {
        return $this->entityManager->getRepository('AppBundle:Book')->findOneBy(['ISBN' => $isbn]);
    }

    public function fetchBooks($start = 0, $limit = 10)
    {
        return $this->entityManager->getRepository('AppBundle:Book')->findBy([],[], $limit, $start);
    }

    public function saveBook(Book $book)
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function removeBook($isbn)
    {
        $book = $this->getBook($isbn);

        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    public function getAuthors()
    {
        $repo = $this->entityManager->getRepository('AppBundle:Book');

        return $repo->findAuthors();
    }

    public function getBooksRankingPerCountry($country, $offset = 0, $limit = 100)
    {
        $repo = $this->entityManager->getRepository('AppBundle:Rating');

        return $repo->findRatingByCountry($country, $offset, $limit);
    }

    public function getCountryList()
    {
        $repo = $this->entityManager->getRepository('AppBundle:User');
        return $repo->findCountryList();
    }
}
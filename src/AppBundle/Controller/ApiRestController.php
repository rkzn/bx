<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiRestController extends FOSRestController
{
    /**
     * List all books.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing items.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="20", description="How many items to return.")
     *
     * @Annotations\View(
     *     template = "AppBundle:Book:getBooks.html.twig",
     * )
     *
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getBooksAction(ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $start = null == $offset ? 0 : $offset + 1;
        $limit = $paramFetcher->get('limit');

        $books = $this->get('app_books')->fetchBooks($start, $limit);

        return ['data' => ['books' => $books, 'offset' => $offset, 'limit' => $limit]];
    }

    /**
     * Presents the form to use to create a new book.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *     template = "AppBundle:Book:newBook.html.twig",
     * )
     *
     * @return FormTypeInterface
     */
    public function newBookAction()
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_API') == false) {
            throw $this->createAccessDeniedException();
        }

        return $this->createForm(new BookType());
    }

    /**
     * Get a single book.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Book",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the book is not found"
     *   }
     * )
     *
     * @Annotations\View(
     *     template = "AppBundle:Book:getBook.html.twig",
     *     templateVar="book"
     * )
     *
     * @param string $isbn the book ISBN
     *
     * @return array
     *
     * @throws NotFoundHttpException when book not exist
     */
    public function getBookAction($isbn)
    {
        $book = $this->get('app_books')->getBook($isbn);

        if (empty($book)) {
            throw $this->createNotFoundException("book does not exist.");
        }

        $rating = [
            'countries' => $this->get('app_books')->getBookRankingPerCountry($book->getISBN()),
            'totalRating' => 0,
            'totalUsers' => 0
        ];

        $countRate = count($rating['countries']);

        foreach($rating['countries'] as &$rateCountry) {
            $rating['totalUsers'] += $rateCountry['Users'];
            $rating['totalRating'] += $rateCountry['Rating'];
            $rateCountry['Percent'] = $rateCountry['Rating'] / 10 * 100;
        }

        if ($countRate > 0) {
            $rating['totalRating'] = $rating['totalRating'] / $countRate;
        }

        $view = new View(['book' => $book, 'rating' => $rating]);
        $group = $this->container->get('security.context')->isGranted('ROLE_API') ? 'restapi' : 'standard';
        $view->getContext()->addGroup('Default');
        $view->getContext()->addGroup($group);

        return $view;
    }

    /**
     * Creates a new book from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Entity\Book",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "AppBundle:Book:newBook.html.twig",
     *   statusCode = Response::HTTP_BAD_REQUEST
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface[]|View
     */
    public function postBooksAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_API') == false) {
            throw $this->createAccessDeniedException();
        }

        $book = new Book();
        $form = $this->createForm(new BookType(), $book);
        $form->submit($request);

        if ($this->get('app_books')->getBook($book->getISBN()) instanceof Book) {
            throw $this->createNotFoundException("book already exist.");
        }

        if ($form->isValid()) {
            $this->get('app_books')->saveBook($book);

            return $this->routeRedirectView('api_get_book', array('isbn' => $book->getISBN()));
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Presents the form to use to update an existing book.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     200 = "Returned when successful",
     *     404 = "Returned when the book is not found"
     *   }
     * )
     *
     * @Annotations\View(
     *     template = "AppBundle:Book:editBook.html.twig",
     * )
     *
     * @param string $isbn the book ISBN
     *
     * @return FormTypeInterface
     *
     * @throws NotFoundHttpException when book not exist
     */
    public function editBooksAction($isbn)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_API') == false) {
            throw $this->createAccessDeniedException();
        }

        $book = $this->get('app_books')->getBook($isbn);
        if (false === $book) {
            throw $this->createNotFoundException("book does not exist.");
        }

        return $this->createForm(new BookType(), $book);
    }

    /**
     * Update existing book from the submitted data or create a new book at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\BookType",
     *   statusCodes = {
     *     201 = "Returned when a new resource is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template="AppBundle:Book:editBook.html.twig",
     *   templateVar="form"
     * )
     *
     * @param Request $request the request object
     * @param int     $isbn      the book ISBN
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when book not exist
     */
    public function putBooksAction(Request $request, $isbn)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_API') == false) {
            throw $this->createAccessDeniedException();
        }

        $book = $this->get('app_books')->getBook($isbn);
        if (false === $book) {
            $book = new Book();
            $book->setISBN($isbn);
            $statusCode = Response::HTTP_CREATED;
        } else {
            $statusCode = Response::HTTP_NO_CONTENT;
        }

        $form = $this->createForm(new BookType(), $book);
        $form->submit($request);

        if ($form->isValid()) {
            $this->get('app_books')->saveBook($book);

            return $this->routeRedirectView('api_get_book', ['isbn' => $book->getISBN()], $statusCode);
        }

        return $form;
    }

    /**
     * Removes a book.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param string $isbn the book ISBN
     *
     * @return View
     */
    public function deleteBooksAction($isbn)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_API') == false) {
            throw $this->createAccessDeniedException();
        }

        $book = $this->get('app_books')->getBook($isbn);

        if (empty($book)) {
            throw $this->createNotFoundException("book does not exist.");
        }

        $this->get('app_books')->removeBook($book);

        return $this->routeRedirectView('api_get_books', [], Response::HTTP_NO_CONTENT);
    }

    /**
     * Removes a book.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param string $isbn the book ISBN
     *
     * @return View
     */
    public function removeBooksAction($isbn)
    {
        return $this->deleteBooksAction($isbn);
    }

    /**
     * Get books ranking per country.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when error"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="country", requirements="[a-z ]+", strict=true, description="Country.")
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="100", description="How many items to return.")
     *
     * @Annotations\View(
     *     template = "AppBundle:Book:getBooksRankingPerCountry.html.twig",
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @return View
     */
    public function getBooksRankingPerCountryAction(ParamFetcher $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $start = null == $offset ? 0 : $offset + 1;
        $limit = $paramFetcher->get('limit');

        $bookManager = $this->container->get('app_books');
        $rating = $bookManager->getBooksRankingPerCountry($paramFetcher->get('country'), $start, $limit);
        return ['data' => ['rating' => $rating, 'offset' => $offset, 'limit' => $limit]];
    }
}
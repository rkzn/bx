<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $bookManager = $this->container->get('app_books');
        $countries = $bookManager->getCountryList();

        return $this->render('AppBundle::index.html.twig', ['countries' => $countries]);
    }

}

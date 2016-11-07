<?php

namespace AppBundle\Controller;

use AppBundle\Common\Amount;
use AppBundle\Entity\Client;
use AppBundle\Exception\ClientNotFoundException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RedCode\CurrencyRateBundle\Command\LoadCurrencyRatesCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;

class ApiRestController extends FOSRestController
{
    /**
     * @return static
     */
    public function getUsersAction()
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $entity = $userManager->findUsers();
        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }
        $view = View::create();
        $view->setData($entity)->setStatusCode(200);
        return $view;
    }



    /**
     * Create a Client from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when error"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="username", nullable=false, strict=true, description="Username.")
     * @RequestParam(name="country", nullable=false, strict=true, description="Country.")
     * @RequestParam(name="city", nullable=false, strict=true, description="City.")
     * @RequestParam(name="currency", nullable=false, strict=true, description="Currency.")
     *
     * @return View
     */
    public function postClientAction(Request $request, ParamFetcher $paramFetcher)
    {
        $clientManager = $this->container->get('app_client');
        $view = View::create();

        try {
            /** @var Client $client */
            $client = $clientManager->createClient(
                $paramFetcher->get('username'),
                $paramFetcher->get('country'),
                $paramFetcher->get('city'),
                $paramFetcher->get('currency')
            );

            $view->setData($client)->setStatusCode(200);

        } catch (\Exception $e) {
            $view->setData(['error' => $e->getMessage()])->setStatusCode(404);
        }

        return $view;
    }
}
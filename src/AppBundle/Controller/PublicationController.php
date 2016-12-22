<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PublicationController extends Controller
{

    /**
     * @Route("/home", name="home")
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:Publication:home.html.twig');
    }
}

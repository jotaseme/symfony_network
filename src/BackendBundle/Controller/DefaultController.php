<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user_repository = $em->getRepository('BackendBundle:User');
        /* @var $user User */
        $user = $user_repository->find(1);
        echo "bienvenido" . $user->getEmail();
        return $this->render('BackendBundle:Default:index.html.twig');
    }
}

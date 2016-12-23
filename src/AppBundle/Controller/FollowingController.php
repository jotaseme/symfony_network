<?php

namespace AppBundle\Controller;

use BackendBundle\Entity\User;
use BackendBundle\Entity\Following;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class FollowingController extends Controller
{
    private $user_session;

    public function __construct()
    {
        $this->user_session = new Session();
    }

    /**
     * @Route("/follow",
     *     options = { "expose" = true },
     *     name="follow")
     * @Method({"POST"})
     */
    public function followAction(Request $request){
        $user = $this->getUser();
        $followed_id = $request->get("followed");

        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository('BackendBundle:User');

        $user_followed = $user_repo->find($followed_id);

        $following = new Following();

        $following->setUser($user);
        $following->setFollowed($user_followed);
        $em->persist($following);
        $em->flush();

        $status = "¡Siguiendo a usuario!";

        return new Response($status );
    }

    /**
     * @Route("/unfollow",
     *     options = { "expose" = true },
     *     name="unfollow")
     * @Method({"POST"})
     */
    public function unfollowAction(Request $request){
        $user = $this->getUser();
        $followed_id = $request->get("followed");

        $em = $this->getDoctrine()->getManager();
        $following_repository = $em->getRepository('BackendBundle:Following');
        $following = $following_repository->findOneBy(array(
            "user" => $user,
            "followed" => $followed_id
        ));

        if(isset($following)){
            $em->remove($following);
            $em->flush();

            $status = "¡Dejaste de seguir a este usuario!";
        }else{
            $status = "¡Ha ocurrido un error!";
        }

        return new Response($status );
    }



}

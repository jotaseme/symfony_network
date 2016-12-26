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


    /**
     * @Route("/users/{nickname}/following",
     *     options = { "expose" = true },
     *     name="following_users")
     */
    public function followingAction(Request $request, $nickname = null){
        $em = $this->getDoctrine()->getManager();
        if($nickname != null){
            $user_repository = $em->getRepository('BackendBundle:User');
            $user = $user_repository->findOneBy(array(
                'nick' => $nickname
            ));
        }else{
            $user = $this->getUser();
        }

        if(empty($user) || !is_object($user)){
            return $this->redirect($this->generateUrl('home'));
        }

        $user_id = $user->getId();

        $dql = "SELECT f FROM BackendBundle:Following f WHERE f.user = $user_id ORDER BY f.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $following_users = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );

        return $this->render('AppBundle:Following:following.html.twig',array(
            'type' => 'following',
            'profile_user' => $user,
            'users' => $following_users
        ));
    }


    /**
     * @Route("/users/{nickname}/is_followed",
     *     options = { "expose" = true },
     *     name="follows_users")
     */
    public function followsAction(Request $request, $nickname = null){
        $em = $this->getDoctrine()->getManager();
        if($nickname != null){
            $user_repository = $em->getRepository('BackendBundle:User');
            $user = $user_repository->findOneBy(array(
                'nick' => $nickname
            ));
        }else{
            $user = $this->getUser();
        }

        if(empty($user) || !is_object($user)){
            return $this->redirect($this->generateUrl('home'));
        }

        $user_id = $user->getId();

        $dql = "SELECT f FROM BackendBundle:Following f WHERE f.followed = $user_id ORDER BY f.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $follows_users = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );

        return $this->render('AppBundle:Following:following.html.twig',array(
            'type' => 'follows',
            'profile_user' => $user,
            'users' => $follows_users
        ));
    }



}

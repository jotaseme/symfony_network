<?php

namespace AppBundle\Controller;

use BackendBundle\Entity\Like;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class LikeController extends Controller
{
    /**
     * @Route("/like/{id}", options = { "expose" = true }, name="like")
     */
    public function likeAction($id = null)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $publication_repositoy = $em->getRepository('BackendBundle:Publication');
        $publication = $publication_repositoy->find($id);

        $like = new Like();
        $like->setUser($user);
        $like->setPublication($publication);

        $em->persist($like);
        $em->flush();
        $status = '¡Like a publicacion!';
        return new Response($status);
    }

    /**
     * @Route("/like/dislike/{id}", options = { "expose" = true }, name="dislike")
     */
    public function dislikeAction($id = null)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $like_repositoy = $em->getRepository('BackendBundle:Like');
        $like = $like_repositoy->findOneBy(array(
            'user' => $user,
            'publication' => $id
        ));

        $em->remove($like);
        $em->flush();
        $status = '¡Dislike a publicacion!';
        return new Response($status);
    }

    /**
     * @Route("/users/{nickname}/likes",
     *     options = { "expose" = true },
     *     name="likes")
     */
    public function likesAction(Request $request, $nickname = null){
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

        $dql = "SELECT l FROM BackendBundle:Like l WHERE l.user = $user_id ORDER BY l.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $likes = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );

        return $this->render('AppBundle:Like:likes.html.twig',array(
            'user' => $user,
            'likes' => $likes
        ));
    }
}

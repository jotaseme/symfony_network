<?php

namespace AppBundle\Controller;

use BackendBundle\Entity\Like;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

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
}

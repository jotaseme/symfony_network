<?php

namespace AppBundle\Controller;

use BackendBundle\Entity\Publication;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\PublicationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PublicationController extends Controller
{
    private $user_session;

    public function __construct()
    {
        $this->user_session = new Session();
    }
    /**
     * @Route("/home", name="home")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class,$publication);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                // upload image
                $file = $form['image']->getData();
                if(!empty($file)&& $file != null){
                    $ext = $file->guessExtension();
                    if($ext=='jpg' || $ext=='jpeg' || $ext=='png' || $ext=='gif'){
                        $file_name = $user->getId().'_'.time().'.'.$ext;
                        $file->move('uploads/publications/images',$file_name);
                        $publication->setImage($file_name);
                    }else{
                        $publication->setImage(null);
                    }
                }else{
                    $publication->setImage(null);
                }
                // upload document
                $document = $form['document']->getData();
                if(!empty($document)&& $document != null){
                    $ext = $document->guessExtension();
                    if($ext=='pdf'){
                        $file_name = $user->getId().'_'.time().'.'.$ext;
                        $document->move('uploads/publications/documents',$file_name);
                        $publication->setDocument($file_name);
                    }else{
                        $publication->setDocument(null);
                    }
                }else{
                    $publication->setDocument(null);
                }
                $publication->setUser($user);
                $publication->setCreatedAt(new \DateTime("now"));

                $em->persist($publication);
                $em->flush();

                $status = "¡Publicacion añadida correctamente";
            }else{
                $status = "¡La publicacion no se ha creado!";
            }

            $this->user_session->getFlashBag()->add('status',$status);
            return $this->redirectToRoute('home');
        }

        $publications = $this->getPublications($request);
        return $this->render('AppBundle:Publication:home.html.twig',array(
            'form'=>$form->createView(),
            'publications' => $publications
        ));
    }

    public function getPublications($request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $publications_repository = $em->getRepository('BackendBundle:Publication');
        $following_repository = $em->getRepository('BackendBundle:Following');

        $following = $following_repository->findBy(array(
            'user'=>$user
        ));

        $following_array = array();
        foreach ($following as $follow){
            array_push($following_array,$follow->getFollowed());
        }

        $query = $publications_repository->createQueryBuilder('p')
            ->where('p.user = (:user_id) OR p.user IN (:following)')
            ->setParameter('user_id',$user->getId())
            ->setParameter('following',$following_array)
            ->orderBy('p.id','DESC')
            ->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );

        return $pagination;
    }

    /**
     * @Route("/publications/remove/{id}", options = { "expose" = true }, name="remove_publication")
     */
    public function deletePublicationAction(Request $request, $id = null){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $publication_repository = $em->getRepository('BackendBundle:Publication');
        $publication = $publication_repository->find($id);

        if($publication==null){
            $status = '¡Error al borrar publicacion';
        }else{
            if($user->getId() == $publication->getUser()->getId()){
                $em->remove($publication);
                $em->flush();
                $status = '¡Publicacion eliminada!';
            }else{
                $status = '¡Error al borrar publicacion';
            }
        }
        return new Response($status);
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegisterType;
use BackendBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    private $user_session;

    public function __construct()
    {
        $this->user_session = new Session();
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
       return $this->render('AppBundle:User:login.html.twig',array(
           'titulo'=>"Login"
       ));
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                //$user_repository = $em->getRepository("BackendBundle:User");
                $query = $em
                    ->createQuery('SELECT u FROM BackendBundle:User u WHERE u.email = :email OR u.nick = :nick')
                    ->setParameter('email', $form->get('email')->getData())
                    ->setParameter('nick', $form->get('nick')->getData());

                $user_isset = $query->getResult();
                if(count($user_isset)==0){
                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($form->get('password')->getData(),$user->getSalt());

                    $user->setPassword($password);
                    $user->setRole("ROLE_USER");
                    $user->setImage(null);

                    $em->persist($user);
                    $em->flush();
                    $status = "¡Usuario registrado correctamente!";
                    $this->user_session->getFlashBag()
                        ->add('status',$status);
                    return $this->redirect("login");

                }else{
                    $status = "¡El usuario ya existe!";
                }
            }else{
                $status = "¡Error en el registro!";
            }

            $this->user_session->getFlashBag()
                ->add('status',$status);
        }

        return $this->render('AppBundle:User:register.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    /**
     * @Route("/register", name="register")
     */
    public function nickTestAction(Request $request){
        $nick = $request->get("nick");
        $em = $this->getDoctrine()->getManager();
    }
}

<?php
namespace AppBundle\Twig;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('get_user',array($this, 'getUserFilter'))
        );
    }

    public function getUserFilter($user_id){
        $user_repository = $this->doctrine->getRepository('BackendBundle:User');
        $user = $user_repository->find($user_id);

        if(!empty($user) && is_object($user)){
            $result = true;
        }else{
            $result = false;
        }

        return $user;
    }

    public function getName(){
        return 'get_user_extension';
    }
}
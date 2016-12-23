<?php

namespace AppBundle\Twig;


use Symfony\Bridge\Doctrine\RegistryInterface;

class FollowingExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('following',array($this, 'followingFilter'))
        );
    }

    public function followingFilter($user, $followed){
        $following_repository = $this->doctrine->getRepository('BackendBundle:Following');
        $following = $following_repository->findOneBy(array(
            'user' => $user,
            'followed' => $followed
        ));

        if(!empty($following) && is_object($following)){
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }

    public function getName(){
        return 'following_extension';
    }
}
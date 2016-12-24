<?php
namespace AppBundle\Twig;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserStatsExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('user_stats',array($this, 'userStatsFilter'))
        );
    }

    public function userStatsFilter($user){
        $like_repository = $this->doctrine->getRepository('BackendBundle:Like');
        $following_repository = $this->doctrine->getRepository('BackendBundle:Following');
        $publication_repository = $this->doctrine->getRepository('BackendBundle:Publication');

        $user_following = $following_repository->findBy(array(
            "user" => $user
        ));

        $user_followers  = $following_repository->findBy(array(
            "followed" => $user
        ));

        $user_publications = $publication_repository->findBy(array(
            "user" => $user
        ));

        $user_likes = $like_repository->findBy(array(
            "user" => $user
        ));

        $result = array(
            'following' => count($user_following),
            'followers' => count($user_followers),
            'user_publications' => count($user_publications),
            'likes' => count($user_likes)
        );

        return $result;



    }

    public function getName(){
        return 'user_stats_extension';
    }
}
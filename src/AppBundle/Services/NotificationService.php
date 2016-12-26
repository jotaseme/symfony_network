<?php

namespace AppBundle\Services;


use BackendBundle\Entity\Notification;


class NotificationService
{
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function set($user,$type, $typeId, $extra = null){
        $em = $this->doctrine;

        $notification = new Notification();
        $notification->setUser($user);
        $notification->setType($type);
        $notification->setTypeId($typeId);
        $notification->setReaded(0);
        $notification->setCreatedAt(new \DateTime("now"));
        $notification->setExtra($extra);

        $em->persist($notification);
        $em->flush();

        return true;
    }

    public function read($user){
        $em = $this->doctrine;
        $notification_repository = $em->getRepository('BackendBundle:Notification');
        $notifications = $notification_repository->findBy(array(
            'user'=>$user
        ));

        foreach ($notifications as $notification)
        {
            $notification->setReaded(1);
            $em->persist($notification);
        }
        $em->flush();

        return true;
    }
}
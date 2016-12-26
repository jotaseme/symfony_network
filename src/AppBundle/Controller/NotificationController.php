<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{

    /**
     * @Route("/notifications",
     *     options = { "expose" = true },
     *     name="notifications")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $id_user = $user->getId();
        $dql = "SELECT n FROM BackendBundle:Notification n WHERE n.user = $id_user ORDER BY n.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $notifications = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );

        $notification = $this->get('app.notification_service');
        $notification->read($user);
        return $this->render('AppBundle:Notification:notification.html.twig',array(
            'user' => $user,
            'notifications' => $notifications
        ));
    }

    /**
     * @Route("/notifications/counter",
     *     options = { "expose" = true },
     *     name="notifications_counter")
     */
    public function countNotificationsAction(){
        $em = $this->getDoctrine()->getManager();
        $notification_repository = $em->getRepository('BackendBundle:Notification');
        $notifications = $notification_repository->findBy(array(
            'user' => $this->getUser(),
            'readed' => 0
        ));

        return new Response(count($notifications));
    }
}

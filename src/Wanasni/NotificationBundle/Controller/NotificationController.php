<?php

namespace Wanasni\NotificationBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Wanasni\NotificationBundle\Entity\Notification;
use Wanasni\NotificationBundle\Services\TypeNotification;

class NotificationController extends Controller
{


    /**
     * @Route(path="/", name="vos_notifications")
     */
    public function notificationAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }


        $rep=$this->getDoctrine()->getManager()->getRepository('WanasniNotificationBundle:Notification');

        $notifications=$rep->findBy(array('user'=>$this->getUser()),array('notifAt'=>'desc'));


        return $this->render(':Notification:list_notification.html.twig',array(
            'notifications'=>$notifications
        ));

    }



    /**
     * @Route(path="/accepte-reservation/{id}" , name="reservation_accept")
     * @ParamConverter("notification", class="WanasniNotificationBundle:Notification")
     */
    public function AcceptAction(Notification $notification)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }


        $reservation=$notification->getReservation();
        $trajet=$reservation->getTrajet();

        $notification->setLu(true);

        $reservation->setEtat(true);
        $trajet->setNbPlacesRestants($trajet->getNbPlacesRestants()-1);

        $serviceNotif=$this->container->get('wanasni_notification.create');
        $notif_accept=$serviceNotif->CreateNotification($reservation,$reservation->getPassager(),TypeNotification::accepte);


        $em=$this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->persist($trajet);
        $em->persist($notification);
        $em->persist($notif_accept);

        $em->flush();


        $serviceNotif->EnvoyeEmail($notif_accept);

        return $this->redirect($this->generateUrl('trajet_show',array(
            'id'=>$trajet->getId()
        )));
    }



    /**
     * @Route(path="/refuse-reservation/{id}" , name="reservation_refuse")
     * @ParamConverter("notification", class="WanasniNotificationBundle:Notification")
     */
    public function RefuseAction(Notification $notification)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }


        $reservation=$notification->getReservation();
        $trajet=$reservation->getTrajet();



        if($trajet->isReserverByPassage($reservation->getPassager()) && $reservation->getEtat() == true){
            $trajet->setNbPlacesRestants($trajet->getNbPlacesRestants()+1);
            $reservation->setEtat(false);
        }

        $notification->setLu(true);



        $serviceNotif=$this->container->get('wanasni_notification.create');
        $notif_refuse=$serviceNotif->CreateNotification($reservation,$reservation->getPassager(),TypeNotification::Refuse);


        $em=$this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->persist($notification);
        $em->persist($trajet);
        $em->persist($notif_refuse);

        $em->flush();


        $serviceNotif->EnvoyeEmail($notif_refuse);

        return $this->redirect($this->generateUrl('trajet_show',array(
            'id'=>$trajet->getId()
        )));
    }


    /**
     * @return JsonResponse
     * @Route(path="/api/all", name="api_user_notif_all")
     */
    public function ApiNotificationAction()
    {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }


        $rep=$this->getDoctrine()->getManager()->getRepository('WanasniNotificationBundle:Notification');

        $notifications=$rep->findBy(array('user'=>$this->getUser()),array('notifAt'=>'desc'));

        $arr_notif=array();
        foreach($notifications as $notif){
            $arr_notif[]=array(
                'id'=>$notif->getId(),
                'contenu'=>$notif->getContenu(),
                'notifAt'=>$notif->getNotifAt()->format("F jS \\a\\t g:ia"),
                'type'=>$notif->getType(),
                'isRead'=>$notif->getLu(),
            );
        }

        return new JsonResponse(
            array(
                'error'=>array('code'=>200,'message'=>'OK'),
                'notifications'=>$arr_notif
            ),200
        );
    }


    /**
     * @return JsonResponse
     * @Route(path="api/nb-unread-notifications", name="api_nb_unread_notifications")
     */
    public function ApiNotifUnreadAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $rep=$this->getDoctrine()->getManager()->getRepository('WanasniNotificationBundle:Notification');

        $NbUnreadNotifications=$rep->nbUnreadNotification($this->getUser());


        return new JsonResponse(array(
            'NbUnreadNotifications'=>$NbUnreadNotifications
        ));
    }

    /**
     * @return JsonResponse
     * @Route(path="/read/{id}", name="read_notification")
     * @ParamConverter("notif", class="WanasniNotificationBundle:Notification")
     */
    public function ApiReadNotification(Notification $notif)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }


        if($notif->getUser() == $this->getUser()){
            $notif->setLu(true);
            $em=$this->getDoctrine()->getManager();
            $em->persist($notif);
            $em->flush();

            return new JsonResponse(array(
                'error'=>array(
                    'code'=>200,
                    'message'=>'success'
                )
            ),200);
        }else{
            return new JsonResponse(array(
                'error'=>array(
                    'code'=>400,
                    'message'=>'error user failed'
                )
            ),400);
        }

    }




}

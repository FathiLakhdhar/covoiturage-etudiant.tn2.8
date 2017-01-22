<?php
/**
 * Created by PhpStorm.
 * User: Toshiba
 * Date: 25-04-2016
 * Time: 10:13
 */

namespace Wanasni\NotificationBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;
use Wanasni\NotificationBundle\Entity\Notification;
use Wanasni\TrajetBundle\Entity\Reservation;
use Wanasni\UserBundle\Entity\User;

class NotificationService
{

    protected $mailer;
    protected $em;
    protected $templating;

    private $types = ["demande", "accepte", "refuse"];


    /**
     * NotificationService constructor.
     * @param $mailer
     * @param $em
     */
    public function __construct(\Swift_Mailer $mailer, EntityManager $em, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->templating = $templating;
    }


    public function CreateNotification(Reservation $reservation, User $user, $type)
    {

        $contenuMessage = $this->generateContenuMsg($type);

        $notif = new Notification();
        $notif->setType($type);
        $notif->setContenu($contenuMessage);
        $notif->setUser($user);
        $notif->setReservation($reservation);


        return $notif;


    }


    public function EnvoyeEmail(Notification $notification)
    {
        /*
         * + send email
         */

        $message = \Swift_Message::newInstance();
        $message
            ->setSubject($notification->getContenu())
            ->setFrom('app.wanasni@gmail.com')
            ->setTo($notification->getUser()->getEmail())
            ->setBody(
                $this->templating->render(':Notification:content_mail_reservation.html.twig',
                    array(
                        'message' => $notification->getContenu(),
                        'trajet' => $notification->getReservation()->getTrajet()
                    )
                ),
                'text/html'
            );
        $this->mailer->send($message);

    }


    private function generateContenuMsg($type)
    {
        $msg = "";
        switch ($type) {
            case "demande":
                $msg = "demande reservation trajet";
                break;
            case "accepte":
                $msg = "votre demande a été acceptée";
                break;
            case "refuse":
                $msg = "votre demande a été refuse";
                break;
        }
        return $msg;
    }


}
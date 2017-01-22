<?php
/**
 * Created by PhpStorm.
 * User: Toshiba
 * Date: 07-04-2016
 * Time: 15:42
 */

namespace Wanasni\TrajetBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;
use Wanasni\TrajetBundle\Entity\Trajet;

class AlertService
{

    protected $mailer;
    protected $em;
    protected $templating;

    /**
     * AlertService constructor.
     * @param $mailer
     */
    public function __construct(\Swift_Mailer $mailer, EntityManager $em, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->em=$em;
        $this->templating = $templating;
    }

    public function EnvoyerMailer(Trajet $trajet){

        $rep=$this->em->getRepository('WanasniTrajetBundle:Alert');

        if($trajet){
            $alerts= $rep->MyFind(
                $trajet->getOrigine()->getLieu(),
                $trajet->getDestination()->getLieu(),
                $trajet->getDatesToArray(),
                $trajet->getFrequence()
                );

            foreach($alerts as $alert){
                $message= \Swift_Message::newInstance();
                $message
                    ->setSubject('AppWanasni Alert')
                    ->setFrom('app.wanasni@gmail.com')
                    ->setTo($alert->getEmail())
                    ->setBody(
                        $this->templating->render(':Trajet/Gerer:content_mail_alert.html.twig',
                            array(
                                'trajet'=>$trajet
                            )
                            ),
                        'text/html'
                    )
                ;
                $this->mailer->send($message);
            }

        }

    }


}
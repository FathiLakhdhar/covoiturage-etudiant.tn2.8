<?php
/**
 * Created by PhpStorm.
 * User: Toshiba
 * Date: 25-04-2016
 * Time: 16:19
 */

namespace Wanasni\NotificationBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class NotificationExtension extends \Twig_Extension
{

    protected $em;
    protected $securityContext;

    /**
     * NotificationExtension constructor.
     * @param $em
     * @param $securityContext
     */
    public function __construct(EntityManager $em,SecurityContext $securityContext)
    {
        $this->em = $em;
        $this->securityContext = $securityContext;
    }


    public function getFunctions()
    {
        return array('wanasni_notification_nb_unread' => new \Twig_Function_Method($this, 'getNbUnread'));
    }


    public function getNbUnread()
    {
        return $this->em->getRepository('WanasniNotificationBundle:Notification')->nbUnreadNotification($this->securityContext->getToken()->getUser());
    }


    public function getName()
    {
        return "wanasni_notification_extention";
    }
}
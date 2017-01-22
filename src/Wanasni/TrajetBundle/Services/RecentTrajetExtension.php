<?php

namespace Wanasni\TrajetBundle\Services;

use Doctrine\ORM\EntityManager;


class RecentTrajetExtension extends \Twig_Extension{


    protected $em;

    /**
     * NotificatioExtension constructor.
     */
    public function __construct(EntityManager $em)
    {
        $this->em=$em;
    }
    public function getFunctions()
    {
        return array('wanasni_recent_trajet' => new \Twig_Function_Method($this, 'getRecentTrajet'));
    }


    function getRecentTrajet(){
        $date = new \DateTime();
        return $this->em->getRepository("WanasniTrajetBundle:Trajet")->SearchByDateAller($date->format('Y-m-d'));
    }

    public function getName()
    {
        return "wanasni_recent_trajet_extention";
    }
}
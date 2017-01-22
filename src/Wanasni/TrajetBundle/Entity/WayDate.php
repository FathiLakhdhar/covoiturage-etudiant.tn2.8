<?php

namespace Wanasni\TrajetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WayDate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\TrajetBundle\Entity\WayDateRepository")
 */
class WayDate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


    /**
     * @ORM\ManyToOne(targetEntity="Wanasni\TrajetBundle\Entity\Trajet", inversedBy="datesAller")
     *
     */
    private $tarjet_aller;

    /**
     * @ORM\ManyToOne(targetEntity="Wanasni\TrajetBundle\Entity\Trajet", inversedBy="datesRetour")
     */
    private $tarjet_retour;

    /**
     * WayDate constructor.
     */
    public function __construct()
    {
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return WayDate
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set tarjet_aller
     *
     * @param \Wanasni\TrajetBundle\Entity\Trajet $tarjetAller
     * @return WayDate
     */
    public function setTarjetAller(\Wanasni\TrajetBundle\Entity\Trajet $tarjetAller = null)
    {
        $this->tarjet_aller = $tarjetAller;
    
        return $this;
    }

    /**
     * Get tarjet_aller
     *
     * @return \Wanasni\TrajetBundle\Entity\Trajet 
     */
    public function getTarjetAller()
    {
        return $this->tarjet_aller;
    }

    /**
     * Set tarjet_retour
     *
     * @param \Wanasni\TrajetBundle\Entity\Trajet $tarjetRetour
     * @return WayDate
     */
    public function setTarjetRetour(\Wanasni\TrajetBundle\Entity\Trajet $tarjetRetour = null)
    {
        $this->tarjet_retour = $tarjetRetour;
    
        return $this;
    }

    /**
     * Get tarjet_retour
     *
     * @return \Wanasni\TrajetBundle\Entity\Trajet 
     */
    public function getTarjetRetour()
    {
        return $this->tarjet_retour;
    }
}
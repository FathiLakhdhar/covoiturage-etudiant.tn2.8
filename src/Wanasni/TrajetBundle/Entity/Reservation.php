<?php

namespace Wanasni\TrajetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Wanasni\UserBundle\Entity\User;

/**
 * Reservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\TrajetBundle\Entity\ReservationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Reservation
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
     * @ORM\Column(name="reserverAt", type="datetime")
     */
    private $reserverAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;


    /**
     * @ORM\OneToMany(targetEntity="Wanasni\NotificationBundle\Entity\Notification", mappedBy="reservation" ,cascade={"remove"})
     */
    private $notifications;


    /**
     * @ORM\ManyToOne(targetEntity="Wanasni\TrajetBundle\Entity\Trajet", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trajet;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Wanasni\UserBundle\Entity\User", inversedBy="reservations")
     */
    private $passager;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set reserverAt
     *
     * @param \DateTime $reserverAt
     * @return Reservation
     */
    public function setReserverAt($reserverAt)
    {
        $this->reserverAt = $reserverAt;
    
        return $this;
    }

    /**
     * Get reserverAt
     *
     * @return \DateTime 
     */
    public function getReserverAt()
    {
        return $this->reserverAt;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     * @return Reservation
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    
        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    
    /**
     * Add notifications
     *
     * @param \Wanasni\NotificationBundle\Entity\Notification $notifications
     * @return Reservation
     */
    public function addNotification(\Wanasni\NotificationBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;
    
        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \Wanasni\NotificationBundle\Entity\Notification $notifications
     */
    public function removeNotification(\Wanasni\NotificationBundle\Entity\Notification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set trajet
     *
     * @param \Wanasni\TrajetBundle\Entity\Trajet $trajet
     * @return Reservation
     */
    public function setTrajet(\Wanasni\TrajetBundle\Entity\Trajet $trajet)
    {
        $this->trajet = $trajet;
    
        return $this;
    }

    /**
     * Get trajet
     *
     * @return \Wanasni\TrajetBundle\Entity\Trajet 
     */
    public function getTrajet()
    {
        return $this->trajet;
    }


    /**
     * @ORM\PrePersist()
     */
    public function Prepersist()
    {

        $this->reserverAt =  new \DateTime();
        $this->etat= false;
    }
    
    
    

    /**
     * Set passager
     *
     * @param \Wanasni\UserBundle\Entity\User $passager
     * @return Reservation
     */
    public function setPassager(\Wanasni\UserBundle\Entity\User $passager = null)
    {
        $this->passager = $passager;
    
        return $this;
    }

    /**
     * Get passager
     *
     * @return \Wanasni\UserBundle\Entity\User 
     */
    public function getPassager()
    {
        return $this->passager;
    }
}
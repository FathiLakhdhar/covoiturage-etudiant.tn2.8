<?php

namespace Wanasni\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\NotificationBundle\Entity\NotificationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Notification
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
     * @ORM\Column(name="notifAt", type="datetime")
     */
    private $notifAt;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;


    /**
     * @ORM\Column(type="boolean")
     */
    private $lu;


    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=20)
     * @Assert\Choice(choices={"demande", "accepte", "refuse"}, message = "Choose a valid type notification.")
     */
    private $type;


    /**
     * @ORM\ManyToOne(targetEntity="Wanasni\UserBundle\Entity\User", inversedBy="Notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="Wanasni\TrajetBundle\Entity\Reservation", inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation;

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
     * Set notifAt
     *
     * @param \DateTime $notifAt
     * @return Notification
     */
    public function setNotifAt($notifAt)
    {
        $this->notifAt = $notifAt;
        return $this;
    }

    /**
     * Get notifAt
     *
     * @return \DateTime 
     */
    public function getNotifAt()
    {
        return $this->notifAt;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Notification
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set lu
     *
     * @param boolean $lu
     * @return Notification
     */
    public function setLu($lu)
    {
        $this->lu = $lu;
    
        return $this;
    }

    /**
     * Get lu
     *
     * @return boolean 
     */
    public function getLu()
    {
        return $this->lu;
    }

    /**
     * Set user
     *
     * @param \Wanasni\UserBundle\Entity\User $user
     * @return Notification
     */
    public function setUser(\Wanasni\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Wanasni\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set reservation
     *
     * @param \Wanasni\TrajetBundle\Entity\Reservation $reservation
     * @return Notification
     */
    public function setReservation(\Wanasni\TrajetBundle\Entity\Reservation $reservation)
    {
        $this->reservation = $reservation;
    
        return $this;
    }

    /**
     * Get reservation
     *
     * @return \Wanasni\TrajetBundle\Entity\Reservation 
     */
    public function getReservation()
    {
        return $this->reservation;
    }


    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->notifAt= new \DateTime();
        $this->lu=false;

    }




    /**
     * Set type
     *
     * @param string $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
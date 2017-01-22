<?php

namespace Wanasni\UserBundle\Entity;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Wanasni\AvisBundle\Entity\Avis;
use Wanasni\PhotoBundle\Entity\Photo;
use Wanasni\TrajetBundle\Entity\Reservation;


/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(type="string",length=30)
     * @Assert\NotBlank(message="Please enter your firstname.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=30,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your first name cannot contain a number"
     * )
     *
     */
    protected $firstname;
    /**
     * @ORM\Column(type="string",length=30)
     * @Assert\NotBlank(message="Please enter your lastname.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=30,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your last name cannot contain a number"
     * )
     *
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Choice(choices = {"homme", "femme"}, message = "Choose a valid gender.")
     */
    protected $gender;


    /**
     * @ORM\Column(type="text", length=500, nullable=true)
     */
    protected $minibio;


    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    protected $phone;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     */
    protected $date_naissance;


    /**
     * @ORM\OneToMany(targetEntity="Wanasni\VehiculeBundle\Entity\Vehicule", mappedBy="user",cascade={"remove"})
     */
    protected $vehicules;

    /**
     * @ORM\OneToMany(targetEntity="Wanasni\TrajetBundle\Entity\Trajet", mappedBy="conducteur",cascade={"remove"})
     */
    private $trajets;


    /**
     * @ORM\OneToMany(targetEntity="Wanasni\NotificationBundle\Entity\Notification", mappedBy="user", cascade={"remove"})
     */
    private $Notifications;


    /**
     * @var Photo
     * @ORM\OneToOne(targetEntity="Wanasni\PhotoBundle\Entity\Photo", cascade={"persist","remove"}, inversedBy="user", fetch="EAGER")
     */
    private $photo;

    /**
     * @var Reservation
     * @ORM\OneToMany(targetEntity="Wanasni\TrajetBundle\Entity\Reservation", mappedBy="passager", cascade={"remove"})
     */
    private $reservations;


    /**
     * @var Avis
     * @ORM\OneToMany(targetEntity="Wanasni\AvisBundle\Entity\Avis", mappedBy="emetteur")
     */
    private $avis_given;

    /**
     * @var Avis
     * @ORM\OneToMany(targetEntity="Wanasni\AvisBundle\Entity\Avis", mappedBy="recepteur", fetch="EAGER")
     */
    private $avis_received;


    /**
     * @return mixed
     */
    public function getMinibio()
    {
        return $this->minibio;
    }

    /**
     * @param mixed $minibio
     */
    public function setMinibio($minibio)
    {
        $this->minibio = $minibio;
    }


    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }


    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->addRole('ROLE_PASSAGER');
        $this->vehicules = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trajets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->Notifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reservations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->avis_given = new \Doctrine\Common\Collections\ArrayCollection();
        $this->avis_received = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set date_naissance
     *
     * @param \DateTime $dateNaissance
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->date_naissance = $dateNaissance;

        return $this;
    }

    /**
     * Get date_naissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }


    /**
     * Add vehicules
     *
     * @param \Wanasni\VehiculeBundle\Entity\Vehicule $vehicules
     * @return User
     */
    public function addVehicule(\Wanasni\VehiculeBundle\Entity\Vehicule $vehicules)
    {
        $vehicules->setUser($this);
        $this->vehicules[] = $vehicules;

        $this->addRole('ROLE_CONDUCTEUR');

        return $this;
    }

    /**
     * Remove vehicules
     *
     * @param \Wanasni\VehiculeBundle\Entity\Vehicule $vehicules
     */
    public function removeVehicule(\Wanasni\VehiculeBundle\Entity\Vehicule $vehicules)
    {
        $this->vehicules->removeElement($vehicules);
    }

    /**
     * Get vehicules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVehicules()
    {
        return $this->vehicules;
    }

    /**
     * Add trajets
     *
     * @param \Wanasni\TrajetBundle\Entity\Trajet $trajets
     * @return User
     */
    public function addTrajet(\Wanasni\TrajetBundle\Entity\Trajet $trajets)
    {
        $this->trajets[] = $trajets;

        return $this;
    }

    /**
     * Remove trajets
     *
     * @param \Wanasni\TrajetBundle\Entity\Trajet $trajets
     */
    public function removeTrajet(\Wanasni\TrajetBundle\Entity\Trajet $trajets)
    {
        $this->trajets->removeElement($trajets);
    }

    /**
     * Get trajets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrajets()
    {
        return $this->trajets;
    }

    /**
     * Add Notifications
     *
     * @param \Wanasni\NotificationBundle\Entity\Notification $notifications
     * @return User
     */
    public function addNotification(\Wanasni\NotificationBundle\Entity\Notification $notifications)
    {
        $this->Notifications[] = $notifications;

        return $this;
    }

    /**
     * Remove Notifications
     *
     * @param \Wanasni\NotificationBundle\Entity\Notification $notifications
     */
    public function removeNotification(\Wanasni\NotificationBundle\Entity\Notification $notifications)
    {
        $this->Notifications->removeElement($notifications);
    }

    /**
     * Get Notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->Notifications;
    }

    /**
     * Set photo
     *
     * @param \Wanasni\PhotoBundle\Entity\Photo $photo
     * @return User
     */
    public function setPhoto(\Wanasni\PhotoBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \Wanasni\PhotoBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }


    public function getFullName()
    {
        return $this->firstname . ' ' . $this->lastname;
    }


    /**
     * @ORM\PrePersist()
     */
    public function PrePersist()
    {
        $photo = new Photo();
        $photo->setAlt($this->getFullName());
        $photo->setValid(false);
        $this->setPhoto($photo);
    }

    /**
     * Add reservations
     *
     * @param \Wanasni\TrajetBundle\Entity\Reservation $reservations
     * @return User
     */
    public function addReservation(\Wanasni\TrajetBundle\Entity\Reservation $reservations)
    {
        $this->reservations[] = $reservations;

        return $this;
    }

    /**
     * Remove reservations
     *
     * @param \Wanasni\TrajetBundle\Entity\Reservation $reservations
     */
    public function removeReservation(\Wanasni\TrajetBundle\Entity\Reservation $reservations)
    {
        $this->reservations->removeElement($reservations);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * Add avis_given
     *
     * @param \Wanasni\AvisBundle\Entity\Avis $avisGiven
     * @return User
     */
    public function addAvisGiven(\Wanasni\AvisBundle\Entity\Avis $avisGiven)
    {
        $this->avis_given[] = $avisGiven;
    
        return $this;
    }

    /**
     * Remove avis_given
     *
     * @param \Wanasni\AvisBundle\Entity\Avis $avisGiven
     */
    public function removeAvisGiven(\Wanasni\AvisBundle\Entity\Avis $avisGiven)
    {
        $this->avis_given->removeElement($avisGiven);
    }

    /**
     * Get avis_given
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAvisGiven()
    {
        return $this->avis_given;
    }

    /**
     * Add avis_received
     *
     * @param \Wanasni\AvisBundle\Entity\Avis $avisReceived
     * @return User
     */
    public function addAvisReceived(\Wanasni\AvisBundle\Entity\Avis $avisReceived)
    {
        $this->avis_received[] = $avisReceived;
    
        return $this;
    }

    /**
     * Remove avis_received
     *
     * @param \Wanasni\AvisBundle\Entity\Avis $avisReceived
     */
    public function removeAvisReceived(\Wanasni\AvisBundle\Entity\Avis $avisReceived)
    {
        $this->avis_received->removeElement($avisReceived);
    }

    /**
     * Get avis_received
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAvisReceived()
    {
        return $this->avis_received;
    }
}
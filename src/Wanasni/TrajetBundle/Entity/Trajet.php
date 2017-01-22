<?php

namespace Wanasni\TrajetBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Wanasni\TrajetBundle\Objet\Segment;
use Wanasni\UserBundle\Entity\User;
use Wanasni\VehiculeBundle\Entity\Vehicule;

/**
 * Trajet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\TrajetBundle\Entity\TrajetRepository")
 * @Assert\Callback(methods={"UniqueValid", "RegularValid", "vehiculeValid"})
 * @ORM\HasLifecycleCallbacks()
 */
class Trajet
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
     * @ORM\OneToOne(targetEntity="Wanasni\TrajetBundle\Entity\Point", cascade={"persist","remove"}, fetch="EAGER")
     * @Assert\Valid()
     */
    private $Origine;


    /**
     * @ORM\OneToOne(targetEntity="Wanasni\TrajetBundle\Entity\Point", cascade={"persist","remove"}, fetch="EAGER")
     * @Assert\Valid()
     */
    private $Destination;


    /**
     * @ORM\OneToMany(targetEntity="Wanasni\TrajetBundle\Entity\Point", mappedBy="trajet", cascade={"all"}, fetch="EAGER")
     */
    private $waypoints;


    /**
     * @var string
     *
     * @ORM\Column(name="frequence", type="string", length=10)
     * @Assert\Choice(choices = {"UNIQUE", "REGULAR"}, message = "Choose a valid frequence.")
     */
    private $frequence;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPlaces", type="integer")
     * @Assert\NotBlank(message="Places ne doit pas être vide.")
     * @Assert\Range(
     *      min = 1,
     *      max = 9,
     *      minMessage = "You must be at least {{ limit }} Places tall to enter",
     *      maxMessage = "You cannot be taller than {{ limit }} Places to enter"
     * )
     */
    private $nbPlaces;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPlacesRestants", type="integer")
     */
    private $nbPlacesRestants;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="total Durée ne doit pas être vide.")
     */
    private $totalDuration;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="total Distance ne doit pas être vide.")
     */
    private $totalDistance;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     *
     */
    private $totalPrix;


    /**
     *
     * @ORM\Column(name="Informations_complementaires", type="text", length=500, nullable=true)
     *
     * @Assert\Length(
     *      max = 500,
     *      maxMessage = "Your informationsComplementaires cannot be longer than {{ limit }} characters"
     * )
     */
    private $informationsComplementaires;

    /**
     * @var \DateTime
     * @ORM\Column(name="heureAller", type="time", nullable=false)
     * @Assert\NotBlank(message="heure Aller ne doit pas être vide.")
     * @Assert\Time()
     */
    private $heureAller;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heureRetour", type="time", nullable=false)
     * @Assert\Time()
     */
    private $heureRetour;

    /**
     * @var string
     *
     * @ORM\Column(name="Depart_prevu", type="string", nullable=false)
     * @Assert\Choice(choices = {"Heure exacte", "+/- 10 minutes", "+/- 20 minutes", "+/- 30 minutes"}, message = "Choose a valid Depart prévu")
     */
    private $Depart_prevu;


    /**
     * @ORM\Column(type="string")
     * @Assert\Choice(choices = {"Petit", "Moyen", "Grand", "Aucun"}, message = "Choose a valid Bagages.")
     */
    private $Bagages;


    /**
     * @ORM\OneToOne(targetEntity="Wanasni\TrajetBundle\Entity\Preferences", cascade={"persist","remove"})
     */
    private $Preferences;


    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $Date_Allet_unique;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $Date_Retour_unique;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $roundTrip;


    /**
     * @var WayDate
     *
     * @ORM\OneToMany(targetEntity="Wanasni\TrajetBundle\Entity\WayDate", mappedBy="tarjet_aller", cascade={"persist", "remove"})
     */
    private $datesAller;

    /**
     * @var WayDate
     * @ORM\OneToMany(targetEntity="Wanasni\TrajetBundle\Entity\WayDate", mappedBy="tarjet_retour", cascade={"persist", "remove"})
     */
    private $datesRetour;


    /**
     * @var \DateTime
     *
     */
    private $regularBeginDate;


    /**
     * @var \DateTime
     *
     */
    private $regularEndDate;


    /**
     * @var Vehicule
     * @ORM\ManyToOne(targetEntity="Wanasni\VehiculeBundle\Entity\Vehicule", inversedBy="trajets", fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private $vehicule;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Wanasni\UserBundle\Entity\User",inversedBy="trajets", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conducteur;

    /**
     * @var boolean
     * @Assert\IsTrue(message = "Vous devez certifier être en possession d'un permis de conduire et d'une assurance en cours de validité pour publier votre annonce")
     */
    private $cgu;


    /**
     * @ORM\Column(type="datetime")
     */
    private $proposerAt;


    /**
     * @ORM\OneToMany(targetEntity="Wanasni\TrajetBundle\Entity\Reservation", mappedBy="trajet", cascade={"all"}, fetch="EAGER")
     */
    private $reservations;


    /**
     * @ORM\Column(type="array")
     *
     */
    private $arrPrix;


    /**
     * Trajet constructor.
     */
    public function __construct()
    {
        $this->waypoints= new ArrayCollection();
        $this->datesAller = new ArrayCollection();
        $this->datesRetour = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->arrPrix = array();
        $this->Date_Allet_unique = date_create();
        $this->Date_Retour_unique = date_create();
        $this->regularBeginDate = date_create();
        $this->regularEndDate = date_create();
    }


    /**
     * @return boolean
     */
    public function isCgu()
    {
        return $this->cgu;
    }

    /**
     * @param boolean $cgu
     */
    public function setCgu($cgu)
    {
        $this->cgu = $cgu;
    }


    /**
     * @return \DateTime
     */
    public function getRegularBeginDate()
    {
        return $this->regularBeginDate;
    }

    /**
     * @param \DateTime $regularBeginDate
     */
    public function setRegularBeginDate($regularBeginDate)
    {
        $this->regularBeginDate = $regularBeginDate;
    }


    /**
     * @return \DateTime
     */
    public function getRegularEndDate()
    {
        return $this->regularEndDate;
    }

    /**
     * @param \DateTime $regularEndDate
     */
    public function setRegularEndDate($regularEndDate)
    {
        $this->regularEndDate = $regularEndDate;
    }


    /**
     * @return boolean
     */
    public function isRoundTrip()
    {
        return $this->roundTrip;
    }

    /**
     * @param boolean $roundTrip
     */
    public function setRoundTrip($roundTrip)
    {
        $this->roundTrip = $roundTrip;
    }

    /**
     * @return \DateTime
     */
    public function getDateAlletUnique()
    {
        return $this->Date_Allet_unique;
    }

    /**
     * @param \DateTime $Date_Allet_unique
     */
    public function setDateAlletUnique($Date_Allet_unique)
    {
        $this->Date_Allet_unique = $Date_Allet_unique;
    }

    /**
     * @return \DateTime
     */
    public function getDateRetourUnique()
    {
        return $this->Date_Retour_unique;
    }

    /**
     * @param \DateTime $Date_Retour_unique
     */
    public function setDateRetourUnique($Date_Retour_unique)
    {
        $this->Date_Retour_unique = $Date_Retour_unique;
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
     * Set frequence
     *
     * @param string $frequence
     * @return Trajet
     */
    public function setFrequence($frequence)
    {
        $this->frequence = $frequence;

        return $this;
    }

    /**
     * Get frequence
     *
     * @return string
     */
    public function getFrequence()
    {
        return $this->frequence;
    }

    /**
     * Set nbPlaces
     *
     * @param integer $nbPlaces
     * @return Trajet
     */
    public function setNbPlaces($nbPlaces)
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    /**
     * Get nbPlaces
     *
     * @return integer
     */
    public function getNbPlaces()
    {
        return $this->nbPlaces;
    }

    /**
     * Set nbPlacesRestants
     *
     * @param integer $nbPlacesRestants
     * @return Trajet
     */
    public function setNbPlacesRestants($nbPlacesRestants)
    {

        if ($nbPlacesRestants <= $this->nbPlaces && $nbPlacesRestants >= 0) {
            $this->nbPlacesRestants = $nbPlacesRestants;
        }

        return $this;
    }

    /**
     * Get nbPlacesRestants
     *
     * @return integer
     */
    public function getNbPlacesRestants()
    {
        return $this->nbPlacesRestants;
    }


    /**
     * Set informationsComplementaires
     *
     * @param string $informationsComplementaires
     * @return Trajet
     */
    public function setInformationsComplementaires($informationsComplementaires)
    {
        $this->informationsComplementaires = $informationsComplementaires;

        return $this;
    }

    /**
     * Get informationsComplementaires
     *
     * @return string
     */
    public function getInformationsComplementaires()
    {
        return $this->informationsComplementaires;
    }

    /**
     * Set Bagages
     *
     * @param string $bagages
     * @return Trajet
     */
    public function setBagages($bagages)
    {
        $this->Bagages = $bagages;

        return $this;
    }

    /**
     * Get Bagages
     *
     * @return string
     */
    public function getBagages()
    {
        return $this->Bagages;
    }

    /**
     * Set Preferences
     *
     * @param \Wanasni\TrajetBundle\Entity\Preferences $preferences
     * @return Trajet
     */
    public function setPreferences(\Wanasni\TrajetBundle\Entity\Preferences $preferences = null)
    {
        $this->Preferences = $preferences;

        return $this;
    }

    /**
     * Get Preferences
     *
     * @return \Wanasni\TrajetBundle\Entity\Preferences
     */
    public function getPreferences()
    {
        return $this->Preferences;
    }


    /**
     * Set totalDuration
     *
     * @param string $totalDuration
     * @return Trajet
     */
    public function setTotalDuration($totalDuration)
    {
        $this->totalDuration = $totalDuration;

        return $this;
    }

    /**
     * Get totalDuration
     *
     * @return string
     */
    public function getTotalDuration()
    {
        return $this->totalDuration;
    }

    /**
     * Set totalDistance
     *
     * @param string $totalDistance
     * @return Trajet
     */
    public function setTotalDistance($totalDistance)
    {
        $this->totalDistance = $totalDistance;

        return $this;
    }

    /**
     * Get totalDistance
     *
     * @return string
     */
    public function getTotalDistance()
    {
        return $this->totalDistance;
    }

    /**
     * Set vehicule
     *
     * @param \Wanasni\VehiculeBundle\Entity\Vehicule $vehicule
     * @return Trajet
     */
    public function setVehicule(\Wanasni\VehiculeBundle\Entity\Vehicule $vehicule = null)
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    /**
     * Get vehicule
     *
     * @return \Wanasni\VehiculeBundle\Entity\Vehicule
     */
    public function getVehicule()
    {
        return $this->vehicule;
    }

    /**
     * Set totalPrix
     *
     * @param integer $totalPrix
     * @return Trajet
     */
    public function setTotalPrix($totalPrix)
    {
        $this->totalPrix = $totalPrix;

        return $this;
    }

    /**
     * Get totalPrix
     *
     * @return integer
     */
    public function getTotalPrix()
    {
        return $this->totalPrix;
    }


    /**
     * Set Depart_prevu
     *
     * @param string $departPrevu
     * @return Trajet
     */
    public function setDepartPrevu($departPrevu)
    {
        $this->Depart_prevu = $departPrevu;

        return $this;
    }

    /**
     * Get Depart_prevu
     *
     * @return string
     */
    public function getDepartPrevu()
    {
        return $this->Depart_prevu;
    }


    /**
     * Set heureRetour
     *
     * @param \DateTime $heureRetour
     * @return Trajet
     */
    public function setHeureRetour($heureRetour)
    {
        $this->heureRetour = $heureRetour;

        return $this;
    }

    /**
     * Get heureRetour
     *
     * @return \DateTime
     */
    public function getHeureRetour()
    {
        return $this->heureRetour;
    }

    /**
     * Set heureAller
     *
     * @param \DateTime $heureAller
     * @return Trajet
     */
    public function setHeureAller($heureAller)
    {
        $this->heureAller = $heureAller;

        return $this;
    }

    /**
     * Get heureAller
     *
     * @return \DateTime
     */
    public function getHeureAller()
    {
        return $this->heureAller;
    }

    /**
     * Set conducteur
     *
     * @param \Wanasni\UserBundle\Entity\User $conducteur
     * @return Trajet
     */
    public function setConducteur(\Wanasni\UserBundle\Entity\User $conducteur = null)
    {
        $this->conducteur = $conducteur;

        return $this;
    }

    /**
     * Get conducteur
     *
     * @return \Wanasni\UserBundle\Entity\User
     */
    public function getConducteur()
    {
        return $this->conducteur;
    }


    /**
     * Get roundTrip
     *
     * @return boolean
     */
    public function getRoundTrip()
    {
        return $this->roundTrip;
    }

    /**
     * Add datesAller
     *
     * @param \Wanasni\TrajetBundle\Entity\WayDate $datesAller
     * @return Trajet
     */
    public function addDatesAller(\Wanasni\TrajetBundle\Entity\WayDate $datesAller)
    {
        $datesAller->setTarjetAller($this);
        $this->datesAller[] = $datesAller;

        return $this;
    }

    /**
     * Remove datesAller
     *
     * @param \Wanasni\TrajetBundle\Entity\WayDate $datesAller
     */
    public function removeDatesAller(\Wanasni\TrajetBundle\Entity\WayDate $datesAller)
    {
        $datesAller->setTarjetAller(null);
        $this->datesAller->removeElement($datesAller);
    }

    /**
     * Get datesAller
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDatesAller()
    {
        return $this->datesAller;
    }

    /**
     * Add datesRetour
     *
     * @param \Wanasni\TrajetBundle\Entity\WayDate $datesRetour
     * @return Trajet
     */
    public function addDatesRetour(\Wanasni\TrajetBundle\Entity\WayDate $datesRetour)
    {
        $datesRetour->setTarjetRetour($this);
        $this->datesRetour[] = $datesRetour;

        return $this;
    }

    /**
     * Remove datesRetour
     *
     * @param \Wanasni\TrajetBundle\Entity\WayDate $datesRetour
     */
    public function removeDatesRetour(\Wanasni\TrajetBundle\Entity\WayDate $datesRetour)
    {
        $datesRetour->setTarjetRetour(null);
        $this->datesRetour->removeElement($datesRetour);
    }

    /**
     * Get datesRetour
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDatesRetour()
    {
        return $this->datesRetour;
    }


    public function getDatesAllerToArray()
    {
        $arr = array();

        if ($this->frequence == 'UNIQUE') {
            $arr[] = $this->getDateAlletUnique()->format('Ymd');
        } else {
            foreach ($this->getDatesAller() as $wayDate) {
                $arr[] = date_format($wayDate->getDate(), 'Ymd');
            }
        }


        return $arr;
    }

    public function getDatesRetourToArray()
    {
        $arr = array();
        if ($this->frequence == 'UNIQUE' && $this->roundTrip) {
            $arr[] = $this->getDateRetourUnique()->format('Ymd');
        } else {
            foreach ($this->getDatesRetour() as $wayDate) {
                $arr[] = date_format($wayDate->getDate(), 'Ymd');
            }
        }
        return $arr;
    }

    public function getDatesToArray()
    {

        $arr = array();
        if ($this->frequence == 'UNIQUE') {
            $arr[] = $this->getDateAlletUnique()->format('Y-m-d');
            if ($this->getDateRetourUnique()) {
                $arr[] = $this->getDateRetourUnique()->format('Y-m-d');
            }

        } else {
            foreach ($this->getDatesAller() as $wayDate) {
                $arr[] = $wayDate->getDate()->format('Y-m-d');
            }
            foreach ($this->getDatesRetour() as $wayDate) {
                $arr[] = $wayDate->getDate()->format('Y-m-d');
            }
        }
        return $arr;

    }


    public function getAllPoint()
    {
        $arrCollection = new ArrayCollection();

        $arrCollection->add($this->getOrigine());

        if ($this->waypoints) {
            foreach ($this->getWaypoints() as $waypoint) {
                $arrCollection->add($waypoint);
            }
        }


        $arrCollection->add($this->getDestination());

        return $arrCollection;

    }


    public function getSegments()
    {

        $arr_Collection = new ArrayCollection();

        for ($i = 0; $i < $this->getAllPoint()->count() - 1; $i++) {
            $segment = new Segment(
                $this->getAllPoint()->get($i)->getLieu(),
                $this->getAllPoint()->get($i + 1)->getLieu(),
                $this->arrPrix[$i]
            );
            $arr_Collection->add($segment);
        }

        return $arr_Collection;

    }


    /**
     * Set proposerAt
     *
     * @param \DateTime $proposerAt
     * @return Trajet
     */
    public function setProposerAt($proposerAt)
    {
        $this->proposerAt = $proposerAt;

        return $this;
    }

    /**
     * Get proposerAt
     *
     * @return \DateTime
     */
    public function getProposerAt()
    {
        return $this->proposerAt;
    }

    /**
     * Add reservations
     *
     * @param \Wanasni\TrajetBundle\Entity\Reservation $reservations
     * @return Trajet
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
     * Set arrPrix
     *
     * @param array $arrPrix
     * @return Trajet
     */
    public function setArrPrix($arrPrix)
    {
        $this->arrPrix = $arrPrix;

        return $this;
    }

    /**
     * Get arrPrix
     *
     * @return array
     */
    public function getArrPrix()
    {
        return $this->arrPrix;
    }

    public function addArrPrix($prix)
    {
        $this->arrPrix[] = $prix;
    }

    public function removeArrPrix($prix)
    {
        if (false !== $key = array_search(strtoupper($prix), $this->arrPrix, true)) {
            unset($this->arrPrix[$key]);
            $this->arrPrix = array_values($this->arrPrix);
        }

        return $this;
    }


    /**
     * Set Origine
     *
     * @param \Wanasni\TrajetBundle\Entity\Point $origine
     * @return Trajet
     */
    public function setOrigine(\Wanasni\TrajetBundle\Entity\Point $origine)
    {
        $origine->setTrajet(null);
        $this->Origine = $origine;

        return $this;
    }

    /**
     * Get Origine
     *
     * @return \Wanasni\TrajetBundle\Entity\Point
     */
    public function getOrigine()
    {
        return $this->Origine;
    }

    /**
     * Set Destination
     *
     * @param \Wanasni\TrajetBundle\Entity\Point $destination
     * @return Trajet
     */
    public function setDestination(\Wanasni\TrajetBundle\Entity\Point $destination)
    {
        $destination->setTrajet(null);
        $this->Destination = $destination;

        return $this;
    }

    /**
     * Get Destination
     *
     * @return \Wanasni\TrajetBundle\Entity\Point
     */
    public function getDestination()
    {
        return $this->Destination;
    }



    /**
     * Add waypoints
     *
     * @param \Wanasni\TrajetBundle\Entity\Point $waypoints
     * @return Trajet
     */
    public function addWaypoint(\Wanasni\TrajetBundle\Entity\Point $waypoints)
    {
        $waypoints->setTrajet($this);
        $this->waypoints[] = $waypoints;

        return $this;
    }

    /**
     * Remove waypoints
     *
     * @param \Wanasni\TrajetBundle\Entity\Point $waypoints
     */
    public function removeWaypoint(\Wanasni\TrajetBundle\Entity\Point $waypoints)
    {
        $this->waypoints->removeElement($waypoints);
    }

    /**
     * Get waypoints
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWaypoints()
    {
        return $this->waypoints;
    }


    public function ChangeNbPlacesRestant()
    {
        if ($this->getNbPlacesRestants() > 0) {
            $this->nbPlacesRestants = $this->nbPlacesRestants - 1;
        }

        return $this;
    }


    public function isReserverByPassage(User $passage = null)
    {
        $bool = false;

        if ($passage != null) {

            foreach ($this->reservations as $r) {
                if ($r->getPassager()->getId() == $passage->getId()) {
                    $bool = true;
                    break;
                }
            }
        }
        return $bool;
    }




    public function UniqueValid(ExecutionContextInterface $context)
    {
        if ($this->frequence == 'UNIQUE') {

            $new = date_create();

            //Datealler Test
            if ($this->checkDate($this->getDateAlletUnique())) {


                if (intval(date_diff($this->getDateAlletUnique(), $new)->format('%R%a')) > 0) {
                    $context->addViolationAt('Date_Allet_unique', 'date Aller invalid >' . date('d/m/y'));
                }

            } else {
                $context->addViolationAt('Date_Allet_unique', 'invalid date Aller');
            }

            if ($this->getDateAlletUnique()->format("y-m-d") == $new->format("y-m-d")) {
                if ($this->heureAller->format('H:i') < $new->format('H:i')) {
                    $context->addViolationAt('heureAller', 'invalid heure aller !!');
                }
            }

            //dateRetour test

            if ($this->roundTrip) {

                if ($this->checkDate($this->getDateRetourUnique())) {

                    if (intval(date_diff($this->getDateRetourUnique(), $this->getDateAlletUnique())->format('%R%a')) > 0) {
                        $context->addViolationAt('Date_Retour_unique', 'date Retour invalid >=' . $this->getDateAlletUnique()->format('d/m/y'));
                    }


                    if (intval(date_diff($this->getDateRetourUnique(), $this->getDateAlletUnique())->format('%R%a')) == 0) {
                        //Heure Retour test

                        if (intval($this->heureRetour->format('Hi')) <= intval($this->heureAller->format('Hi'))) {
                            $context->addViolationAt('heureRetour', 'invalid heure retour (heure retour doit etre superieur à heure aller)');
                        }

                        if (intval(date_diff($this->heureAller, $this->heureRetour)->format('%H%I')) < intval(date_create($this->getTotalDuration())->format('Hi'))) {
                            $context->addViolationAt('heureRetour', 'invalid heure retour (heure retour doit etre superieur à heure aller + durée estime)');
                        }


                    }


                } else {
                    $context->addViolationAt('Date_Retour_unique', 'invalid date Retour');
                }


            }


        }
    }

    public function RegularValid(ExecutionContextInterface $context)
    {
        if ($this->frequence == 'REGULAR') {

            $new = date_create();
            if ($this->regularBeginDate->format('Ymd') == $new->format('Ymd')) {
                if ($this->heureAller->format('H:i') < $new->format('H:i')) {
                    $context->addViolationAt('heureAller', 'invalid heure aller !!');
                }
            }

            if ($this->roundTrip) {

                if (intval($this->heureRetour->format('Hi')) <= intval($this->heureAller->format('Hi'))) {
                    $context->addViolationAt('heureRetour', 'invalid heure retour (heure retour doit etre superieur à heure aller)');
                }

                if (intval(date_diff($this->heureAller, $this->heureRetour)->format('%H%I')) < intval(date_create($this->getTotalDuration())->format('Hi'))) {
                    $context->addViolationAt('heureRetour', 'invalid heure retour (heure retour doit etre superieur à heure aller + durée estime)');
                }
            }


        }
    }


    public function vehiculeValid(ExecutionContextInterface $context)
    {
        if ($this->getVehicule()->getNbrPlaces() < $this->nbPlaces) {
            $context->addViolationAt('nbPlaces', 'invalid nombre de places pour cette véhicule (max : ' . $this->getVehicule()->getNbrPlaces() . ")");
        }
    }

    function checkDate(\DateTime $date)
    {
        $month = intval($date->format('m'));
        $day = intval($date->format('d'));
        $year = intval($date->format('y'));
        return checkdate($month, $day, $year);
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        $p = 0;
        foreach ($this->arrPrix as $prix) {
            $p += $prix;
        }
        $this->setTotalPrix($p);
        $this->setProposerAt(new \DateTime());
    }


    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->nbPlacesRestants = $this->nbPlaces;
    }


    /**
     * @ORM\PreRemove()
     */
    public function PreRemove()
    {
        foreach($this->waypoints as $point){
            $point->setTrajet(null);
            $this->removeWaypoint($point);
        }
    }

}
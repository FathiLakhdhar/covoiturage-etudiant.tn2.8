<?php

namespace Wanasni\VehiculeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * Modele
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\VehiculeBundle\Entity\ModeleRepository")
 */
class Modele
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="CarModel", type="string", length=255)
     * @Groups({"list", "details"})
     */
    private $carModel;


    /**
     * @var Marque
     * @ORM\ManyToOne(targetEntity="Wanasni\VehiculeBundle\Entity\Marque", inversedBy="modeles")
     */
    private $marque;


    /**
     * @ORM\OneToMany(targetEntity="Wanasni\VehiculeBundle\Entity\Vehicule", mappedBy="modele")
     */
    private $vehicules;


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
     * Set carModel
     *
     * @param string $carModel
     * @return Modele
     */
    public function setCarModel($carModel)
    {
        $this->carModel = $carModel;
    
        return $this;
    }

    /**
     * Get carModel
     *
     * @return string 
     */
    public function getCarModel()
    {
        return $this->carModel;
    }



    /**
     * Set marque
     *
     * @param \Wanasni\VehiculeBundle\Entity\Marque $marque
     * @return Modele
     */
    public function setMarque(\Wanasni\VehiculeBundle\Entity\Marque $marque = null)
    {
        $this->marque = $marque;
    
        return $this;
    }

    /**
     * Get marque
     *
     * @return \Wanasni\VehiculeBundle\Entity\Marque 
     */
    public function getMarque()
    {
        return $this->marque;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vehicules = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add vehicules
     *
     * @param \Wanasni\VehiculeBundle\Entity\Vehicule $vehicules
     * @return Modele
     */
    public function addVehicule(\Wanasni\VehiculeBundle\Entity\Vehicule $vehicules)
    {
        $this->vehicules[] = $vehicules;
    
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
}
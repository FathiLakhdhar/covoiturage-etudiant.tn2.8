<?php

namespace Wanasni\TrajetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Preferences
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wanasni\TrajetBundle\Entity\PreferencesRepository")
 */
class Preferences
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
     * @var boolean
     *
     * @ORM\Column(name="Fumeurs", type="boolean")
     *
     */
    private $fumeurs;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Animaux", type="boolean")
     */
    private $animaux;

    /**
     * @var boolean
     *
     * @ORM\Column(name="AnimauxEnCage", type="boolean")
     *
     */
    private $animauxEnCage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Musique", type="boolean")
     *
     */
    private $musique;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Detours", type="boolean")
     *
     */
    private $detours;

    /**
     * @var boolean
     *
     * @ORM\Column(name="PauseCafe", type="boolean")
     *
     */
    private $pauseCafe;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Nourriture", type="boolean")
     *
     */
    private $nourriture;


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
     * Set fumeurs
     *
     * @param boolean $fumeurs
     * @return Preferences
     */
    public function setFumeurs($fumeurs)
    {
        $this->fumeurs = $fumeurs;
    
        return $this;
    }

    /**
     * Get fumeurs
     *
     * @return boolean 
     */
    public function getFumeurs()
    {
        return $this->fumeurs;
    }

    /**
     * Set animaux
     *
     * @param boolean $animaux
     * @return Preferences
     */
    public function setAnimaux($animaux)
    {
        $this->animaux = $animaux;
    
        return $this;
    }

    /**
     * Get animaux
     *
     * @return boolean 
     */
    public function getAnimaux()
    {
        return $this->animaux;
    }

    /**
     * Set animauxEnCage
     *
     * @param boolean $animauxEnCage
     * @return Preferences
     */
    public function setAnimauxEnCage($animauxEnCage)
    {
        $this->animauxEnCage = $animauxEnCage;
    
        return $this;
    }

    /**
     * Get animauxEnCage
     *
     * @return boolean 
     */
    public function getAnimauxEnCage()
    {
        return $this->animauxEnCage;
    }

    /**
     * Set musique
     *
     * @param boolean $musique
     * @return Preferences
     */
    public function setMusique($musique)
    {
        $this->musique = $musique;
    
        return $this;
    }

    /**
     * Get musique
     *
     * @return boolean 
     */
    public function getMusique()
    {
        return $this->musique;
    }

    /**
     * Set detours
     *
     * @param boolean $detours
     * @return Preferences
     */
    public function setDetours($detours)
    {
        $this->detours = $detours;
    
        return $this;
    }

    /**
     * Get detours
     *
     * @return boolean 
     */
    public function getDetours()
    {
        return $this->detours;
    }

    /**
     * Set pauseCafe
     *
     * @param boolean $pauseCafe
     * @return Preferences
     */
    public function setPauseCafe($pauseCafe)
    {
        $this->pauseCafe = $pauseCafe;
    
        return $this;
    }

    /**
     * Get pauseCafe
     *
     * @return boolean 
     */
    public function getPauseCafe()
    {
        return $this->pauseCafe;
    }

    /**
     * Set nourriture
     *
     * @param boolean $nourriture
     * @return Preferences
     */
    public function setNourriture($nourriture)
    {
        $this->nourriture = $nourriture;
    
        return $this;
    }

    /**
     * Get nourriture
     *
     * @return boolean 
     */
    public function getNourriture()
    {
        return $this->nourriture;
    }
}

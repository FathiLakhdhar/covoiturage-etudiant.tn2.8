<?php

namespace Wanasni\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sessions
 *
 * @ORM\Table(name="sessions")
 * @ORM\Entity(repositoryClass="Wanasni\UserBundle\Repository\SessionsRepository")
 */
class Sessions
{
    /**
     * @var int
     *
     * @ORM\Column(name="sess_id", type="string", length=128)
     * @ORM\Id()
     */
    private $sessId;

    /**
     * @var string
     *
     * @ORM\Column(name="sess_data", type="blob", nullable=false, length=88)
     */
    private $sessData;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_time", type="integer")
     */
    private $sessTime;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_lifetime", type="integer")
     */
    private $sessLifetime;


    /**
     * Get sess_id
     *
     * @return integer 
     */
    public function getSessId()
    {
        return $this->sessId;
    }

    /**
     * Set sessId
     *
     * @param string $sessId
     * @return Sessions
     */
    public function setSessId($sessId)
    {
        $this->sessId = $sessId;

        return $this;
    }

    /**
     * Set sessData
     *
     * @param string $sessData
     * @return Sessions
     *
     */
    public function setSessData($sessData)
    {
        $this->sessData = $sessData;

        return $this;
    }

    /**
     * Get sessData
     *
     * @return string 
     */
    public function getSessData()
    {
        return $this->sessData;
    }

    /**
     * Set sessTime
     *
     * @param integer $sessTime
     * @return Sessions
     */
    public function setSessTime($sessTime)
    {
        $this->sessTime = $sessTime;

        return $this;
    }

    /**
     * Get sessTime
     *
     * @return integer 
     */
    public function getSessTime()
    {
        return $this->sessTime;
    }

    /**
     * Set sessLifetime
     *
     * @param integer $sessLifetime
     * @return Sessions
     */
    public function setSessLifetime($sessLifetime)
    {
        $this->sessLifetime = $sessLifetime;

        return $this;
    }

    /**
     * Get sessLifetime
     *
     * @return integer 
     */
    public function getSessLifetime()
    {
        return $this->sessLifetime;
    }
}

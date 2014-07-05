<?php

namespace MewesK\WebRedirectorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * Redirect
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MewesK\WebRedirectorBundle\Entity\RedirectRepository")
 */
class Redirect
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
     * @var string
     *
     * @ORM\Column(name="hostname", type="string", length=1023)
     */
    private $hostname;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=1023)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=1023)
     */
    private $destination;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isRegex", type="boolean")
     */
    private $isRegex;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

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
     * Set hostname
     *
     * @param string $hostname
     *
     * @return Redirect
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Get hostname
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Redirect
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set destination
     *
     * @param string $destination
     *
     * @return Redirect
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set isRegex
     *
     * @param boolean $isRegex
     *
     * @return Redirect
     */
    public function setIsRegex($isRegex)
    {
        $this->isRegex = $isRegex;

        return $this;
    }

    /**
     * Get isRegex
     *
     * @return boolean
     */
    public function getIsRegex()
    {
        return $this->isRegex;
    }

    /**
     * Set updated
     *
     * @param boolean $updated
     *
     * @return Redirect
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return boolean
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created
     *
     * @param boolean $created
     *
     * @return Redirect
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return boolean
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created automatically before each persist
     *
     * @PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime();
    }

    /**
     * Set updated automatically before each update
     *
     * @PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime();
    }
}

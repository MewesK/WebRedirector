<?php

namespace MewesK\WebRedirectorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\Validator\Constraints as Assert;
use MewesK\WebRedirectorBundle\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * Redirect
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MewesK\WebRedirectorBundle\Entity\RedirectRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Assert\GroupSequenceProvider
 */
class Redirect implements GroupSequenceProviderInterface
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
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="hostname", type="string", length=1023)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "1",
     *      max = "1023",
     *      minMessage = "The hostname must be at least {{ limit }} characters length",
     *      maxMessage = "The hostname cannot be longer than {{ limit }} characters length"
     * )
     * @CustomAssert\IsValidPCRE(
     *     groups={"Regex"}
     * )
     * @CustomAssert\IsValidHostname(
     *     groups={"NotRegex"}
     * )
     */
    private $hostname;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=1023, nullable=true)
     *
     * @Assert\Length(
     *      max = "1023",
     *      maxMessage = "The path cannot be longer than {{ limit }} characters length"
     * )
     * @CustomAssert\IsValidPCRE(
     *     groups={"Regex"}
     * )
     * @CustomAssert\IsValidPath(
     *     groups={"NotRegex"}
     * )
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=1023)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "1",
     *      max = "1023",
     *      minMessage = "The destination must be at least {{ limit }} characters length",
     *      maxMessage = "The destination cannot be longer than {{ limit }} characters length"
     * )
     * @Assert\Url(
     *     groups={"NotRegexNotPlaceholders"}
     * )
     */
    private $destination;

    /**
     * @var boolean
     *
     * @ORM\Column(name="useRegex", type="boolean")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $useRegex;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usePlaceholders", type="boolean")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $usePlaceholders;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    public function __construct() {
        $this->enabled = false;
        $this->path = '';
        $this->useRegex = false;
        $this->usePlaceholders = false;
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
     * Set position
     *
     * @param string $position
     *
     * @return Redirect
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
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
     * Set useRegex
     *
     * @param boolean $useRegex
     *
     * @return Redirect
     */
    public function setUseRegex($useRegex)
    {
        $this->useRegex = $useRegex ? true : false;

        return $this;
    }

    /**
     * Get useRegex
     *
     * @return boolean
     */
    public function getUseRegex()
    {
        return $this->useRegex;
    }

    /**
     * Set usePlaceholders
     *
     * @param boolean $usePlaceholders
     *
     * @return Redirect
     */
    public function setUsePlaceholders($usePlaceholders)
    {
        $this->usePlaceholders = $usePlaceholders ? true : false;

        return $this;
    }

    /**
     * Get usePlaceholders
     *
     * @return boolean
     */
    public function getUsePlaceholders()
    {
        return $this->usePlaceholders;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Redirect
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled ? true : false;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set updatedAt
     *
     * @param boolean $updatedAt
     *
     * @return Redirect
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return boolean
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param boolean $createdAt
     *
     * @return Redirect
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return boolean
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt automatically before each persist
     *
     * @PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set updatedAt automatically before each update
     *
     * @PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Returns which validation groups should be used for a certain state
     * of the object.
     *
     * @return array An array of validation groups
     */
    public function getGroupSequence()
    {
        $groups = array(
            'Redirect',
            $this->getUseRegex() ? 'Regex' : 'NotRegex'
        );
        if (!$this->getUsePlaceholders() && !$this->getUseRegex()) {
            $groups[] = 'NotRegexNotPlaceholders';
        }
        return $groups;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getHostname() . ($this->getPath() ? ' + ' . $this->getPath() : '') .  ' -> ' . $this->getDestination();
    }
}

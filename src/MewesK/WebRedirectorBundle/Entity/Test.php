<?php

namespace MewesK\WebRedirectorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MewesK\WebRedirectorBundle\Validator\Constraints as CustomAssert;

/**
 * Test
 */
class Test extends Redirect
{
    /**
     * @var string
     *
     * @Assert\Length(
     *      max = "1023",
     *      maxMessage = "The destination cannot be longer than {{ limit }} characters length"
     * )
     * @Assert\Url()
     */
    private $url;

    /**
     * Set destination
     *
     * @param string $url
     *
     * @return Test
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Constructor
     *
     * @param Redirect $redirect
     */
    public function __construct(Redirect $redirect = null) {
        parent::__construct();
        if ($redirect) {
            $this->setCreatedAt($redirect->getCreatedAt());
            $this->setDestination($redirect->getDestination());
            $this->setEnabled($redirect->getEnabled());
            $this->setHostname($redirect->getHostname());
            $this->setPath($redirect->getPath());
            $this->setPosition($redirect->getPosition());
            $this->setUpdatedAt($redirect->getUpdatedAt());
            $this->setUsePlaceholders($redirect->getUsePlaceholders());
            $this->setUseRegex($redirect->getUseRegex());
        }
    }
}

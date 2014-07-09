<?php

namespace MewesK\WebRedirectorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MewesK\WebRedirectorBundle\Validator\Constraints as CustomAssert;

/**
 * Test
 */
class Test
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(
     *      min = "1",
     *      max = "1023",
     *      minMessage = "The destination must be at least {{ limit }} characters length",
     *      maxMessage = "The destination cannot be longer than {{ limit }} characters length"
     * )
     * @Assert\Url()
     */
    private $url;

    /**
     * @var Redirect
     *
     * @Assert\NotNull()
     */
    private $redirect;

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
     * Set redirect
     *
     * @param Redirect $redirect
     *
     * @return Test
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Get redirect
     *
     * @return Redirect
     */
    public function getRedirect()
    {
        return $this->redirect;
    }
}

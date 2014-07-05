<?php

namespace MewesK\WebRedirectorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Redirect controller.
 *
 * @Route("/")
 */
class SecurityController extends Controller
{

    /**
     * Lists all Redirect entities.
     *
     * @Route("/login", name="login")
     * @Method("GET")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->get('request');
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $this->get('session')->getFlashBag()->add('error', $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR)->getMessage());
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $this->get('session')->getFlashBag()->add('error', $session->get(SecurityContextInterface::AUTHENTICATION_ERROR)->getMessage());
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
        }

        return array(
            'last_username' => (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME)
        );
    }
} 
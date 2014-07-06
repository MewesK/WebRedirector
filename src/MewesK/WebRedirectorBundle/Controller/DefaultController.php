<?php

namespace MewesK\WebRedirectorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    public function indexAction($url)
    {
        $request = $this->get('request');

        $scheme = $request->getScheme();
        $hostname = $request->getHttpHost();
        $path = $request->getRequestUri();

        var_dump(array(
            $scheme,
            $hostname,
            $path
        )); die();

        return array();
    }
}

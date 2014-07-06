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
        $path = $request->getPathInfo();

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('MewesKWebRedirectorBundle:Redirect')->createQueryBuilder('r')
            ->where('r.useRegex = true')
            ->orWhere('r.hostname = :hostname AND r.path = :path')
            ->setParameter('hostname', $hostname)
            ->setParameter('path', $path)
            ->getQuery();
        $entities = $query->getResult();

        foreach($entities as $entity) {
            if ($entity->getUseRegex() === true) {
                $entityHostname = $entity->getHostname();
                $entityPath = $entity->getPath();
            }
        }

        var_dump($entities); die();

        return array();
    }
}

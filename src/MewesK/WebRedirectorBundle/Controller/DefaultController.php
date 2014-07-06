<?php

namespace MewesK\WebRedirectorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    private static $DEBUG = true;

    public function indexAction($url)
    {
        $request = $this->get('request');

        // request URL parts
        $scheme = $request->getScheme();
        $hostname = $request->getHttpHost();
        $path = $request->getPathInfo();
        $query = $request->getQueryString();

        // get possible redirects
        $entities = $this->getDoctrine()->getManager()->getRepository('MewesKWebRedirectorBundle:Redirect')->getPossibleRedirects($hostname, $path);

        if (self::$DEBUG) {
            var_dump(count($entities));
            echo "\n===\n\n";
        }

        // magic
        foreach($entities as $entityKey => $entity) {
            $entityHostname = $entity->getHostname();
            $entityPath = $entity->getPath();
            $entityDestination = $entity->getDestination();

            if (self::$DEBUG) {
                echo "Possible Redirect:\n";
                var_dump($entityHostname, $entityPath, $entityDestination);
            }

            // handle regex
            if ($entity->getUseRegex()) {
                if (self::$DEBUG) {
                    echo "\nIs using regex\n";
                }

                // remove if regex won't match
                if (!(preg_match($entityHostname, $hostname, $matchesHostname) && preg_match($entityPath, $path, $matchesPath))) {
                    if (self::$DEBUG) {
                        echo "Doesn't match, removing\n";
                        echo "\n===\n\n";
                    }

                    unset($entities[$entityKey]);
                    continue;
                }

                // perform regex replace if necessary
                else {
                    if (self::$DEBUG) {
                        echo "Does match, matches:\n";
                        var_dump($matchesHostname, $matchesPath);
                    }

                    foreach($matchesHostname as $matchKey => $matchHostname) {
                        $entityDestination = preg_replace('/(?<!\$)\$H'.$matchKey.'/i', $matchHostname, $entityDestination);
                    }

                    foreach($matchesPath as $matchKey => $matchPath) {
                        $entityDestination = preg_replace('/(?<!\$)\$P'.$matchKey.'/i', $matchPath, $entityDestination);
                    }
                }
            }

            // handle placeholders
            if ($entity->getUsePlaceholders()) {
                if (self::$DEBUG) {
                    echo "\nIs using placeholders\n";
                }

                $entityDestination = preg_replace('/(?<!\$)\$S/i', $scheme, $entityDestination);
                $entityDestination = preg_replace('/(?<!\$)\$Q/i', $query, $entityDestination);
            }

            if (self::$DEBUG) {
                echo "\nResult:\n";
                var_dump($entityHostname, $entityPath, $entityDestination);
                echo "\n===\n\n";
            }
        }

        if (self::$DEBUG) {
            var_dump(count($entities));
            die();
        }

        return array();
    }
}

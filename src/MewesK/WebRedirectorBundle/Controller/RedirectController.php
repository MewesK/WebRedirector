<?php

namespace MewesK\WebRedirectorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MewesK\WebRedirectorBundle\Entity\Redirect;
use Symfony\Component\HttpFoundation\Request;

class RedirectController extends Controller
{
    public function indexAction(Request $request)
    {
        // get possible redirects
        $entities = $this->getDoctrine()->getManager()->getRepository('MewesKWebRedirectorBundle:Redirect')->getPossibleRedirects($hostname, $path);
        $destinations = array();

        // magic
        foreach($entities as $entityKey => $entity) {
            /** @var $entity Redirect */
            $destination = self::performRedirectTranslation($request, $entity);

            // remove if not matching
            if ($destination === false) {
                unset($entities[$entityKey]);
                continue;
            }

            $destinations[$entityKey] = $destination;
        }

        $finalRedirect = reset($destinations);

        return $finalRedirect === false ? $this->render('MewesKWebRedirectorBundle::error.html.twig', array(
            'error_code' => 404,
            'error_message' => 'Unable to find a matching redirect.'
        )) : new RedirectResponse($finalRedirect->getDestination());
    }

    public static function performRedirectTranslation(Request $request, Redirect $redirect, $explain = false) {
        $entityDestination = $redirect->getDestination();
        $hostnameGroups = array();
        $pathGroups = array();
        $placeholders = array();

        // handle regex
        if ($redirect->getUseRegex()) {
            // remove if regex won't match
            if (!(preg_match($redirect->getHostname(), $request->getHttpHost(), $matchesHostname) &
                (!is_null($redirect->getPath()) & preg_match($redirect->getPath(), $request->getPathInfo(), $matchesPath)))) {
                return false;
            }

            // perform regex replace if necessary
            else {
                 foreach($matchesHostname as $matchKey => $matchHostname) {
                    $entityDestination = preg_replace('/(?<!\$)\$H'.$matchKey.'/i', $matchHostname, $entityDestination);

                     if ($explain) {
                         $hostnameGroups['$H'.$matchKey] = $matchHostname;
                     }
                }

                foreach($matchesPath as $matchKey => $matchPath) {
                    $entityDestination = preg_replace('/(?<!\$)\$P'.$matchKey.'/i', $matchPath, $entityDestination);

                    if ($explain) {
                        $pathGroups['$P'.$matchKey] = $matchPath;
                    }
                }
            }
        }

        // handle placeholders
        if ($redirect->getUsePlaceholders()) {
            $entityDestination = preg_replace('/(?<!\$)\$S/i', $request->getScheme(), $entityDestination);
            $entityDestination = preg_replace('/(?<!\$)\$Q/i', $request->getQueryString(), $entityDestination);

            if ($explain) {
                $placeholders['$Q'] = $request->getScheme();
                $placeholders['$S'] = $request->getQueryString();
            }
        }

        return !$explain ? $entityDestination : array(
            'destination' => $entityDestination,
            'hostnameGroups' => $hostnameGroups,
            'pathGroups' => $pathGroups,
            'placeholders' => $placeholders
        );
    }
}

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
        $entities = $this->getDoctrine()->getManager()->getRepository('MewesKWebRedirectorBundle:Redirect')
            ->getPossibleRedirects($request->getHttpHost(), $request->getPathInfo());
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
        $destination = $redirect->getDestination();

        $hostnameGroups = array();
        $pathGroups = array();
        $placeholders = array();

        $matchesHostname = array();
        $matchesPath = array();

        // handle regex
        if ($redirect->getUseRegex()) {
            // remove if regex won't match
            if (!(preg_match($redirect->getHostname(), $request->getHttpHost(), $matchesHostname) &
                ($redirect->getPath() && $redirect->getPath() != '' && preg_match($redirect->getPath(), $request->getPathInfo(), $matchesPath)))) {
                return false;
            }

            // perform regex replace if necessary
            else {
                 foreach($matchesHostname as $matchKey => $matchHostname) {
                     $destination = preg_replace('/(?<!\$)\$H'.$matchKey.'/i', $matchHostname, $destination);

                     if ($explain) {
                         $hostnameGroups['$H'.$matchKey] = $matchHostname;
                     }
                }

                foreach($matchesPath as $matchKey => $matchPath) {
                    $destination = preg_replace('/(?<!\$)\$P'.$matchKey.'/i', $matchPath, $destination);

                    if ($explain) {
                        $pathGroups['$P'.$matchKey] = $matchPath;
                    }
                }
            }
        }

        // handle placeholders
        if ($redirect->getUsePlaceholders()) {
            $destination = preg_replace('/(?<!\$)\$S/i', $request->getScheme(), $destination);
            $destination = preg_replace('/(?<!\$)\$Q/i', $request->getQueryString(), $destination);

            if ($explain) {
                $placeholders['$Q'] = $request->getScheme();
                $placeholders['$S'] = $request->getQueryString();
            }
        }

        return !$explain ? $destination : array(
            'destination' => $destination,
            'hostnameGroups' => $hostnameGroups,
            'pathGroups' => $pathGroups,
            'placeholders' => $placeholders
        );
    }
}

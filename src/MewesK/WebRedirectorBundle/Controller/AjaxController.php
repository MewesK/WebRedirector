<?php

namespace MewesK\WebRedirectorBundle\Controller;

use MewesK\WebRedirectorBundle\Entity\RedirectRepository;
use MewesK\WebRedirectorBundle\Entity\Test;
use MewesK\WebRedirectorBundle\Form\TestType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MewesK\WebRedirectorBundle\Entity\Redirect;
use MewesK\WebRedirectorBundle\Form\RedirectType;

/**
 * Ajax controller.
 *
 * @Route("/admin")
 */
class AjaxController extends Controller
{
    /**
     * Displays a form to edit an existing Redirect entity.
     *
     * @Route("/{id}/position", name="admin_position")
     * @Method("POST")
     */
    public function positionAction(Request $request, $id)
    {
        if (!$request->request->has('position')) {
            return new JsonResponse(array(
                'error_code' => 400,
                'error_message' => '"position" parameter is not defined.'
            ));
        }

        $position = intval($request->request->get('position'));

        if ($position < 0) {
            return new JsonResponse(array(
                'error_code' => 400,
                'error_message' => '"position" parameter must be >= 0.'
            ));
        }

        $em = $this->getDoctrine()->getManager();

        /** @var $repository RedirectRepository */
        $repository = $em->getRepository('MewesKWebRedirectorBundle:Redirect');
        $nextPosition = $repository->getNextAvailablePosition();

        if ($position >= $nextPosition) {
            return new JsonResponse(array(
                'error_code' => 400,
                'error_message' => '"position" parameter must be <= '.($nextPosition - 1).'.'
            ));
        }

        /** @var $entity Redirect */
        $entity = $repository->find($id);

        if (!$entity) {
            return new JsonResponse(array(
                'error_code' => 404,
                'error_message' => 'Unable to find Redirect entity.'
            ));
        }

        $repository->setNewPosition($entity, $position);

        return new JsonResponse(true);
    }

    /**
     * Displays a form to edit an existing Redirect entity.
     *
     * @Route("/{id}/enable", name="admin_enable")
     * @Method("POST")
     */
    public function enableAction(Request $request, $id)
    {
        if (!$request->request->has('enabled')) {
            return new JsonResponse(array(
                'error_code' => 400,
                'error_message' => '"enabled" parameter is not defined.'
            ));
        }

        $enabled = $request->request->get('enabled') === 'true';

        $em = $this->getDoctrine()->getManager();

        /** @var $repository RedirectRepository */
        $repository = $em->getRepository('MewesKWebRedirectorBundle:Redirect');

        /** @var $entity Redirect */
        $entity = $repository->find($id);

        if (!$entity) {
            return new JsonResponse(array(
                'error_code' => 404,
                'error_message' => 'Unable to find Redirect entity.'
            ));
        }

        $entity->setEnabled($enabled);
        $em->persist($entity);
        $em->flush();

        return new JsonResponse(true);
    }
}

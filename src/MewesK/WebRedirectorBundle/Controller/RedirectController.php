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
 * Redirect controller.
 *
 * @Route("/admin")
 */
class RedirectController extends Controller
{
    /**
     * Lists all Redirect entities.
     *
     * @Route("/", name="admin")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entities = $this->getDoctrine()->getManager()->getRepository('MewesKWebRedirectorBundle:Redirect')->findAllOrderedByPosition();

        return array('entities' => $entities);
    }

    /**
     * Lists all Redirect entities.
     *
     * @Route("/redirects.{_format}", name="admin_export", requirements={"_format"="csv|xls|xlsx"})
     * @Method("GET")
     */
    public function exportAction()
    {
        $entities = $this->getDoctrine()->getManager()->getRepository('MewesKWebRedirectorBundle:Redirect')->findAllOrderedByPosition();

        return $this->render('MewesKWebRedirectorBundle:Redirect:index.excel.twig', array('entities' => $entities));
    }

    /**
     * Displays a form to create a new Redirect entity.
     *
     * @Route("/new", name="admin_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new Redirect();
        $form = $this->createForm(new RedirectType(), $entity);

        if ($request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $entity->setPosition($em->getRepository('MewesKWebRedirectorBundle:Redirect')->getNextAvailablePosition());

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin'));
            }
        }

        return array('entity' => $entity, 'form' => $form->createView(),);
    }

    /**
     * Finds and displays a Redirect entity.
     *
     * @Route("/{id}", name="admin_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getDoctrine()->getManager()->getRepository('MewesKWebRedirectorBundle:Redirect')->find($id);

        if (!$entity) {
            return $this->render('MewesKWebRedirectorBundle::error.html.twig', array(
                'error_code' => 404,
                'error_message' => 'Unable to find Redirect entity.'
            ));
        }

        return array('entity' => $entity);
    }

    /**
     * Displays a form to edit an existing Redirect entity.
     *
     * @Route("/{id}/edit", name="admin_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MewesKWebRedirectorBundle:Redirect')->find($id);

        if (!$entity) {
            return $this->render('MewesKWebRedirectorBundle::error.html.twig', array(
                'error_code' => 404,
                'error_message' => 'Unable to find Redirect entity.'
            ));
        }

        $form = $this->createForm(new RedirectType(), $entity);

        if ($request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('admin'));
            }
        }

        return array('entity' => $entity, 'form' => $form->createView());
    }

    /**
     * Deletes a Redirect entity.
     *
     * @Route("/{id}/delete", name="admin_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MewesKWebRedirectorBundle:Redirect')->find($id);

        if (!$entity) {
            return $this->render('MewesKWebRedirectorBundle::error.html.twig', array(
                'error_code' => 404,
                'error_message' => 'Unable to find Redirect entity.'
            ));
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin'));
    }

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
     * Finds and displays a Redirect entity.
     *
     * @Route("/{id}/test", name="admin_test")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function testAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MewesKWebRedirectorBundle:Redirect')->find($id);

        if (!$entity) {
            return $this->render('MewesKWebRedirectorBundle::error.html.twig', array(
                'error_code' => 404,
                'error_message' => 'Unable to find Redirect entity.'
            ));
        }

        $test = new Test();
        $test->setRedirect($entity);
        $form = $this->createForm(new TestType(), $test);

        if ($request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                // TODO: perform test

                return $this->render('MewesKWebRedirectorBundle:Redirect:testSuccess.html.twig', array(
                    'entity' => $entity
                ));
            }
        }

        return array('entity' => $entity, 'form' => $form->createView(),);
    }
}

<?php

namespace MewesK\WebRedirectorBundle\Controller;

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
        $entities = $this->getDoctrine()->getManager()->getRepository('MewesKWebRedirectorBundle:Redirect')->findAll();

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
        $entities = $this->getDoctrine()->getManager()->getRepository('MewesKWebRedirectorBundle:Redirect')->findAll();

        return $this->render('MewesKWebRedirectorBundle:Redirect:index.excel.twig', array('entities' => $entities));
    }

    /**
     * Displays a form to create a new Redirect entity.
     *
     * @Route("/new", name="admin_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction()
    {
        $entity = new Redirect();
        $form = $this->createForm(new RedirectType(), $entity);

        if ($this->get('request')->getMethod()) {
            $form->handleRequest($this->get('request'));

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
    public function editAction($id)
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

        if ($this->get('request')->getMethod()) {
            $form->handleRequest($this->get('request'));

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
    public function deleteAction(Request $request, $id)
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
    public function positionAction($id)
    {
        $request = $this->get('request');

        if (!$request->request->has('position')) {
            return new JsonResponse(array(
                'error_code' => 400,
                'error_message' => '"position" parameter is not defined.'
            ));
        }

        $position = intval($request->request->get('position'));

        $em = $this->getDoctrine()->getManager();

        /** @var $entity Redirect */
        $entity = $em->getRepository('MewesKWebRedirectorBundle:Redirect')->find($id);

        if (!$entity) {
            return new JsonResponse(array(
                'error_code' => 404,
                'error_message' => 'Unable to find Redirect entity.'
            ));
        }

        $entity->setPosition($position);
        $em->persist($entity);
        $em->flush();
    }
}

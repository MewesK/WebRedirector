<?php

namespace MewesK\WebRedirectorBundle\Controller;

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
            throw $this->createNotFoundException('Unable to find Redirect entity.');
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
            throw $this->createNotFoundException('Unable to find Redirect entity.');
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
            throw $this->createNotFoundException('Unable to find Redirect entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin'));
    }
}

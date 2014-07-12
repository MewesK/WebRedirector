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
 * Test controller.
 *
 * @Route("/admin")
 */
class TestController extends Controller
{
    /**
     * Finds and displays a Redirect entity.
     *
     * @Route("/{id}/test", name="admin_test")
     * @Method({"GET","POST"})
     * @Template("MewesKWebRedirectorBundle:Redirect:test.html.twig")
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

        $test = new Test($entity);
        $form = $this->createForm('mewesk_webredirectorbundle_test', $test);
        $result = null;

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $result = DefaultController::performRedirectTranslation(Request::create($test->getUrl()), $test);
            }
        }

        return array(
            'entity' => $test,
            'form' => $form->createView(),
            'result' => $result
        );
    }
    /**
     * Finds and displays a Redirect entity.
     *
     * @Route("/test", name="admin_test_unsaved")
     * @Method("POST")
     * @Template("MewesKWebRedirectorBundle:Redirect:test.html.twig")
     */
    public function testUnsavedAction(Request $request)
    {
        $test = new Test(null);
        $form = $this->createForm('mewesk_webredirectorbundle_test', $test, array('csrf_protection' => false));
        $result = null;

        $form->handleRequest($request);

        if ($test->getUrl() && $form->isValid()) {
            $result = DefaultController::performRedirectTranslation(Request::create($test->getUrl()), $test);
        }

        return array(
            'entity' => $test,
            'form' => $form->createView(),
            'result' => $result
        );
    }
}

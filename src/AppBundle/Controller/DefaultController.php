<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Default controller.
 *

 */
class DefaultController extends Controller
{
    /**
     *
     *

     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render("base.html.twig");
    }
}

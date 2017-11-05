<?php

namespace Gibz\ElectricBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GibzElectricBundle:Default:index.html.twig');
    }
}

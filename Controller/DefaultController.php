<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('cmsspaBundle:Default:index.html.twig', array(
             'name' => $name,
         ));
    }
}

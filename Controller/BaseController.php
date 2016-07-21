<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected $dbNew = 'linuxPl';
    protected $dbOld = 'ogicom';
    protected $product = array();
    protected $handler = array();

    protected function getDbHandlers(){
	  $this->handler = array(
		'emNew' => $this->getDoctrine()
		      ->getManager($this->dbNew), 
		'emOld' => $this->getDoctrine()
		      ->getManager($this->dbOld)
	  );
    }
    
    protected function printJson($data) {
          $response = new Response();
	  $response->setContent(json_encode($data));
	  $response->headers->set('Content-Type', 'application/json');
	  return $response;
    }
}

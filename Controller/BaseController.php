<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use cms\spaBundle\Entity\ProductHistory;

class BaseController extends Controller
{
    protected $dbNew = 'linuxPl';
    protected $dbOld = 'ogicom';
    protected $product = array();
    protected $handler = array();
    protected $secondDatabase;

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
    
    protected function renderEmail($data, $path) {
	   return $this->render(
		    $path,
		    array('data' => $data)
	   );
    }
    
    protected function sendEmail() {
	  $headers = 'MIME-Version: 1.0' . "\r\n";
	  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	  //$recipient = $this->order['customer']['email'];
	  $recipient = 'ad9bis@gmail.com';
	  if (mail($recipient, $this->order['title'], $this->order['message'], $headers)){
		$result = array('success' => true);
	  } else {
		$result = array('success' => false);
	  }
	  return $result;
    }
    
    protected function setCustomer($id, $origin) {
	    $customer = $this->handler[$origin]
		  ->getRepository('cmsspaBundle:Customer')
		  ->find($id);
            if ($customer == null) {
		  throw $this->createNotFoundException(
		      'No customer with ID:  '.$id
		  );
            }
	    $this->order['customer']['firstname'] = $customer->getFirstname();
	    $this->order['customer']['lastname'] = $customer->getLastname();
	    $this->order['customer']['email'] = $customer->getEmail();
    }
    
    protected function setProductHistory($id, $attribute, $quantity) {
	  $history = new ProductHistory();
	  $history->setProductId($id);
	  $history->setAttributeId($attribute);
	  $history->setQuantity($quantity);
	  $history->setDate(new \DateTime());
	  $history->setUser('test');
	  $history->setBaseOrigin($this->secondDatabase == 'emNew' ? 1 : 0);
	  $em = $this->getDoctrine()->getManager('linuxPl');
	  $em->persist($history);
	  $em->flush();
    }
    
}

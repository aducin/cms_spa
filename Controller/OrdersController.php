<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Mapping as ORM;


class OrdersController extends BaseController
{

    public function detailsByIdAction($origin, $id) {
	  $origin = 'em'.ucfirst($origin);
	  $origin == 'emNew' ? $secondDb = 'emOld' : $secondDb = 'emNew';
	  $this->getDbHandlers();
	  $data = $this->handler[$origin]
		->getRepository('cmsspaBundle:Orders')
		->findCustomerId($id);
	  $this->order['customer']['id'] = $data[0]['customerId'];
	  $this->order['reference'] = $data[0]['reference'];
	  $customer = $this->handler[$origin]
	        ->getRepository('cmsspaBundle:Customer')
	        ->find($this->order['customer']['id']);
	  $this->order['customer']['firstname'] = $customer->getFirstname();
	  $this->order['customer']['lastname'] = $customer->getLastname();
	  $this->order['customer']['email'] = $customer->getEmail();
	  $this->order['cartDetails'] = $this->handler[$origin]
		->getRepository('cmsspaBundle:OrderDetail')
		->findOrderById($id);
	  $counter = 0;
	  foreach ($this->order['cartDetails'] as $single) {
		$this->order['cartDetails'][$counter]['quantity']['current'] = $this->handler[$origin]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->find($single['productId'])
		      ->getQuantity();
		$this->order['cartDetails'][$counter]['quantity']['toUpdate'] = $this->handler[$secondDb]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->find($single['productId'])
		      ->getQuantity();
		$counter++ ;
	  }
	  $response = $this->printJson($this->order);
          return $response;
    }
    
        private function printJson($data) {
          $response = new Response();
	  $response->setContent(json_encode($data));
	  $response->headers->set('Content-Type', 'application/json');
	  return $response;
    }

}
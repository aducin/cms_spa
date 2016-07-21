<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Mapping as ORM;


class OrdersController extends Controller
{
    private $dbNew = 'linuxPl';
    private $dbOld = 'ogicom';
    private $order = array();
    private $handler = array();

    private function getDbHandlers(){
	  $this->handler = array(
		'emNew' => $this->getDoctrine()
		      ->getManager($this->dbNew), 
		'emOld' => $this->getDoctrine()
		      ->getManager($this->dbOld)
	  );
    }

    public function detailsByIdAction($origin, $id) {
	  $origin = 'em'.ucfirst($origin);
	  $origin == 'emNew' ? $secondDb = 'emOld' : $secondDb = 'emNew';
	  $this->getDbHandlers();
	  $this->order['customer'] = $this->handler[$origin]
		->getRepository('cmsspaBundle:Orders')
		->findCustomerId($id);
	  $this->order['details'] = $this->handler[$origin]
		->getRepository('cmsspaBundle:OrderDetail')
		->findOrderById($id);
	  $counter = 0;
	  foreach ($this->order['details'] as $single) {
		$this->order['details'][$counter]['quantity']['current'] = $this->handler[$origin]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->find($single['productId'])
		      ->getQuantity();
		$this->order['details'][$counter]['quantity']['toUpdate'] = $this->handler[$secondDb]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->find($single['productId'])
		      ->getQuantity();
		$counter++ ;
	  }
	  var_dump($this->order); exit();
    }

}
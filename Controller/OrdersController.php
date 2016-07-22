<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Mapping as ORM;


class OrdersController extends BaseController
{

    private $dataBase;
    private $secondDatabase;

    public function detailsByIdAction($origin, $id) {
	  $this->setDatabase($origin);
	  $data = $this->handler[$this->dataBase]
		->getRepository('cmsspaBundle:Orders')
		->findCustomerId($id);
          if(empty($data[0])) {
		throw $this->createNotFoundException(
		      'There is no order with ID:  '.$id
	        );
	  }
	  $this->order['customer']['id'] = $data[0]['customerId'];
	  if (isset($_GET['basic']) && $_GET['basic'] == true) {
		$response = $this->printJson($this->order);
		return $response;
	  }
	  $this->order['reference'] = $data[0]['reference'];
	  $this->order['totalPaid'] = $data[0]['totalPaid'];
	  $this->order['totalProduct'] = $data[0]['totalProduct'];
	  $this->setCustomer($this->order['customer']['id'], $this->dataBase);
	  $this->order['cartDetails'] = $this->handler[$this->dataBase]
		->getRepository('cmsspaBundle:OrderDetail')
		->findOrderById($id);
	  $counter = 0;
	  foreach ($this->order['cartDetails'] as $single) {
		$this->order['cartDetails'][$counter]['quantity']['current'] = $this->handler[$this->dataBase]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->find($single['productId'])
		      ->getQuantity();
		$this->order['cartDetails'][$counter]['quantity']['toUpdate'] = $this->handler[$this->secondDatabase]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->find($single['productId'])
		      ->getQuantity();
		$counter++ ;
	  }
	  $response = $this->printJson($this->order);
          return $response;
    }
    
    public function evenQuantityByIdAction($origin, $id) {
	  $this->setDatabase($origin);
	  $this->order['cartDetails'] = $this->handler[$this->dataBase]
		->getRepository('cmsspaBundle:OrderDetail')
		->findOrderById($id);
          foreach ($this->order['cartDetails'] as $single) {
		if ($single["attributeId"] == 0) {
		      $currentQuantityToUpdate = $this->handler[$this->dataBase]
			    ->getRepository('cmsspaBundle:StockAvailable')
			    ->find($single['productId'])
			    ->getQuantity();
	              $productToUpdate = $this->handler[$this->secondDatabase]
			    ->getRepository('cmsspaBundle:StockAvailable')
			    ->find($single['productId']);
		      $productToUpdate->setQuantity(intval($currentQuantityToUpdate));
		      try {
			    $this->handler[$this->secondDatabase]->persist($productToUpdate);
			    $this->handler[$this->secondDatabase]->flush();
			    $result = array('success' => true);
		      } catch (\Exception $e) {
			    $response = $this->printJson($result);
			    return $response;
		      }
		} else {
		      $result = array('success' => 'attribute`s handling not prepared yet');
		}
          }
	  $response = $this->printJson($result);
	  return $response;
    }
    
    private function setDatabase($origin) {
	  $this->dataBase = 'em'.ucfirst($origin);
	  $this->dataBase == 'emNew' ? $this->secondDatabase = 'emOld' : $this->secondDatabase = 'emNew';
	  $this->getDbHandlers();
    }
    
    /*
    public function historyByIdAction($id) {
	  $this->getDbHandlers();
	  $data = $this->handler['emOld']
		->getRepository('cmsspaBundle:Orders')
		->findCustomerId($id);
	  if(empty($data[0])) {
		throw $this->createNotFoundException(
		      'There is no order with ID:  '.$id
	        );
	  }
          $this->setCustomer($data[0]["customerId"], 'emOld');
          $secondShopActivity = $this->handler['emNew']
		->getRepository('cmsspaBundle:Customer')
		->findCustomerByEmail($this->order['customer']['email']);
	  $secondShopActivity != false ? $this->order['customer']['secondShopctivity'] = true : $this->order['customer']['secondShopctivity'] = false;
	  $orders = $this->handler['emOld']
		->getRepository('cmsspaBundle:Orders')
		->findOrdersByCustomer($data[0]["customerId"]);
	  $counter = 0;
	  $innerCounter = 1;
	  foreach ($orders as $single) {
		if (floatval($single["totalProduct"]) >= 50) {
		      $this->order['data'][$counter]["id"] = $single["id"];
		      $this->order['data'][$counter]["reference"] = $single["reference"];
		      $this->order['data'][$counter]["totalProduct"] = $single["totalProduct"];
		      $this->order['data'][$counter]["totalShipping"] = $single["totalShipping"];
		      $this->order['data'][$counter]["dateAdd"] = $single["dateAdd"];
		      $this->order['data'][$counter]["ordNumber"] = $innerCounter;
		      $counter++;
		      if ( $innerCounter == 5 ) {
			  $innerCounter = 'Rabat!';
		      } elseif ( $innerCounter == 'Rabat!' ) {
			  $innerCounter = 1;
		      } else {
			  $innerCounter++;
		      }
		}
	  }
	  $this->order['lastVoucher'] = $innerCounter - 1;
	  $response = $this->printJson($this->order);
          return $response;
    }
    */

}
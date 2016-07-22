<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Mapping as ORM;


class CustomersController extends BaseController
{

    public function vouchersByIdAction($origin, $id) {
	  $this->getDbHandlers();
	  $origin = 'em'.ucfirst($origin);
	  $origin == 'emNew' ? $secondDb = 'emOld' : $secondDb = 'emNew';
	  $this->setCustomer($id, $origin);
	  $secondShopActivity = $this->handler[$secondDb]
		->getRepository('cmsspaBundle:Customer')
		->findCustomerByEmail($this->order['customer']['email']);
	  $secondShopActivity != false ? $this->order['customer']['secondShopctivity'] = true : $this->order['customer']['secondShopctivity'] = false;
	  $orders = $this->handler['emOld']
		->getRepository('cmsspaBundle:Orders')
		->findOrdersByCustomer($id);
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

}
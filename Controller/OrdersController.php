<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use cms\spaBundle\Entity\ProductHistory;
use Doctrine\ORM\Mapping as ORM;


class OrdersController extends BaseController
{

    private $dataBase;

    public function detailsByIdAction($origin, $id, $email = null) {
	  $this->setDatabase($origin);
	  $data = $this->handler[$this->dataBase]
		->getRepository('cmsspaBundle:Orders')
		->findCustomerId($id);
          if(empty($data[0])) {
		$result = array('success' => false, 'reason' => 'no order with such id');
		$response = $this->printJson($result);
		return $response;
	  }
	  $this->order['customer']['id'] = $data[0]['customerId'];
	  if (isset($_GET['basic']) && $_GET['basic'] == true) {
		$response = $this->printJson($this->order);
		return $response;
	  }
	  $this->order['reference'] = $data[0]['reference'];
	  $this->order['totalPaid'] = $data[0]['totalPaid'];
	  $this->order['totalProduct'] = $data[0]['totalProduct'];
	  $this->order['totalShipment'] = number_format(($data[0]['totalPaid'] - $data[0]['totalProduct']), 2, '.', '');
	  if (isset($_GET['action']) && $_GET['action'] == 'discount') {
		$amount = number_format((float) ($this->order['totalPaid'] * 0.15), 2, '.', '');
		if ($amount <= 50) {
		      $this->order['totalPaidDiscount'] = number_format((float) ($this->order['totalPaid'] * 0.85), 2, '.', '');
		} else {
		      $this->order['totalPaidDiscount'] = number_format((float) ($this->order['totalPaid'] - 50), 2, '.', '');
		}
		$amount = number_format((float) ($this->order['totalPaid'] * 0.15), 2, '.', '');
		if ($amount <= 50) {
		      $this->order['totalProductDiscount'] = number_format((float) ($this->order['totalProduct'] * 0.85), 2, '.', '');
		} else {
		      $this->order['totalProductDiscount'] = number_format((float) ($this->order['totalProduct'] - 50), 2, '.', '');
		      $this->order['discountExtended'] = true;
		}
	  }
	  $data[0]['module'] === 'bankwire' ? $this->order['payment'] = 'przelew bankowy' : $this->order['payment'] = 'pobranie';
	  $this->setCustomer($this->order['customer']['id'], $this->dataBase);
	  $this->order['cartDetails'] = $this->handler[$this->dataBase]
		->getRepository('cmsspaBundle:OrderDetail')
		->findOrderById($id);
	  $counter = 0;
	  foreach ($this->order['cartDetails'] as $single) {
		$this->order['cartDetails'][$counter]['counter'] = $counter + 1;
			if ($single['reduction'] == '0.000000') {
				  $this->order['cartDetails'][$counter]['reduction'] = '---';
			} else {
				  $this->order['cartDetails'][$counter]['reduction'] = number_format((float) ($this
				  ->order['cartDetails'][$counter]['reduction']), 2, '.', '').' zł';
			}
		$this->order['cartDetails'][$counter]['totalPrice'] = number_format((float) $this
		      ->order['cartDetails'][$counter]['totalPrice'], 2, '.', '');
		$this->order['cartDetails'][$counter]['totalPriceDiscount'] = number_format((float) ($this
		      ->order['cartDetails'][$counter]['totalPrice'] * 0.85), 2, '.', '');
		$this->order['cartDetails'][$counter]['unitPrice'] = number_format((float) $this
		      ->order['cartDetails'][$counter]['unitPrice'], 2, '.', '');
		$this->order['cartDetails'][$counter]['unitPriceDiscount'] = number_format((float) ($this
		      ->order['cartDetails'][$counter]['unitPrice'] * 0.85), 2, '.', '');
		$this->order['cartDetails'][$counter]['linkRewrite'] = $this->handler[$this->dataBase]
		      ->getRepository('cmsspaBundle:ProductsLang')
		      ->find($single["productId"])->getLinkRewrite();
		$this->order['cartDetails'][$counter]['quantity']['current'] = $this->handler[$this->dataBase]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->getCurrentQuantity($single["productId"], $single["attributeId"]);
		$this->order['cartDetails'][$counter]['quantity']['toUpdate'] = $this->handler[$this->secondDatabase]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->getCurrentQuantity($single["productId"], $single["attributeId"]);
		$cover = $this->product['images'] = $this->handler['emOld']
			->getRepository('cmsspaBundle:Image')
			->findCoverImage($single["productId"]);
		$this->order['cartDetails'][$counter]['cover'] = 'http://modele-ad9bis.pl/img/p/'.$single["productId"].'-'.$cover.'-thickbox.jpg';
		$counter++ ;
	  }
	  if ($email === null) {
		$response = $this->printJson($this->order);
		return $response;
	  }
    }
    
    public function evenQuantityByIdAction($origin, $id) {
	  
	  $this->setDatabase($origin);
	  $this->order['cartDetails'] = $this->handler[$this->dataBase]
		->getRepository('cmsspaBundle:OrderDetail')
		->findOrderById($id);
	  $this->result = array();
	  $counter = 0;
          foreach ($this->order['cartDetails'] as $single) {
		if ($single['attributeId'] != 0) {
		      $attributeName = $this->handler[$this->dataBase]
			    ->getRepository('cmsspaBundle:ProductAttribute')
			    ->checkAttributeName($single['attributeId']);
		      $secondDbAttributes = $this->handler[$this->secondDatabase]
			    ->getRepository('cmsspaBundle:ProductAttribute')
			    ->checkProductAttribute($single["productId"]);
		      foreach ($secondDbAttributes as $attribute) {
			    if ($attribute["name"] == $attributeName) {
				  $attributeId = $attribute["productAttribute"];
			    }
		      }
		} else {
		      $attributeId = 0;
		}
		$this->result[$counter]['id'] = $single["productId"];
		$this->result[$counter]['attributeId'] = $attributeId;
		$cover = $this->product['images'] = $this->handler[$this->dataBase]
			->getRepository('cmsspaBundle:Image')
			->findCoverImage($single["productId"]);
		$this->result[$counter]['cover'] = 'http://modele-ad9bis.pl/img/p/'.$single["productId"].'-'.$cover.'-thickbox.jpg';
		$this->result[$counter]['linkRewrite'] = $this->handler[$this->dataBase]
		      ->getRepository('cmsspaBundle:ProductsLang')
		      ->find($single["productId"])->getLinkRewrite();
		$this->result[$counter]['name'] = $single["productName"];
		$this->result[$counter]['ordered'] = $single["productQuantity"];
		$this->result[$counter]['baseDbQuantity'] = $this->handler[$this->dataBase]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->getCurrentQuantity($single["productId"], $single["attributeId"]);
		$this->result[$counter]['quantityBeforeChange'] = $this->handler[$this->secondDatabase]
		      ->getRepository('cmsspaBundle:StockAvailable')
		      ->getCurrentQuantity($single["productId"], $attributeId);     
		try {
		      $this->handler[$this->secondDatabase]
			    ->getRepository('cmsspaBundle:StockAvailable')
			    ->evenQuantityAndAttribute($single["productId"], $attributeId, $this->result[$counter]['baseDbQuantity']);
		      $this->result[$counter]['quantityAfterChange'] = $this->handler[$this->secondDatabase]
			    ->getRepository('cmsspaBundle:StockAvailable')
			    ->getCurrentQuantity($single["productId"], $attributeId); 
		      if ($this->result[$counter]['quantityBeforeChange'] != $this->result[$counter]['quantityAfterChange']) {
			    $this->result[$counter]['modification'] = 'Zmieniono ilość';
			    $this->setProductHistory($single["productId"], $single["attributeId"], $this->result[$counter]['baseDbQuantity']);
		      } else {
			    $this->result[$counter]['modification'] = '---';
		      }
		      $this->result[$counter]['success'] = true;
		} catch (\Exception $e) {
	              $response = $this->printJson(array('success' => false));
		      return $response;
		}
		$counter++;
          }
	  $response = $this->printJson($this->result);
	  return $response;
    }
    
    public function orderEmailAction($origin, $id) {
	  $this->detailsByIdAction($origin, $id, true);
	  if (isset($_GET['action'])) {
		if ($_GET['action'] == 'undelivered') {
		      $path = 'cmsspaBundle:Mails:undelivered.html.twig';
		      $this->order['title'] = 'Modele-ad9bis.pl - powtórne potwierdzenie zamówienia';
		} elseif ($_GET['action'] == 'deliveryNumber') {
		      if (isset($_GET['deliveryNumber'])) {
			    $this->order['deliveryNumber'] = $_GET['deliveryNumber'];
		      }
		      $path = 'cmsspaBundle:Mails:deliveryNumber.html.twig';
		      $this->order['title'] = 'Modele-ad9bis.pl - Twoja przesyłka została wysłana';
		} elseif ($_GET['action'] == 'discount') {
		      $path = 'cmsspaBundle:Mails:discount.html.twig';
		      $this->order['title'] = 'Modele-ad9bis.pl - 15% rabat!';
		} elseif ($_GET['action'] == 'voucher') {
		      if (isset($_GET['voucherNumber'])) {
			   $this->order['voucherNumber'] = intval($_GET['voucherNumber']);
		      }
		      $path = 'cmsspaBundle:Mails:voucher.html.twig';
		      $this->order['title'] = 'Modele-ad9bis.pl - informacja o przyznanym kuponie';
		}
		$message = $this->renderEmail($this->order, $path);
		$this->order['message'] = $message->getContent();
		if ($_GET['result'] == 'send') {
		      $result = $this->sendEmail();
		      $result['success'] === true ? $result['reason'] = 'Powtórne powiadomienie o zamówieniu zostało 			skutecznie wysłane!' : $result['reason'] = 'Nie udało się wysłać powtórnego powiadomienia!';
		} elseif ($_GET['result'] == 'display') {
		      echo $this->order['message'];
		      exit();
		}
		$response = $this->printJson($result);
		return $response;
	  }
	  
    }
    
    private function setDatabase($origin) {
	  $this->dataBase = 'em'.ucfirst($origin);
	  $this->dataBase == 'emNew' ? $this->secondDatabase = 'emOld' : $this->secondDatabase = 'emNew';
	  $this->getDbHandlers();
    }

}
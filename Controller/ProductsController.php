<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use cms\spaBundle\Entity\ProductHistory;
use Doctrine\ORM\Mapping as ORM;


class ProductsController extends BaseController
{
    
    /*
    public function detailsByIdAction($id) // elder version for short search
    {
	  $this->getDbHandlers();
	  $this->product['id'] = $id;
          $this->product['name'] = $this->handler['emNew']
		->getRepository('cmsspaBundle:Products')
		->findNameById($id);
          
          if (!$this->product['name']) {
	    throw $this->createNotFoundException(
		'There is no product with ID:  '.$id
	    );
	  } else {
		$this->getPrices();
		$this->getQuantities();
		$response = $this->printJson($this->product);
		return $response;
          }
    }
    */
    
    public function detailsByIdAction($id, $attribute)
    {
	$this->getDbHandlers();
	$product = $this->handler['emNew']
	      ->getRepository('cmsspaBundle:ProductsLang')
	      ->find($id);

        if (!$product) {
	      throw $this->createNotFoundException(
		  'There is no product with ID:  '.$id
	      );
	} else {
	    $this->product['id'] = intval($id);
	    if ($attribute == 0) {
		  $this->product['attribute']['new'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:ProductAttribute')
			->checkProductAttribute($id);
		  if (!empty($this->product['attribute']['new'])) {
			$this->product['attribute']['old'] = $this->handler['emOld']
			      ->getRepository('cmsspaBundle:ProductAttribute')
			      ->checkProductAttribute($id);
			$result = array(
			      'success' => false, 
			      'reason' => 'attribute needed', 
			      'dataNew' => $this->product['attribute']['new'],
			      'dataOld' => $this->product['attribute']['old']
			);
			$response = $this->printJson($result);
			return $response;
		  } else {
			$this->product['attribute'] = array ('new' => 0, 'old' => 0);
		  }
	    } else {
		  $explode = explode('-', $attribute);
		  $this->product['attribute'] = array ('new' => $explode[0], 'old' => $explode[1]);
		  $this->product['attribute']['newName'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:ProductAttribute')
			->checkAttributeName($explode[0]);
		  $this->product['attribute']['oldName'] = $this->handler['emOld']
			->getRepository('cmsspaBundle:ProductAttribute')
			->checkAttributeName($explode[1]);
	    }
	    $this->getPrices();
	    $this->getQuantities();
	    if (isset($_GET['basic']) && $_GET['basic'] == true) {
		  $this->product['name'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:Products')
			->findNameById($id);
		  $response = $this->printJson($this->product);
		  return $response;
		  //return $this->render('cmsspaBundle:Products:detailsId.html.twig', array(
		  //    'product' => $this->product
		  //));
	    } else {
		  $additionalDetails = $this->handler['emNew']
			->getRepository('cmsspaBundle:ProductsShop')
			->find($id);
		  $this->setProductDates($product, $additionalDetails);
		  $response = $this->printJson($this->product);
		  return $response;
		  //return $this->render('cmsspaBundle:Products:detailsFullEdition.html.twig', array(
		  //      'product' => $this->product
		  //));
	    }
        }
    }
    
    public function detailsByNameAction() {
	  if (isset($_GET['search'])) {
		$name = $_GET['search'];
	  } else {
		$name = '';
	  }
	  
	  $this->getDbHandlers();
          $this->product = $this->handler['emNew']
		->getRepository('cmsspaBundle:Products')
		->findByNamePart($name);

          if (!$this->product) {
	    throw $this->createNotFoundException(
		'No product with phrase:  '.$name
	    );
	  } else {
		$response = $this->printJson($this->product);
		return $response;
		//return $this->render('cmsspaBundle:Products:detailsName.html.twig', array(
		//    'products' => $products,
		//    'name' => $name,
		//));
          }
    }
    
    public function getCategoriesAction() {
	  $this->getDbHandlers();
	  $categories = $this->handler['emNew']
		  ->getRepository('cmsspaBundle:CategoryLang')
		  ->findAllNotEmptyCategories();
	  $response = $this->printJson($categories);
	  return $response;
    }
    
    public function getManufacturersAction() {
	  $this->getDbHandlers();
	  $manufacturers = $this->handler['emNew']
		  ->getRepository('cmsspaBundle:Manufacturer')
		  ->findAllNotEmptyManufacturers();
	  $response = $this->printJson($manufacturers);
	  return $response;
    }
    
    private function getPrices() {
	  $this->product['price']['new'] = $this->handler['emNew']
		->getRepository('cmsspaBundle:ProductsShop')
		->find($this->product['id'])
		->getPrice();
	  $this->product['price']['old'] = $this->handler['emOld']
	        ->getRepository('cmsspaBundle:ProductsShop')
		->find($this->product['id'])
		->getPrice();
	  $this->product['discount']['new'] = $this->handler['emNew']
	        ->getRepository('cmsspaBundle:SpecificPrice')
		->findByIdProduct($this->product['id']); 
	  if ($this->product['discount']['new'] !== false) {
		  $this->setRealPrice('new');
	  } 
	  $this->product['discount']['old'] = $this->handler['emOld']
	        ->getRepository('cmsspaBundle:SpecificPrice')
		->findByIdProduct($this->product['id']); 
	  if ($this->product['discount']['old'] !== false) {
		  $this->setRealPrice('old');
	  }
    }
    
    private function getQuantities() {
	  $this->product['quantity']['new'] = $this->handler['emNew']
		->getRepository('cmsspaBundle:StockAvailable')
		->getCurrentQuantity($this->product['id'], $this->product['attribute']['new']);
	  $this->product['quantity']['old'] = $this->handler['emOld']
		->getRepository('cmsspaBundle:StockAvailable')
		->getCurrentQuantity($this->product['id'], $this->product['attribute']['old']);
    }
    
    private function setProductDates($product, $additionalDetails) {
	  $this->product['name'] = $product->getName();
          $this->product['description'] = $product->removeHtmlWhitespace($product->getDescription());
	  $this->product['descriptionShort'] = $product->removeHtmlWhitespace($product->getDescriptionShort());
	  $this->product['metaDescription'] = $product->getMetaDescription();
	  $this->product['metaTitle'] = $product->getMetaTitle();
	  $this->product['linkRewrite'] = $product->getLinkRewrite();
	  $this->product['condition'] = $additionalDetails->getCondition();
	  $this->product['active'] = $additionalDetails->getActive();
	  $this->product['manufacturer'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:Products')
			->find($this->product['id'])
			->getIdManufacturer();
          $this->product['productCategories'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:CategoryProduct')
			->findProductCategories($product->getIdProduct());
	  $this->product['productTags'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:ProductTag')
			->findTagList($product->getIdProduct());
		  //$this->product['tagString'] = $this->product['productTags']['tagString'];
	  unset($this->product['productTags']['tagString']);
	  $this->product['categories'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:CategoryLang')
			->findAllNotEmptyCategories();
	  $this->product['manufacturers'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:Manufacturer')
			->findAllNotEmptyManufacturers();
    }
    
    private function setRealPrice($origin) {
	  if ($this->product['discount'][$origin]['reductionType'] == 'percentage') {
		  $discount = $this->product['price'][$origin] * $this->product['discount'][$origin]['reduction'];
		  $this->product['priceReal'][$origin] = $this->product['price'][$origin] - $discount;
	  } elseif ($this->product['discount'][$origin]['reductionType'] == 'amount') {
		  $this->product['priceReal'][$origin] = $this->product['price'][$origin] - $this->product['discount'][$origin]['reduction'];
	  }
    }
    
    public function singleUpdateAction($id, $attribute) {
	  if ($_POST['db'] !== 'both') {
		$this->handler = array(
		      $this->getDoctrine()
		      ->getManager($_POST['db'])
		);
		$arrayAttribute = array($attribute);
	  } else {
		$this->getDbHandlers();
		$explode = explode('-', $attribute);
		$arrayAttribute = array($explode[0], $explode[1]);
	  }
	  $counter = 0;
	  foreach ($this->handler as $single) {
		if (isset($_POST['price'])) {
		      $product = $single
			    ->getRepository('cmsspaBundle:Products')
			    ->find($id);
		      $product->setPrice(floatval($_POST["price"]));
		      $productShop = $single
			    ->getRepository('cmsspaBundle:ProductsShop')
			    ->find($id);
		      $productShop->setPrice(floatval($_POST["price"]));
		} elseif (isset($_POST['quantity'])) {
		      if ($_POST['db'] !== 'both') {
			    $this->secondDatabase = $_POST['db'] == 'linuxPl' ? 0 : 1;
		      } else {
			    $this->secondDatabase = $counter == 0 ? 1 : 0;
		      }
		      $product = $single
			    ->getRepository('cmsspaBundle:StockAvailable')
			    ->evenQuantityAndAttribute($id, $arrayAttribute[$counter], $_POST['quantity']);
		}
		$counter++;
		try {
		      $single->persist($product);
		      if (isset($productShop)) {
			    $single->persist($productShop);
		      }
		      $single->flush();
		      $result = array('success' => true);
		} catch (\Exception $e) {
		      $response = $this->printJson($result);
		      return $response;
		}
	  }
	  $response = $this->printJson($result);
	  return $response;
    }
}
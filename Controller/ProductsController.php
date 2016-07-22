<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
    
    public function detailsByIdAction($id)
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
		->find($this->product['id'])
		->getQuantity();
	  $this->product['quantity']['old'] = $this->handler['emOld']
		->getRepository('cmsspaBundle:StockAvailable')
		->find($this->product['id'])
		->getQuantity();
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
    
    public function singleUpdateAction($id) {
	  if ($_POST['db'] !== 'both') {
		$this->handler = array(
		      $this->getDoctrine()
		      ->getManager($_POST['db'])
		);
	  } else {
		$this->getDbHandlers();
	  }
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
		      $product = $single
			    ->getRepository('cmsspaBundle:StockAvailable')
			    ->find($id);
		      $product->setQuantity(strip_tags($_POST['quantity']));
		}
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
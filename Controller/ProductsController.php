<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Mapping as ORM;


class ProductsController extends Controller
{
    private $dbNew = 'linuxPl';
    private $dbOld = 'ogicom';
    private $product = array();
    
    private function dbHandler(){
    
	  $this->handler = array(
		'emNew' => $this->getDoctrine()->getManager($this->dbNew), 
		'emOld' => $this->getDoctrine()->getManager($this->dbOld)
	  );
    }
    
    public function detailsByIdAction($id)
    {
    
	  $this->dbHandler();
	  $this->product['id'] = $id;
          $this->product['name'] = $this->handler['emNew']->getRepository('cmsspaBundle:Products')->findNameById($id);
          
          if (!$this->product['name']) {
	    throw $this->createNotFoundException(
		'There is no product with ID:  '.$id
	    );
	  } else {
		$this->getPrices();
		$this->getQuantities();
		$this->printJson($this->product);
		//return $this->render('cmsspaBundle:Products:detailsId.html.twig', array(
		//    'product' => $this->product
		//));
          }
    }
    
        public function detailsByIdFullEditionAction($id)
    {
    
	$this->dbHandler();
	$product = $this->handler['emNew']->getRepository('cmsspaBundle:ProductsLang')->find($id);

        if (!$product) {
	      throw $this->createNotFoundException(
		  'There is no product with ID:  '.$id
	      );
	} else {
	    $this->product['id'] = $product->getIdProduct();
	    $this->product['name'] = $product->getName();
	    $this->product['description'] = $product->removeHtmlWhitespace($product->getDescription());
	    $this->product['descriptionShort'] = $product->removeHtmlWhitespace($product->getDescriptionShort());
	    $this->product['metaDescription'] = $product->getMetaDescription();
	    $this->product['metaTitle'] = $product->getMetaTitle();
	    $this->product['linkRewrite'] = $product->getLinkRewrite();
	    $this->product['manufacturer'] = $this->handler['emNew']->getRepository('cmsspaBundle:Products', $this->dbNew)
		  ->find($id)->getIdManufacturer();
	    $additionalDetails = $this->handler['emNew']->getRepository('cmsspaBundle:ProductsShop')->find($id);
	    $this->getPrices();
	    $this->getQuantities();
            $this->product['condition'] = $additionalDetails->getCondition();
	    $this->product['active'] = $additionalDetails->getActive();
	    $this->product['productCategories'] = $this->handler['emNew']->getRepository('cmsspaBundle:CategoryProduct')
		  ->findProductCategories($product->getIdProduct());
	    $this->product['productTags'] = $this->handler['emNew']->getRepository('cmsspaBundle:ProductTag')
		  ->findTagList($product->getIdProduct());
	    //$this->product['tagString'] = $this->product['productTags']['tagString'];
	    unset($this->product['productTags']['tagString']);
	    $this->product['categories'] = $this->getCategoriesAction(false);
	    $this->product['manufacturers'] = $this->getManufacturersAction(false);
	    $this->printJson($this->product);
	    //return $this->render('cmsspaBundle:Products:detailsFullEdition.html.twig', array(
            //      'product' => $this->product
	    //));
        }
    }
    
    public function detailsByNameAction() {
    
	  if (isset($_GET['search'])) {
		$name = $_GET['search'];
	  } else {
		$name = '';
	  }
	  
	  $this->dbHandler();
          $products = $this->handler['emNew']->getRepository('cmsspaBundle:Products')->findByNamePart($name);

          if (!$products) {
	    throw $this->createNotFoundException(
		'No product with phrase:  '.$name
	    );
	  } else {
		$result = array('products' => $products, 'name' => $name);
		$this->printJson($result);
		//return $this->render('cmsspaBundle:Products:detailsName.html.twig', array(
		//    'products' => $products,
		//    'name' => $name,
		//));
          }
    }
    
    public function getCategoriesAction($json = true) {
    
	  $categories = $this->getDoctrine()->getRepository('cmsspaBundle:CategoryLang')->findAll();
	  $categoryList = array();
	  $counter = 0;
	  $emptyCats = array(1, 10, 14, 15, 19, 20, 22, 23, 24, 25, 26, 27, 32, 33);
	  foreach($categories as $single) {
	      if (!in_array($single->getIdCategory(), $emptyCats)) {
		    $categoryList[$counter]['id'] = $single->getIdCategory();
		    $categoryList[$counter]['meta_title'] = $single->getMetaTitle();
		    $counter++;
	      }
	  }
	  if ($json === true) {
		$this->printJson($categoryList);
	  } else {
		return $categoryList;
	  }
    }
    
    private function getPrices() {
    
	  $this->product['price']['new'] = $this->handler['emNew']->getRepository('cmsspaBundle:ProductsShop')
		  ->find($this->product['id'])->getPrice();
	  $this->product['price']['old'] = $this->handler['emOld']->getRepository('cmsspaBundle:ProductsShop')
		  ->find($this->product['id'])->getPrice();
	  $this->product['discount']['new'] = $this->handler['emNew']->getRepository('cmsspaBundle:SpecificPrice')
		  ->findByIdProduct($this->product['id']); 
	  if ($this->product['discount']['new'] !== false) {
		  $this->product['priceReal']['new'] = $this->setRealPrice('new');
	  } 
	  $this->product['discount']['old'] = $this->handler['emOld']->getRepository('cmsspaBundle:SpecificPrice')
		  ->findByIdProduct($this->product['id']); 
	  if ($this->product['discount']['old'] !== false) {
		  $this->product['priceReal']['old'] = $this->setRealPrice('old');
	  }
    }
    
    private function getQuantities() {
    
	  $this->product['quantity']['new'] = $this->handler['emNew']->getRepository('cmsspaBundle:StockAvailable')
		->find($this->product['id'])->getQuantity();
	  $this->product['quantity']['old'] = $this->handler['emOld']->getRepository('cmsspaBundle:StockAvailable')
		->find($this->product['id'])->getQuantity();
    }
    
    public function getManufacturersAction($json = true) {
    
	  $manufacturers = $this->getDoctrine()->getRepository('cmsspaBundle:Manufacturer')->findAll();
	  $manufacturerList = array();
	  $counter = 0;
	  foreach($manufacturers as $single) {
		if ($single->getName() != 'pusty') {
		      $manufacturerList[$counter]['id'] = $single->getIdManufacturer();
		      $manufacturerList[$counter]['name'] = $single->getName();
		      $counter++;
		}
	  }
	  if ($json === true) {
		$this->printJson($manufacturerList); 
	  } else {
		return $manufacturerList;
	  }
    }
    
    private function printJson($data) {
    
	  header('Content-Type: application/json');
	  echo json_encode($data); 
	  exit();
    }
    
    private function setRealPrice($origin) {

	  $product = $this->product;
	  if ($product['discount'][$origin]['reduction_type'] == 'percentage') {
		  $discount = $product['price'][$origin] * $product['discount'][$origin]['reduction'];
		  $priceReal = $product['price'][$origin] - $discount;
	  } elseif ($product['discount'][$origin]['reduction_type'] == 'amount') {
		  $priceReal = $product['price'][$origin] - $product['discount'][$origin]['reduction'];
	  }
	  return $priceReal;
    }
    
    public function singleUpdateAction($id) {

	  $em = array();
	  if ($_POST['db'] !== 'both') {
		$em[0] = $this->getDoctrine()->getManager($_POST['db']);
	  } else {
		$em[0] = $this->getDoctrine()->getManager($this->dbNew);
		$em[1] = $this->getDoctrine()->getManager($this->dbOld);
	  }
	  foreach ($em as $single) {
		if (isset($_POST['price'])) {
		      $product = $single->getRepository('cmsspaBundle:Products')->find($id);
		      $product->setPrice(floatval($_POST["price"]));
		      $productShop = $single->getRepository('cmsspaBundle:ProductsShop')->find($id);
		      $productShop->setPrice(floatval($_POST["price"]));
		} elseif (isset($_POST['quantity'])) {
		      $product = $single->getRepository('cmsspaBundle:StockAvailable')->find($id);
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
		      $result = array('success' => false);
		      $this->printJson($result); 
		}
	  }
	  $this->printJson($result); 
    }
}
<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Mapping as ORM;


class ProductsController extends Controller
{
    private $dbNew = 'linuxPl';
    private $dbOld = 'ogicom';
    
    public function detailsByIdAction($id)
    {

	  $emNew = $this->getDoctrine()->getManager($this->dbNew);
	  $emOld = $this->getDoctrine()->getManager($this->dbOld);
          $product = $emNew->getRepository('cmsspaBundle:Products')->findNameById($id);

          if (!$product) {
	    throw $this->createNotFoundException(
		'There is no product with ID:  '.$id
	    );
	  } else {
		$product = $product[0];
		$product['id'] = $id;
		$product['price']['new'] = $emNew->getRepository('cmsspaBundle:ProductsShop')
		      ->find($id)->getPrice();
		$product['price']['old'] = $emOld->getRepository('cmsspaBundle:ProductsShop')
		      ->find($id)->getPrice();
		$product['quantity']['new'] = $this->getSingleQuantity($this->dbNew, $id);
		$product['quantity']['old'] = $this->getSingleQuantity($this->dbOld, $id);
		//echo json_encode($product); exit();
		return $this->render('cmsspaBundle:Products:detailsId.html.twig', array(
		    'product' => $product
		));
          }
    }
    
        public function detailsByIdFullEditionAction($id)
    {
    
	$emNew = $this->getDoctrine()->getManager($this->dbNew);
	$emOld = $this->getDoctrine()->getManager($this->dbOld);
	$product = $emNew->getRepository('cmsspaBundle:ProductsLang')->find($id);

        if (!$product) {
	      throw $this->createNotFoundException(
		  'There is no product with ID:  '.$id
	      );
	} else {
	
	    $productDetails = array();
	    $productDetails['id'] = $product->getIdProduct();
	    $productDetails['name'] = $product->getName();
	    $productDetails['description'] = $product->removeHtmlWhitespace($product->getDescription());
	    $productDetails['descriptionShort'] = $product->removeHtmlWhitespace($product->getDescriptionShort());
	    $productDetails['metaDescription'] = $product->getMetaDescription();
	    $productDetails['metaTitle'] = $product->getMetaTitle();
	    $productDetails['linkRewrite'] = $product->getLinkRewrite();
	    $productDetails['manufacturer'] = $emNew->getRepository('cmsspaBundle:Products', $this->dbNew)->find($id)
	    ->getIdManufacturer();
	    $productDetails['priceNew'] = $emNew->getRepository('cmsspaBundle:ProductsShop')->find($id)->getPrice();
	    $productDetails['priceOld'] = $emOld->getRepository('cmsspaBundle:ProductsShop')->find($id)->getPrice();
	    $productDetails['quantityNew'] = $this->getSingleQuantity($this->dbNew, $id);
            $productDetails['quantityOld'] = $this->getSingleQuantity($this->dbOld, $id);
	    $productDetails['productCategories'] = $emNew->getRepository('cmsspaBundle:CategoryProduct')
	    ->findProductCategories($product->getIdProduct());
	    $productDetails['productTags'] = $emNew->getRepository('cmsspaBundle:ProductTag')
	    ->findTagList($product->getIdProduct());
	    $productDetails['tagString'] = $productDetails['productTags']['tagString'];
	    unset($productDetails['productTags']['tagString']);
	    $productDetails['categories'] = $this->getCategoriesAction(false);
	    $productDetails['manufacturers'] = $this->getManufacturersAction(false);
	    //echo json_encode($productDetails); exit();
	    return $this->render('cmsspaBundle:Products:detailsFullEdition.html.twig', array(
		'product' => $productDetails
	    ));
        }
    }
    
    public function detailsByNameAction() {
	  if (isset($_GET['search'])) {
		$name = $_GET['search'];
	  } else {
		$name = '';
	  }
	  $em = $this->getDoctrine()->getManager($this->dbNew);
          $products = $em->getRepository('cmsspaBundle:Products')->findByNamePart($name);

          if (!$products) {
	    throw $this->createNotFoundException(
		'No product with phrase:  '.$name
	    );
	  } else {
		$result = array('products' => $products, 'name' => $name);
		//echo json_decode($result); exit();
		return $this->render('cmsspaBundle:Products:detailsName.html.twig', array(
		    'products' => $products,
		    'name' => $name,
		));
          }
    }
    
    public function getCategoriesAction($json = true) {
	  $categories = $this->getDoctrine()
	      ->getRepository('cmsspaBundle:CategoryLang')
	      ->findAll();
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
		echo json_encode($categoryList); 
		exit();
	  } else {
		return $categoryList;
	  }
    }
    
    public function getManufacturersAction($json = true) {
	  $manufacturers = $this->getDoctrine()
	      ->getRepository('cmsspaBundle:Manufacturer')
	      ->findAll();
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
		echo json_encode($manufacturerList); 
		exit();
	  } else {
		return $manufacturerList;
	  }
    }
    
    private function getSingleQuantity($db, $id) {
	  $quantity = $this->getDoctrine()
		    ->getRepository('cmsspaBundle:StockAvailable', $db)
		    ->find($id);
	  return $quantity->getQuantity();
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
		}
	  }
	  echo json_encode($result); exit();
    }
}
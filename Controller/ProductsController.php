<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use cms\spaBundle\Entity\CategoryProduct;
use cms\spaBundle\Entity\Condition;
use cms\spaBundle\Entity\Image;
use cms\spaBundle\Entity\Modyfy;
use cms\spaBundle\Entity\ProductHistory;
use cms\spaBundle\Entity\ProductTag;
use cms\spaBundle\Entity\Tag;
use Doctrine\ORM\Mapping as ORM;


class ProductsController extends BaseController
{
    
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
			$counter = 0;
			foreach ($this->product['attribute']['new'] as $single) {
			      $this->product['attribute']['new'][$counter]["productAttributeBoth"] = $single["productAttribute"].'-'.$this->product['attribute']['old'][$counter]["productAttribute"];
			      $this->product['attribute']['new'][$counter]["attributeIdBoth"] = 
			      $single["attributeId"].'-'.$this->product['attribute']['old'][$counter]["attributeId"];
			      $counter++;
			}
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
	    $this->product['image'] = $this->handler['emOld']
			->getRepository('cmsspaBundle:Image')
			->findCoverImage($id);
	    if (isset($_GET['basic']) && $_GET['basic'] == true) {
		  $this->product['name'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:Products')
			->findNameById($id);
	          $this->product['linkRewrite'] = $product->getLinkRewrite();
		  $this->product['success'] = true;
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
	    $result = array('success' => false, 'reason' => 'Brak produktu o nazwie: '.$name);
	    $response = $this->printJson($result);
		return $response;
	  } else {
		$response = $this->printJson($this->product);
		return $response;
		//return $this->render('cmsspaBundle:Products:detailsName.html.twig', array(
		//    'products' => $products,
		//    'name' => $name,
		//));
          }
    }
    
    private function fullUpdate($dates, $attribute) {
	  $this->getDbHandlers();
	  $dates['id'] = intval($dates['id']);
	  $counter = 0;
	  foreach ($this->handler as $single) {
		$product = $single
			->getRepository('cmsspaBundle:ProductsLang')
			->find($dates['id']);
		$product->setName(strip_tags($dates['name']));
		$product->setDescription(strip_tags($dates['description']));
		$product->setDescriptionShort(strip_tags($dates['descriptionShort']));
		$product->setLinkRewrite(strip_tags($dates['linkRewrite']));
		$product->setMetaDescription(strip_tags($dates['metaDescription']));
		$product->setMetaTitle(strip_tags($dates['metaTitle']));
		$single->persist($product);
		$product = $single
			->getRepository('cmsspaBundle:StockAvailable')
			->evenQuantityAndAttribute($dates['id'], $attribute[$counter], $dates['quantity']);
		$product = $single
			->getRepository('cmsspaBundle:Products')
			->find($dates['id']);
		$product->setIdManufacturer($dates['manufactorer']['id']);
		$product->setActive($dates['active']['value']);
		$product->setCondition($dates['condition']['value']);
		$condition = $this->handler['emNew']
			->getRepository('cmsspaBundle:Condition')
			->findByCondition($dates['condition']['value']);
		$counter == 0 ? $product->
				setPrice(floatval($dates['priceNew'])) : 
				$product->setPrice(floatval($dates['priceOld']));
		$single->persist($product);
		$product = $single
			->getRepository('cmsspaBundle:ProductsShop')
			->find($dates['id']);
		$counter == 0 ? $product->
				setPrice(floatval($dates['priceNew'])) : 
				$product->setPrice(floatval($dates['priceOld']));
		$product->setActive($dates['active']['value']);
		$product->setCondition($dates['condition']['value']);
		if ($dates['modified'] == true) {
		      $modify = new Modyfy();
		      $modify->setIdNumber($dates['id']);
		      $modify->setName(strip_tags($dates['name']));
		      $date = getdate();
		      if ($date['mday'] <= 9) {
			    $date['mday'] = '0'.$date['mday'];
		      }
		      if ($date['mon'] <= 9) {
			    $date['mon'] = '0'.$date['mon'];
		      }
		      $curDate = $date['year'].'-'.$date['mon'].'-'.$date['mday'];
		      $modify->setDate($date['year'].'-'.$date['mon'].'-'.$date['mday']);
		      $this->handler['emOld']->persist($modify);
		      $this->handler['emOld']->flush();
		}
		$categoryList = array();
		foreach ($dates['categories'] as $category) {
		      if ($category['checked'] === "true") {
			      if ($counter == 1) {
				    if ($category["metaTitle"] == "Skala H0") {
					  $category["id"] = 2;
				    }
				    if ($category["metaTitle"] == "Główna") {
					  $category["id"] = 6;
				    }
			      }
			      $categoryList[] = $category["id"];
		      }
		}
		$categoryDelete = $single
			->getRepository('cmsspaBundle:CategoryProduct')
			->deleteProductCategories($dates['id']);
		foreach ($categoryList as $category) {
		      $row = new CategoryProduct();
		      $row->setIdCategory(intval($category));
		      $row->setIdProduct($dates['id']);
		      $single->persist($row);
		      $single->flush();
		}
		$tags = explode(', ', $dates['tagString']);
		$recentTag = array();
		$missingTag = array();
		foreach ($tags as $tag) {
		      $recent = $single
			->getRepository('cmsspaBundle:Tag')
			->findTagId($tag);
		      if ($recent != 'missing') {
			      $recentTag[] = $recent;
		      } else {
			      $missingTag[] = $tag;
		      }
		}
		if (!empty($missingTag)) {
		      foreach ($missingTag as $tag) {
			    $newTag = new Tag();
			    $newTag->setName($tag);
			    $newTag->setIdLang(1);
			    $single->persist($newTag);
			    $single->flush();
			    $recentTag[] = $single
				->getRepository('cmsspaBundle:Tag')
				->findTagId($tag);
		      }
		}
		$delete = $single
			->getRepository('cmsspaBundle:ProductTag')
			->deleteProductTag($dates['id']);
		foreach ($recentTag as $tag) {
			$newTag = new ProductTag();
			$newTag->setIdProduct($dates['id']);
			$newTag->setIdTag($tag);
			$single->persist($newTag);
		}
		if ($dates['deletePhoto'] == 'true') {
		      $single
		      ->getRepository('cmsspaBundle:Image')
		      ->deleteAllImages($dates['id']);
		}
		$single->persist($product);
		$single->flush();
		$counter++;
          }
          $result = array ('success' => true, 'data' => 'Data has been sucesfully modified');
                    $response = new Response();
	  $response->setContent(json_encode($result));
	  $response->headers->set('Content-Type', 'application/json');
          		return $response->getContent(); exit();
	  $response = $this->printJson($response->getContent());
	  return $response;
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
	  $this->product['price']['new'] = number_format((float)$this->handler['emNew']
		->getRepository('cmsspaBundle:ProductsShop')
		->find($this->product['id'])
		->getPrice(), 2, '.', '');
	  $this->product['price']['old'] = number_format((float)$this->handler['emOld']
	        ->getRepository('cmsspaBundle:ProductsShop')
		->find($this->product['id'])
		->getPrice(), 2, '.', '');
	  $this->product['discount']['new'] = $this->handler['emNew']
	        ->getRepository('cmsspaBundle:SpecificPrice')
		->findByIdProduct($this->product['id']); 
	  if ($this->product['discount']['new'] !== false) {
		  $this->product['discount']['new']["percentage"] = floatval($this->product['discount']['new']["reduction"]) * 100;
		  $this->setRealPrice('new');
	  } 
	  $this->product['discount']['old'] = $this->handler['emOld']
	        ->getRepository('cmsspaBundle:SpecificPrice')
		->findByIdProduct($this->product['id']); 
	  if ($this->product['discount']['old'] !== false) {
		  $this->product['discount']['old']['reduction'] = number_format((float) $this->product['discount']['old']['reduction'], 2, '.', '');
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
    
    public function historyProductByIdAction($id) {
	  $this->getDbHandlers();
	  $history = $this->handler['emNew']
			->getRepository('cmsspaBundle:ProductHistory')
			->findByProductId($id);
	  $this->product = array();
	  $counter = 0;
	  foreach ($history as $single) {
	  $this->product[$counter]['id'] = $single->getProductId();
	  if ($single->getAttributeId() != 0) {
		$this->product[$counter]['attribute'] = $single->getAttributeId();
	  }
	  $this->product[$counter]['quantity'] = $single->getQuantity();
	  $single->getBaseOrigin() == 0 ? $this->product[$counter]['dataBase'] = 'old' : $this->product[$counter]['dataBase'] = 'new';
	  $dateObj = $single->getDate();
	  if($dateObj instanceof \DateTime){
		$this->product[$counter]['date'] = $dateObj->format('Y-m-d H:i:s');
	  }
	  $counter++;
	  }
	  $response = $this->printJson($this->product);
	  return $response;
    }
    
    public function productConditionAction() {
	  $conditions = array();
	  $conditions['productConditions'][0] = array('value' => 'used', 'name' => 'Używany');
	  $conditions['productConditions'][1] = array('value' => 'new', 'name' => 'Nowy');
	  $conditions['productConditions'][2] = array('value' => 'refurbished', 'name' => 'Odnowiony');
	  $conditions['productActivity'][0] = array('value' => 0, 'name' => 'Nieaktywny');
	  $conditions['productActivity'][1] = array('value' => 1, 'name' => 'Aktywny');
	  $response = $this->printJson($conditions);
	  return $response;
    }
    
    public function productsModifiedAction() {
	  $this->getDbHandlers();
	  $modified = $this->handler['emOld']
			->getRepository('cmsspaBundle:Modyfy')
			->findAll();
          $productList = array();
          $counter = 0;
	  foreach ($modified as $single) {
		    $productList[$counter]['id'] = $single->getIdNumber();
		    $productList[$counter]['name'] = $single->getName();
		    $productList[$counter]['date'] = $single->getDate();
		    $counter++;
	  }
	  $response = $this->printJson($productList);
	  return $response;
    }
    
    public function productsModifiedDeleteAction($id) {
	  $this->getDbHandlers();
	  $modified = $this->handler['emOld']
			->getRepository('cmsspaBundle:Modyfy')
			->find($id);
	  if (!$modified) {
		$result = array('success' => false, 'reason' => 'no product found with id: '.$id);
	  } else {
		$this->handler['emOld']->remove($modified);
		$this->handler['emOld']->flush();
		$result = array('success' => true, 'reason' => 'deleted product with id: '.$id);
	  }
	  $response = $this->printJson($result);
	  return $response;
    }
    
    public function productUpdateAction($id, $fAttribute, $sAttribute = null) {
	  if (isset($GLOBALS["_PUT"]['scope']) && $GLOBALS["_PUT"]['scope'] == 'full') {
		$dates = $GLOBALS["_PUT"];
		$attribute = array();
		$attribute[0] = $fAttribute;
		$attribute[1] = $sAttribute;
		$result = $this->fullUpdate($dates, $attribute);
		$response = $this->printJson($result);
		return $response;
	  }
	  if ($GLOBALS["_PUT"]['db'] !== 'both') {
		$this->handler = array(
		      $this->getDoctrine()
		      ->getManager($GLOBALS["_PUT"]['db'])
		);
	  } else {
		$this->getDbHandlers();
	  }
	  $arrayAttribute = array($fAttribute, $sAttribute);
	  $counter = 0;
	  foreach ($this->handler as $single) {
		if (isset($GLOBALS["_PUT"]['price'])) {
		      $product = $single
			    ->getRepository('cmsspaBundle:Products')
			    ->find($id);
		      $product->setPrice(floatval($GLOBALS["_PUT"]["price"]));
		      $productShop = $single
			    ->getRepository('cmsspaBundle:ProductsShop')
			    ->find($id);
		      $productShop->setPrice(floatval($GLOBALS["_PUT"]["price"]));
		} elseif (isset($GLOBALS["_PUT"]['quantity'])) {
		      if ($GLOBALS["_PUT"]['db'] !== 'both') {
			    $this->secondDatabase = $GLOBALS["_PUT"]['db'] == 'linuxPl' ? 0 : 1;
		      } else {
			    $this->secondDatabase = $counter == 0 ? 1 : 0;
		      }
		      $product = $single
			    ->getRepository('cmsspaBundle:StockAvailable')
			    ->evenQuantityAndAttribute($id, $arrayAttribute[$counter], $GLOBALS["_PUT"]['quantity']);
		      $this->setProductHistory($id, $arrayAttribute[$counter], $GLOBALS["_PUT"]['quantity']);
		}
		$counter++;
		try {
		      if (isset($productShop)) {
			    $single->persist($product);
			    $single->persist($productShop);
			    $single->flush();
		      }
		      $result = array('success' => true);
		} catch (\Exception $e) {
		      $result = array('success' => false);
		      $response = $this->printJson($result);
		      return $response;
		}
	  }
	  $response = $this->printJson($result);
	  return $response;
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
	  $this->product['manufactorer'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:Products')
			->find($this->product['id'])
			->getIdManufacturer();
          $this->product['productCategories'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:CategoryProduct')
			->findProductCategories($product->getIdProduct());
	  $this->product['productCategoriesName'] = array();
	  $counter = 0;
          foreach ($this->product['productCategories'] as $single) {
          $this->product['productCategoriesName'][$counter] = $this->handler['emNew']
			->getRepository('cmsspaBundle:CategoryLang')
			->find($single)->getMetaTitle();
          $counter++;
          }
	  $this->product['productTags'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:ProductTag')
			->findTagList($product->getIdProduct());
		  //$this->product['tagString'] = $this->product['productTags']['tagString'];
	  unset($this->product['productTags']['tagString']);
	  $this->product['images'] = $this->handler['emOld']
			->getRepository('cmsspaBundle:Image')
			->findCoverImage($product->getIdProduct(), true);
	  $this->product['categories'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:CategoryLang')
			->findAllNotEmptyCategories();
	  $counter = 0;
	  foreach ($this->product['categories'] as $single) {
		if (in_array($single['id'], $this->product['productCategories'])) {
		      $this->product['categories'][$counter]['checked'] = true;
		} else {
		      $this->product['categories'][$counter]['checked'] = false;
		}
		$counter++;
	  }
	  $this->product['manufactorers'] = $this->handler['emNew']
			->getRepository('cmsspaBundle:Manufacturer')
			->findAllNotEmptyManufacturers();
    }
    
    private function setRealPrice($origin) {
	  if ($this->product['discount'][$origin]['reductionType'] == 'percentage') {
		  $discount = $this->product['price'][$origin] * $this->product['discount'][$origin]['reduction'];
		  $priceReal = $this->product['price'][$origin] - $discount;
		  $this->product['priceReal'][$origin] = number_format((float) $priceReal, 2, '.', '');
	  } elseif ($this->product['discount'][$origin]['reductionType'] == 'amount') {
		  $priceReal = $this->product['price'][$origin] - $this->product['discount'][$origin]['reduction'];
		  $this->product['priceReal'][$origin] = number_format((float) $priceReal, 2, '.', '');
	  }
    }
    
}
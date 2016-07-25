<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductAttributeRepository extends \Doctrine\ORM\EntityRepository
{

      public function checkProductAttribute($id) {
	    $result = $this->getEntityManager()
		  ->createQuery('SELECT p.id_product_attribute as productAttribute, c.id_attribute as attributeId, a.name FROM cmsspaBundle:ProductAttribute p INNER JOIN cmsspaBundle:ProductAttributeCombination c WITH p.id_product_attribute = c.id_product_attribute INNER JOIN cmsspaBundle:AttributeLang a WITH c.id_attribute = a.id_attribute WHERE p.id_product = :id_product GROUP BY p.id_product_attribute')
		  ->setParameter('id_product', $id)->getResult();
	    if (empty($result)) {
		  return false;
	    } else {
		  return $result;
	    }
      }
      
      public function checkAttributeName($id) {
	    $result = $this->getEntityManager()
		  ->createQuery('SELECT a.name FROM cmsspaBundle:ProductAttribute p INNER JOIN cmsspaBundle:ProductAttributeCombination c WITH p.id_product_attribute = c.id_product_attribute INNER JOIN cmsspaBundle:AttributeLang a WITH c.id_attribute = a.id_attribute WHERE p.id_product_attribute = :id_product GROUP BY p.id_product_attribute')
		  ->setParameter('id_product', $id)->getResult();
	    return $result[0]['name'];
      }

}
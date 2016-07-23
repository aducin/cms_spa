<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StockAvailableRepository extends \Doctrine\ORM\EntityRepository
{

      public function evenQuantityAndAttribute($id, $attribute, $quantity) {
	    $qb = $this->getEntityManager()->createQueryBuilder();
	    $q = $qb->update('cmsspaBundle:StockAvailable', 's')
		  ->set('s.quantity', ':quantity')
		  ->where('s.id_product = :id AND s.id_product_attribute = :attribute')
		  //->where('s.id_product_attribute = :attribute')
		  ->setParameter('quantity', $quantity)
		  ->setParameter('id', $id)
		  ->setParameter('attribute', $attribute)
		  ->getQuery();
	    $q->execute();
      }
      
      public function getCurrentQuantity($id, $attribute) {
	    $result = $this->getEntityManager()
		  ->createQuery('SELECT s.quantity FROM cmsspaBundle:StockAvailable s WHERE s.id_product = :id_product AND s.id_product_attribute = :attribute')
		  ->setParameter('id_product', $id)
		  ->setParameter('attribute', $attribute)
		  ->getResult();
	    return $result[0]['quantity'];
      }

}
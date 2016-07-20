<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SpecificPriceRepository extends \Doctrine\ORM\EntityRepository
{

      public function findByIdProduct($id) {
	    
	    $result = $this->getEntityManager()
		  ->createQuery('SELECT s.reduction, s.reduction_type FROM cmsspaBundle:SpecificPrice s WHERE s.id_product = :id_product ORDER BY s.id_product')->setParameter('id_product', $id)->getResult();
	    if (!isset($result[0]['reduction'])) {
		  return false;
	    } else {
		  return $result[0];
	    }
      }

}
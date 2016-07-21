<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdersRepository extends \Doctrine\ORM\EntityRepository
{
     
     public function findCustomerId($id) {
	  return $this->getEntityManager()
		  ->createQuery('
		  SELECT o.reference, o.id_customer as customerId FROM cmsspaBundle:Orders o WHERE o.id_order = :id')
		  ->setParameter('id', $id)->getResult();
    }
}
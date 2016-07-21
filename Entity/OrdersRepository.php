<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdersRepository extends \Doctrine\ORM\EntityRepository
{
     
     public function findCustomerId($id) {
	  return $this->getEntityManager()
		  ->createQuery('
		  SELECT o.reference, o.id_customer as customerId, o.total_products as totalProduct, o.total_paid as totalPaid FROM cmsspaBundle:Orders o WHERE o.id_order = :id')
		  ->setParameter('id', $id)->getResult();
    }
    
    public function findOrdersByCustomer($id) {
	  return $this->getEntityManager()
		  ->createQuery('
		  SELECT o.id_order as id, o.reference, o.total_products as totalProduct, o.total_shipping as totalShipping,o.date_add as dateAdd FROM cmsspaBundle:Orders o WHERE o.id_customer = :id')
		  ->setParameter('id', $id)->getResult();
    }
}
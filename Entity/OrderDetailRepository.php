<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderDetailRepository extends \Doctrine\ORM\EntityRepository
{
     
     public function findOrderById($id) {
	  return $orderDetails = $this->getEntityManager()
		  ->createQuery('
		  SELECT o.product_id as productId, o.product_attribute_id as attributeId, o.product_name as productName,   o.product_quantity as productQuantity, o.reduction_amount_tax_incl as reduction, o.total_price_tax_incl as totalPrice, o.unit_price_tax_incl as unitPrice FROM cmsspaBundle:OrderDetail o WHERE o.id_order = :id')
		  ->setParameter('id', $id)->getResult();
    }
}
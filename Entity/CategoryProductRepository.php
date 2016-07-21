<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryProductRepository extends \Doctrine\ORM\EntityRepository
{
     
     public function findProductCategories($id) {

	  $categories =  $this->getEntityManager()
             ->createQuery('SELECT c.id_category FROM cmsspaBundle:CategoryProduct c WHERE c.id_product = :id')
             ->setParameter('id', '$id')->getResult();
          $list = array();
	  foreach ($categories as $single) {
	      $list[] = $single['id_category'];
	  }
          return $list;
    }
}
<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryLangRepository extends \Doctrine\ORM\EntityRepository
{
     
     public function findAllNotEmptyCategories() {
	  $categories =  $this->getEntityManager()
		->createQuery('SELECT c.id_category, c.meta_title FROM cmsspaBundle:CategoryLang c 
		WHERE c.id_category NOT IN (1, 10, 14, 15, 19, 20, 22, 23, 24, 25, 26, 27, 32, 33)')->getResult();
	  $counter = 0;
	  $categoryList = array();
          foreach($categories as $single) {
		$categoryList[$counter]['id'] = $single['id_category'];
		$categoryList[$counter]['meta_title'] = $single['meta_title'];
		$counter++;
	  }
          return $categoryList;
	  
    }
}
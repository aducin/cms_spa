<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManufacturerRepository extends \Doctrine\ORM\EntityRepository
{
     
     public function findAllNotEmptyManufacturers() {
	  $manufacturers =  $this->getEntityManager()
		->createQuery('SELECT m.id_manufacturer, m.name FROM cmsspaBundle:Manufacturer m ')
		->getResult();
          $counter = 0;
	  $manufacturerList = array();
          foreach($manufacturers as $single) {
		if ($single['name'] != 'pusty') {
		      $manufacturerList[$counter]['id'] = $single['id_manufacturer'];
		      $manufacturerList[$counter]['name'] = $single['name'];
		      $counter++;
		}
	  }
          return $manufacturerList;
	  
    }
}
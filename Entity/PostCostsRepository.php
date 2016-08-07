<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostCostsRepository extends \Doctrine\ORM\EntityRepository
{

      public function getLatestPostCosts() {
	    return $this->getEntityManager()
		  ->createQuery('
		  SELECT p.current, p.reg_date as date FROM cmsspaBundle:PostCosts p ORDER BY p.id DESC')
		  ->setMaxResults(5)->getResult(); 
      }

}
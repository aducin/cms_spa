<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagRepository extends \Doctrine\ORM\EntityRepository
{
     
     public function findTagName($id)
     {
	    $name =  $this->getEntityManager()
             ->createQuery('SELECT t.name FROM cmsspaBundle:Tag t WHERE t.id_tag = :id')
             ->setParameter('id', $id)->getResult();
	    return $name[0];
     }
     
          public function findTagId($name)
     {
	    $name =  $this->getEntityManager()
             ->createQuery('SELECT t.id_tag FROM cmsspaBundle:Tag t WHERE t.id_lang = 1 AND t.name = :name')
             ->setParameter('name', $name)->getResult();
            if (isset($name[0]["id_tag"])) {
		  return $name[0]["id_tag"];
	    } else {
		  return 'missing';
	    }
     }

}
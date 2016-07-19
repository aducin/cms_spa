<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductsRepository extends \Doctrine\ORM\EntityRepository
{
     public function findNameById($id)
     {
         return $this->getEntityManager()
             ->createQuery('SELECT p.name FROM cmsspaBundle:ProductsLang p WHERE p.id_product = :id_product ORDER BY p.id_product')->setParameter('id_product', $id)->getResult();
     }
     
     public function findByNamePart($phrase) 
     {
         return $this->getEntityManager()
             ->createQuery('SELECT p.id_product, p.name FROM cmsspaBundle:ProductsLang p WHERE p.name LIKE :name ORDER BY p.id_product ASC')->setParameter('name', '%'.$phrase.'%')->getResult();
     
     }
     
}
<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductsRepository extends \Doctrine\ORM\EntityRepository
{
     public function findNameById($id)
     {
         $result = $this->getEntityManager()
             ->createQuery('SELECT p.name FROM cmsspaBundle:ProductsLang p WHERE p.id_product = :id_product ORDER BY p.id_product')->setParameter('id_product', $id)->getResult();
         return $result[0]['name'];
     }
     
     public function findByNamePart($phrase) 
     {
	 $select = 'SELECT p.id_product as id, p.name FROM cmsspaBundle:ProductsLang p ';
	 $where = 'WHERE p.name LIKE :name';
	 if (isset($_GET['manufacturer']) && $_GET['manufacturer'] != 0) {
	      $select = $select. 'INNER JOIN cmsspaBundle:Products a WITH p.id_product = a.id_product ';
	      $where = $where.' AND a.id_manufacturer = '.$_GET['manufacturer'];
	 }
	 if (isset($_GET['category']) && $_GET['category'] != 0) {
	      $select = $select. 'INNER JOIN cmsspaBundle:CategoryProduct c WITH p.id_product = c.id_product ';
	      $where = $where.' AND c.id_category = '.$_GET['category'];
	 }
	 $url = $select.$where;
	 $url = $url. ' ORDER BY p.id_product ASC';
         return $this->getEntityManager()
             ->createQuery($url)->setParameter('name', '%'.$phrase.'%')->getResult();
     
     }
     
     public function updateCondition($id, $condition) {
	    $qb = $this->getEntityManager()->createQueryBuilder();
	    $q = $qb->update('cmsspaBundle:Products', 'p')
		  ->set('p.condition', ':condition')
		  ->where('p.id_product = :id')
		  ->setParameter('condition', $condition)
		  ->setParameter('id', $id)
		  ->getQuery();
	    $q->execute();
      }
     
}
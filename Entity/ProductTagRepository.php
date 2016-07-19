<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductTagRepository extends \Doctrine\ORM\EntityRepository
{
     public function findTagList($id)
     {
	    $tags =  $this->getEntityManager()
             ->createQuery('SELECT p.id_tag FROM cmsspaBundle:ProductTag p WHERE p.id_product = :id')
             ->setParameter('id', $id)->getResult();
	    $list = array();
	    $tagString = '';
	    $counter = 0;
	    foreach ($tags as $single) {
		$list[$counter]['id'] = $single['id_tag'];
		$name = $this->findTagName($single['id_tag']);
		$list[$counter]['name'] = $name[0]['name'];
		$tagString .= $name[0]['name'].' ,';
		$counter++;
	    }
	    $string = trim($tagString, " ,");
	    $list['tagString'] = $string;
	    return $list;
     }
     
     public function findTagName($id)
     {
	    return $this->getEntityManager()
		    ->createQuery('SELECT t.name FROM cmsspaBundle:Tag t WHERE t.id_tag = :id')
		    ->setParameter('id', $id)->getResult();
	    
     }

}
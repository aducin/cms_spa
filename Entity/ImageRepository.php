<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImageRepository extends \Doctrine\ORM\EntityRepository
{
     public function deleteAllImages($id) {
	  return $this->getEntityManager()
	  ->createQuery('DELETE FROM cmsspaBundle:Image i WHERE i.id_product = '.$id)
	  ->getResult();
     }
     
     public function findCoverImage($id, $full = null) {
	  if ($full == true) {
	      $dql = 'SELECT i.id_image FROM cmsspaBundle:Image i WHERE i.id_product = '.$id;
	  } else {
	      $dql = 'SELECT i.id_image FROM cmsspaBundle:Image i WHERE i.id_product = '.$id.' AND i.cover = 1';
	  }
	  $cover = $this->getEntityManager()
		->createQuery($dql)
		->getResult();
	  if ($full == true) {
		$url = array();
	        $counter = 0;
		foreach ($cover as $single) {
		    $url[$counter] = 'http://modele-ad9bis.pl/img/p/'.$id.'-'.$single["id_image"].'-thickbox.jpg';
		    $counter++;
		}
		return $url;
          } else {
		return $cover[0]["id_image"];
          }
	  
    }
}
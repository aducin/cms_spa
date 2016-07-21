<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomerRepository extends \Doctrine\ORM\EntityRepository
{

      public function findCustomerByEmail($email) {
	    $result = $this->getEntityManager()
		  ->createQuery('SELECT c.email FROM cmsspaBundle:Customer c WHERE c.email = :email')
		  ->setParameter('email', $email)->getResult();
	    if (empty($result[0])) {
		  return false;
	    } else {
		  return $result[0]['email'];
	    }
      }

}
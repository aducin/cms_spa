<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use cms\spaBundle\Entity\PostCosts;
use Doctrine\ORM\Mapping as ORM;


class PostalController extends BaseController
{

    public function fundsAction() {
	  $this->getDbHandlers();
	  try {
		$postList = $this->handler['emNew']
		      ->getRepository('cmsspaBundle:PostCosts')
		      ->getLatestPostCosts();
		$counter = 0;
		$list = array();
		foreach ($postList as $single) {
		      $postal['list'][$counter]['number'] = $counter + 1;
		      $postal['list'][$counter]['current'] = number_format((float) ($single['current']), 2, '.', '');
		      $postal['list'][$counter]['date'] = $single['date'];
		      $counter++;
		}
		$postal['success'] = true;
		$postal['current'] = $postal['list'][0]['current'];
		$response = $this->printJson($postal);
		return $response;
	  } catch (\Exception $e) {
		$response = $this->printJson(array('success' => false, 'reason' => 'Nie udało się pobrać informacji o kosztach przesyłek.'));
		return $response;
	  }
    }
    
    public function fundsChangeAction() {
	  $this->getDbHandlers();
	  $action = $GLOBALS["_POST"]['action'];
	  $amount = $GLOBALS["_POST"]['amount'];
	  $current = $this->handler['emNew']
		      ->getRepository('cmsspaBundle:PostCosts')
		      ->getLatestCurrent();
	  if ($action == 'add') {
		$postal = new PostCosts();
		$postal->setPlus(floatval($amount));
		$postal->setCurrent(floatval($current) + floatval($amount));
	  } elseif ($action = 'subtract') {
		$postal = new PostCosts();
		$postal->setSubtract(floatval($amount));
		$postal->setCurrent(floatval($current) - floatval($amount));
	  }
	  try {
		$this->handler['emNew']->persist($postal);
		$this->handler['emNew']->flush();
		$response = $this->printJson(array('success' => true, 'reason' => 'Kwota na przesyłki została zmodyfikowana!'));
	  } catch (\Exception $e) {
		$response = $this->printJson(array('success' => false, 'reason' => 'Nie udało się zaktualizować kwoty na przesyłki.'));
	  }
	  return $response;
    }

}
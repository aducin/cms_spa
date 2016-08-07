<?php

namespace cms\spaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
	  //$params = json_decode(file_get_contents('php://input'),true);
	  var_dump($GLOBALS["_POST"]['action']);
	  var_dump($GLOBALS["_POST"]['amount']);
	  exit();
    }

}
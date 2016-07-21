<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\OrdersRepository")
  * @ORM\Table(name="ps_orders")
  */

class Orders
{

    /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
    protected $id_order;

    /**
      * @ORM\Column(type="string", length=100)
      */
    protected $reference;

    /**
      * @ORM\Column(type="integer")
      */
    protected $id_customer;


    /**
     * Get idOrder
     *
     * @return integer
     */
    public function getIdOrder()
    {
        return $this->id_order;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Orders
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set idCustomer
     *
     * @param integer $idCustomer
     *
     * @return Orders
     */
    public function setIdCustomer($idCustomer)
    {
        $this->id_customer = $idCustomer;

        return $this;
    }

    /**
     * Get idCustomer
     *
     * @return integer
     */
    public function getIdCustomer()
    {
        return $this->id_customer;
    }
    
}

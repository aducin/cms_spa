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
      * @ORM\Column(type="decimal", scale=2)
      */
    protected $total_paid;

    /**
      * @ORM\Column(type="decimal", scale=2)
      */
    protected $total_products;
    
     /**
      * @ORM\Column(type="decimal", scale=2)
      */
    protected $total_shipping;
    
    /**
      * @ORM\Column(type="string", length=100)
      */
    protected $date_add;

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
    

    /**
     * Set totalProducts
     *
     * @param string $totalProducts
     *
     * @return Orders
     */
    public function setTotalProducts($totalProducts)
    {
        $this->total_products = $totalProducts;

        return $this;
    }

    /**
     * Get totalProducts
     *
     * @return string
     */
    public function getTotalProducts()
    {
        return $this->total_products;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return Orders
     */
    public function setDateAdd($dateAdd)
    {
        $this->date_add = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->date_add;
    }

    /**
     * Set totalShipping
     *
     * @param string $totalShipping
     *
     * @return Orders
     */
    public function setTotalShipping($totalShipping)
    {
        $this->total_shipping = $totalShipping;

        return $this;
    }

    /**
     * Get totalShipping
     *
     * @return string
     */
    public function getTotalShipping()
    {
        return $this->total_shipping;
    }

    /**
     * Set totalPaid
     *
     * @param string $totalPaid
     *
     * @return Orders
     */
    public function setTotalPaid($totalPaid)
    {
        $this->total_paid = $totalPaid;

        return $this;
    }

    /**
     * Get totalPaid
     *
     * @return string
     */
    public function getTotalPaid()
    {
        return $this->total_paid;
    }
}

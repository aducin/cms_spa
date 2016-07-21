<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\OrderDetailRepository")
  * @ORM\Table(name="ps_order_detail")
  */

class OrderDetail
{

    /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
    protected $id_order_detail;
    
    /**
      * @ORM\Column(type="integer")
      */
    protected $id_order;

    /**
      * @ORM\Column(type="integer")
      */
    protected $product_id;

    /**
      * @ORM\Column(type="integer")
      */
    protected $product_attribute_id;

    /**
      * @ORM\Column(type="string", length=100)
      */
    protected $product_name;

    /**
      * @ORM\Column(type="integer")
      */
    protected $product_quantity;

    /**
      * @ORM\Column(type="decimal", scale=2)
      */
    protected $total_price_tax_incl;

    /**
      * @ORM\Column(type="decimal", scale=2)
      */
    protected $unit_price_tax_incl;


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
     * Set productId
     *
     * @param integer $productId
     *
     * @return OrderDetail
     */
    public function setProductId($productId)
    {
        $this->product_id = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set productAttributeId
     *
     * @param integer $productAttributeId
     *
     * @return OrderDetail
     */
    public function setProductAttributeId($productAttributeId)
    {
        $this->product_attribute_id = $productAttributeId;

        return $this;
    }

    /**
     * Get productAttributeId
     *
     * @return integer
     */
    public function getProductAttributeId()
    {
        return $this->product_attribute_id;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return OrderDetail
     */
    public function setProductName($productName)
    {
        $this->product_name = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Set productQuantity
     *
     * @param integer $productQuantity
     *
     * @return OrderDetail
     */
    public function setProductQuantity($productQuantity)
    {
        $this->product_quantity = $productQuantity;

        return $this;
    }

    /**
     * Get productQuantity
     *
     * @return integer
     */
    public function getProductQuantity()
    {
        return $this->product_quantity;
    }

    /**
     * Set totalPriceTaxIncl
     *
     * @param string $totalPriceTaxIncl
     *
     * @return OrderDetail
     */
    public function setTotalPriceTaxIncl($totalPriceTaxIncl)
    {
        $this->total_price_tax_incl = $totalPriceTaxIncl;

        return $this;
    }

    /**
     * Get totalPriceTaxIncl
     *
     * @return string
     */
    public function getTotalPriceTaxIncl()
    {
        return $this->total_price_tax_incl;
    }

    /**
     * Set unitPriceTaxIncl
     *
     * @param string $unitPriceTaxIncl
     *
     * @return OrderDetail
     */
    public function setUnitPriceTaxIncl($unitPriceTaxIncl)
    {
        $this->unit_price_tax_incl = $unitPriceTaxIncl;

        return $this;
    }

    /**
     * Get unitPriceTaxIncl
     *
     * @return string
     */
    public function getUnitPriceTaxIncl()
    {
        return $this->unit_price_tax_incl;
    }

    /**
     * Get idOrderDetail
     *
     * @return integer
     */
    public function getIdOrderDetail()
    {
        return $this->id_order_detail;
    }

    /**
     * Set idOrder
     *
     * @param integer $idOrder
     *
     * @return OrderDetail
     */
    public function setIdOrder($idOrder)
    {
        $this->id_order = $idOrder;

        return $this;
    }
}

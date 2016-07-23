<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\StockAvailableRepository")
  * @ORM\Table(name="ps_stock_available")
  */
class StockAvailable
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      */
      protected $id_product;
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      */
      protected $id_product_attribute;

      /**
      * @ORM\Column(type="integer")
      */
      protected $quantity;

    /**
     * Get idProduct
     *
     * @return integer
     */
    public function getIdProduct()
    {
        return $this->id_product;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return StockAvailable
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set idProductAttribute
     *
     * @param integer $idProductAttribute
     *
     * @return StockAvailable
     */
    public function setIdProductAttribute($idProductAttribute)
    {
        $this->id_product_attribute = $idProductAttribute;

        return $this;
    }

    /**
     * Get idProductAttribute
     *
     * @return integer
     */
    public function getIdProductAttribute()
    {
        return $this->id_product_attribute;
    }
}

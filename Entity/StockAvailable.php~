<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity
  * @ORM\Table(name="ps_stock_available")
  */
class StockAvailable
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
      protected $id_product;

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
}

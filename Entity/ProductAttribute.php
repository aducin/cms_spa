<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\ProductAttributeRepository")
  * @ORM\Table(name="ps_product_attribute")
  */
class ProductAttribute
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      */
      protected $id_product_attribute;

      /**
      * @ORM\Column(type="integer")
      */
      protected $id_product;


    /**
     * Set idProductAttribute
     *
     * @param integer $idProductAttribute
     *
     * @return ProductAttribute
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

    /**
     * Set idProduct
     *
     * @param integer $idProduct
     *
     * @return ProductAttribute
     */
    public function setIdProduct($idProduct)
    {
        $this->id_product = $idProduct;

        return $this;
    }

    /**
     * Get idProduct
     *
     * @return integer
     */
    public function getIdProduct()
    {
        return $this->id_product;
    }
}

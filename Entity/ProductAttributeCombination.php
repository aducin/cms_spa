<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\StockAvailableRepository")
  * @ORM\Table(name="ps_product_attribute_combination")
  */
class ProductAttributeCombination
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      */
      protected $id_attribute;

      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      */
      protected $id_product_attribute;
     

    /**
     * Set idAttribute
     *
     * @param integer $idAttribute
     *
     * @return ProductAttributeCombination
     */
    public function setIdAttribute($idAttribute)
    {
        $this->id_attribute = $idAttribute;

        return $this;
    }

    /**
     * Get idAttribute
     *
     * @return integer
     */
    public function getIdAttribute()
    {
        return $this->id_attribute;
    }

    /**
     * Set idProductAttribute
     *
     * @param integer $idProductAttribute
     *
     * @return ProductAttributeCombination
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

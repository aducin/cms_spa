<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\ProductTagRepository")
  * @ORM\Table(name="ps_product_tag")
  */
class ProductTag
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
      protected $id_tag;


    /**
     * Set idProduct
     *
     * @param integer $idProduct
     *
     * @return ProductTag
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

    /**
     * Set idTag
     *
     * @param integer $idTag
     *
     * @return ProductTag
     */
    public function setIdTag($idTag)
    {
        $this->id_tag = $idTag;

        return $this;
    }

    /**
     * Get idTag
     *
     * @return integer
     */
    public function getIdTag()
    {
        return $this->id_tag;
    }
}

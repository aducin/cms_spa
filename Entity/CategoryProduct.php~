<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\CategoryProductRepository")
  * @ORM\Table(name="ps_category_product")
  */
class CategoryProduct
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      */
      protected $id_category;

      /**
      * @ORM\Column(type="integer")
      */
      protected $id_product;


    /**
     * Set idCategory
     *
     * @param integer $idCategory
     *
     * @return CategoryProduct
     */
    public function setIdCategory($idCategory)
    {
        $this->id_category = $idCategory;

        return $this;
    }

    /**
     * Get idCategory
     *
     * @return integer
     */
    public function getIdCategory()
    {
        return $this->id_category;
    }

    /**
     * Set idProduct
     *
     * @param integer $idProduct
     *
     * @return CategoryProduct
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

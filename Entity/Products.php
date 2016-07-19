<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\ProductsRepository")
  * @ORM\Table(name="ps_product")
  */

class Products
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
    protected $id_manufacturer;

    /**
      * @ORM\Column(type="integer")
      */
    protected $id_category_default;

    /**
      * @ORM\Column(type="decimal", scale=2)
      */
    protected $price;
    

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
     * Set idManufacturer
     *
     * @param integer $idManufacturer
     *
     * @return Products
     */
    public function setIdManufacturer($idManufacturer)
    {
        $this->id_manufacturer = $idManufacturer;

        return $this;
    }

    /**
     * Get idManufacturer
     *
     * @return integer
     */
    public function getIdManufacturer()
    {
        return $this->id_manufacturer;
    }

    /**
     * Set idCategoryDefault
     *
     * @param integer $idCategoryDefault
     *
     * @return Products
     */
    public function setIdCategoryDefault($idCategoryDefault)
    {
        $this->id_category_default = $idCategoryDefault;

        return $this;
    }

    /**
     * Get idCategoryDefault
     *
     * @return integer
     */
    public function getIdCategoryDefault()
    {
        return $this->id_category_default;
    }

    
    /**
     * Set price
     *
     * @param string $price
     *
     * @return Products
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }
    
          public function findNameById($em, $id)
     {
     var_dump($id); var_dump($em); exit();
             $query = $em->createQuery(
		'SELECT p.name FROM cmsspaBundle:ProductsLang p WHERE p.id_product = :id_product ORDER BY p.id_product ASC'
		)->setParameter('id_product', $id);
             $product = $query->getResult();
             return $product;
     }
}

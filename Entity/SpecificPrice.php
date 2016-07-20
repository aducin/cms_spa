<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\SpecificPriceRepository")
  * @ORM\Table(name="ps_specific_price")
  */
class SpecificPrice
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
      protected $id_specific_price;

      /**
      * @ORM\Column(type="integer")
      */
      protected $id_product;

      /**
      * @ORM\Column(type="decimal", scale=2)
      */
      protected $reduction;

      /**
      * @ORM\Column(type="string", length=100)
      */
      protected $reduction_type;


    /**
     * Get idSpecificPrice
     *
     * @return integer
     */
    public function getIdSpecificPrice()
    {
        return $this->id_specific_price;
    }

    /**
     * Set idProduct
     *
     * @param integer $idProduct
     *
     * @return SpecificPrice
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
     * Set reduction
     *
     * @param string $reduction
     *
     * @return SpecificPrice
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return string
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * Set reductionType
     *
     * @param string $reductionType
     *
     * @return SpecificPrice
     */
    public function setReductionType($reductionType)
    {
        $this->reduction_type = $reductionType;

        return $this;
    }

    /**
     * Get reductionType
     *
     * @return string
     */
    public function getReductionType()
    {
        return $this->reduction_type;
    }
}

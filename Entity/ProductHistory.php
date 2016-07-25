<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity
  * @ORM\Table(name="ps_product_history")
  */
class ProductHistory
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
      protected $id;

      /**
      * @ORM\Column(type="integer")
      */
      protected $productId;

      /**
      * @ORM\Column(type="integer")
      */
      protected $attributeId;

      /**
      * @ORM\Column(type="integer")
      */
      protected $quantity;

      /**
      * @ORM\Column(type="string", length=50)
      */
      protected $user;

      /**
     * @ORM\Column(name="reg_date", type="datetime")
      */
      protected $date;

      /**
      * @ORM\Column(type="integer")
      */
      protected $baseOrigin;


    /**
     * Set idAttribute
     *
     * @param integer $idAttribute
     *
     * @return ProductHistory
     */
    public function setIdAttribute($id)
    {
        $this->id_attribute = $id;

        return $this;
    }

    /**
     * Get idAttribute
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return ProductHistory
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set attributeId
     *
     * @param integer $attributeId
     *
     * @return ProductHistory
     */
    public function setAttributeId($attributeId)
    {
        $this->attributeId = $attributeId;

        return $this;
    }

    /**
     * Get attributeId
     *
     * @return integer
     */
    public function getAttributeId()
    {
        return $this->attributeId;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return ProductHistory
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
     * Set user
     *
     * @param string $user
     *
     * @return ProductHistory
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ProductHistory
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set baseOrigin
     *
     * @param integer $baseOrigin
     *
     * @return ProductHistory
     */
    public function setBaseOrigin($baseOrigin)
    {
        $this->baseOrigin = $baseOrigin;

        return $this;
    }

    /**
     * Get baseOrigin
     *
     * @return integer
     */
    public function getBaseOrigin()
    {
        return $this->baseOrigin;
    }
}

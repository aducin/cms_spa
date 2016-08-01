<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\ImageRepository")
  * @ORM\Table(name="ps_image")
  */
class Image
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
      protected $id_image;

      /**
      * @ORM\Column(type="integer")
      */
      protected $id_product;

      /**
      * @ORM\Column(type="integer")
      */
      protected $position;

      /**
      * @ORM\Column(type="integer")
      */
      protected $cover;


    /**
     * Get idImage
     *
     * @return integer
     */
    public function getIdImage()
    {
        return $this->id_image;
    }

    /**
     * Set idProduct
     *
     * @param integer $idProduct
     *
     * @return Image
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
     * Set position
     *
     * @param integer $position
     *
     * @return Image
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set cover
     *
     * @param integer $cover
     *
     * @return Image
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return integer
     */
    public function getCover()
    {
        return $this->cover;
    }
}

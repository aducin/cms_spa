<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity
  * @ORM\Table(name="ps_product_lang")
  */
class ProductsLang
{

    /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
    protected $id_product;
    
    /**
      * @ORM\Column(type="text")
      */
    protected $description;

    /**
      * @ORM\Column(type="text")
      */
    protected $description_short;

    /**
      * @ORM\Column(type="string", length=100)
      */
    protected $link_rewrite;
    
    /**
      * @ORM\Column(type="string", length=100)
      */
    protected $name;
    
    /**
      * @ORM\Column(type="string", length=100)
      */
    protected $meta_description;
    
    /**
      * @ORM\Column(type="string", length=100)
      */
    protected $meta_title;
    

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
     * Set description
     *
     * @param string $description
     *
     * @return Products
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set descriptionShort
     *
     * @param string $descriptionShort
     *
     * @return Products
     */
    public function setDescriptionShort($descriptionShort)
    {
        $this->description_short = $descriptionShort;

        return $this;
    }

    /**
     * Get descriptionShort
     *
     * @return string
     */
    public function getDescriptionShort()
    {
        return $this->description_short;
    }

    /**
     * Set linkrewrite
     *
     * @param string $linkrewrite
     *
     * @return Products
     */
    public function setLinkRewrite($link_rewrite)
    {
        $this->link_rewrite = $link_rewrite;

        return $this;
    }

    /**
     * Get linkrewrite
     *
     * @return string
     */
    public function getLinkRewrite()
    {
        return $this->link_rewrite;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Products
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return Products
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return Products
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }
    
    public function removeHtmlWhitespace($string) {
	    $string = str_replace('&nbsp;', '', $string);
	    $string = str_replace( '&ndash;', '-', $string);
	    $string = str_replace('&oacute;', 'ó', $string);
	    return $string;
    }
}

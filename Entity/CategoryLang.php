<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity
  * @ORM\Table(name="ps_category_lang")
  */
class CategoryLang
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
      protected $id_category;

      /**
      * @ORM\Column(type="string", length=100)
      */
      protected $name;

      /**
      * @ORM\Column(type="text")
      */
      protected $description;
      
       /**
      * @ORM\Column(type="text")
      */
      protected $meta_title;
     
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
     * Set name
     *
     * @param string $name
     *
     * @return CategoryLang
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
     * Set description
     *
     * @param string $description
     *
     * @return CategoryLang
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
    
    public function removeHtmlWhitespace($string) {
	    $string = str_replace('&nbsp;', '', $string);
	    $string = str_replace( '&ndash;', '-', $string);
	    $string = str_replace('&oacute;', 'ó', $string);
	    return $string;
    }

    /**
     * Add idProduct
     *
     * @param \cms\spaBundle\Entity\CategoryProduct $idProduct
     *
     * @return CategoryLang
     */
    public function addIdProduct(\cms\spaBundle\Entity\CategoryProduct $idProduct)
    {
        $this->id_product[] = $idProduct;

        return $this;
    }


    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return CategoryLang
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
}

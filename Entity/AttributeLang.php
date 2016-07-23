<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\StockAvailableRepository")
  * @ORM\Table(name="ps_attribute_lang")
  */
class AttributeLang
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      */
      protected $id_attribute;

      /**
      * @ORM\Column(type="integer")
      */
      protected $id_lang;

      /**
      * @ORM\Column(type="string", length=100)
      */
      protected $name;


    /**
     * Set idAttribute
     *
     * @param integer $idAttribute
     *
     * @return AttributeLang
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
     * Set idLang
     *
     * @param integer $idLang
     *
     * @return AttributeLang
     */
    public function setIdLang($idLang)
    {
        $this->id_lang = $idLang;

        return $this;
    }

    /**
     * Get idLang
     *
     * @return integer
     */
    public function getIdLang()
    {
        return $this->id_lang;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AttributeLang
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
}

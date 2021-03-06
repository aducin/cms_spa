<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\TagRepository")
  * @ORM\Table(name="ps_tag")
  */
class Tag
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
      protected $id_tag;
      
      /**
      * @ORM\Column(type="integer")
      */
      protected $id_lang;

      /**
      * @ORM\Column(type="string", length=100)
      */
      protected $name;


    /**
     * Get idTag
     *
     * @return integer
     */
    public function getIdTag()
    {
        return $this->id_tag;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tag
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
     * Set idLang
     *
     * @param integer $idLang
     *
     * @return Tag
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
}

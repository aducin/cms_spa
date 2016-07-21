<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\ManufacturerRepository")
  * @ORM\Table(name="ps_manufacturer")
  */
class Manufacturer
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="AUTO")
      */
      protected $id_manufacturer;

      /**
      * @ORM\Column(type="string", length=100)
      */
      protected $name;


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
     * Set name
     *
     * @param string $name
     *
     * @return Manufacturer
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

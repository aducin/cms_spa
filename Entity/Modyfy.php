<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

 /**
  * @ORM\Entity
  * @ORM\Table(name="ps_modyfy")
  */
class Modyfy
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      */
      protected $id_number;

      /**
      * @ORM\Column(type="string", length=100)
      */
      protected $name;

      /**
      * @ORM\Column(type="string", length=100)
      */
      protected $date;


    /**
     * Set idNumber
     *
     * @param integer $idNumber
     *
     * @return Modyfy
     */
    public function setIdNumber($idNumber)
    {
        $this->id_number = $idNumber;

        return $this;
    }

    /**
     * Get idNumber
     *
     * @return integer
     */
    public function getIdNumber()
    {
        return $this->id_number;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Modyfy
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
     * Set date
     *
     * @param string $date
     *
     * @return Modyfy
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
}

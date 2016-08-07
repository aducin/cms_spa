<?php

namespace cms\spaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
  * @ORM\Entity(repositoryClass="cms\spaBundle\Entity\PostCostsRepository")
  * @ORM\Table(name="ps_post_costs")
  */
class PostCosts
{
      
      /**
      * @ORM\Id
      * @ORM\Column(type="integer")
      */
      protected $id;

      /**
	* @ORM\Column(type="decimal", scale=2)
	*/
      protected $current; 

      /**
	* @ORM\Column(type="decimal", scale=2)
	*/
      protected $plus; 

      /**
	* @ORM\Column(type="decimal", scale=2)
	*/
      protected $subtract;

      /**
      * @ORM\Column(type="string", length=100)
      */
      protected $reg_date;


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return PostCosts
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set current
     *
     * @param string $current
     *
     * @return PostCosts
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get current
     *
     * @return string
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Set plus
     *
     * @param string $plus
     *
     * @return PostCosts
     */
    public function setPlus($plus)
    {
        $this->plus = $plus;

        return $this;
    }

    /**
     * Get plus
     *
     * @return string
     */
    public function getPlus()
    {
        return $this->plus;
    }

    /**
     * Set subtract
     *
     * @param string $subtract
     *
     * @return PostCosts
     */
    public function setSubtract($subtract)
    {
        $this->subtract = $subtract;

        return $this;
    }

    /**
     * Get subtract
     *
     * @return string
     */
    public function getSubtract()
    {
        return $this->subtract;
    }

    /**
     * Set regDate
     *
     * @param string $regDate
     *
     * @return PostCosts
     */
    public function setRegDate($regDate)
    {
        $this->reg_date = $regDate;

        return $this;
    }

    /**
     * Get regDate
     *
     * @return string
     */
    public function getRegDate()
    {
        return $this->reg_date;
    }
}

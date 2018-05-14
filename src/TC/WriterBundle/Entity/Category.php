<?php

namespace TC\WriterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="TC\WriterBundle\Repository\CategoryRepository")
 */
class Category
{
    
    /**
    * @ORM\OneToMany(targetEntity="TC\WriterBundle\Entity\Guides", mappedBy="category")
    */
    private $guides;

    /**
    * @ORM\ManyToOne(targetEntity="TC\WriterBundle\Entity\Games", inversedBy="categories")
    * @ORM\JoinColumn(nullable=false)
    */
    private $game;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="miniatureURL", type="string", length=255)
     */
    private $miniatureURL;

    /**
     * @var string
     *
     * @ORM\Column(name="miniatureAlt", type="string", length=255)
     */
    private $miniatureAlt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set miniatureURL
     *
     * @param string $miniatureURL
     *
     * @return category
     */
    public function setMiniatureURL($miniatureURL)
    {
        $this->miniatureURL = $miniatureURL;

        return $this;
    }

    /**
     * Get miniatureURL
     *
     * @return string
     */
    public function getMiniatureURL()
    {
        return $this->miniatureURL;
    }

    /**
     * Set miniatureAlt
     *
     * @param string $miniatureAlt
     *
     * @return category
     */
    public function setMiniatureAlt($miniatureAlt)
    {
        $this->miniatureAlt = $miniatureAlt;

        return $this;
    }

    /**
     * Get miniatureAlt
     *
     * @return string
     */
    public function getMiniatureAlt()
    {
        return $this->miniatureAlt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->guides = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add guide
     *
     * @param \TC\WriterBundle\Entity\Guides $guide
     *
     * @return Category
     */
    public function addGuide(\TC\WriterBundle\Entity\Guides $guide)
    {
        $this->guides[] = $guide;

        $guide->setCategory($this);

        return $this;
    }

    /**
     * Remove guide
     *
     * @param \TC\WriterBundle\Entity\Guides $guide
     */
    public function removeGuide(\TC\WriterBundle\Entity\Guides $guide)
    {
        $this->guides->removeElement($guide);
    }

    /**
     * Get guides
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGuides()
    {
        return $this->guides;
    }

    /**
     * Set game
     *
     * @param \TC\WriterBundle\Entity\Games $game
     *
     * @return Category
     */
    public function setGame(\TC\WriterBundle\Entity\Games $game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \TC\WriterBundle\Entity\Games
     */
    public function getGame()
    {
        return $this->game;
    }
}

<?php

namespace TC\WriterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Games
 *
 * @ORM\Table(name="games")
 * @ORM\Entity(repositoryClass="TC\WriterBundle\Repository\GamesRepository")
 */
class Games
{
    /**
    * @ORM\OneToMany(targetEntity="TC\WriterBundle\Entity\News", mappedBy="game")
    */
    private $news;

    /**
    * @ORM\OneToMany(targetEntity="TC\WriterBundle\Entity\Articles", mappedBy="game")
    */
    private $articles;

    /**
    * @ORM\OneToMany(targetEntity="TC\WriterBundle\Entity\Guides", mappedBy="game")
    */
    private $guides;

    /**
    * @ORM\OneToMany(targetEntity="TC\WriterBundle\Entity\Category", mappedBy="game")
    */
    private $categories;

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
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
    * @var string
    *
    * @ORM\Column(name="description", type="text")
    */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="editor", type="string", length=255)
     */
    private $editor;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="plateform", type="string", length=255)
     */
    private $plateform;

    /**
     * @var string
     *
     *@ORM\Column(name="cover_alt", type="string", length=255)
     */
    private $coverAlt;

     /**
     * @var string
     *
     *@ORM\Column(name="cover_url", type="string", length=255)
     */
    private $coverUrl;

    /**
     * @var string
     *
     *@ORM\Column(name="jaquette_alt", type="string", length=255)
     */
    private $jaquetteAlt;

     /**
     * @var string
     *
     *@ORM\Column(name="jaquette_url", type="string", length=255)
     */
    private $jaquetteUrl;

    /**
    * @var string
    *
    *@ORM\Column(name="best", type="boolean")
    */
    private $best = false;

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
     * @return Games
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Games
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
     * Set editor
     *
     * @param string $editor
     *
     * @return Games
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor
     *
     * @return string
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Games
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add news
     *
     * @param \TC\WriterBundle\Entity\News $news
     *
     * @return Games
     */
    public function addNews(\TC\WriterBundle\Entity\News $news)
    {
        $this->news[] = $news;

        $news->setGame($this);

        return $this;
    }

    /**
     * Remove news
     *
     * @param \TC\WriterBundle\Entity\News $news
     */
    public function removeNews(\TC\WriterBundle\Entity\News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Add article
     *
     * @param \TC\WriterBundle\Entity\Articles $article
     *
     * @return Games
     */
    public function addArticle(\TC\WriterBundle\Entity\Articles $article)
    {
        $this->articles[] = $article;

        $article->setGame($this);

        return $this;
    }

    /**
     * Remove article
     *
     * @param \TC\WriterBundle\Entity\Articles $article
     */
    public function removeArticle(\TC\WriterBundle\Entity\Articles $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add guide
     *
     * @param \TC\WriterBundle\Entity\Guides $guide
     *
     * @return Games
     */
    public function addGuide(\TC\WriterBundle\Entity\Guides $guide)
    {
        $this->guides[] = $guide;

        $guide->setGame($this);

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
     * Set coverAlt
     *
     * @param string $coverAlt
     *
     * @return Games
     */
    public function setCoverAlt($coverAlt)
    {
        $this->coverAlt = $coverAlt;

        return $this;
    }

    /**
     * Get coverAlt
     *
     * @return string
     */
    public function getCoverAlt()
    {
        return $this->coverAlt;
    }

    /**
     * Set coverUrl
     *
     * @param string $coverUrl
     *
     * @return Games
     */
    public function setCoverUrl($coverUrl)
    {
        $this->coverUrl = $coverUrl;

        return $this;
    }

    /**
     * Get coverUrl
     *
     * @return string
     */
    public function getCoverUrl()
    {
        return $this->coverUrl;
    }

    /**
     * Set jaquetteAlt
     *
     * @param string $jaquetteAlt
     *
     * @return Games
     */
    public function setJaquetteAlt($jaquetteAlt)
    {
        $this->jaquetteAlt = $jaquetteAlt;

        return $this;
    }

    /**
     * Get jaquetteAlt
     *
     * @return string
     */
    public function getJaquetteAlt()
    {
        return $this->jaquetteAlt;
    }

    /**
     * Set jaquetteUrl
     *
     * @param string $jaquetteUrl
     *
     * @return Games
     */
    public function setJaquetteUrl($jaquetteUrl)
    {
        $this->jaquetteUrl = $jaquetteUrl;

        return $this;
    }

    /**
     * Get jaquetteUrl
     *
     * @return string
     */
    public function getJaquetteUrl()
    {
        return $this->jaquetteUrl;
    }

    /**
     * Set best
     *
     * @param boolean $best
     *
     * @return Games
     */
    public function setBest($best)
    {
        $this->best = $best;

        return $this;
    }

    /**
     * Get best
     *
     * @return boolean
     */
    public function getBest()
    {
        return $this->best;
    }

    /**
     * Set plateform
     *
     * @param string $plateform
     *
     * @return Games
     */
    public function setPlateform($plateform)
    {
        $this->plateform = $plateform;

        return $this;
    }

    /**
     * Get plateform
     *
     * @return string
     */
    public function getPlateform()
    {
        return $this->plateform;
    }

    /**
     * Add category
     *
     * @param \TC\WriterBundle\Entity\Guides $category
     *
     * @return Games
     */
    public function addCategory(\TC\WriterBundle\Entity\Guides $category)
    {
        $this->categories[] = $category;

        $category->setGame($this);

        return $this;
    }

    /**
     * Remove category
     *
     * @param \TC\WriterBundle\Entity\Guides $category
     */
    public function removeCategory(\TC\WriterBundle\Entity\Guides $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Games
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
}

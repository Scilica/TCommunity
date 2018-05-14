<?php

namespace TC\WriterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="TC\WriterBundle\Repository\TagRepository")
 */
class Tag
{
    /**
    * @ORM\ManyToMany(targetEntity="TC\WriterBundle\Entity\News" ,cascade={"persist"},inversedBy="tag")
    * @ORM\JoinTable(name="tc_news_tag")
    */
    private $news;

    /**
    * @ORM\ManyToMany(targetEntity="TC\WriterBundle\Entity\Articles", cascade={"persist"},inversedBy="tag")
    * @ORM\JoinTable(name="tc_articles_tag")
    */
    private $articles;

    /**
    * @ORM\ManyToMany(targetEntity="TC\WriterBundle\Entity\Guides", cascade={"persist"},inversedBy="tag")
    * @ORM\JoinTable(name="tc_guides_tag")
    */
    private $guides;

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
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;


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
     * @return Tag
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
     * Set color
     *
     * @param string $color
     *
     * @return Tag
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add article
     *
     * @param \TC\WriterBundle\Entity\Articles $article
     *
     * @return Tag
     */
    public function addArticle(\TC\WriterBundle\Entity\Articles $article)
    {
        $this->articles[] = $article;

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
     * @return Tag
     */
    public function addGuide(\TC\WriterBundle\Entity\Guides $guide)
    {
        $this->guides[] = $guide;

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
     * Add news
     *
     * @param \TC\WriterBundle\Entity\News $news
     *
     * @return Tag
     */
    public function addNews(\TC\WriterBundle\Entity\News $news)
    {
        $this->news[] = $news;

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
}

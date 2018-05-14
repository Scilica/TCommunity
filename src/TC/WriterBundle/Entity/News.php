<?php

namespace TC\WriterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Cengizhan\ViewsCounterBundle\Model\VisitableInterface;
use Cengizhan\ViewsCounterBundle\Traits\VisitableEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="TC\WriterBundle\Repository\NewsRepository")
 */
class News implements VisitableInterface
{

    use VisitableEntityTrait;
    /**
    * @ORM\ManyToOne(targetEntity="TC\WriterBundle\Entity\Games", inversedBy="news")
    * @ORM\JoinColumn(nullable=false)
    */
    private $game;

    /**
    * @ORM\ManyToMany(targetEntity="TC\WriterBundle\Entity\Tag", mappedBy="news")
    * @ORM\JoinColumn(nullable=false)
    */
    private $tag;
    
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
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="miniature_alt", type="string", length=255)
     */
    private $miniatureAlt;

    /**
     * @var string
     *
     * @ORM\Column(name="miniature_url", type="string", length=255)
     */
    private $miniatureUrl;


    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
    * @ORM\Column(name="published", type="boolean")
    */
    private $published = true;

    public function __construct()
    {
        $this->date = new \Datetime();
    }
    
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
     * @return News
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
     * Set author
     *
     * @param string $author
     *
     * @return News
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return News
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
     * Set miniatureAlt
     *
     * @param string $miniatureAlt
     *
     * @return News
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
     * Set miniatureUrl
     *
     * @param string $miniatureUrl
     *
     * @return News
     */
    public function setMiniatureUrl($miniatureUrl)
    {
        $this->miniatureUrl = $miniatureUrl;

        return $this;
    }

    /**
     * Get miniatureUrl
     *
     * @return string
     */
    public function getMiniatureUrl()
    {
        return $this->miniatureUrl;
    }

    /**
     * Set intro
     *
     * @param string $intro
     *
     * @return News
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return News
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return News
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set game
     *
     * @param \TC\WriterBundle\Entity\Games $game
     *
     * @return News
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


    /**
     * Add tag
     *
     * @param \TC\WriterBundle\Entity\Tag $tag
     *
     * @return News
     */
    public function addTag(\TC\WriterBundle\Entity\Tag $tag)
    {
        $this->tag[] = $tag;

        $tag->addNews($this);

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \TC\WriterBundle\Entity\Tag $tag
     */
    public function removeTag(\TC\WriterBundle\Entity\Tag $tag)
    {
        $this->tag->removeElement($tag);
    }

    /**
     * Get tag
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTag()
    {
        return $this->tag;
    }
}

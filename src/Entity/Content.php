<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Content
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $author;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $title;


    /**
     * @ORM\Column(type="string")
     */
    private $text;


    /**
     * @ORM\Column(type="string")
     */
    private $access;


    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Headword", inversedBy="contents")
     */
    private $headwords;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Adventure", inversedBy="contents")
     */
    private $attachedAdventure;

    public function __construct()
    {
        $this->headwords = new ArrayCollection();
        $this->attachedAdventure = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $author User
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param $title string
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $text string
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param $access string
     */
    public function setAccess($access)
    {
        $this->access = $access;
    }

    /**
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }


    /**
     * @return ArrayCollection
     */
    public function getHeadwords()
    {
        return $this->headwords;
    }

    /**
     * @return ArrayCollection
     */
    public function getAttachedAdventure()
    {
        return $this->attachedAdventure;
    }

}
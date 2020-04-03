<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Campaign
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
     * @Assert\NotBlank()
     */
    private $blurb;

    /**
     * * @ORM\OneToMany(targetEntity="Adventure", mappedBy="campaign")
     *
     */
    private $adventures;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param User $author
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
     * @param string $title
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
     * @param string $blurb
     */
    public function setBlurb($blurb)
    {
        $this->blurb = $blurb;
    }

    /**
     * @return string
     */
    public function getBlurb()
    {
        return $this->blurb;
    }


    /**
     * @return ArrayCollection
     */
    public function getAdventures()
    {
        return $this->adventures;
    }


    public function __constructor()
    {
        $this->adventures = new ArrayCollection();
    }
}
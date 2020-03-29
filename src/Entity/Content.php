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
    public $id;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    public $author;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    public $title;


    /**
     * @ORM\Column(type="string")
     */
    public $text;


    /**
     * @ORM\Column(type="string")
     */
    public $access;


    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Headword", inversedBy="contents")
     */
    public $headwords;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Adventure", inversedBy="contents")
     */
    public $attachedAdventure;

    public function __constructor()
    {
        $this->headwords = new ArrayCollection();
        $this->attachedAdventure = new ArrayCollection();
    }


}
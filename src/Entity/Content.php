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
     * @ORM\ManyToMany(targetEntity="Headword", inversedBy="contents")
     */
    public $headwords;

    public function __constructor()
    {
        $this->headwords = new ArrayCollection();
    }

}
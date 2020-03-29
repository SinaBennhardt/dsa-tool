<?php


namespace App\Entity;

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
     * @Assert\NotBlank()
     */
    public $blurb;

    /**
     * * @ORM\OneToMany(targetEntity="Adventure", mappedBy="campaign")
     *
     */
    public $adventures;
}
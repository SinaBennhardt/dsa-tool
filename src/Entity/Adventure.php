<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Adventure
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
    public $name;

    /**
     * @ORM\Column(type="string")
     */
    public $blurb;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign", inversedBy="adventures")
     *
     */
    public $campaign;

    /**
     * @ORM\ManyToMany(targetEntity="Content", mappedBy="attachedAdventure")
     *
     */
    public $contents;
}

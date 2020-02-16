<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Headword
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(allowNull=true)
     */
    public $headwordName;

    /**
     * @ORM\ManyToMany(targetEntity="Content", mappedBy="headwords")
     *
     */
    public $contents;
}
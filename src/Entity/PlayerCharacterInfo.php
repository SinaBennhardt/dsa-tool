<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class PlayerCharacterInfo
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    public $id;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     *
     */
    public $user;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     */

    public $playerType = 'Held';

    /**
     * @ORM\OneToOne(targetEntity="PlayerProperties")
     */
    public $playerProperty;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     */
    public $characterName;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    public $race;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    public $culture;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    public $profession;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotNull()
     */
    public $socialStatus;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    public $advantages;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    public $disadvantages;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    public $uniqueSkills;



}
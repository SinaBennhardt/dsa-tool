<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class PlayerProperties
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     *
     */
   public $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */

   public $user;


    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    public $courage;

    /**
     * @ORM\Column(type="integer")
     *@Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    public $intelligence;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    public $intuition;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    public $charisma;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    public $fingerDex;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    public $generalDex;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    public $constitution;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    public $strength;

    public function getLP() {
        $KO = $this->constitution;
        $KK = $this->strength;

        $LP = round(($KO + $KO + $KK)/2) + 10;

        return $LP;
    }

    public function getAsP() {
        $MU = $this->courage;
        $IN = $this->intuition;
        $CH = $this->charisma;

        $AsP = round(($MU + $IN + $CH)/2);
        return $AsP;
    }


    public function getKP()
    {
        $KP = 24;
        return $KP;
    }

    public function getMR() {

        $MU = $this->courage;
        $KL = $this->intelligence;
        $KO = $this->constitution;

        $MR = round(($MU + $KL + $KO)/5) - 4;

        return $MR;
    }

 }
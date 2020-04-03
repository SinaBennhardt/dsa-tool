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
   private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */

   private $user;


    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    private $courage;

    /**
     * @ORM\Column(type="integer")
     *@Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    private $intelligence;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    private $intuition;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    private $charisma;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    private $fingerDex;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    private $generalDex;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    private $constitution;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 20
     * )
     */
    private $strength;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $user User
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $courage integer
     */
    public function setCourage($courage)
    {
        $this->courage = $courage;
    }

    /**
     * @return integer
     */
    public function getCourage()
    {
        return $this->courage;
    }

    /**
     * @param $intelligence integer
     */
    public function setIntelligence($intelligence)
    {
        $this->intelligence = $intelligence;
    }

    /**
     * @return integer
     */
    public function getIntelligence()
    {
        return $this->intelligence;
    }

    /**
     * @param $intuition integer
     */
    public function setIntuition($intuition)
    {
        $this->intuition = $intuition;
    }

    /**
     * @return integer
     */
    public function getIntuition()
    {
        return $this->intuition;
    }

    /**
     * @param $charisma integer
     */
    public function setCharisma($charisma)
    {
        $this->charisma = $charisma;
    }

    /**
     * @return integer
     */
    public function getCharisma()
    {
        return $this->charisma;
    }

    /**
     * @param $fingerDex integer
     */
    public function setFingerDex($fingerDex)
    {
        $this->fingerDex = $fingerDex;
    }

    /**
     * @return integer
     */
    public function getFingerDex()
    {
        return $this->fingerDex;
    }

    /**
     * @param $generalDex integer
     */
    public function setGeneralDex($generalDex)
    {
        $this->generalDex = $generalDex;
    }

    /**
     * @return integer
     */
    public function getGeneralDex()
    {
        return $this->generalDex;
    }

    /**
     * @param $constitution integer
     */
    public function setConstitution($constitution)
    {
        $this->constitution = $constitution;
    }

    /**
     * @return integer
     */
    public function getConstitution()
    {
        return $this->constitution;
    }

    /**
     * @param $strength integer
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;
    }

    /**
     * @return integer
     */
    public function getStrength()
    {
        return $this->strength;
    }


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
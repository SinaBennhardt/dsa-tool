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
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     *
     */
    private $user;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     */

    private $playerType = 'Held';

    /**
     * @ORM\OneToOne(targetEntity="PlayerProperties")
     */
    private $playerProperty;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     */
    private $characterName;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $race;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $culture;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $profession;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     */
    private $socialStatus;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $advantages;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $disadvantages;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $uniqueSkills;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $user string
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $playerType string
     */
    public function setPlayerType($playerType)
    {
        $this->playerType = $playerType;
    }

    /**
     * @return string
     */
    public function getPlayerType()
    {
        return $this->playerType;
    }


    /**
     * @param $playerProperty PlayerProperties
     */
    public function setPlayerProperty($playerProperty)
    {
        $this->playerProperty = $playerProperty;
    }

    /**
     * @return PlayerProperties
     */
    public function getPlayerProperty()
    {
        return $this->playerProperty;
    }


    /**
     * @param $characterName string
     */
    public function setCharacterName($characterName)
    {
        $this->characterName = $characterName;
    }

    /**
     * @return string
     */
    public function getCharacterName()
    {
        return $this->characterName;
    }

    /**
     * @param $race string
     */
    public function setRace($race)
    {
        $this->race = $race;
    }

    /**
     * @return string
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param $culture string
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;
    }

    /**
     * @return string
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * @param $profession string
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;
    }

    /**
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * @param $socialStatus integer
     */
    public function setSocialStatus($socialStatus)
    {
        $this->socialStatus = $socialStatus;
    }

    /**
     * @return integer
     */
    public function getSocialStatus()
    {
        return $this->socialStatus;
    }

    /**
     * @param $advantages string
     */
    public function setAdvantages($advantages)
    {
        $this->advantages = $advantages;
    }

    /**
     * @return string
     */
    public function getAdvantages()
    {
        return $this->advantages;
    }

    /**
     * @param $disadvantages string
     */
    public function setDisadvantages($disadvantages)
    {
        $this->disadvantages = $disadvantages;
    }

    /**
     * @return string
     */
    public function getDisadvantages()
    {
        return $this->disadvantages;
    }

    /**
     * @param $uniqueSkills string
     */
    public function setUniqueSkills($uniqueSkills)
    {
        $this->uniqueSkills = $uniqueSkills;
    }

    /**
     * @return string
     */
    public function getUniqueSkills()
    {
        return $this->uniqueSkills;
    }
}
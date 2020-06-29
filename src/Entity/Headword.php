<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HeadwordRepository")
 */
class Headword
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(allowNull=true)
     */
    private $headwordName;

    /**
     * @ORM\ManyToMany(targetEntity="Content", mappedBy="headwords")
     *
     */
    private $contents;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign", inversedBy="headwords")
     *
     */
    private $campaign;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $headwordName string
     */
    public function setHeadwordName($headwordName)
    {
        $this->headwordName = $headwordName;
    }

    /**
     * @return string
     */
    public function getHeadwordName()
    {
        return $this->headwordName;
    }

    /**
     * @return ArrayCollection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;

    }

    /**
     * @param $campaign
     * @return void
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
    }



    public function __construct()
    {
        $this->contents = new ArrayCollection();
    }
}
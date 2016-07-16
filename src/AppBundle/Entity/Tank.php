<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-26
 * Time: 13:11
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TankRepository")
 * @ORM\Table(name="tank")
 */
class Tank
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $tankId;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $nation;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $shortName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $tag;

    /**
     * @ORM\Column(type="smallint")
     */
    private $tier;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPremium = 0;

    /**
     * @ORM\Column(type="array")
     */
    private $images;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceCredit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceGold = null;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tankId
     *
     * @param integer $tankId
     *
     * @return Tank
     */
    public function setTankId($tankId)
    {
        $this->tankId = $tankId;

        return $this;
    }

    /**
     * Get tankId
     *
     * @return integer
     */
    public function getTankId()
    {
        return $this->tankId;
    }

    /**
     * Set nation
     *
     * @param string $nation
     *
     * @return Tank
     */
    public function setNation($nation)
    {
        $this->nation = $nation;

        return $this;
    }

    /**
     * Get nation
     *
     * @return string
     */
    public function getNation()
    {
        return $this->nation;
    }

    /**
     * Set compDescr
     *
     * @param integer $compDescr
     *
     * @return Tank
     */
    public function setCompDescr($compDescr)
    {
        $this->compDescr = $compDescr;

        return $this;
    }

    /**
     * Get compDescr
     *
     * @return integer
     */
    public function getCompDescr()
    {
        return $this->compDescr;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tank
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     *
     * @return Tank
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return Tank
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set tier
     *
     * @param integer $tier
     *
     * @return Tank
     */
    public function setTier($tier)
    {
        $this->tier = $tier;

        return $this;
    }

    /**
     * Get tier
     *
     * @return integer
     */
    public function getTier()
    {
        return $this->tier;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Tank
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isPremium
     *
     * @param boolean $isPremium
     *
     * @return Tank
     */
    public function setIsPremium($isPremium)
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    /**
     * Get isPremium
     *
     * @return boolean
     */
    public function getIsPremium()
    {
        return $this->isPremium;
    }

    /**
     * Set images
     *
     * @param array $images
     *
     * @return Tank
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get images
     *
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set priceCredit
     *
     * @param integer $priceCredit
     *
     * @return Tank
     */
    public function setPriceCredit($priceCredit)
    {
        $this->priceCredit = $priceCredit;

        return $this;
    }

    /**
     * Get priceCredit
     *
     * @return integer
     */
    public function getPriceCredit()
    {
        return $this->priceCredit;
    }

    /**
     * Set priceGold
     *
     * @param integer $priceGold
     *
     * @return Tank
     */
    public function setPriceGold($priceGold)
    {
        $this->priceGold = $priceGold;

        return $this;
    }

    /**
     * Get priceGold
     *
     * @return integer
     */
    public function getPriceGold()
    {
        return $this->priceGold;
    }

    /**
     * @return string
     */
    public function getSmallIcon()
    {
        return $this->getImages()['small_icon'];
    }

    /**
     * @return string
     */
    public function getBigIcon()
    {
        return $this->getImages()['big_icon'];
    }

    /**
     * @return string
     */
    public function getContourIcon()
    {
        return $this->getImages()['contour_icon'];
    }

}

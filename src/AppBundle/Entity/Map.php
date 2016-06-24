<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-23
 * Time: 21:52
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Battle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MapRepository")
 */
class Map
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40)
     */
    private $arenaId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $camo;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

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
     * Set arenaId
     *
     * @param string $arenaId
     *
     * @return Map
     */
    public function setArenaId($arenaId)
    {
        $this->arenaId = $arenaId;

        return $this;
    }

    /**
     * Get arenaId
     *
     * @return string
     */
    public function getArenaId()
    {
        return $this->arenaId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Map
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
     * Set camo
     *
     * @param string $camo
     *
     * @return Map
     */
    public function setCamo($camo)
    {
        $this->camo = $camo;

        return $this;
    }

    /**
     * Get camo
     *
     * @return string
     */
    public function getCamo()
    {
        return $this->camo;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Map
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}

<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-26
 * Time: 13:42
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RefreshMonitorRepository")
 * @ORM\Table(name="refresh_monitor")
 */
class RefreshMonitor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastTankRefreshAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastMapRefreshAt;

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
     * Set lastTankRefreshAt
     *
     * @param \DateTime $lastTankRefreshAt
     *
     * @return RefreshMonitor
     */
    public function setLastTankRefreshAt($lastTankRefreshAt)
    {
        $this->lastTankRefreshAt = $lastTankRefreshAt;

        return $this;
    }

    /**
     * Get lastTankRefreshAt
     *
     * @return \DateTime
     */
    public function getLastTankRefreshAt()
    {
        return $this->lastTankRefreshAt;
    }

    /**
     * Set lastMapRefreshAt
     *
     * @param \DateTime $lastMapRefreshAt
     *
     * @return RefreshMonitor
     */
    public function setLastMapRefreshAt($lastMapRefreshAt)
    {
        $this->lastMapRefreshAt = $lastMapRefreshAt;

        return $this;
    }

    /**
     * Get lastMapRefreshAt
     *
     * @return \DateTime
     */
    public function getLastMapRefreshAt()
    {
        return $this->lastMapRefreshAt;
    }
}

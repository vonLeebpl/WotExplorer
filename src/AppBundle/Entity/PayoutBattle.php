<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-08-16
 * Time: 22:53
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="payout_battle")
 */
class PayoutBattle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Payout
     * @ORM\ManyToOne(targetEntity="Payout", inversedBy="payoutBattles")
     */
    private $payout;

    /**
     * @var Payout
     * @ORM\ManyToOne(targetEntity="Battle", inversedBy="payoutBattles")
     */
    private $battle;

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
     * Set payout
     *
     * @param \AppBundle\Entity\Payout $payout
     *
     * @return PayoutBattle
     */
    public function setPayout(\AppBundle\Entity\Payout $payout = null)
    {
        $this->payout = $payout;

        return $this;
    }

    /**
     * Get payout
     *
     * @return \AppBundle\Entity\Payout
     */
    public function getPayout()
    {
        return $this->payout;
    }

    /**
     * Set battle
     *
     * @param \AppBundle\Entity\Battle $battle
     *
     * @return PayoutBattle
     */
    public function setBattle(\AppBundle\Entity\Battle $battle = null)
    {
        $this->battle = $battle;

        return $this;
    }

    /**
     * Get battle
     *
     * @return \AppBundle\Entity\Battle
     */
    public function getBattle()
    {
        return $this->battle;
    }
}

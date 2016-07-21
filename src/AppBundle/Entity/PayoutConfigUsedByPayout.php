<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-07-18
 * Time: 18:58
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="payout_config_used_by_payout")
 */
class PayoutConfigUsedByPayout
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Payout", inversedBy="config")
     * @ORM\JoinColumn(name="payout_id", referencedColumnName="id")
     */
    private $payout;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="This value can not be less than 0")
     */
    private $ptsCommanderWin = 0;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="This value can not be less than 0")
     *
     */
    private $ptsCommanderDraw = 0;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="This value can not be less than 0")
     */
    private $ptsCommanderLost = 0;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="This value can not be less than 0")
     */
    private $ptsPlayerWin = 0;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="This value can not be less than 0")
     */
    private $ptsPlayerDraw = 0;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="This value can not be less than 0")
     */
    private $ptsPlayerLost = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="This value can not be less than 0")
     */
    private $minResourceToBePaid = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="This value can not be less than 0")
     */
    private $minResourceToBeExtraPaid = 0;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     * @Assert\Type(
     *     type="numeric",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      minMessage = "Percent value can not be less than 0.",
     *      maxMessage = "Percent value can not be more than 100."
     * )
     */
    private $percentCw = 0;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     * @Assert\Type(
     *     type="numeric",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      minMessage = "Percent value can not be less than 0.",
     *      maxMessage = "Percent value can not be more than 100."
     * )
     */
    private $percentSh = 0;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     * @Assert\Type(
     *     type="numeric",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      minMessage = "Percent value can not be less than 0.",
     *      maxMessage = "Percent value can not be more than 100."
     * )
     */
    private $percentHqBonus = 0;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     * @Assert\Type(
     *     type="numeric",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      minMessage = "Percent value can not be less than 0.",
     *      maxMessage = "Percent value can not be more than 100."
     * )
     */
    private $percentExtraShare = 0;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     * @Assert\Type(
     *     type="numeric",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      minMessage = "Percent value can not be less than 0.",
     *      maxMessage = "Percent value can not be more than 100."
     * )
     */
    private $recruitFactor = 0;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     * @Assert\Type(
     *     type="numeric",
     *     message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 1,
     *      minMessage = "Percent value can not be less than 0.",
     *      maxMessage = "Percent value can not be more than 100."
     * )
     */
    private $reservistFactor = 0;

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // check if global split variables sum to 100%
        if ($this->percentCw + $this->percentSh + $this->percentHqBonus != 1)
        {
            $context->buildViolation('Global gold split percents need to sum up to 100!')
                ->addViolation();
            return;
        }
        if ($this->minResourceToBeExtraPaid < $this->minResourceToBePaid)
        {
            $context->buildViolation('Minimum extra resource can not be less than minimum resource to be paid!')
                ->addViolation();
            return;
        }
    }

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
     * Set ptsCommanderWin
     *
     * @param integer $ptsCommanderWin
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPtsCommanderWin($ptsCommanderWin)
    {
        $this->ptsCommanderWin = $ptsCommanderWin;

        return $this;
    }

    /**
     * Get ptsCommanderWin
     *
     * @return integer
     */
    public function getPtsCommanderWin()
    {
        return $this->ptsCommanderWin;
    }

    /**
     * Set ptsCommanderDraw
     *
     * @param integer $ptsCommanderDraw
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPtsCommanderDraw($ptsCommanderDraw)
    {
        $this->ptsCommanderDraw = $ptsCommanderDraw;

        return $this;
    }

    /**
     * Get ptsCommanderDraw
     *
     * @return integer
     */
    public function getPtsCommanderDraw()
    {
        return $this->ptsCommanderDraw;
    }

    /**
     * Set ptsCommanderLost
     *
     * @param integer $ptsCommanderLost
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPtsCommanderLost($ptsCommanderLost)
    {
        $this->ptsCommanderLost = $ptsCommanderLost;

        return $this;
    }

    /**
     * Get ptsCommanderLost
     *
     * @return integer
     */
    public function getPtsCommanderLost()
    {
        return $this->ptsCommanderLost;
    }

    /**
     * Set ptsPlayerWon
     *
     * @param integer $ptsPlayerWin
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPtsPlayerWin($ptsPlayerWin)
    {
        $this->ptsPlayerWin = $ptsPlayerWin;

        return $this;
    }

    /**
     * Get ptsPlayerWon
     *
     * @return integer
     */
    public function getPtsPlayerWin()
    {
        return $this->ptsPlayerWin;
    }

    /**
     * Set ptsPlayerDraw
     *
     * @param integer $ptsPlayerDraw
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPtsPlayerDraw($ptsPlayerDraw)
    {
        $this->ptsPlayerDraw = $ptsPlayerDraw;

        return $this;
    }

    /**
     * Get ptsPlayerDraw
     *
     * @return integer
     */
    public function getPtsPlayerDraw()
    {
        return $this->ptsPlayerDraw;
    }

    /**
     * Set ptsPlayerLost
     *
     * @param integer $ptsPlayerLost
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPtsPlayerLost($ptsPlayerLost)
    {
        $this->ptsPlayerLost = $ptsPlayerLost;

        return $this;
    }

    /**
     * Get ptsPlayerLost
     *
     * @return integer
     */
    public function getPtsPlayerLost()
    {
        return $this->ptsPlayerLost;
    }

    /**
     * Set minResourceToBePaid
     *
     * @param integer $minResourceToBePaid
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setMinResourceToBePaid($minResourceToBePaid)
    {
        $this->minResourceToBePaid = $minResourceToBePaid;

        return $this;
    }

    /**
     * Get minResourceToBePaid
     *
     * @return integer
     */
    public function getMinResourceToBePaid()
    {
        return $this->minResourceToBePaid;
    }

    /**
     * Set minResourceToBeExtraPaid
     *
     * @param integer $minResourceToBeExtraPaid
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setMinResourceToBeExtraPaid($minResourceToBeExtraPaid)
    {
        $this->minResourceToBeExtraPaid = $minResourceToBeExtraPaid;

        return $this;
    }

    /**
     * Get minResourceToBeExtraPaid
     *
     * @return integer
     */
    public function getMinResourceToBeExtraPaid()
    {
        return $this->minResourceToBeExtraPaid;
    }

    /**
     * Set percentCw
     *
     * @param string $percentCw
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPercentCw($percentCw)
    {
        $this->percentCw = $percentCw;

        return $this;
    }

    /**
     * Get percentCw
     *
     * @return string
     */
    public function getPercentCw()
    {
        return $this->percentCw;
    }

    /**
     * Set percentSh
     *
     * @param string $percentSh
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPercentSh($percentSh)
    {
        $this->percentSh = $percentSh;

        return $this;
    }

    /**
     * Get percentSh
     *
     * @return string
     */
    public function getPercentSh()
    {
        return $this->percentSh;
    }

    /**
     * Set percentHqBonus
     *
     * @param string $percentHqBonus
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPercentHqBonus($percentHqBonus)
    {
        $this->percentHqBonus = $percentHqBonus;

        return $this;
    }

    /**
     * Get percentHqBonus
     *
     * @return string
     */
    public function getPercentHqBonus()
    {
        return $this->percentHqBonus;
    }

    /**
     * Set percentExtraShare
     *
     * @param string $percentExtraShare
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPercentExtraShare($percentExtraShare)
    {
        $this->percentExtraShare = $percentExtraShare;

        return $this;
    }

    /**
     * Get percentExtraShare
     *
     * @return string
     */
    public function getPercentExtraShare()
    {
        return $this->percentExtraShare;
    }

    /**
     * Set recruitFactor
     *
     * @param string $recruitFactor
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setRecruitFactor($recruitFactor)
    {
        $this->recruitFactor = $recruitFactor;

        return $this;
    }

    /**
     * Get recruitFactor
     *
     * @return string
     */
    public function getRecruitFactor()
    {
        return $this->recruitFactor;
    }

    /**
     * Set reservistFactor
     *
     * @param string $reservistFactor
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setReservistFactor($reservistFactor)
    {
        $this->reservistFactor = $reservistFactor;

        return $this;
    }

    /**
     * Get reservistFactor
     *
     * @return string
     */
    public function getReservistFactor()
    {
        return $this->reservistFactor;
    }

    /**
     * Set payout
     *
     * @param \AppBundle\Entity\Payout $payout
     *
     * @return PayoutConfigUsedByPayout
     */
    public function setPayout(Payout $payout = null)
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
}

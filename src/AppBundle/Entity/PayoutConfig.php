<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-07-16
 * Time: 16:19
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="payout_config")
 */
class PayoutConfig
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private $ptsPlayerWon = 0;

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
     * @return PayoutConfig
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
     * @return PayoutConfig
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
     * @return PayoutConfig
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
     * @param integer $ptsPlayerWon
     *
     * @return PayoutConfig
     */
    public function setPtsPlayerWon($ptsPlayerWon)
    {
        $this->ptsPlayerWon = $ptsPlayerWon;

        return $this;
    }

    /**
     * Get ptsPlayerWon
     *
     * @return integer
     */
    public function getPtsPlayerWon()
    {
        return $this->ptsPlayerWon;
    }

    /**
     * Set ptsPlayerDraw
     *
     * @param integer $ptsPlayerDraw
     *
     * @return PayoutConfig
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
     * @return PayoutConfig
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
     * @return PayoutConfig
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
     * @return PayoutConfig
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
     * @param integer $percentCw
     *
     * @return PayoutConfig
     */
    public function setPercentCw($percentCw)
    {
        $this->percentCw = $percentCw;

        return $this;
    }

    /**
     * Get percentCw
     *
     * @return integer
     */
    public function getPercentCw()
    {
        return $this->percentCw;
    }

    /**
     * Set percentSh
     *
     * @param integer $percentSh
     *
     * @return PayoutConfig
     */
    public function setPercentSh($percentSh)
    {
        $this->percentSh = $percentSh;

        return $this;
    }

    /**
     * Get percentSh
     *
     * @return integer
     */
    public function getPercentSh()
    {
        return $this->percentSh;
    }

    /**
     * Set percentHqBonus
     *
     * @param integer $percentHqBonus
     *
     * @return PayoutConfig
     */
    public function setPercentHqBonus($percentHqBonus)
    {
        $this->percentHqBonus = $percentHqBonus;

        return $this;
    }

    /**
     * Get percentHqBonus
     *
     * @return integer
     */
    public function getPercentHqBonus()
    {
        return $this->percentHqBonus;
    }

    /**
     * Set percentExtraShare
     *
     * @param integer $percentExtraShare
     *
     * @return PayoutConfig
     */
    public function setPercentExtraShare($percentExtraShare)
    {
        $this->percentExtraShare = $percentExtraShare;

        return $this;
    }

    /**
     * Get percentExtraShare
     *
     * @return integer
     */
    public function getPercentExtraShare()
    {
        return $this->percentExtraShare;
    }

    /**
     * Set recruitFactor
     *
     * @param integer $recruitFactor
     *
     * @return PayoutConfig
     */
    public function setRecruitFactor($recruitFactor)
    {
        $this->recruitFactor = $recruitFactor;

        return $this;
    }

    /**
     * Get recruitFactor
     *
     * @return integer
     */
    public function getRecruitFactor()
    {
        return $this->recruitFactor;
    }

    /**
     * Set reservistFactor
     *
     * @param integer $reservistFactor
     *
     * @return PayoutConfig
     */
    public function setReservistFactor($reservistFactor)
    {
        $this->reservistFactor = $reservistFactor;

        return $this;
    }

    /**
     * Get reservistFactor
     *
     * @return integer
     */
    public function getReservistFactor()
    {
        return $this->reservistFactor;
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // check if global split variables sum to 100%
        if ($this->getPercentCw() + $this->getPercentSh() + $this->getPercentHqBonus() != 1)
        {
            $context->buildViolation('Global gold split percents need to sum up to 100!')
                ->addViolation();
            return;
        }
    }
}

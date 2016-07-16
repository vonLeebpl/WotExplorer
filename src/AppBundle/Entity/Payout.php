<?php
/**
 * Copyright (c) 2016. by vonLeeb_pl@PSQD, MIT License
 */

/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-07-16
 * Time: 16:06
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PayoutRepository")
 * @ORM\Table(name="payout")
 */
class Payout
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    private $createdAt;
    
    private $createdBy;
    
    private $isCompleted;
    
    private $completedAt;
    
    private $completedBy;
    
    private $description;

    private $totalGoldToPay;
}
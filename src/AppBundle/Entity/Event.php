<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-04-11
 * Time: 23:56
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 * @ORM\Table(name="event")
 */
class Event
{

    /**
     * @ORM\Column(type="string", name="event_id")
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $event_name;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    protected $start;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $end;

    /**
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $fronts;


    /**
     * Set id
     *
     * @param string $id
     * @return Event
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set event_name
     *
     * @param string $eventName
     * @return Event
     */
    public function setEventName($eventName)
    {
        $this->event_name = $eventName;

        return $this;
    }

    /**
     * Get event_name
     *
     * @return string 
     */
    public function getEventName()
    {
        return $this->event_name;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Event
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Event
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Event
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set fronts
     *
     * @param string $fronts
     * @return Event
     */
    public function setFronts($fronts)
    {
        $this->fronts = $fronts;

        return $this;
    }

    /**
     * Get fronts
     *
     * @return string 
     */
    public function getFronts()
    {
        return $this->fronts;
    }

    /**
     * @param $event array with data from WOT API call
     */
    public function parseFromArray($event)
    {

        $this->setId($event['event_id']);
        $this->setEventName($event['event_name']);
        $this->setStatus($event['status']);
        $this->setStart(new \DateTime($event['start']));
        $this->setEnd(new \DateTime($event['end']));
        $fr= [];
        foreach($event['fronts'] as $front)
        {
            $fr[] = $front['front_id'];
        }
        $this->setFronts((string)(implode(',',$fr)));
    }
}

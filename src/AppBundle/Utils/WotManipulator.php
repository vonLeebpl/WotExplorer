<?php
/**
 * Created by PhpStorm.
 * User: JPa
 * Date: 2016-06-23
 * Time: 22:31
 */

namespace AppBundle\Utils;


use AppBundle\Entity\Map;
use AppBundle\Wot\WotApiWrapper;
use Doctrine\ORM\EntityManager;

class WotManipulator
{
    private $entityManager;

    public function __construct(EntityManager $entity_manager)
    {
        $this->entityManager = $entity_manager;
    }

    public function refreshWotMaps()
    {
        $wrapper = new WotApiWrapper();
        $data = $wrapper->getMaps();

        $rep = $this->entityManager->getRepository('AppBundle:Map');

        foreach ($data as $item)
        {
            $map = $rep->findOneByArenaId($item['arena_id']);
            if (!$map)
            {
                $map = new Map();
                $map->setArenaId($item['arena_id']);
                $map->setName($item['name_i18n']);
                $map->setCamo($item['camouflage_type']);
                $map->setDescription($item['description']);

                $this->entityManager->persist($map);
            }
        }

        $this->entityManager->flush();
    }
}
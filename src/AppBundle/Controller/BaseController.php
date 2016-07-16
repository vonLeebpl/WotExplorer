<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}

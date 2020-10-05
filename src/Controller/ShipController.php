<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShipController extends AbstractController
{
    /**
     * @Route("/ship", name="ship")
     */
    public function index()
    {
        return $this->render('ship/index.html.twig', [
            'controller_name' => 'ShipController',
        ]);
    }
}

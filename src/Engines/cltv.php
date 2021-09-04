<?php

namespace App\Engines;

use App\Entity\Game;
use App\Entity\ExternDatas;
use App\Entity\Adventure;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class cltv extends AbstractController
{
    
    public function cltv(Request $request): Response{

        $con = $this->getDoctrine()->getRepository(Game::class);
        $con2 = $this->getDoctrine()->getRepository(ExternDatas::class);
        $con3 = $this->getDoctrine()->getRepository(Adventure::class);

        return $this;

    }



}
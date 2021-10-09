<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Game;
use App\Entity\Adventure;
use Symfony\Component\Routing\Annotation\Route;

class CltvController extends AbstractController
{
        
    public function cltv(): float{

        $periodh = '2021-06-01'; // $request->request->get('')
        $periodi = '2021-07-31'; // $request->request->get('')

        $em = $this->getDoctrine()->getManager();
        $con = $em->getRepository(Game::class);        
        $con3 = $em->getRepository(Adventure::class);  

        $ncnt[] = $con->findByCountncn($periodh,$periodi);
        $nadvt[] = $con3->findByCountadv($periodh,$periodi);             
               
        return $nadvt[0][0][1]/$ncnt[0][0][1];

    }

}

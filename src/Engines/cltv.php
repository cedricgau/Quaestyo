<?php

namespace App\Engines;

use App\Entity\Game;
use App\Entity\ExternDatas;
use App\Entity\Adventure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class cltv extends AbstractController
{
    
    public function cltv(Request $request): Response{

        $perioda = $request->request->get('');
        $periodb = $request->request->get('');

        $con = $this->getDoctrine()->getRepository(Game::class);        
        $con3 = $this->getDoctrine()->getRepository(Adventure::class);

              
        $ncn[] = $con->findByCountncn($perioda,$periodb);
        $nadv[] = $con3->findByCountadv($perioda,$periodb);

        for($c=0; $c<13 ; $c++){
            if(isset($ncn[$c][0][1]) && $ncn[$c][0][1]!==0){                                           
                 $cac_ncn_data[] = $ncn[$c][0][1];
                 $cac_nadv_data[] = $nadv[$c][0][1];
                 $cltv_data[] = $nadv[$c][0][1]/$ncn[$c][0][1];               
                 
             }else{                            
                $cac_ncn_data[] = 0;
                $cac_nadv_data[] = 0;
                $cltv_data[] = 0;           
           
            }
             
        }


        $total_ncn_data = array_sum($cac_ncn_data)-($cac_ncn_data[count($cac_ncn_data)-1]);
        $total_nadv_data = array_sum($cac_nadv_data)-($cac_nadv_data[count($cac_nadv_data)-1]);

        return $total_nadv_data/$total_ncn_data;

    }



}
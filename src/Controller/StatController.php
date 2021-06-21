<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\ExternDatas;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatController extends AbstractController
{
    /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(){

        $a = date("Y")-1;
        $i = date("n")-1;
        $k=$i;

        $con = $this->getDoctrine()->getRepository(Game::class);
        $con2 = $this->getDoctrine()->getRepository(ExternDatas::class);

        for($i=$i+1; $i<$k+13 ; $i++){
    		if ($i>12){
        		$j=$i-12;
        		$b=$a+1;
    		}else{
        		$b=$a;
       			$j=$i; 
    		}
            
            $vol_colnums[] = $j;

		    setlocale(LC_TIME, 'fra_fra');  
    		$vol_cols[]  = utf8_encode(strftime('%B', mktime(0, 0, 0, $i)));
            $perioda = $b.'-'.$j.'-01';
            $periodb = $b.'-'.$j.'-31';
            
            $depex[] = $con2->findByCountdepex($perioda,$periodb);
            $depex2[] = $con2->findByCountdepex2($perioda,$periodb);
            $ncn[] = $con->findByCountncn($perioda,$periodb);            
            $avpa[] = $con->findByCountavpa($perioda,$periodb);
            $avpa2[] = $con->findByCountnc($perioda,$periodb);
            $num[] = $con->findByCountnum($perioda,$periodb);
        }	
        
        foreach ($num as $x => $y){
            $b=0;                           
        foreach($y as $z => $a){               
            if ($a['NUM']>0) { 
               
                $num1_data[]=5.59;
                    
                }
            }            
            $num1_data[] = $b;
        }

        //datas cac
        
        for($c=0; $c<12 ; $c++){
            if(isset($ncn[$c][0][1]) && $ncn[$c][0][1]!==0 && isset($depex[$c][0]["advert"]) && isset($depex2[$c][0]["CA"])){                                
                 $cac_data[] = round($depex[$c][0]["advert"]/$ncn[$c][0][1],2);
                 $arpu_data[] = round($depex2[$c][0]["CA"]/$avpa2[$c][0][1],2);
                 $ctlv_data[] = $num1_data[$c];
             }else{           
                $cac_data [] = 0;
                $arpu_data[] = 0;
                $ctlv_data[] = 0;  
            }
             
        }
        
               
        return $this->render('admin/stats.html.twig', [
        
        'vol_cols' => $vol_cols,
        'vol_cols2' => json_encode($vol_colnums),
        'ctlv_data' => $ctlv_data,
        'ctlv_data2' => json_encode($ctlv_data),             
        'cac_data' => $cac_data,        
        'cac_data2' => json_encode($cac_data), 
        'arpu_data' => $arpu_data,
        'arpu_data2' => json_encode($arpu_data),      
        
        ]);
    }
}

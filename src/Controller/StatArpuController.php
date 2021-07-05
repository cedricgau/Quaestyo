<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\ExternDatas;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatArpuController extends AbstractController
{
    /**
     * @Route("/stat/arpu", name="stat_arpu")
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
    		$arpu_cols[]  = utf8_encode(strftime('%B', mktime(0, 0, 0, $i)));
            $perioda = $b.'-'.$j.'-01';
            $periodb = $b.'-'.$j.'-31';
            
            $depex[] = $con2->findByCountdepex($perioda,$periodb);
            $depex2[] = $con2->findByCountdepex2($perioda,$periodb);            
            $avpa[] = $con->findByCountavpa($perioda,$periodb);
            $avpa2[] = $con->findByCountnc($perioda,$periodb);
        }

              
        //datas arpu

        for($c=0; $c<12 ; $c++){
            if(isset($avpa[$c][0][1]) && $avpa[$c][0][1]!==0 && isset($depex2[$c][0]["CA"])){
                 $arpu_ca_data[] = $depex2[$c][0]["CA"];
                 $arpu_avpa_data[] = $avpa[$c][0][1];
                 $arpu_avpa2_data[] = $avpa2[$c][0][1];
                 $resultat[] = round($depex2[$c][0]["CA"]-$depex[$c][0]["advert"],2);
                 $pourcent1[] = round(($depex2[$c][0]["CA"]-$depex[$c][0]["advert"])/$depex2[$c][0]["CA"]*100,2);
                 $arpu_data[] = round($depex2[$c][0]["CA"]/$avpa2[$c][0][1],2);       
  
             }else{
                $arpu_ca_data[] = 0;
                 $arpu_avpa_data[] = 0;
                 $arpu_avpa2_data[] = 0;                 
                 $resultat[] = 0;
                 $pourcent1[] = 0;
                 $arpu_data[] = 0;      
                
            }
             
        }        
        
        $total_ca_data = array_sum($arpu_ca_data);        
        $total_avpa_data = array_sum($arpu_avpa_data);        
        $total_avpa2_data = array_sum($arpu_avpa2_data); 
        $total_arpu_data = round($total_ca_data/$total_avpa2_data,2);
        $total_resultat = array_sum($resultat);
        $serie_data = [$total_arpu_data, $total_arpu_data, $total_arpu_data, $total_arpu_data, $total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data];


        return $this->render('admin/statarpu.html.twig', [        
        'arpu_cols' => $arpu_cols,
        'arpu_cols2' => json_encode($vol_colnums),
        'arpu_ca_data' => $arpu_ca_data,
        'total_ca_data' => $total_ca_data,
        'arpu_avpa_data' => $arpu_avpa_data,
        'total_avpa_data' => $total_avpa_data,
        'arpu_avpa2_data' => $arpu_avpa2_data,
        'total_avpa2_data' => $total_avpa2_data,            
        'resultat' => $resultat,
        'total_resultat' => $total_resultat,
        'pourcent1' => $pourcent1, 
        'arpu_data' => $arpu_data,
        'arpu_data2' => json_encode($arpu_data),
        'total_arpu_data' => $total_arpu_data,     
        'serie_data2' => json_encode($serie_data)
        ]);
    }
}

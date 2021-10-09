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

        for($i=$i+1; $i<$k+14 ; $i++){
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
            $depex3[] = $con2->findByCountdepex3($perioda,$periodb);
            $depex4[] = $con2->findByCountdepex4($perioda,$periodb);
            $ncn[] = $con->findByCountncn($perioda,$periodb);            
            $avpa[] = $con->findByCountavpa($perioda,$periodb);          
            
        }	
        
        

        //datas cac/arpu/cltv
        
        for($c=0; $c<13 ; $c++){
            if(isset($ncn[$c][0][1]) && $ncn[$c][0][1]!==0 && isset($depex[$c][0]["advert"]) && isset($depex2[$c][0]["CA"])){                                
                 $cac_data[] = round($depex[$c][0]["advert"]/$ncn[$c][0][1],2);
                 $arpu_data[] = round($depex2[$c][0]["CA"]/$ncn[$c][0][1],2);
                 $tele_data[] = $depex3[$c][0]["download"];
                 $des_data[] = $depex4[$c][0]["uninstall"];
                 $pan_moy_data[] = round($depex2[$c][0]["CA"]/$avpa[$c][0][1],2);
                 $arpu_ca_data[] = $depex2[$c][0]["CA"];
                 $arpu_avpa_data[] = $avpa[$c][0][1];
                 $cac_dep_data[] = $depex[$c][0]["advert"];
                 $cac_ncn_data[] = $ncn[$c][0][1];                
                 if($c>0){
                   
                    $nbapp_data[] = $nbapp_data[$c-1]+$depex3[$c][0]["download"]+$depex4[$c][0]["uninstall"];
                    if(!isset($churn[$c-1]) || $churn[$c-1] === 0){
                        $churn[] = round(abs($depex4[$c][0]["uninstall"]/$depex3[$c][0]["download"]),4)*100;
                    }else{
                        $churn[] = round(abs($depex4[$c][0]["uninstall"]/$depex3[$c][0]["download"]),4)*100;
                    }
                 }                         
                 
                 
             }else{           
                $cac_data [] = 0;
                $arpu_data[] = 0;                
                $pan_moy_data[] = 0;               
                $arpu_ca_data[] = 0;
                $arpu_ca_data[] = 0;
                $cac_dep_data[] = 0;
                $cac_ncn_data[] = 0;                
                $tele_data[] = 0;
                $des_data[] = 0;
                $nbapp_data[] = 0;
            }
             
        }
       
        // cas particulier du calcul avec pondération avant le 1/02/2022
        
        $periodc =  '2021-02-01';
        $periodd =  date('Y-m-d',strtotime('-12 month'));
        $periode =  date('Y-m-d');
        
        if ( date('Y-m-d')< '2022-02-01'){             
        
            $num = $con->findByCountnum($periodc,$periode);                               
            $datex = date("n")."/".date("Y");
            
            switch ($datex){
                case "6/2021" :
                    $pond = 1.5;
                    break;
                case "7/2021" :
                    $pond = 1.5;
                    break;
                case "8/2021" :
                    $pond = 1.5;
                    break;
                case "9/2021" :
                    $pond = 1.5;
                    break;
                case "10/2021" :
                    $pond = 1.4;
                    break;
                case "11/2021" :
                    $pond = 1.3;
                    break;
                case "12/2021" :
                    $pond = 1.1;
                    break;
                case "1/2022" :
                    $pond = 1.05;
                    break;
                default :
                    $pond = 1;
            }
        }else{
            $num = $con->findByCountnum($periodd,$periode);
            $pond=1;
        }

        $n=0;
        $total = 0;
        $totalpond = 0 ;
                       
        foreach ($num as $x => $a){                   
            
                   if(isset(${'num'.$a['NUM']})){
                     ${'num'.$a['NUM']}++;
                   }else{
                    ${'num'.$a['NUM']}=1;
                    $tab[]=$a['NUM'];
                    $n++;
                   }
            
        }
       sort($tab);

       for($i=0; $i<$n ; $i++){           
           $total = $total + ${'num'.$tab[$i]};
           $totalpond = $totalpond +  ${'num'.$tab[$i]}*$tab[$i];
        }

        $n=0;
        foreach ($pan_moy_data as $l){
            $cltv_data[] = $totalpond/$total*$pond*$l;
            $resultat[] = ($totalpond/$total*$pond*$l)-$cac_data[$n];
            $n++;
        }

        $total_dep_data = array_sum($cac_dep_data)-($cac_dep_data[count($cac_dep_data)-1]);
        $total_ncn_data = array_sum($cac_ncn_data)-($cac_ncn_data[count($cac_ncn_data)-1]);
        $cac = round($total_dep_data/$total_ncn_data,2);
        
        $total_ca_data = array_sum($arpu_ca_data)-($arpu_ca_data[count($arpu_ca_data)-1]); 
        $total_avpa2_data = array_sum($cac_ncn_data)-($cac_ncn_data[count($cac_ncn_data)-1]);         
        $arpu = round($total_ca_data/$total_avpa2_data,2);

                
        $cltv = $arpu*$totalpond/$total*$pond;

        $n=0;
        $tt=0;
        foreach($resultat as $x){
            if( $x > 0){
                $tt = $tt+$x;
                $n++;
            } 
        }

        for($i=0; $i<12 ; $i++){
            $result[] = $tt/$n;
        }
        
        $total_tele = array_sum($tele_data)-($tele_data[count($tele_data)-1]);
        $total_uninst = array_sum($des_data)-($des_data[count($des_data)-1]);

        return $this->render('admin/stats.html.twig', [
        
        'vol_cols' => $vol_cols,
        'vol_cols2' => json_encode($vol_colnums),
        'cltv_data' => $cltv_data,
        'cltv_data2' => json_encode($cltv_data),             
        'cac_data' => $cac_data,        
        'cac_data2' => json_encode($cac_data), 
        'arpu_data' => $arpu_data,
        'arpu_data2' => json_encode($arpu_data),        
        'resultat' => $resultat,
        'resultat2' => json_encode($resultat),
        'cltv' => $cltv,
        'cac' => $cac,         
        'arpu' => $arpu,  
        'result2' => json_encode($result),
        'tele_data' => $tele_data,
        'tele_data2' => json_encode($tele_data),
        'des_data' => $des_data,
        'des_data2' => json_encode($des_data),
        'total_tele' => $total_tele,
        'total_uninst' => $total_uninst,
        'nbapp_data' => $nbapp_data,
        'nbapp_data2' => json_encode($nbapp_data),    
        ]);
    }
}

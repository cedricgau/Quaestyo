<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\ExternDatas;
use App\Controller\functions\Today;
use App\Controller\functions\Calculation;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatController extends AbstractController
{
    /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(){

          
        $con = $this->getDoctrine()->getRepository(Game::class);
        $con2 = $this->getDoctrine()->getRepository(ExternDatas::class);

        $today = new Today();   

        for($i=(int)$today->getMonth() ; $i<$today->getMonth()+13 ; $i++){
            
    		if ($i>12){
        		$month = $i-12;
        		$year = $today->getYear();
    		}else{
        		$year = $today->getYear()-1;
       			$month = $i; 
    		}
           
            $vol_colnums[] = $month; // tableau des mois précédent
            
		    setlocale(LC_TIME, 'fra_fra');  
    		$vol_cols[]  = utf8_encode(strftime('%B', mktime(0, 0, 0, $i)));
            $perioda = $year.'-'.$month.'-01';
            $periodb = $year.'-'.$month.'-31';
            
            $depex[] = $con2->findByCountdepex($perioda,$periodb);            
            $ncnCac[] = $con->findByCountCacCustomer($perioda,$periodb);
            $ncnArpu[] = $con->findByCountArpuCustomer($perioda,$periodb);  
            $ncnCltv[] = $con->findByCountCltvCustomer($perioda,$periodb);              
            $avpa[] = $con->findByCountAdventurePayed($perioda,$periodb);          
            
        }	            
        //dd($vol_colnums);
        
        //datas cac/arpu/cltv

        $calculation = new Calculation();
        $cac_data = $calculation->getCac($depex,$ncnCac);
        $arpu_data = $calculation->getArpu($depex,$ncnArpu);
        $ncn_data = $calculation->getCltv($depex,$ncnCltv);
        $pan_moy_data = $calculation->getPanierMoyen($depex,$avpa);
        $nbapp_data = $calculation->getActiveApplication($depex);
        $extern_data = $calculation->getExternData($depex);
        foreach($extern_data as $datas){
            $tele_data[] = $datas->getDownload();
            $des_data[] = $datas->getUninstall();
            $ca_data[] = $datas->getCA();
            $ad_data[] = $datas->getAdvert();
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

        $total_dep_data = array_sum($ad_data)-($ad_data[count($ad_data)-1]);        
        $total_ncn_data = array_sum($ncn_data)-($ncn_data[count($ncn_data)-1]);        
        $cac = round($total_dep_data/$total_ncn_data,2);
        
        $total_ca_data = array_sum($ca_data)-($ca_data[count($ca_data)-1]); 
        $total_avpa2_data = array_sum($ncn_data)-($ncn_data[count($ncn_data)-1]);         
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

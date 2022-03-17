<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\ExternDatas;
use App\Controller\functions\Today;
use App\Controller\functions\Calculation;
use App\Entity\Adventure;
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
        $con3 = $this->getDoctrine()->getRepository(Adventure::class);

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
            switch($month){
                case 2:
                    $lastday = 28;
                    break;
                case 4:
                case 6:
                case 9:
                case 11:
                    $lastday = 30;
                    break;
                default:
                    $lastday = 31;
            }
            $perioda = $year.'-'.$month.'-01';
            $periodb = $year.'-'.$month.'-'.$lastday;
                       
            $depex[] = $con2->findByCountdepex($perioda,$periodb);            
            $ncnCac[] = $con->findByCountCacCustomer($perioda,$periodb);
            $ncnArpu[] = $con->findByCountArpuCustomer($perioda,$periodb);  
            $ncnCltv[] = $con->findByCountCltvCustomer($perioda,$periodb);              
            $avpa[] = $con->findByCountAdventurePayed($perioda,$periodb);
            $nadv[] = $con3->findByCountadv($perioda,$periodb);          
            
        }           

        $calculation = new Calculation();
        $cac_data = $calculation->getCac($depex,$ncnCac);
        $arpu_data = $calculation->getArpu($depex,$ncnArpu);        
        $cltv_data = $calculation->getCltv($arpu_data,$nadv,$ncnCltv);
        $ncn_data = $calculation->getNcn($depex,$ncnCac);
        $pan_moy_data = $calculation->getPanierMoyen($depex,$avpa);
        $nbapp_data = $calculation->getActiveApplication($depex);
        $extern_data = $calculation->getExternData($depex);
        foreach($extern_data as $datas){
            $tele_data[] = $datas->getDownload();
            $des_data[] = $datas->getUninstall();
            $ca_data[] = $datas->getCA();
            $ad_data[] = $datas->getAdvert();
        }
        
        $n=0;
        foreach ($cltv_data as $l){   
            $resultat[] = $l-$cac_data[$n];
            $n++;
        }

        $total_dep_data = array_sum($ad_data)-($ad_data[count($ad_data)-1]);        
        $total_ncn_data = array_sum($ncn_data)-($ncn_data[count($ncn_data)-1]);
          
        $cac = round($total_dep_data/$total_ncn_data,2);
        
        $tabtemp = [];
        $tabtemp2 = [];
        $tabtemp3 = [];

        for($i=0; $i<13 ; $i++){
            $tabtemp[] = $ncnArpu[$i][0][1];
            $tabtemp2[] = $nadv[$i][0][1];
            $tabtemp3[] = $ncnCltv[$i][0][1];
        }
        
        $total_ca_data = array_sum($ca_data)-($ca_data[count($ca_data)-1]); 
        $total_avpa2_data = array_sum($tabtemp)-($tabtemp[count($tabtemp)-1]); 
                    
        $arpu = round($total_ca_data/$total_avpa2_data,2);          
        $cltv = array_sum($tabtemp2)/array_sum($tabtemp3)*$arpu;
        
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

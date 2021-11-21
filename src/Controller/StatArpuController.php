<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\ExternDatas;
use App\Controller\functions\Today;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatArpuController extends AbstractController
{
    /**
     * @Route("/stat/arpu", name="stat_arpu")
     */
    public function statistiques(Request $request){

        $today = new Today();       
        
        $con = $this->getDoctrine()->getRepository(Game::class);
        $con2 = $this->getDoctrine()->getRepository(ExternDatas::class);

        for($i = (int)$today->getMonth() ; $i<$today->getMonth()+13 ; $i++){
            
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
            $periodstart = $year.'-'.$month.'-01';
            $periodend = $year.'-'.$month.'-31';
            
            $depex[] = $con2->findByCountdepex($periodstart,$periodend);                       
            $avpa[] = $con->findByCountAdventurePayed($periodstart,$periodend); // aventures jouées payantes avec les 333,444,555,666
            $playerpayed[] = $con->findByCountArpuCustomer($periodstart,$periodend); // joueurs qui ont joués payant avec les 333,444,555,666
        }

              
        //datas arpu

        for($c=0; $c<13 ; $c++){
            if(isset($avpa[$c][0][1]) && $avpa[$c][0][1]!==0 && isset($depex[$c][0]["CA"])){
                 $arpu_ca_data[] = $depex[$c][0]["CA"];
                 $arpu_avpa_data[] = $avpa[$c][0][1];
                 $arpu_playerpayed_data[] = $playerpayed[$c][0][1];
                 $resultat[] = $depex[$c][0]["CA"]-$depex[$c][0]["advert"];
                 $pourcent1[] = ($depex[$c][0]["CA"]-$depex[$c][0]["advert"])/$depex[$c][0]["CA"]*100;
                 $arpu_data[] = $depex[$c][0]["CA"]/$playerpayed[$c][0][1];       
  
             }else{
                $arpu_ca_data[] = 0;
                 $arpu_avpa_data[] = 0;
                 $arpu_playerpayed_data[] = 0;                 
                 $resultat[] = 0;
                 $pourcent1[] = 0;
                 $arpu_data[] = 0;      
                
            }
             
        }        
        
        $total_ca_data = array_sum($arpu_ca_data)-($arpu_ca_data[count($arpu_ca_data)-1]);        
        $total_avpa_data = array_sum($arpu_avpa_data)-($arpu_avpa_data[count($arpu_avpa_data)-1]);        
        $total_playerpayed_data = array_sum($arpu_playerpayed_data)-($arpu_playerpayed_data[count($arpu_playerpayed_data)-1]);
        $total_arpu_data = round($total_ca_data/$total_playerpayed_data,2);
        $total_resultat = array_sum($resultat)-($resultat[count($resultat)-1]);
        $serie_data = [$total_arpu_data, $total_arpu_data, $total_arpu_data, $total_arpu_data, $total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data,$total_arpu_data];


        // Calcul du ARPU avec période temporaire choisie
        
        if($request->request->get('dated') !== null && $request->request->get('datef') !== null){
            $periodh = $request->request->get('dated');
            $periodi = $request->request->get('datef');            

        }else{
            $periodh = '2021-02-01';  
            $periodi = '2021-07-31';            
        }

        if($request->request->get('dated2') !== null && $request->request->get('datef2') !== null){
                    $periodj = $request->request->get('dated2');
                    $periodk = $request->request->get('datef2');
        }else{
                    $periodj = '2021-02-01';  
                    $periodk = '2021-07-31';  
        }

        $ca[] = $con2->findByCountdepex($periodh,$periodi);
        $nc1[] = $con->findByCountArpuCustomer($periodh,$periodi);
        $ca2[] = $con2->findByCountdepex($periodj,$periodk);
        $nc2[] = $con->findByCountArpuCustomer($periodj,$periodk);  

        $c=0;
        $total1=0;
        $total2=0;

        while(isset($ca[0][$c]["CA"])){
            $total1 += $ca[0][$c]["CA"];                       
            $c++;
        }

        $c=0;

        while(isset($ca2[0][$c]["CA"])){            
            $total2 += $ca2[0][$c]["CA"];            
            $c++;
        }
              
        if(array_sum($nc1[0][0]) !==0 ){
            $arpu_temp1 = $total1/array_sum($nc1[0][0]);
        }else{
            $arpu_temp1 = 0;
        }

        if(array_sum($nc2[0][0])!==0){ 
            $arpu_temp2 = $total2/array_sum($nc2[0][0]);
        }else{
            $arpu_temp2 = 0; 
        }        

        return $this->render('admin/statarpu.html.twig', [        
        'arpu_cols' => $vol_cols,
        'arpu_cols2' => json_encode($vol_colnums),
        'arpu_ca_data' => $arpu_ca_data,
        'total_ca_data' => $total_ca_data,
        'arpu_avpa_data' => $arpu_avpa_data,
        'total_avpa_data' => $total_avpa_data,
        'arpu_playerpayed_data' => $arpu_playerpayed_data,
        'total_playerpayed_data' => $total_playerpayed_data,            
        'resultat' => $resultat,
        'total_resultat' => $total_resultat,
        'pourcent1' => $pourcent1, 
        'arpu_data' => $arpu_data,
        'arpu_data2' => json_encode($arpu_data),
        'total_arpu_data' => $total_arpu_data,     
        'serie_data2' => json_encode($serie_data),
        'arpu_temp1' => $arpu_temp1,
        'arpu_temp2' => $arpu_temp2,
        'periodh' => $periodh,
        'periodi' => $periodi,
        'periodj' => $periodj,
        'periodk' => $periodk, 
        ]);
    }
}

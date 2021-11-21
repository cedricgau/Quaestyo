<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Adventure;
use App\Entity\ExternDatas;
use App\Controller\CltvController;
use App\Controller\functions\Today;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatLtvcController extends AbstractController
{
    /**
     * @Route("/stat/ltvc", name="stat_ltvc")
     */
    public function statistiques(Request $request){

        $today = new Today();       
        
        $con = $this->getDoctrine()->getRepository(Game::class);
        $con2 = $this->getDoctrine()->getRepository(ExternDatas::class);
        $con3 = $this->getDoctrine()->getRepository(Adventure::class);

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
       // dd($tab2);
       $plur="";
       for($i=0; $i<$n ; $i++){           
           if($tab[$i]>1) $plur="s";
           $numb[] = array('champ' => $tab[$i].' aventure'.$plur.' jouée'.$plur,'client' => ${'num'.$tab[$i]},'nbav' => $tab[$i],'pond' => ${'num'.$tab[$i]}*$tab[$i]);           
           $total = $total + ${'num'.$tab[$i]};
           $totalpond = $totalpond +  ${'num'.$tab[$i]}*$tab[$i];
        }


        // cas général

        for($i = (int) $today->getMonth() ; $i<$today->getMonth()+13 ; $i++){
            
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
            $ncn[] = $con->findByCountCltvCustomer($periodstart,$periodend);
            $nadv[] = $con3->findByCountadv($periodstart,$periodend);         
            
        }       
       
        //datas cltv

        for($c=0; $c<13 ; $c++){
            if(isset($ncn[$c][0][1]) && $ncn[$c][0][1]!==0 && isset($depex[$c][0]["advert"])){
                 $cac_dep_data[] = $depex[$c][0]["advert"];                              
                 $cac_ncn_data[] = $ncn[$c][0][1];
                 $cac_nadv_data[] = $nadv[$c][0][1];
                 $cltv_data[] = $nadv[$c][0][1]/$ncn[$c][0][1];               
                 
             }else{
                $cac_dep_data[]= 0;               
                $cac_ncn_data[] = 0;
                $cac_nadv_data[] = 0;
                $cltv_data[] = 0;           
           
            }
             
        }
        //dd($cac_ncn_data);      
        $total_cac_data = array_sum($cac_dep_data)/array_sum($cac_ncn_data); 
        $total_ncn_data = array_sum($cac_ncn_data)-($cac_ncn_data[count($cac_ncn_data)-1]);
        $total_nadv_data = array_sum($cac_nadv_data)-($cac_nadv_data[count($cac_nadv_data)-1]);      
        

        // Calcul du CLTV avec période temporaire choisie
        // $cltv_temp1 = new CltvController();
        // $cltv_temp2 = $cltv_temp1->cltv();
        // dd($cltv_temp2);
       
        if($request->request->get('dated') !== null && $request->request->get('datef') !== null){
            $periodh = $request->request->get('dated');
            $periodi = $request->request->get('datef');            

        }else{
            $periodh = '2021-02-01';  
            $periodi = '2021-07-31';            
        }

        $ncnt1[] = $con->findByCountCltvCustomer($periodh,$periodi);
        $nadvt1[] = $con3->findByCountadv($periodh,$periodi);             
               
        $cltv_temp1 = $nadvt1[0][0][1]/$ncnt1[0][0][1];

        if($request->request->get('dated2') !== null && $request->request->get('datef2') !== null){
            $periodj = $request->request->get('dated2');
            $periodk = $request->request->get('datef2');
        }else{
            $periodj = '2021-02-01';  
            $periodk = '2021-07-31';  
        }

        $ncnt2[] = $con->findByCountCltvCustomer($periodj,$periodk);
        $nadvt2[] = $con3->findByCountadv($periodj,$periodk);             
               
        $cltv_temp2 = $nadvt2[0][0][1]/$ncnt2[0][0][1];
         
                                
        return $this->render('admin/statltvc.html.twig', [
            'numb' => $numb,
            'pond' => $pond,
            'total'=> $total,
            'totalpond' => $totalpond,            
            'cac' => $total_cac_data,
            'vol_colnums' =>  $vol_cols,
            'cac_ncn_data' => $cac_ncn_data,
            'cac_ncn_data2' => json_encode($cac_ncn_data),
            'total_ncn_data' => $total_ncn_data,
            'cac_nadv_data' => $cac_nadv_data,
            'cac_nadv_data2' => json_encode($cac_nadv_data),
            'total_nadv_data' => $total_nadv_data,
            'cltv_data' => $cltv_data,
            'cltv_temp1' => $cltv_temp1,
            'cltv_temp2' => $cltv_temp2,
            'periodh' => $periodh,
            'periodi' => $periodi,
            'periodj' => $periodj,
            'periodk' => $periodk,    
        ]);
    }
}


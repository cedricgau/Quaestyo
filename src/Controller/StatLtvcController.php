<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Adventure;
use App\Entity\ExternDatas;
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

        $telecharge = 0;
        
        $today = new Today();        
        $con = $this->getDoctrine()->getRepository(Game::class);
        $con2 = $this->getDoctrine()->getRepository(ExternDatas::class);
        $con3 = $this->getDoctrine()->getRepository(Adventure::class);

        
        $periodd =  date('Y-m-d',strtotime('-12 month'));
        
        if(isset($_GET['listClients'])){
            
        $details = $con->findByCountdetails($periodd,intval($_GET['listClients']));
        $path = $this->getParameter('csv_dir').'/list.csv';

           $file = fopen($path , 'w');
           fputs( $file, "\xEF\xBB\xBF" );

           foreach ($details as $data) { 
               fputcsv($file, $data, ";"); 
           } 

           fclose($file);

           $telecharge = 1;
        }
        
        if(isset($_GET['listAdv'])){

            $date = new Today();
            $year = $date->getYear()-1;
            $month = $_GET['listAdv'];

            if ( $_GET['listAdv'] > 12 ){
                $month = $month - 12;
                $year++; 
            } 
           
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

            if ( $month < 10 ) $month = "0".$month;
            
            $perioda = $year.'-'.$month.'-01';
            $periodb = $year.'-'.$month.'-'.$lastday;
            // dd($perioda,$periodb); 
            $details = $con3->findByCountAdvpayed($perioda,$periodb);
            $path = $this->getParameter('csv_dir').'/list.csv';

           $file = fopen($path , 'w');
           fputs( $file, "\xEF\xBB\xBF" );

           foreach ($details as $data) { 
               fputcsv($file, $data, ";"); 
           } 

           fclose($file);

           $telecharge = 1;
        }

        if(isset($_GET['listCli'])){

            $date = new Today();
            $year = $date->getYear()-1;
            $month = $_GET['listCli'];

            if ( $_GET['listCli'] > 12 ){
                $month = $month - 12;
                $year++; 
            } 
           
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

            if ( $month < 10 ) $month = "0".$month;
            
            $perioda = $year.'-'.$month.'-01';
            $periodb = $year.'-'.$month.'-'.$lastday;
            // dd($perioda,$periodb); 
            $details = $con->findByCltvCustomer($perioda,$periodb);
            $path = $this->getParameter('csv_dir').'/list.csv';

           $file = fopen($path , 'w');
           fputs( $file, "\xEF\xBB\xBF" );

           foreach ($details as $data) { 
               fputcsv($file, $data, ";"); 
           } 

           fclose($file);

           $telecharge = 1;
        }
        
        $num = $con->findByCountnum($periodd);
        $pond=1;        

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
            $periodstart = $year.'-'.$month.'-01';
            $periodend = $year.'-'.$month.'-'.$lastday;
		    
            
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
            'telecharge' => $telecharge,    
        ]);
    }
}


<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\ExternDatas;
use App\Controller\functions\Today;
use App\Controller\functions\Calculation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatCacController extends AbstractController
{
    /**
     * @Route("/stat/cac", name="stat_cac")
     */
    public function statistiques(Request $request){

        $today = new Today();       
        
        $con = $this->getDoctrine()->getRepository(Game::class);
        $con2 = $this->getDoctrine()->getRepository(ExternDatas::class);

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
            $cc[] = $con->findByCountcc($periodstart,$periodend); // creations de compte
            $cch[] = $con->findByCountcch($periodstart,$periodend); // creations de compte en 333,444,555,666
            $jag[] = $con->findByCountjag($periodstart,$periodend); // joueurs de parties gratuites sans 333,444,555,666
            $jagQD[] = $con->findByCountjagQD($periodstart,$periodend); // joueurs de Quaestyo Demo sans 333,444,555,666           
            $ncn[] = $con->findByCountCacCustomer($periodstart,$periodend); // joueurs qui ont créés un compte et qui ont joué à une aventure payante, même  après. Aucun 333,444,555,666          
            $ncnp[] = $con->findByCountCacCustomerParis($periodstart,$periodend); // joueuers d'ile de France qui ont créés un compte et qui ont joué à une aventure payante, même  après. Aucun 333,444,555,666        
        
        }             
               
        //datas cac

        $calculation = new Calculation();
        $cac_data = $calculation->getCac($depex,$ncn);    
        $nbapp_data[] = $depex[0][0]["download"] + $depex[0][0]["uninstall"];
        $churn[] = round(abs($depex[0][0]["uninstall"]/$depex[0][0]["download"]),4)*100;;
        for($c=0; $c<13 ; $c++){
            if(isset($ncn[$c][0][1]) && $ncn[$c][0][1]!==0 && isset($depex[$c][0]["advert"])){
                 $cac_dep_data[] = $depex[$c][0]["advert"];
                 $tele_data[] = $depex[$c][0]["download"];
                 $des_data[] = $depex[$c][0]["uninstall"];
                 $cac_cc_data[] = $cc[$c][0][1];
                 $cac_cch_data[] = $cch[$c][0][1];
                 $cac_dpc_data[] = $depex[$c][0]["advert"]/$cc[$c][0][1];
                 $cac_np_data[] = $cc[$c][0][1]-$ncn[$c][0][1];                
                 $cac_jag_data[] = $jag[$c][0][1];
                 $cac_jagQD_data[] = $jagQD[$c][0][1];                 
                 $cac_ncn_data[] = $ncn[$c][0][1];  
                 $cac_ncnp_data[] = $ncnp[$c][0][1];                
                 $csa[] = $cc[$c][0][1]-$ncn[$c][0][1]-$jag[$c][0][1];
                 $pourcent3[] = round($ncn[$c][0][1]/$cc[$c][0][1]*100,2);  
                 $cac_par_data[] = round($depex[$c][0]["advert"]/$ncnp[$c][0][1],2);
                 $nbncli_data[] = $depex[$c][0]["download"]-$ncn[$c][0][1];
                 $psc_data[] = $depex[$c][0]["download"]-$cch[$c][0][1]-$cc[$c][0][1];
                 
                 if($c>0){                   
                    $nbapp_data[] = $nbapp_data[$c-1]+$depex[$c][0]["download"]+$depex[$c][0]["uninstall"];
                    if(!isset($churn[$c-1]) || $churn[$c-1] === 0){
                        $churn[] = round(abs($depex[$c][0]["uninstall"]/$depex[$c][0]["download"]),4)*100;
                    }else{
                        $churn[] = round(abs($depex[$c][0]["uninstall"]/$depex[$c][0]["download"]),4)*100;
                    }
                 }        
                 
             }else{
                $cac_dep_data[]= 0;
                $cac_cc_data[] = 0;
                $cac_cch_data[] = 0;
                $cac_dpc_data[] = 0;
                $cac_np_data[] = 0;               
                $pourcent3[] = 0;                
                $cac_jag_data[] = 0;
                $cac_jagQD_data[] = 0;
                $cac_ncn_data[] = 0;
                $cac_ncnp_data[] = 0;                
                $cac_par_data[] = 0;
                $tele_data[] = 0;
                $des_data[] = 0;
                $nbncli_data[] = 0;
                $nbapp_data[] = 0;
                $churn[] = 0;
                $psc_data[] = 0;
                $csa[] = 0;                
            }
             
        }
       
                            
        $total_dep_data = array_sum($cac_dep_data)-($cac_dep_data[count($cac_dep_data)-1]);        
        $total_cc_data = array_sum($cac_cc_data)-($cac_cc_data[count($cac_cc_data)-1]); 
        $total_cch_data = array_sum($cac_cch_data)-($cac_cch_data[count($cac_cch_data)-1]); 
        $total_np_data = array_sum($cac_np_data)-($cac_np_data[count($cac_np_data)-1]);
        $total_csa = array_sum($csa)-($csa[count($csa)-1]);           
        $total_jag_data = array_sum($cac_jag_data)-($cac_jag_data[count($cac_jag_data)-1]); 
        $total_jagQD_data = array_sum($cac_jagQD_data)-($cac_jagQD_data[count($cac_jagQD_data)-1]);    
        $total_ncn_data = array_sum($cac_ncn_data)-($cac_ncn_data[count($cac_ncn_data)-1]);         
        $total_ncnp_data = array_sum($cac_ncnp_data)-($cac_ncnp_data[count($cac_ncnp_data)-1]);
        $total_psc =  array_sum($psc_data)-($psc_data[count($psc_data)-1]);       
        $total_cac_data = round($total_dep_data/$total_ncn_data,2);       
        $cac_moy_data = [$total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data,$total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data];
        $total_tele = array_sum($tele_data)-($tele_data[count($tele_data)-1]);
        $total_uninst = array_sum($des_data)-($des_data[count($des_data)-1]);
        $total_dpc_data =  $total_dep_data/$total_cc_data;

        // Calcul du CAC avec période temporaire choisie
        
        if($request->request->get('dated') !== null && $request->request->get('datef') !== null){
            $periodh = $request->request->get('dated');
            $periodi = $request->request->get('datef');            

        }else{
            $periodh = '2021-02-01';  
            $periodi = 'Y-m-d';            
        }
        if($request->request->get('dated2') !== null && $request->request->get('datef2') !== null){
            $periodj = $request->request->get('dated2');
            $periodk = $request->request->get('datef2');
        }else{
            $periodj = '2021-02-01';  
            $periodk = 'Y-m-d';  
        }


        $advert1[] = $con2->findByCountdepex($periodh,$periodi);
        $nc1[] = $con->findByCountCacCustomer($periodh,$periodi);
        $advert2[] = $con2->findByCountdepex($periodj,$periodk);
        $nc2[] = $con->findByCountCacCustomer($periodj,$periodk);

        $c=0;
        $total1=0;
        $total2=0;

        while(isset($advert1[0][$c]["advert"])){
            $total1 += $advert1[0][$c]["advert"];                       
            $c++;
        }

        $c=0;

        while(isset($advert2[0][$c]["advert"])){
            $total2 += $advert2[0][$c]["advert"];                       
            $c++;
        }
              
        if(array_sum($nc1[0][0]) !==0 ){
            $cac_temp1 = $total1/array_sum($nc1[0][0]);
        }else{
            $cac_temp1 = 0;
        }

        if(array_sum($nc2[0][0])!==0){ 
            $cac_temp2 = $total2/array_sum($nc2[0][0]);
        }else{
            $cac_temp2 = 0; 
        }
               
                        

        return $this->render('admin/statcac.html.twig', [       
        'cac_cols' => $vol_cols,
        'cac_cols2' => json_encode($vol_colnums),        
        'tele_data' => $tele_data,
        'tele_data2' => json_encode($tele_data),        
        'nbncli_data' => $nbncli_data,
        'nbncli_data2' => json_encode($nbncli_data),
        'des_data' => $des_data,
        'des_data2' => json_encode($des_data),
        'nbapp_data' => $nbapp_data,
        'nbapp_data2' => json_encode($nbapp_data),                 
        'cac_dep_data' => $cac_dep_data,
        'total_dep_data' => $total_dep_data,
        'cac_dpc_data' => $cac_dpc_data,
        'total_dpc_data' => $total_dpc_data,
        'cac_np_data' => $cac_np_data,
        'total_np_data' => $total_np_data,
        'pourcent3' => $pourcent3,
        'cac_jag_data' => $cac_jag_data,
        'total_jag_data' => $total_jag_data,
        'cac_jagQD_data' => $cac_jagQD_data,
        'total_jagQD_data' => $total_jagQD_data,
        'cac_cc_data' => $cac_cc_data,
        'cac_cc_data2' => json_encode($cac_cc_data),
        'total_cc_data' => $total_cc_data,
        'cac_cch_data' => $cac_cch_data,
        'cac_cch_data2' => json_encode($cac_cch_data),
        'total_cch_data' => $total_cch_data,
        'cac_ncn_data' => $cac_ncn_data,
        'cac_ncn_data2' => json_encode($cac_ncn_data),
        'total_ncn_data' => $total_ncn_data, 
        'cac_ncnp_data' => $cac_ncnp_data,
        'total_ncnp_data' => $total_ncnp_data,         
        'cac_data' => $cac_data,
        'total_cac_data' => $total_cac_data,         
        'cac_data2' => json_encode($cac_data),
        'cac_par_data2' => json_encode($cac_par_data),         
        'cac_moy_data' => $cac_moy_data,
        'cac_moy_data2' => json_encode($cac_moy_data),
        'churn' => $churn,
        'total_tele' => $total_tele,
        'total_uninst' => $total_uninst,
        'psc_data' => $psc_data,
        'total_psc' => $total_psc,
        'csa' => $csa,
        'total_csa' => $total_csa,
        'cac_temp1' => $cac_temp1,
        'cac_temp2' => $cac_temp2,
        'periodh' => $periodh,
        'periodi' => $periodi,
        'periodj' => $periodj,
        'periodk' => $periodk,                    
        ]);
    }
}

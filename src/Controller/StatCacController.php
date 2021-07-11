<?php

namespace App\Controller;

use App\Entity\ExternDatas;
use App\Entity\Game;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatCacController extends AbstractController
{
    /**
     * @Route("/stat/cac", name="stat_cac")
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
    		$cac_cols[]  = utf8_encode(strftime('%B', mktime(0, 0, 0, $i)));
            $perioda = $b.'-'.$j.'-01';
            $periodb = $b.'-'.$j.'-31';

            $depex[] = $con2->findByCountdepex($perioda,$periodb);
            $depex3[] = $con2->findByCountdepex3($perioda,$periodb);
            $depex4[] = $con2->findByCountdepex4($perioda,$periodb);
            $cc[] = $con->findByCountcc($perioda,$periodb);
            $cch[] = $con->findByCountcch($perioda,$periodb);
            $jag[] = $con->findByCountjag($perioda,$periodb);
            $jagQD[] = $con->findByCountjagQD($perioda,$periodb);            
            $ncn[] = $con->findByCountncn($perioda,$periodb);
            $ncnp[] = $con->findByCountncnp($perioda,$periodb);        
        
        }             
               
        //datas cac

        for($c=0; $c<13 ; $c++){
            if(isset($ncn[$c][0][1]) && $ncn[$c][0][1]!==0 && isset($depex[$c][0]["advert"])){
                 $cac_dep_data[] = $depex[$c][0]["advert"];
                 $tele_data[] = $depex3[$c][0]["download"];
                 $des_data[] = $depex4[$c][0]["uninstall"];
                 $cac_cc_data[] = $cc[$c][0][1];
                 $cac_cch_data[] = $cch[$c][0][1];
                 $cac_dpc_data[] = $depex[$c][0]["advert"]/$cc[$c][0][1];
                 $cac_np_data[] = $cc[$c][0][1]-$ncn[$c][0][1];                
                 $cac_jag_data[] = $jag[$c][0][1];
                 $cac_jagQD_data[] = $jagQD[$c][0][1];                 
                 $cac_ncn_data[] = $ncn[$c][0][1];  
                 $cac_ncnp_data[] = $ncnp[$c][0][1];
                 $pourcent3[] = round($ncn[$c][0][1]/$cc[$c][0][1]*100,2);                 
                 $cac_data[] = round($depex[$c][0]["advert"]/$ncn[$c][0][1],2);
                 $cac_par_data[] = round($depex[$c][0]["advert"]/$ncnp[$c][0][1],2);
                 $nbncli_data[] = $cc[$c][0][1]-$ncn[$c][0][1];
                 if($c>0){
                   
                    $nbapp_data[] = $nbapp_data[$c-1]+$depex3[$c][0]["download"]+$depex4[$c][0]["uninstall"];
                    if(!isset($churn[$c-1]) || $churn[$c-1] === 0){
                        $churn[] = round(abs($depex4[$c][0]["uninstall"]/$depex3[$c][0]["download"]),4)*100;
                    }else{
                        $churn[] = round(abs($depex4[$c][0]["uninstall"])/($nbapp_data[$c]),4)*100;
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
                $cac_data [] = 0;
                $cac_par_data[] = 0;
                $tele_data[] = 0;
                $des_data[] = 0;
                $nbncli_data[] = 0;
                $nbapp_data[] = 0;
                $churn[] = 0;
            }
             
        }
       
                            
        $total_dep_data = array_sum($cac_dep_data)-($cac_dep_data[count($cac_dep_data)-1]);        
        $total_cc_data = array_sum($cac_cc_data)-($cac_cc_data[count($cac_cc_data)-1]); 
        $total_cch_data = array_sum($cac_cch_data)-($cac_cch_data[count($cac_cch_data)-1]); 
        $total_np_data = array_sum($cac_np_data)-($cac_np_data[count($cac_np_data)-1]);        
        $total_jag_data = array_sum($cac_jag_data)-($cac_jag_data[count($cac_jag_data)-1]); 
        $total_jagQD_data = array_sum($cac_jagQD_data)-($cac_jagQD_data[count($cac_jagQD_data)-1]);    
        $total_ncn_data = array_sum($cac_ncn_data)-($cac_ncn_data[count($cac_ncn_data)-1]);         
        $total_ncnp_data = array_sum($cac_ncnp_data)-($cac_ncnp_data[count($cac_ncnp_data)-1]);        
        $total_cac_data = round($total_dep_data/$total_ncn_data,2);       
        $cac_moy_data = [$total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data,$total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data, $total_cac_data];
        $total_tele = array_sum($tele_data)-($tele_data[count($tele_data)-1]);
        $total_uninst = array_sum($des_data)-($des_data[count($des_data)-1]);
        $total_dpc_data =  $total_dep_data/$total_cc_data;
        

        return $this->render('admin/statcac.html.twig', [       
        'cac_cols' => $cac_cols,
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
        ]);
    }
}

<?php
namespace App\Controller\functions;

use App\Entity\ExternDatas;




class Calculation
{

    public function getCac($ad , $noCustomer)
    {

        for($c=0; $c<13 ; $c++){
            if(isset($noCustomer[$c][0][1]) && isset($ad[$c][0]["advert"]) && $noCustomer[$c][0][1] !== 0 && $ad[$c][0]["advert"] !== 0){                                
                 $cacData[] = round($ad[$c][0]["advert"]/$noCustomer[$c][0][1],2);
            }else{           
                 $cacData[] = 0;
            }
        }
        return $cacData;
    }

    public function getArpu($ca , $noCustomer) 
    {

        for($c=0; $c<13 ; $c++){
            if(isset($noCustomer[$c][0][1]) && isset($ca[$c][0]["CA"]) && $noCustomer[$c][0][1] !== 0 && $ca[$c][0]["CA"] !== 0){                                
                 $arpuData[] = round($ca[$c][0]["CA"]/$noCustomer[$c][0][1],2);
            }else{           
                 $arpuData[] = 0;
            }
        }
        return $arpuData;
    }

    public function getPanierMoyen($ca , $payedPlayer) 
    {

        for($c=0; $c<13 ; $c++){
            if(isset($payedPlayer[$c][0][1]) && isset($ca[$c][0]["CA"]) && $payedPlayer[$c][0][1] !== 0 && $ca[$c][0]["CA"] !== 0){                                
                 $panierMoyenData[] = round($ca[$c][0]["CA"]/$payedPlayer[$c][0][1],2);
            }else{           
                 $panierMoyenData[] = 0;
            }
        }
        return $panierMoyenData;
    }

    public function getActiveApplication($activeApplication) 
    {
        
        for($c=0; $c<13 ; $c++){
            
            if($c>0 && isset($activeApplication[$c][0]["download"]) && isset($activeApplication[$c][0]["uninstall"])){
                                                         
                $nbappData[] = $nbappData[$c-1]+$activeApplication[$c][0]["download"]+$activeApplication[$c][0]["uninstall"];
                
            }else{ 

                $nbappData[] = 0; 

            }                
        }
        return $nbappData;
    }

    public function getCltv($extern , $cltv) 
    {
        for($c=0; $c<13 ; $c++){                                           
            if(isset($extern[$c][0]["advert"])){
                $cltvData[] = $cltv[$c][0][1];                
            }else{
                $cltvData[] = 0; 
            } 
        }
        return $cltvData;
    }
    
    public function getExternData($extern) 
    {

        for($c=0; $c<13 ; $c++){
            if(isset($extern[$c][0]["download"]) && isset($extern[$c][0]["CA"]) && isset($extern[$c][0]["uninstall"]) && isset($extern[$c][0]["advert"])){                                
                 $externData[] = new ExternDatas($extern[$c][0]["CA"],$extern[$c][0]["advert"],$extern[$c][0]["download"],$extern[$c][0]["uninstall"]);
            }else{           
                 $externData[] = new ExternDatas(0,0,0,0);
            }
        }
        return  $externData;
    }
    
}
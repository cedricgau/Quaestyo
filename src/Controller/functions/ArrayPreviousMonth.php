<?php

namespace App\Controller\functions;

use App\Controller\functions\Today;

class ArrayPreviousMonth{

    public function getMonthesNumber() 
    {

        $today = new Today();  
        
        for($i=$today->getMonth() ; $i<$today->getMonth()+13 ; $i++){
            
    		if ($i>12){
        		$monthes[] = $i-12;
        		$year = $today->getYear();
    		}else{
        		$year = $today->getYear()-1;
       			$monthes[] = $i; 
    		}         
            
        }
        return $monthes;    
    }

    

    
}
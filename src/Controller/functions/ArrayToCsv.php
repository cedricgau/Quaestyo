<?php

namespace App\Controller\functions;

class ArrayToCsv
{
    
    function convertToCsv($array , $nameCsv)     {
        
        
        $file = fopen($nameCsv , 'w');
        fputs( $file, "\xEF\xBB\xBF" );

        foreach ($array as $data) { 
            fputcsv($file, $data, ";"); 
        } 

           fclose($file);
    }

}
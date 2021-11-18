<?php

namespace App\Controller\functions;

class ArrayToCsv
{
    
    function convertToCsv($array , $nameCsv) 
    {
        
        $path = $this->getParameter('csv_dir').'/'.$nameCsv.'.csv';
        $file = fopen($path , 'w');
        fputs( $file, "\xEF\xBB\xBF" );

        foreach ($array as $data) { 
            fputcsv($file, $data, ";"); 
        } 

           fclose($file);
    }

}
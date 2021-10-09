<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Adventure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdventureController extends AbstractController
{
    /**
     * @Route("/stat/adv", name="stat_adv")
     */
    public function statadventure(Request $request){

        $lst="";

        if($request->request->get('list') !== null) {

            $con2 = $this->getDoctrine()->getRepository(Adventure::class);

            $lst = $request->request->get('list');
            $start = $request->request->get('periodStart');
            $end = $request->request->get('periodEnd');

            $tab[] = $con2->findByAdv($lst,$start,$end);
            $title = ['ID','MAIL','PSEUDO','DATE DE CREATION','VILLE','SMARTPHONE','DATE DE JEU','SCORE'];
            $newTab = array($title);
            
            foreach ($tab[0] as $x){                
                $newTab2 = array($x['id_player'],$x['mail'],$x['pseudo'],date_format($x['date_creation'],'Y-m-d'),$x['city'],$x['phone'],date_format($x['date_played'],'Y-m-d'),$x['score']);
                array_push($newTab,$newTab2);            
            }            
                        
            $path = $this->getParameter('csv_dir').'/List_'.$lst.'.csv';

           $file = fopen($path , 'w');
           fputs( $file, "\xEF\xBB\xBF" );

           foreach ($newTab as $data) { 
               fputcsv($file, $data, ";"); 
           } 

           fclose($file);

        }

        $con = $this->getDoctrine()->getRepository(Game::class);

        if($request->request->get('dated') !== null && $request->request->get('datef') !== null){
            $perioda = $request->request->get('dated');
            $periodb = $request->request->get('datef');            

        }else{
            $perioda = '2021-02-01';  
            $periodb = date('Y-m-d');            
        }

            $adventureJoue[] = $con->findByCountc($perioda,$periodb);                         
                     
        
        //datas adventures jouées

        $x = 0;
        $tt = 0;

        while(isset($adventureJoue[0][$x]["code_adv"])){
            $advJoue[] = array('code_adv' => $adventureJoue[0][$x]["code_adv"], 'name' => $adventureJoue[0][$x]["name"], 'total' => $adventureJoue[0][$x][1]);
            $tt += $adventureJoue[0][$x][1];
            $x++;
        }       
                        
        return $this->render('admin/statadv.html.twig', [
        'fich' => $lst,
        'periodStart' => $perioda,
        'periodEnd' => $periodb,       
        'advJoue' => $advJoue,
        'advJoue2' => json_encode($advJoue),
        'total' => $tt,                             
        ]);
    }
}

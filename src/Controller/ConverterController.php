<?php
namespace App\Controller;

use DateTime;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Adventure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConverterController extends AbstractController{

            /** 
            * @Route("/prodcsv", name="prodcsv")
            */

        function test(Request $request): Response{

            $apres = $request->request->get('datedeb');
            $avant = $request->request->get('datefin');
            $crea = $request->request->get('datecrea');
            $nbcol = $request->request->get('nbcol');

            
            $con = $this->getDoctrine()->getRepository(Game::class);
            $obj = $con->findByAdventure(0);
            $con = $this->getDoctrine()->getRepository(Player::class);
            $obj2 = $con->findByPlayer($apres,$avant);
            $con = $this->getDoctrine()->getRepository(Adventure::class);
            $obj3 = $con->findAll();
            $con2 = $this->getDoctrine()->getRepository(Player::class);
            $obj4 = $con2->findByNoPlayer($crea);

           $title = ['ID','MAIL','PSEUDO','DATE_CREATION','VILLE','PREMIER_PAIEMENT','AV1','Statut','Date_AV1'];

               for($j=2;$j<$nbcol;$j++){
                   array_push( $title ,'AV'.$j,'Statut','Date_AV'.$j);
               }
           array_push( $title ,'Dernière_date_jouée','PAS_JOUEE_DEPUIS','TOT_AV','TOT_AV_PAYANTES','TOT_AV_GRATUITES','TOT_AV_PRIVES','C1','C3','C4','C5','C6','IOS/ANDROID');
           $tab = array ($title);

           foreach($obj2 as $d2) {

               $total=0; // total/joueur du nombre d'aventure jouée
               $totalpa=0; // total général des aventures payantes
               $totalg=0; // total général des aventures gratuites
               $totalpr=0; // total général des aventures privées
               $gfp=null;
               $dlpr=null; // date la plus récente
               $gdc = date_format($d2->getDateCreation(),'Y-m-d'); // conversion format date YYYY-MM-DD
               $p=0;                            
               $line = null; 
                   foreach($obj as $d1) {                      
                       $u=0;
                                    
                       if (($d2->getIdPlayer()===$d1->getIdPlayer())) { 
                          
                           foreach($obj3 as $d3){
                               
                               if($d1->getCodeAdv() === $d3->getCodeAdv()){

                                if($p==0){                                    
                                    if ($d2->getFirstPurchase()!==null) $gfp=date_format($d2->getFirstPurchase(),'Y-m-d');

                                    $col1 = $d2->getIdPlayer(); // Colonne 1 : ID   
                                    $col2 = $d2->getMail();    // Colonne 2 : MAIL
                    
                                    if ($d2->getPseudo()!==null){
                                        $col3 = $d2->getPseudo(); // Colonne 3 : PSEUDO
                                    }else{
                                        $col3 = 'INCONNU'; 
                                    }
                    
                                    $col4 = $gdc; // Colonne 4 : DATE DE CREATION DU COMPTE UTILISATEUR
                    
                                    if ($d2->getLatitude()===48.858200073242 && $d2->getLongitude()===2.338700056076 ){ // Colonne 5 : VILLE DE CREATION DU COMPTE UTILISATEUR
                                        $col5 = "Paris";
                                    }elseif (is_null($d2->getCity())){
                                        $col5 = "???";
                                    }else{
                                        $col5 = $d2->getCity();
                                    } 
                                    
                                    $col6 = $gfp; // Colonne 8 : DATE DU PREMIER PAIEMENT

                                    $p++;

                                    $line = array ($col1,$col2,$col3,$col4,$col5,$col6);
                                }                                
                                   $col9 = $d3->getName(); // Colonne 9 : NOM DE L'AVENTURE JOUEE

                                    if( $d3->getState()!==null){
                                        $col10 = $d3->getState(); // Colonne 10 : STATUT DE L'AVENTURE JOUEE

                                           switch($d3->getState()){
                                               case "PAYANT":
                                                   $totalpa++;                                                                                  
                                                   break;
                                               case "GRATUIT":
                                                   $totalg++;
                                                   break;
                                               case "PRIVE":
                                                   $totalpr++;
                                                   break;
                                               default:
                                           }
                                       }else{
                                           $col10 = 'INCONNU'; // Colonne 10 : STATUT DE L'AVENTURE JOUEE
                                       }
                                                                              
                                       $col11 = date_format($d1->getDatePlayed(),'Y-m-d'); // Colonne 11 : DERNIERE DATE JOUEE

                                       $dlpr = date_format($d1->getDatePlayed(),'Y-m-d');
                                       $total++;
                                       
                                       array_push ( $line, $col9,$col10,$col11);            
                                        $u++;                           
                                          
                                   }
                                  
                               }
                               
                                     
                           }
                                             
                       }
                       if ($line!=null){
                            for($i=0;$i<$nbcol-$total-1;$i++){
                                array_push ($line,'','','');
                            }
                            
                            $now= new DateTime(date('Y-m-d'));
                            $col13 = $dlpr;
                            $dlpro = new DateTime($dlpr);       
                            $intervalfinal = abs((int) $now->diff($dlpro)->format('%R%a'));
                            $col14 = $intervalfinal; // Colonne : DATE DE L'AVENTURE ET ECART AVEC LA DATE DE LA PRECEDENTE AVENTURE
                            $col15 = $d2->getcurrency1();
                            $col16 = $d2->getcurrency3();
                            $col17 = $d2->getcurrency4();
                            $col18 = $d2->getcurrency5();
                            $col19 = $d2->getcurrency6();
                           
                            if ($d2->getPhone()!==null && strcasecmp($d2->getPhone(),"iOS") === 0){ 
                                $col20 = 'IOS';
                            }else if($d2->getPhone()!==null && strcasecmp($d2->getPhone(),"ANDROID") === 0){
                                $col20 = 'ANDROID';                            
                            }else{
                                $col20 = $d2->getPhone();
                            }
                            array_push ($line, $col13, $col14, $total, $totalpa, $totalg, $totalpr, $col15, $col16, $col17, $col18, $col19, $col20);
                            array_push ($tab,$line);

                       }
                       
                   }                 
                  
                  
                  foreach($obj4 as $d4) {
                                                     
                    if ($d4->getFirstPurchase()!==null) $gfp=date_format($d4->getFirstPurchase(),'Y-m-d');

                    $col1 = $d4->getIdPlayer(); // Colonne 1 : ID   
                    $col2 = $d4->getMail();    // Colonne 2 : MAIL
    
                    if ($d4->getPseudo()!==null){
                        $col3 = $d4->getPseudo(); // Colonne 3 : PSEUDO
                    }else{
                        $col3 = 'INCONNU'; 
                    }
    
                    $col4 = $gdc; // Colonne 4 : DATE DE CREATION DU COMPTE UTILISATEUR
    
                    if ($d4->getLatitude()===48.858200073242 && $d4->getLongitude()===2.338700056076 ){ // Colonne 5 : VILLE DE CREATION DU COMPTE UTILISATEUR
                        $col5 = "Paris";
                    }elseif (is_null($d4->getCity())){
                        $col5 = "???";
                    }else{
                        $col5 = $d4->getCity();
                    } 
                    
                    $col6 = $gfp; // Colonne 8 : DATE DU PREMIER PAIEMENT                            
                    
                    $line = array ($col1,$col2,$col3,$col4,$col5,$col6);

                    for($j=0;$j<$nbcol+1;$j++){
                        array_push ($line,'','','');
                    }
                    $col16 = $d4->getcurrency3();
                    $col17 = $d4->getcurrency4();
                    $col18 = $d4->getcurrency5();
                    $col19 = $d4->getcurrency6();

                    array_push ($line,'',$col16,$col17,$col18,$col19);
                    array_push ($tab,$line);
               }        
         
           $path = '..\public\Files_CSV\Quaestyo.csv';
           $file = fopen($path , 'w');
           fputs( $file, "\xEF\xBB\xBF" );

           foreach ($tab as $data) { 
               fputcsv($file, $data, ";"); 
           } 

           fclose($file);
                      
           return $this->redirectToRoute('quaestyo_homeland', [               
               'messagec' => 'Fichier créé et téléchargeable ici',               
           ]);
        }
        
    }       
?>
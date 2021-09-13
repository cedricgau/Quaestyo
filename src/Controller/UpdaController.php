<?php

namespace App\Controller;

use DateTime;
use App\Entity\Player;
use App\Entity\Game;
use App\Entity\Adventure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdaController extends AbstractController
{
    /**
     * @Route("/update", name="update")
     */

    
    public function update($nameFile): Response
    {
        
        $em=$this->getDoctrine()->getManager();

        if($nameFile === 'player'){
            $con = $em->getRepository(Player::class);
        }else if($nameFile === 'ADV_IND-leaderboard'){
            $con = $em->getRepository(Game::class);
        }else if($nameFile === 'meta.adventures'){
            $con = $em->getRepository(Adventure::class);
        }

        // chemin d'accès aux fichiers JSON
         
        $file = $this->getParameter('json_dir').'/'.$nameFile;

                
        if (file_exists($file)){
            

            $data = file_get_contents($file);
            $obj = json_decode($data); // Fichier 
            unlink($file); // détruit le fichier                      
            
            foreach($obj as $d) {

                if($nameFile === 'player' && $con->find($d->{'_id'}->{'$oid'})== null){

                    $player = new Player();

                    // conversion des différentes dates

                    if(isset($d->{'created'}->{'$date'}->{'$numberLong'}) && $d->{'created'}->{'$date'}->{'$numberLong'}!=null){
                        $dt = substr($d->{'created'}->{'$date'}->{'$numberLong'},0,-3);
                        $dt2 = new DateTime(date('Y-m-d', $dt));
                        
                    }else{
                        $dt2=null;
                    }
                    if(isset($d->{'firstPurchaseDate'}->{'$date'}->{'$numberLong'}) && $d->{'firstPurchaseDate'}->{'$date'}->{'$numberLong'}!=null){
                        $dt = substr($d->{'firstPurchaseDate'}->{'$date'}->{'$numberLong'},0,-3);
                        $dt3 = new DateTime(date('Y-m-d', $dt));
                        
                    }else{
                        $dt3=null;
                    }
                    if(isset($d->{'lastSeen'}->{'$numberLong'}) && $d->{'lastSeen'}->{'$numberLong'}!=null){
                        $dt = substr($d->{'lastSeen'}->{'$numberLong'},0,-3);
                        $dt4 = new DateTime(date('Y-m-d', $dt));
                        
                    }else{
                        $dt4=null;
                    }

                    if ( isset($d->{'displayName'})){
                        $aremp = $d->{'displayName'}; // Colonne 3 : PSEUDO
                    }else{
                        $aremp = 'INCONNU'; 
                    }
                    if (trim($d->{'location'}->{'latitide'})=="48.858200073242" && trim($d->{'location'}->{'longditute'})=="2.338700056076" ){ // Colonne 5 : VILLE DE CREATION DU COMPTE UTILISATEUR
                        $aremp2 = "Paris";
                    }elseif (is_null($d->{'location'}->{'city'})){
                        $aremp2 = "INCONNU";
                    }else{
                        $aremp2 = $d->{'location'}->{'city'};
                    }
                    if ( isset($d->{'pushRegistrations'}[0]->{'deviceOS'}) && strcasecmp($d->{'pushRegistrations'}[0]->{'deviceOS'},"iOS") == 0){ 
                        $type = 'IOS';
                    }else{
                        $type = 'ANDROID';
                    }
                    if (!isset($d->{'state'}))  $d->{'state'}="";
                    
                            $player
                                ->setIdPlayer($d->{'_id'}->{'$oid'})
                                ->setMail($d->{'userName'})
                                ->setPseudo($aremp)
                                ->setDateCreation($dt2)
                                ->setCity($aremp2)
                                ->setLatitude($d->{'location'}->{'latitide'})
                                ->setLongitude($d->{'location'}->{'longditute'})
                                ->setFirstPurchase($dt3)
                                ->setState($d->{'state'})
                                ->setCurrency1($d->{'currency1'}->{'$numberLong'})
                                ->setCurrency2($d->{'currency2'}->{'$numberLong'})
                                ->setCurrency3($d->{'currency3'}->{'$numberLong'})
                                ->setCurrency4($d->{'currency4'}->{'$numberLong'})
                                ->setCurrency5($d->{'currency5'}->{'$numberLong'})
                                ->setCurrency6($d->{'currency6'}->{'$numberLong'})
                                ->setPhone($type)
                                ->setLastSeen($dt4);    

                            $em->persist($player);

                }else if($nameFile === 'ADV_IND-leaderboard' && $con->findOneBy(array('code_adv' => $d->{'SHORT_CODE'}, 'id_player' => $d->{'userId'})) == null){

                    $game = new Game();

                    if(isset($d->{'ts'}->{'$numberLong'}) && $d->{'ts'}->{'$numberLong'}!=null){
                        $dt = substr($d->{'ts'}->{'$numberLong'},0,-3);
                        $dtc = new DateTime(date('Y-m-d', $dt));                        
                    }else{
                        $dtc=null;
                    }

                    // Aventure 33 , 36 et 46 met le player en currency4 = 444
                    
                    if( $d->{'SHORT_CODE'} === ('ADV_33') || $d->{'SHORT_CODE'} === ('ADV_36') || $d->{'SHORT_CODE'} === ('ADV_46') || $d->{'SHORT_CODE'} === ('ADV_78')){
                        $nvcurr = $em->getRepository(Player::class)->find($d->{'userId'});
                        if($nvcurr!=null){
                            $nvcurr->setCurrency4(444);
                            $em->flush();
                        }
                    }

                    $advstate = $em->getRepository(Adventure::class)->findOneBy(['code_adv' => $d->{'SHORT_CODE'}]);
                    if($advstate){
                        $st = $advstate->getState();
                    }else{
                        $st = "PAYANT";
                    }
                                        
                            $game
                                ->setCodeAdv($d->{'SHORT_CODE'})
                                ->setIdPlayer($d->{'userId'})
                                ->setDatePlayed($dtc)
                                ->setState($st);

                        
                            $em->persist($game);

                }else if($nameFile === 'meta.adventures' && $con->find($d->{'_id'}->{'$oid'})== null){
                    $adventure = new Adventure();

                    if (isset($d->{'name'}->{'description'})){
                        $aremp = $d->{'name'}->{'description'}; // Colonne 3 : PSEUDO
                    }else{
                        $aremp = ''; 
                    }            
                    if (!isset($d->{'location'}->{'description'}))  $d->{'location'}->{'description'}="";

                    $adventure
                        ->setIdAdventure($d->{'_id'}->{'$oid'})
                        ->setCodeAdv($d->{'short_code'})
                        ->setName($d->{'name'})
                        ->setSubname($aremp)
                        ->setLatitude($d->{'location'}->{'latitude'})
                        ->setLongitude($d->{'location'}->{'longitude'})
                        ->setCity($d->{'location'}->{'city'})
                        ->setState($d->{'location'}->{'description'});
        
                    $em->persist($adventure);
                }
            }
        
        
        $em->flush();
        
        return $this->redirectToRoute('quaestyo_homeland', [               
            'messageb' => 'La base de donnée a bien été mise à jour',               
        ]);
        }
        return $this->redirectToRoute('quaestyo_homeland', [
            'messageb' => 'La mise à jour n\'a pas fonctionnée, Appelez Cédric',            
        ]);  
    }
}
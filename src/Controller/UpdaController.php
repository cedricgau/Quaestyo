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

class UpdaController extends AbstractController
{
    /**
     * @Route("/update", name="update")
     */

    
    public function update($nameFile): Response
    {
        
        $em=$this->getDoctrine()->getManager();

        if($nameFile === 'player' || $nameFile === 'playerDetails' || $nameFile === 'playerMails'){
            $con = $em->getRepository(Player::class);            
        }else if($nameFile === 'ADV_IND-leaderboard' || $nameFile === 'adventureScore'){
            $con = $em->getRepository(Game::class);
        }else if($nameFile === 'meta.adventures'){
            $con = $em->getRepository(Adventure::class);
        }

        
        // chemin d'accès aux fichiers JSON ou CSV
         
        $file = $this->getParameter('json_dir').'/'.$nameFile;

        $mail="";
                
        if (file_exists($file))
        {

            if($nameFile === 'playerMails')
            {
                
                if (($handle = fopen($file, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle,";")) !== FALSE) {
                        $unit = explode(";",$data[0]);
                        if($con->find($unit[0]) !== null){
                            $player = $con->find($unit[0]);
                            $new_mail = trim($unit[1]);                            
                            $player->setMail($new_mail);                                
                            $em->flush();
                        }
                    
                    }
                fclose($handle);
                }
            
            }else if ($nameFile === 'player' || $nameFile === 'playerDetails' || $nameFile === 'ADV_IND-leaderboard' || $nameFile === 'meta.adventures' || $nameFile === 'adventureScore') { 
            
                $data = file_get_contents($file);
                $obj = json_decode($data); // Fichier 
                unlink($file); // détruit le fichier                    
                foreach($obj as $d) {

                    if($nameFile === 'player' && $con->find($d->{'_id'}->{'$oid'}) === null){

                        $player = new Player();

                        // conversion des différentes dates

                        if(isset($d->{'created'}->{'$date'}->{'$numberLong'}) && $d->{'created'}->{'$date'}->{'$numberLong'} !== null){
                            $dt = substr($d->{'created'}->{'$date'}->{'$numberLong'},0,-3);
                            $dt2 = new DateTime(date('Y-m-d', $dt));
                            
                        }else{
                            $dt2=null;
                        }
                        if(isset($d->{'firstPurchaseDate'}->{'$date'}->{'$numberLong'}) && $d->{'firstPurchaseDate'}->{'$date'}->{'$numberLong'} !== null){
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
                        if (trim($d->{'location'}->{'latitide'}) === "48.858200073242" && trim($d->{'location'}->{'longditute'}) === "2.338700056076" ){ // Colonne 5 : VILLE DE CREATION DU COMPTE UTILISATEUR
                            $aremp2 = "Paris";
                        }elseif (is_null($d->{'location'}->{'city'})){
                            $aremp2 = "INCONNU";
                        }else{
                            $aremp2 = $d->{'location'}->{'city'};
                        }
                        if ( isset($d->{'pushRegistrations'}[0]->{'deviceOS'}) && strcasecmp($d->{'pushRegistrations'}[0]->{'deviceOS'},"iOS") === 0){ 
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

                    }else if($nameFile === 'ADV_IND-leaderboard' && $con->findOneBy(array('code_adv' => $d->{'SHORT_CODE'}, 'id_player' => $d->{'userId'})) === null){

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

                        if(isset($d->{'SCORE'}->{'$numberLong'}) && $d->{'SCORE'}->{'$numberLong'} !== null) $score = (int)$d->{'SCORE'}->{'$numberLong'};

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
                                    ->setState($st)
                                    ->setScore($score);
                            
                                $em->persist($game);

                    }else if($nameFile === 'meta.adventures' && $con->find($d->{'_id'}->{'$oid'})== null){
                        $adventure = new Adventure();
						
                        if (isset($d->{'name'}->{'description'})){
                            $aremp = $d->{'name'}->{'description'}; // Colonne 3 : PSEUDO
                        }else{
                            $aremp = ''; 
                        }            
                        if (!isset($d->{'location'}->{'description'}) || $d->{'location'}->{'description'} !== "PRIVE" || $d->{'location'}->{'description'} !== "GRATUIT" || $d->{'location'}->{'description'} !== "PAYANT")  $d->{'location'}->{'description'}="";
						
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

                    }else if($nameFile === 'playerDetails'){
                        $player = new Player();

                        // mise à jour des emails GP_ et FB_
                                    
                        if(substr($d->{'data'}->{'email_lower'},0,3) === "fb_" || substr($d->{'data'}->{'email_lower'},0,3) === "gp_" || substr($d->{'data'}->{'user_name'},0,3) === "FB_" || substr($d->{'data'}->{'user_name'},0,3) === "GP_"){
                            $player = $em->getRepository(Player::class)->find($d->{'id'});
                            if ($player) {
                                if(substr($d->{'data'}->{'email'},0,3) === "FB_" || substr($d->{'data'}->{'email'},0,3) === "GP_" ){
                                    $mail = substr($d->{'data'}->{'email'},3);
                                }else{
                                    $mail = $d->{'data'}->{'email'};
                                }
                                $player->setMail($mail);
                                
                                $em->flush();                            
                            }  
                        }
                    }else if($nameFile === 'adventureScore'){
                        $adventure = new Adventure();

                        // mise à jour des scores
                        $adventure = $em->getRepository(Game::class)->findOneBy(['id_player' => $d->{'userId'},'code_adv' => $d->{'SHORT_CODE'}]);                        
                            if ($adventure) {
                                
                                $adventure->setScore($d->{'SCORE'}->{'$numberLong'});
                                
                                $em->flush();                            
                            }  
                    }
                } 
                        
                       
        
                $em->flush();               
       
            }
            return $this->redirectToRoute('quaestyo_homeland', [               
                'messageb' => 'La base de donnée a bien été mise à jour',               
            ]);
       
        }
            
    }
}
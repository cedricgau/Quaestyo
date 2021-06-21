<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Player;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PlayerFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        

        // chemin d'accès aux fichiers JSON
         
        $file = 'F:\TP_Symfony_PHP\Quaestyo\public\Files_JSON\player.json';
        if (file_exists($file)){

        $data = file_get_contents($file);
        $obj = json_decode($data); // Fichier des joueurs

        foreach($obj as $d) {

            $player = new Player();

            // conversion des différentes dates

            if(isset($d->{'created'}->{'$date'}->{'$numberLong'}) && $d->{'created'}->{'$date'}->{'$numberLong'}!=null){
                $dt = substr($d->{'created'}->{'$date'}->{'$numberLong'},0,-3);
                $dt2 = new DateTime(date('Y-m-d', $dt));
                //$dt2 = new DateTimeInterface ($dt);
            }else{
                $dt2=null;
            }
            if(isset($d->{'firstPurchaseDate'}->{'$date'}->{'$numberLong'}) && $d->{'firstPurchaseDate'}->{'$date'}->{'$numberLong'}!=null){
                $dt = substr($d->{'firstPurchaseDate'}->{'$date'}->{'$numberLong'},0,-3);
                $dt3 = new DateTime(date('Y-m-d', $dt));
                //$dt3 = new DateTimeInterface ($dt);
            }else{
                $dt3=null;
            }
            if(isset($d->{'lastSeen'}->{'$numberLong'}) && $d->{'lastSeen'}->{'$numberLong'}!=null){
                $dt = substr($d->{'lastSeen'}->{'$numberLong'},0,-3);
                $dt4 = new DateTime(date('Y-m-d', $dt));
                //$dt4 = new DateTimeInterface ($dt);
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

                    $manager->persist($player);

        }
        
        
        $manager->flush();

        }
    }
}

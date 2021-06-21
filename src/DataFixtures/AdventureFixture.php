<?php

namespace App\DataFixtures;

use App\Entity\Adventure;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdventureFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
       
        $file = 'F:\TP_Symfony_PHP\Quaestyo\public\Files_JSON\meta.adventures.json'; 
        $data = file_get_contents($file);  
        $obj = json_decode($data); // Fichier des aventures


        foreach($obj as $d){

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
        
            $manager->persist($adventure);
        }
        $manager->flush();
    }
}

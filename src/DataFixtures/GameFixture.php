<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Game;
use DateTimeInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class GameFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $file = 'F:\TP_Symfony_PHP\Quaestyo\public\Files_JSON\ADV_IND-leaderboard.json';
        
        if (file_exists($file)){

        $data = file_get_contents($file);  
        $obj = json_decode($data); // Fichier des aventures


        foreach($obj as $d){

            $game = new Game();

            if(isset($d->{'ts'}->{'$numberLong'}) && $d->{'ts'}->{'$numberLong'}!=null){
                $dt = substr($d->{'ts'}->{'$numberLong'},0,-3);
                $dtc = new DateTime(date('Y-m-d', $dt));
                
            }else{
                $dtc=null;
            }                                      
                                        
            $game
                ->setCodeAdv($d->{'SHORT_CODE'})
                ->setIdPlayer($d->{'userId'})
                ->setDatePlayed($dtc)
                ->setState("PAYANT");

        
            $manager->persist($game);
        }

        $manager->flush();
    }
}
}

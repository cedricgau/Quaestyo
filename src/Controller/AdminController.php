<?php

namespace App\Controller;

use DateTime;
use App\Entity\Drop;
use App\Entity\Game;
use App\Entity\Player;
use App\Form\DropType;
use App\Entity\Adventure;
use App\Entity\ExternDatas;
use App\Form\AdventureType;
use App\Form\MajPlayerType;
use App\Form\PlayerMailType;
use App\Form\ExternDatasType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    /**
     * @Route("/quaestyo_homeland", name="quaestyo_homeland")
     */
    public function index(Request $request, UserInterface $user): Response
    {
        // initialisation des messages 

        $messagea = "";
        $messageb = "";
        $messagec = "";
        $messaged = "";
        $messagee = "";
        $messagef = "";
        $messageg = "";

        setlocale(LC_TIME, 'fra_fra');
        $affdate = strftime('%A %d %B %Y');        
        $affdate2 = strftime('%d %m %Y');
        
        // Mise à jour des currencies du Player
        
        $extdatas = new ExternDatas();
        
        $form_externdatas = $this->createForm(ExternDatasType::class, $extdatas);
        
        $form_externdatas->handleRequest($request);

        if( $form_externdatas->isSubmitted() && $form_externdatas->isValid()){           

            $em = $this->getDoctrine()->getManager();
             
            $equalm = date("m", strtotime($form_externdatas->get('date_payed')->getData()->format('M')));
            $equaly = date("Y", strtotime($form_externdatas->get('date_payed')->getData()->format('Y')));
            $datedem = new DateTime($equaly."-".$equalm."-01");
                       
            $exdata = $em->getRepository(ExternDatas::class)->findOneBy(array('date_payed' => $datedem));

                      
            if (!$exdata){ 
                $exdata = new ExternDatas();
                $exdata
                  ->setCA($form_externdatas->get('CA')->getData())                  
                  ->setAdvert($form_externdatas->get('advert')->getData())
                  ->setDatepayed($datedem)
                  ->setDownload($form_externdatas->get('download')->getData())
                  ->setUninstall($form_externdatas->get('uninstall')->getData());
                  
                $em->persist($exdata);                
            }else{
                $exdata
                  ->setCA($form_externdatas->get('CA')->getData())                  
                  ->setAdvert($form_externdatas->get('advert')->getData())
                  ->setDatepayed($datedem)->setDownload($form_externdatas->get('download')->getData())
                  ->setUninstall($form_externdatas->get('uninstall')->getData());      
            
            }
            $em->flush();
            $messagea = 'Mise à jour du Chiffre d\'affaires : '.$exdata->getCA().' , du coût de la publicité  : '.$exdata->getAdvert().', des téléchargements  : '.$exdata->getDownload().' et des désinstallations  : '.$exdata->getUninstall().' pour le mois : '.$equalm.'/'.$equaly;
            }

        // Mise à jour de la base de données

        $drop = new Drop();        
        $form_drop = $this->createForm(DropType::class, $drop);
        
        $form_drop->handleRequest($request);

        if( $form_drop->isSubmitted() && $form_drop->isValid()){            
            $file = $form_drop->get('file')->getData();
            $filename = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

            try {
                $file->move(
                    $this->getParameter('json_dir'),
                    $filename,
                );
                $this->forward('App\Controller\UpdaController::update', [
                    'nameFile' => $filename,
                ]);

            } catch (FileException $e) {
               
            }
        }

        // Mise à jour des currencies du Player
        
        $play = new Player();
        
        $form_play = $this->createForm(MajPlayerType::class, $play);
        
        $form_play->handleRequest($request);

        if( $form_play->isSubmitted() && $form_play->isValid()){           

            $em = $this->getDoctrine()->getManager();
        
            $player = $em->getRepository(Player::class)->find($form_play->get('id_player')->getData());

            if (!$player) {
                $messaged = 'Aucun Joueur trouvé pour l\'ID : '.$form_play->get('id_player')->getData();                
            }else{
                $player
                    ->setCurrency1($form_play->get('currency1')->getData())
                    ->setCurrency2($form_play->get('currency2')->getData())
                    ->setCurrency3($form_play->get('currency3')->getData())      
                    ->setCurrency4($form_play->get('currency4')->getData())   
                    ->setCurrency5($form_play->get('currency5')->getData())
                    ->setCurrency6($form_play->get('currency6')->getData());                   
                    
                $em->flush();
                $messaged = 'Mise à jour ID Player : '.$player->getIdPlayer().'des currencies 3,4,5,6 : '.$player->getCurrency3().','.$player->getCurrency4().','.$player->getCurrency5().','.$player->getCurrency6();
            }
        }

        // Mise à jour du mail/ville du Player

        $playmail = new Player();
        
        $form_mail_player = $this->createForm(PlayerMailType::class, $playmail);
        
        $form_mail_player->handleRequest($request);

        if( $form_mail_player->isSubmitted() && $form_mail_player->isValid()){           

            $em = $this->getDoctrine()->getManager();
        
            $player = $em->getRepository(Player::class)->find($form_mail_player->get('id_player')->getData());

            if (!$player) {
                $messaged = 'Aucun Joueur trouvé pour l\'ID : '.$form_mail_player->get('id_player')->getData();
                /* $file = '..\public\Files_JSON\playerDetails.json'; 
                $data = file_get_contents($file);
                $obj = json_decode($data);
                
                foreach($obj as $d) {                    
                    if(substr($d->{'data'}->{'user_name'},0,3) === "FB_" || substr($d->{'data'}->{'user_name'},0,3) === "GP_" ){
                        $player = $em->getRepository(Player::class)->find($d->{'id'});
                        if ($player) {
                            $player->setMail($d->{'data'}->{'email'});
                            var_dump($d->{'data'}->{'email'});
                            $em->flush();
                        }  
                    }
                } */
            }else{
                if($form_mail_player->get('mail')->getData()!==null && $form_mail_player->get('city')->getData()!==null){
                    $player
                        ->setMail($form_mail_player->get('mail')->getData())
                        ->setCity($form_mail_player->get('city')->getData());        
                        
                    $em->flush();
                    $messageg = 'Mise à jour ID Player : '.$player->getIdPlayer().' du mail : '.$player->getMail().' et de la ville : '.$player->getCity();

                }else if( $form_mail_player->get('mail')->getData()!==null){
                    $player
                        ->setMail($form_mail_player->get('mail')->getData());
                        $em->flush();
                    $messageg = 'Mise à jour ID Player : '.$player->getIdPlayer().' du mail : '.$player->getMail();
                    
                }else if( $form_mail_player->get('city')->getData()!==null){
                    $player
                        ->setCity($form_mail_player->get('city')->getData());
                        $em->flush();
                    $messageg = 'Mise à jour ID Player : '.$player->getIdPlayer().' de la ville : '.$player->getCity();

                }else{
                    $messageg = 'Mise à jour ID Player : '.$player->getIdPlayer().' RIEN puisque rien n\'est renseigné ! ';
                }
            }
        }

        // Mise à jour de l'état de l'Adventure
        
        $adv = new Adventure();
        
        $form_adv = $this->createForm(AdventureType::class, $adv);
        
        $form_adv->handleRequest($request);

        if( $form_adv->isSubmitted() && $form_adv->isValid()){           

            $em = $this->getDoctrine()->getManager();            
            $adventure = $em->getRepository(Adventure::class)->findOneBy(['code_adv' => $form_adv->get('code_adv')->getData()]);

            if (!$adventure) {
                $messagef = 'Aucune aventure trouvé pour le code : '.$form_adv->get('code_adv')->getData();                
            }else{
                $adventure
                    ->setState($form_adv->get('state')->getData());                          
                    
                $em->flush();
                $messagef = 'Mise à jour de l\'aventure : '.$form_adv->get('code_adv')->getData().' pour un état : '.$form_adv->get('state')->getData();
            }
        }

        // affichage des stats de la base de données

        $em = $this->getDoctrine()->getManager();        
        $p = $em->getRepository(Player::class)->findAll();
        $cp = count($p);
        $g = $em->getRepository(Game::class)->findAll();        
        $cg = count($g);
        $dMaj = $em->getRepository(Game::class)->findBy(array(), array('date_played' => 'DESC'));
        $dm = date_format($dMaj[0]->getDatePlayed(),'d/m/Y');
        $a = $em->getRepository(Adventure::class)->findAll();
        $ca = count($a);
       
        
        if ( $request->query->get('messagea') !== null) $messagea = $request->query->get('messagea');
        if ( $request->query->get('messageb') !== null) $messageb = $request->query->get('messageb');
        if ( $request->query->get('messagec') !== null) $messagec = $request->query->get('messagec');
        if ( $request->query->get('messaged') !== null) $messaged = $request->query->get('messaged');
        if ( $request->query->get('messagee') !== null) $messagee = $request->query->get('messagee');
        if ( $request->query->get('messagef') !== null) $messagef = $request->query->get('messagef');
        if ( $request->query->get('messageg') !== null) $messageg = $request->query->get('messageg');


        return $this->render('admin/index.html.twig', [
            'controller_name' => strstr($user->getUsername(),"@",true).',',
            'form_externdatas' => $form_externdatas->createView(),
            'form_drop' => $form_drop->createView(),
            'form_majp' => $form_play->createView(),
            'form_adv' => $form_adv->createView(),
            'form_mail_player' => $form_mail_player->createView(),
            'today' =>  $affdate,
            'today2' =>  $affdate2,
            'dm' => $dm,
            'cp' => $cp,
            'cg' => $cg,
            'ca' => $ca,
            'messagea' => $messagea,
            'messageb' => $messageb,
            'messagec' => $messagec,
            'messaged' => $messaged,
            'messagee' => $messagee,
            'messagef' => $messagef,
            'messageg' => $messageg,
        ]);
        
    }
}

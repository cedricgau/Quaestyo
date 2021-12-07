<?php

namespace App\Controller;

use App\Controller\functions\ArrayToCsv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CsvConverterController extends AbstractController
{
    /**
     * @Route("/csv/converter", name="csv_converter")
     */
    public function index(Request $request): Response
    {
           $path = $this->getParameter('csv_dir').'/testdemerde.csv';
           
           $arrayToCsv = new ArrayToCsv();
            $tab = array(
                "0"  => array("a"),
                "1"  => array("b"),
                "2"  => array("c"),
                "3"  => array("d")
               
            );
            $arrayToCsv->convertToCsv($tab,$path);
           
        return $this->render('csv_converter/index.html.twig', [
            'controller_name' => $path,
        ]);
    }
}

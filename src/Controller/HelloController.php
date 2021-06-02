<?php

namespace App\Controller;

use Twig\Environment;
use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HelloController
{
    // protected $calculator;

    // public function __construct(Calculator $calculator)
    // {
    //     $this->calculator = $calculator;
    // }

    // protected $logger;

    // public function __construct(LoggerInterface $logger)
    // {
    //     $this->logger = $logger;
    // }

    /**
     * @Route("/hello/{prenom?World}", name="hello")
     */

    public function hello(Request $request, $prenom, LoggerInterface $logger, Calculator $calculator, Slugify $slugify, Environment $twig)
    {
        dump($twig);

        // $slugify = new Slugify();

        dump($slugify->slugify("Bonjour le monde !"));

        $logger->info("Mon message de log !");

        $tva = $calculator->calcul(100);

        dump($tva);

        return new Response("Hello $prenom !");
    }
}

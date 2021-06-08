<?php

namespace App\Controller;

use Twig\Environment;
use App\Taxes\Detector;
use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HelloController extends AbstractController
{
    protected $twig;

    // public function __construct(Environment $twig)
    // {
    //     $this->twig = $twig;
    // }
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

    public function hello(Request $request, $prenom)
    {

        // dump($detector->detect(101));
        // dump($detector->detect(10));

        // $slugify = new Slugify();

        // dump($slugify->slugify("Bonjour le monde !"));

        // $logger->info("Mon message de log !");

        // $tva = $calculator->calcul(100);

        // dump($tva);

        // return new Response("Hello $prenom !");

        $html = $this->render('hello.html.twig', [
            'prenom' => $prenom,
            'age' => 28,
            'prenoms' => [
                'Jessey',
                'Aïssata',
                'Jamaal'
            ],
            // Exercice 1
            'ages' => [
                12,
                18,
                29,
                15
            ],
            // Explication 2
            'formateur' => [
                'prenom' => 'Lior',
                'nom' => 'Chamla',
                'age' => 33
            ],
            // Explication 3 (inclusion de templates)
            'formateur1' => ['prenom' => 'Lior', 'nom' => 'Chamla'],
            'formateur2' => ['prenom' => 'Jerôme', 'nom' => 'Dupont'],
            // Explication 4
        ]);
        return $this->render('hello.html.twig', [
            'prenom' => $prenom
        ]);
    }

    /**
     * @Route("/example", name="example")
     */

    public function example()
    {
        return $this->render('example.html.twig', [
            'age' => 33
        ]);
    }

    // protected function render(string $path, array $variables = [])
    // {
    //     $html = $this->twig->render($path, $variables);
    //     return new Response($html);
    // }
}

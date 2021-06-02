<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $tva = $this->calculator->calcul(100);
        dump($tva);

        dd("Ca fonctionne");
        // die();
    }

    /**
     * @Route("/test/{age<\d+>?0}", name="test", methods={"GET", "POST"}, host="localhost", schemes={"https", "http"})
     */

    public function test(Request $request, $age)
    {
        // $request = Request::createFromGlobals();

        // $age = $request->query->get('age', 0);
        // $age = $request->attributes->get('age');

        return new Response("Vous avez $age ans !");

        // $age = 0;

        // if (!empty($_GET['age'])) {
        //     $age = $_GET['age'];
        // }

        // dd("Vous avez $age ans !");
    }
}

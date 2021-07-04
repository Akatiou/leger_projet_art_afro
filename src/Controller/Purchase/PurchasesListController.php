<?php

namespace App\Controller\Purchase;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasesListController extends AbstractController
{

    // Plus besoin de tout ça, car donné par l'abstract controller [
    // protected $security;
    // protected $router;
    // protected $twig;

    // public function __construct(Security $security, RouterInterface $router, Environment $twig)
    // {
    //     $this->security = $security;
    //     $this->router = $router;
    //     $this->twig = $twig;
    // } ]

    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à votre commande !")
     */

    public function index()
    {
        // 1. Nous devons nous assurer que la personne est connecté, (sinon redirection vers la page d'accueil) -> Security
        /** @var User */
        $user = $this->getUser();

        // if (!$user) {
        //     $url = $this->router->generate('homepage');

        //     // Redirection -> RedirectResponse
        //     return new RedirectResponse($url);

        //     // Générer une URL en fonction d'une route ->UrlGeneratorInterface ou RouterInterface
        // } On faisait ce qu'il y a au dessu, c-a-d rediriger vers la page d'accueil, mais finalement on fait ce qu'il y a en dessous

        //FInalement on met l'annotation IsGranted au dessus
        // if (!$user) {
        //     throw new AccessDeniedException("Vous devez être connecté pour accéder à votre commande !");
        // }

        // 2. Nous voulons savoir QUI est connecté -> Security

        // 3. Nous voulons passer l'utilisateur connecté à Twig afin d'afficher ses commandes -> Environment de Twig / Response
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        // $html = $this->twig->render('purchase/index.html.twig', [
        //     'purchases' => $user->getPurchases()
        // ]);
        // return new Response($html);
    }
}

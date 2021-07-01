<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    //--------------------------------------------------
    //    Connexion (Login)
    //---------------------------------------------------

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);

        // $form = $factory->createNamed('', LoginType::class, ['email' => $utils->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    //--------------------------------------------------
    //    Déconnexion (Logout)
    //---------------------------------------------------

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }

    //--------------------------------------------------
    //    Création d'une nouvelle session (New Login)
    //---------------------------------------------------
}
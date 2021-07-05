<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PurchasePaymentSuccessController extends AbstractController
{

    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService, EventDispatcherInterface $dispatcher)
    {
        // 1. Je récupère la commande
        $purchase = $purchaseRepository->find($id);

        // S'il n'y a pas de purchase ou alors il y a une purchase, mais que l'utilisateur de cet purchase ne correspond pas à l'utilisateur connecté
        // Ou alors qu'il y a une purchase et qu'elle ait un statut payer, alors warning et redirection
        if (
            !$purchase ||
            ($purchase && $purchase->getUser() !== $this->getUser()) ||
            ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        ) {
            $this->addFlash('warning', "La commande n'existe pas !");
            return $this->redirectToRoute("purchase_index");
        }

        // 2. Je la fait passer au statut payer (PAID)
        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();

        // 3. Je vide le panier empty
        $cartService->empty();


        // Lancer un évènement qui permettra aux autres dév de réagir à la prise d'une commande

        $purchaseEvent = new PurchaseSuccessEvent($purchase);

        $dispatcher->dispatch($purchaseEvent, 'purchase.success');

        // 4. Je redirige avec un flash vers la liste des commandes
        $this->addFlash('success', "La commande a été payé et confirmé !");
        return $this->redirectToRoute("purchase_index");
    }
}

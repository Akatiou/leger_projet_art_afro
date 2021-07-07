<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    //--------------------------------------------------
    //   Afficher la page d'accueil côté Admin
    //---------------------------------------------------
    /**
     * @Route("/admin", name="dashboard")
     */
    public function dashboard(ProductRepository $productRepository, UserRepository $userRepository, PurchaseRepository $purchaseRepository): Response
    {
        // $product = $productRepository->findOneBy([
        //     'slug' => $slug
        // ]);

        return $this->render('admin/dashboard.html.twig', [
            'product' => $productRepository->findAll(),
            'products' => $productRepository->findByMaxValue(),
            'users' => $userRepository->findAll(),
            'purchases' => $purchaseRepository->findAll(),
            'lastproducts' => $productRepository->findByLaterDate()
        ]);
    }


    //--------------------------------------------------
    //   Afficher tous les produits côté Admin
    //---------------------------------------------------
    /**
     * @Route("/admin/product", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {

        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
            // 'product' => $product
        ]);
    }


    //--------------------------------------------------
    //   Afficher UN produit côté Admin
    //---------------------------------------------------

    /**
     * @Route("/admin/product/{id}", name="admin_product_show", priority=-1, methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }


    //--------------------------------------------------
    //    Création d'UN produit
    //---------------------------------------------------

    /**
     * @Route("/admin/product/create", name="product_create")
     */

    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('admin/product/create.html.twig', [
            'formView' => $formView
        ]);
    }


    //--------------------------------------------------
    //   Modification d'un produit
    //---------------------------------------------------

    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */

    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
                'id' => $id
            ]);
        }

        $formView = $form->createView();

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }


    //--------------------------------------------------
    //   Suppression d'un produit
    //---------------------------------------------------

    /**
     * @Route("/admin/product/{id}/delete", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }


    //--------------------------------------------------
    //               ADMIN USER !!!!
    //---------------------------------------------------

    //--------------------------------------------------
    //   Voir tous les users
    //---------------------------------------------------

    /**
     * @Route("/admin/user", name="user_index", methods={"GET"})
     */
    public function indexUser(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    //--------------------------------------------------
    //   Voir UN user
    //---------------------------------------------------

    /**
     * @Route("/admin/user/{id}", name="user_show", priority=-1, methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show_user(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }


    //--------------------------------------------------
    //   Ajout d'un user
    //---------------------------------------------------

    /**
     * @Route("/admin/user/new", name="user_new", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function new_user(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $user = new User;

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index', [
                'user' => $user
            ]);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    //--------------------------------------------------
    //   Modification d'un user
    //---------------------------------------------------

    /**
     * @Route("/admin/user/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit_user(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    //--------------------------------------------------
    //   Suppression d'un user
    //---------------------------------------------------

    /**
     * @Route("/admin/user/{id}/delete", name="user_delete", methods={"DELETE"})
     */
    public function delete_user(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}

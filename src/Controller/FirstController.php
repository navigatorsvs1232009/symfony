<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProductFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class FirstController extends AbstractController
{
    #[Route('/', name: 'main_homepage')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $productList = $entityManager->getRepository(Product::class)->findAll();
        return $this->render('main/first/index.html.twig', []);
    }

    #[Route('/edit-product/{id}', methods: ['GET', 'POST'], name: 'product_edit',requirements: ['id' => '\d+'])]
    #[Route('/add-product', methods: ['GET', 'POST'], name: 'product_add')]
    public function editProduct(Request $request, int $id = null, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        if($id){
            $product = $entityManager->getRepository(Product::class)->find($id);
        } else {
            $product = new Product();
        }

        $form = $this->createForm(EditProductFormType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_edit', ['id'=>$product->getId()]);

        }
        return $this->render('main/first/edit_product.html.twig', [
            'form' =>$form->createView()
        ]);

    }
}

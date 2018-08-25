<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Knp\Component\Pager\PaginatorInterface as Paginator;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/managment/product", name="productManagment")
     * @param Request $request
     * @param ProductRepository $repository
     * @param Paginator $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managment(Request $request,ProductRepository $repository,Paginator $paginator)
    {
        $productslist = $repository->findAll();
        $products  = $paginator->paginate(
            $productslist,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,12
        /*nbre d'éléments par page*/
        );
        return $this->render('product/showAdmin.html.twig', ["products" => $products]);
    }

    /**
     * @Route("/product/show" , name="showAll")
     * @param Request $request
     * @param ProductRepository $repository
     * @param Paginator $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request,ProductRepository $repository,Paginator $paginator)
    {
        $productslist = $repository->findAll();
        $products  = $paginator->paginate(
            $productslist,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,12
            /*nbre d'éléments par page*/
        );
        return $this->render('product/show.html.twig', ["products" => $products]);
    }

    /**
     * @Route("/product/show/{id}",name="show")
     * @param $id
     * @param ProductRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOne($id,ProductRepository $repository)
    {
        $product = $repository->findById($id);
        return $this->render('product/showDetails.html.twig',["products"=>$product]);
    }

    /**
     * @Route("/managment/product/new",name="create")
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function make(Request $request, ObjectManager $manager)
    {
        $fileUploader = new FileUploader('C:\laragon\www\my-project\public\uploads');
        $product = new Product();
        $form = $this->createFormBuilder($product)
            ->add("name")
            ->add("price")
            ->add("quantity")
            ->add("description",TextType::class,array('label' => 'Description','attr'=>array('placeholder'=>'description')))
            ->add('photo', FileType::class, array('label' => 'Image','attr'=>array('name'=>'file')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $product->getPhoto();
            $fileName = $fileUploader->upload($file);

            $product->setPhoto($fileName);
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute("productManagment");
        }
        return $this->render('product/create.html.twig', [
            "formProduct" => $form->createView()
        ]);

    }

    /**
     * @Route("/managment/product/delete/{id}",name="delete")
     * @param Request $request
     * @param ObjectManager $manager
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, ObjectManager $manager,Product $product){

        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute("productManagment");

    }
}

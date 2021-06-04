<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Crud;
use App\Form\CrudType;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
       
        return $this->render('main/index.html.twig');
    }
    /**
     * @Route("/home", name="home")
     */
    public function home(): Response
    {
        $data=$this->getDoctrine()->getRepository(Crud::class)->findAll();
        return $this->render('main/home.html.twig', [
            'list' => $data,
        ]);
        // foreach($data as $key =>$dat)       
        //  {          
        //       $datas[$key]['title']=$dat->getTitle();    
        //             $datas[$key]['contentt']=$dat->getContentt();    
        //   }      
        //   return new JsonResponse($datas);
        
    }
    
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $curd=new Crud();
        $form =$this->createForm(CrudType::class,$curd);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($curd);
            $em->flush();
            return $this->redirectToRoute('main');
        }
        return $this->render('main/create.html.twig',[
            'form'=>$form->createView()
        ]);

    }
    /**
     * @Route("/update{id}", name="update")
     */
    public function update(Request $request,$id)
    {
        $curd=$this->getDoctrine()->getRepository(Crud::class)->find($id);
        $form =$this->createForm(CrudType::class,$curd);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($curd);
            $em->flush();
            $this->addFlash('notice',"updated Successfully!!");
            return $this->redirectToRoute('home');
        }
        return $this->render('main/update.html.twig',[
            'form'=>$form->createView()
        ]);

    }
     /**
     * @Route("/delete{id}", name="delete")
     */
    public function delete(Request $request,$id)
    {
        $curd=$this->getDoctrine()->getRepository(Crud::class)->find($id);
        
            $em=$this->getDoctrine()->getManager();
            $em->remove($curd);
            $em->flush();
            $this->addFlash('notice',"deleted Successfully!!");
            return $this->redirectToRoute('home');

    }
}

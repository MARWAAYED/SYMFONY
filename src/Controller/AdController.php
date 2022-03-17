<?php

namespace App\Controller;


use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\Loader\Configurator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Ad::class);

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }


    /**
     * creation des annances
     * 
     * @Route("/ads/new", name="ads_create")
     * 
     * @return Response
     */

    public function create(Request $request, ObjectManager $manager){
        $Ad = new Ad();

        $form = $this->createForm(AdType::class, $Ad);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
          $manager->persist($Ad);
          $manager->flush();
    
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
            

        ]);

    }

    /**
     * permet d'afficher une seule annance
     * 
     * @Route("/ads/{slug}",name="ads_show")
     * 
     * @return Response
     */
    public function show($slug, Ad $Ad){
        //recuperation d'une annance
        //$Ad = $repo->findOneBySlug($slug); 

        return $this->render('ad/show.html.twig',['ad'=>$Ad]);


    }
    
}

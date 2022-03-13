<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * permet d'afficher une seule annance
     * 
     * @Route("/ads/{slug}",name="ads_show")
     * 
     * @return Response
     */
    public function show($slug, AdRepository $repo){
        //recuperation d'une annance
        $Ad = $repo->findOneBySlug($slug); 

        return $this->render('ad/show.html.twig',['ad'=>$Ad]);


    }
}

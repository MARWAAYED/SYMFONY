<?php

namespace App\Controller;


use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
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

    public function create(Request $request){
        $Ad = new Ad();

        $image = new Image();

        $image->setUrl('https://www.orientation.com/articles/wp-content/uploads/2020/09/%C3%A9tudes_rh.jpg')
            ->setCaption('titre 1');
        
            
        $image2 = new Image();

        $image2->setUrl('https://www.orientation.com/articles/wp-content/uploads/2020/09/%C3%A9tudes_rh.jpg')
            ->setCaption('titre 2');

        $Ad->addImage($image)
        ->addImage($image2);

        $form = $this->createForm(AdType::class, $Ad);

        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($Ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "l'annance <strong>{$Ad->getTitle()}</strong> a été bien enregistrée"
            );

            return $this->redirectToRoute('ads_show',[
                'slug' =>$Ad->getSlug()
            ]);
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

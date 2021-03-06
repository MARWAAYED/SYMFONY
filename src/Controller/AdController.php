<?php

namespace App\Controller;


use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\DependencyInjection\Loader\Configurator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_USER")
     * @return Response
     */

    public function create(Request $request){
        $Ad = new Ad();

        $form = $this->createForm(AdType::class, $Ad);

        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid()){
            foreach($Ad->getImages() as $image) {
                $image->setAd($Ad);
                $manager->persist($image);
            }

            $Ad->setAuthor($this->getUser());

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
         * affichage de formulair de modification
         * 
         * @Route("/ads/{slug}/edit", name="ads_edit")
         * 
         * @Security("is_granted('ROLE_USER') and user === Ad.getAuthor()", 
         * message="cette annance ne vous appartient pas : vous ne pouvez pa la modifier")
         * 
         * @return Response
         */
        public function edit(Ad $Ad, Request $request, EntityManagerInterface $manager){
            $form = $this->createForm(AdType::class, $Ad);

            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()){
                foreach($Ad->getImages() as $image) {
                    $image->setAd($Ad);
                    $manager->persist($image);
                }
    
    
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($Ad);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    "les modification de l'annance <strong>{$Ad->getTitle()}</strong> ont été bien enregistrée"
                );
    
                return $this->redirectToRoute('ads_show',[
                    'slug' =>$Ad->getSlug()
                ]);
            }

            return $this->render('ad/edit.html.twig',
            [ 'form' => $form->createView(),
                'ad' => $Ad
            ]
        );
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
  /**
   * permet de supprimer une annonce
   * 
   * @Route("/ads/{slug}/delete", name="ads_delete")
   * @Security("is_granted('ROLE_USER') and user == Ad.getAuthor()", message="vous
   * n'avez pas le droit d'accéder à cette ressource")
   *
   * @param Ad $Ad
   * @param EntityManagerInterface $manager
   * @return Response
   */  
  public function delete(Ad $Ad, EntityManagerInterface $manager){
    $manager->remove($Ad);
    $manager->flush();

    $this->addFlash(
        'success',
        "l'annance <strong>{$Ad->getTitle()}</strong> ont été bien supprimer"
    );

    return $this->redirectToRoute("ads_index");

  }  
}

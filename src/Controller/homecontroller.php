<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class homecontroller extends AbstractController {
    /**
     *@Route("/hello/{prenoms}/age/{age}", name="hello")
     *@Route("/hello" , name="hello_base")
     *@Route("/hello/{prenoms}", name="hello_prenoms")
     *
     *Montre la page qui dit bonjour
     *
     * @return void
     */
   
    public function hello ($prenoms = "anonyme", $age = 0){
       
        return $this->render(
            'hello.html.twig',
            [
                'prenoms'=> $prenoms,
                'age'=> $age

            ]
            );
    }

    /**
     *@Route("/", name="homepage")
     */
    
    public function home(){
        $prenoms = ["marwa"=>31,"mehdi"=>12,"mohamed"=>55];
        return $this->render(
            'home.html.twig',
            [
                'title'=>"Bonjour Bonjour",
                'age' => 12,
                'tableau'=>$prenoms,
               
            ]

        );
    }
}

?>
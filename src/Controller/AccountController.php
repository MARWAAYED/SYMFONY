<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error =$utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        dump($error);


        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]
    );
    }

    /**
     * deconnecter
     *
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout(){

    }

    /**
     * insription
     *
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, 
    UserPasswordEncoderInterface $encoder){
        $user = new User();

        $form =$this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a été bien crée , vous pouvez maintenant vous connecter"
            );

            return $this->redirectToRoute('account_login');


        }

        return $this->render('account/registration.html.twig',[
            'form' =>$form->createView()
        ]);

    }

    /**
     * profile afficher modifier
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/account/profile", name="account_profile")
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager){

        $user = $this->getUser();
        $form =$this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "les modification ont été enregistrer"
            );
            }
        return $this->render('account/profile.html.twig',[
            'form' =>$form->createView()
        ]);
    }

    /**
     * Undocumented function
     * @IsGranted("ROLE_USER")
     * @Route("/account/password-update", name="account_password")
     * @return Response
     */
    public function updatePassword(Request $request, 
    UserPasswordEncoderInterface $encoder, 
    EntityManagerInterface $manager) {

        $passwordUpdate = new PasswordUpdate();

       $user =$this->getUser();
        
        $form = $this->createForm(PasswordUpdateType::class,$passwordUpdate);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){

            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){ 
                 //gerer l'erreur
                 $form->get('oldPassword')->addError(new FormError("le mot de passe que 
                 vous avez tapé n'est pas votre mot de passe actuel"));
             } else {
                 $newPassword = $passwordUpdate->getNewPassword();
                 $hash = $encoder->encodePassword($user, $newPassword);

                 $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "votre mot de passe a été modifier"
                );
                return $this->redirectToRoute('home');
             }
             
            }

        return $this->render('account/password.html.twig',[
            'form' =>$form->createView()
        ]);
    }
    /**
     * profil utilisateur connecté
     * 
     *@Route("/account", name="account_index")
     *
     * @return Response
     */
    public function myAccount() {
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }
}

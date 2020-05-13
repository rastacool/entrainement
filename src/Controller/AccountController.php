<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Controller\AccountController;
use Symfony\Component\Form\FormError;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * PERMET D'afficher et de gere le formulaire de conexion
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        
        return $this->render('account/login.html.twig', [
            'hasError'=> $error !== null,
            'username'=> $username
        ]);
    }

    /**
     * Permet de se deconecter
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void 
     */
    public function logout()
    {
        //rien
    }


    /**
     * permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user,$user->getHash());
            $user -> setHash($hash);
            $manager = $this->getDoctrine()->getManager();  
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "votre compte a bien été crée"
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form'=> $form->createView()
            ]);
        }

    /**
     * Permet d'afficheer et de traiter le formulaire de modification de profil
     * 
     * @Route("/account/profile", name="acount_profile")
     * @IsGranted ("ROLE_USER")
     * 
     * 
     * @return Response
     */

    public function profile(Request $request) {
        $manager = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données du profils on été enregistré"
            );
        }

        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Permet de modfier le mdp
     * 
     * @Route("/account/password-update", name="account_password")
     * @IsGranted ("ROLE_USER")
     * 
     * 
     * @return Response
     */

     public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder) {
        
        $manager = $this->getDoctrine()->getManager();

         $passwordUpdate = new PasswordUpdate();

         $user = $this->getUser();
 
         $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()){
             // verifier lancien mdp sois le meme que luser
             if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                 //gererl'erreur
                 $form->get('oldPassword')->addError(new FormError("ceci n'est pas votre mdp actuel"));
             }


         else {
             $newPassword = $passwordUpdate->getNewPassword();
             $hash = $encoder->encodePassword($user, $newPassword);

             $user->setHash($hash);

             $manager->persist($user);
             $manager->flush();

             $this->addFlash(
                 'success', "Votre mdp a bien été modifié!"
             );

             return $this->redirectToRoute('homepage');
         }
        }


         return $this->render('account/password.html.twig', [
             'form' => $form->createView()
         ]);
     
     }

     /**
     * Permet d'afficheer et de traiter le formulaire de modification de profil
     * 
     * @Route("/account/profil", name="acount_profil")
     * @IsGranted ("ROLE_USER")
     * 
     * 
     * 
     * @return Response
     */
        public function profil()
    {

    return $this->render('account/profil.html.twig');
        ;
    }
    /**
     * Permet d'afficher le profile de l'utilisateur connecté
     * 
     *@Route("/account", name="account_index")
     * @IsGranted ("ROLE_USER")
     *    
     * @return Response
     */
    public function myAccount() {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * Permet d'afficher la liste des reservation
     * 
     * @Route("/account/bookings", name = "account_bookings")
     * 
     * @return Reponsse
     */
    public function bookings() {
        return $this->render('account/bookings.html.twig');
    }

    /**
     * Permet d'afficher le profile de l'utilisateur connecté
     * 
     *@Route("/profile", name="profile_index")
     * @IsGranted ("ROLE_USER")
     *    
     * @return Response
     */
    public function myprofile() {
        return $this->render('user/profile.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}

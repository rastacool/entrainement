<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdController extends Controller
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findall();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }
 /**
     * permet de creeer une annonce
     *
     * @Route("/ads/new", name="ads_create")
     * @IsGranted ("ROLE_USER")
     * 
     * @return Response
     */
    public function create(Request $request){
        $ad = new Ad();
        

        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);


        
        if($form->isSubmitted() && $form->isValid()){
        $manager = $this->getDoctrine()->getManager();  
        $user = $this->getUser();
        
        $ad->setAuthor($user);

        foreach($ad->getImages() as $image) {
            $image->setAd($ad);
            $manager->persist($image);
        } 
        $manager->persist($ad);
        $manager->flush();
        $this->addFlash(
                'success',
                " l'annonce <strong> {$ad->getTitle()} </strong> a bien été enregistré"
            );


        return $this->redirectToroute('ads_show',[
            'slug' => $ad->getSlug()]);
        }
        
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);

    }
    /**
     * Permet de modifier une annonce
     * 
    * @Route("/ads/{slug}/edit", name="ads_edit")
     *@Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="c'est pas de vous" )
     *
     * @return Response
     */
    public function edit(Ad $ad, Request $request){
        
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();  
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            } 
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                    'success',
                    " l'annonce a été modifier <strong> {$ad->getTitle()} </strong> a bien été enregistré"
                );
                return $this->redirectToroute('ads_show',[
                    'slug' => $ad->getSlug()]);
                }
    

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad'   => $ad
        ]);

    }
    /**
     * Permet daficher une annonce
     * 
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     */
    public function show(Ad $ad){
        return $this->render('ad/show.html.twig',[
            'ad' => $ad
        ]);
    }


    /**
     * permet de supprimer une annonce
     *
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Vous avez pas l'acces a cette annnonce")
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return response
     */
    public function delete(Ad $ad){
        $manager = $this->getDoctrine()->getManager();  
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'succes',
            "l'annonce <strong> {$ad->getTitle()}</strong> a bien été suprimée!"
        );

        return $this->redirectToRoute("ads_index");

    }
   
}

<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use App\Controller\AdminAdController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' =>  $repo->findall()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * 
     * @param Ad $ad
     * @return Response 
     */

    public function edit(Ad $ad, Request $request) {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "l'annonce <strong>{$ad->getTitle()} </strong> a bien été enregistré "
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $ad
     * @param ObjetManager $manager
     * @return Response
     */
    public function delete(Ad $ad){
        $manager = $this->getDoctrine()->getManager();

        if(count($ad->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "vous ne pouvez pas supprimez l'annonce <strong> {$ad->getTitle()}</strong> car elle possede deja des reservations!"
            );
        }
        else {
        $manager->remove($ad);
        $manager->flush();

        $this->addflash(
            'success',
            "l'annonce <strong> {$ad->getTitle()} </strong> a bien été supprimée !"
        );
        
        }


        return $this->redirectToRoute('admin_ads_index');
    }
}
